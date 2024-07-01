<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Guard') {
    header("Location: index.php"); // Redirect users who are not logged in or not Security Guard
    exit();
}

// Include database connection
require '../require/db.php';

// Initialize variables for errors and success messages and others
$errors = array();
$success = '';
$pass_code = '';
$plate_number = '';
$vehicle_type = '';
$owner_name = '';
$owner_phone = '';

// Setting the limit (number of row per fetch) and offset (number of records to skip) for pagination
$skip = 0;
$rows_per_page = 10;

// Getting the total number of records (pages)
$sql = "SELECT * FROM gate_passes";
$result = $conn -> query($sql);
$total_records = $result -> num_rows;

$pages = ceil ($total_records / $rows_per_page);

// Getting the page to print
if (isset($_SESSION['page_number'])) {
    $page = $_SESSION['page_number'] - 1;
    $skip = $page * $rows_per_page;
}

// Display records with serial numbers
$counter = $skip + 1;

// Format Date and time
function formatDateTimeToCustom12Hour($datetime_string) {
    $datetime = new DateTime($datetime_string);
    return $datetime->format('j-n-Y, g:i A');
}

// Function to generate a unique pass code
function generatePassCode($plate_number) {
    return $plate_number . rand(1000, 9999);
}

// Function to get the username of the currently logged in user
function getCurrentUser($conn) {
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        return null;
    }

    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    $stmt->close();
    return $user;
}

// Get the vehicle with the pass code
function getVehicle($conn, $pass_code) {
    $query = "SELECT * FROM vehicles WHERE pass_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $pass_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicle = $result->fetch_assoc();
    
    $stmt->close();
    return $vehicle;
}

// Function to check if plate number exists in a table with a specific condition
function plateNumberExists($conn, $table, $plate_number, $condition = '') {
    $query = "SELECT * FROM $table WHERE plate_number = ?";
    if ($condition) {
        $query .= " AND $condition";
    }
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $plate_number);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Function to check if pass Code exists in a table with a specific condition
function passCodeExists($conn, $table, $pass_code, $condition = '') {
    $query = "SELECT * FROM $table WHERE pass_code = ?";
    if ($condition) {
        $query .= " AND $condition";
    }
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $pass_code);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Unregister vehicle gate pass generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'unregisterGatePass') {
    $plate_number = strtoupper($conn->real_escape_string($_POST['plateNumber']));
    
    $inGatePasses = plateNumberExists($conn, 'gate_passes', $plate_number, "verification_status = 'Valid'");
    $inRegisteredVehicles = plateNumberExists($conn, 'vehicles', $plate_number);

    if ($inGatePasses || $inRegisteredVehicles) {
        if ($inGatePasses) {
            $errors[] = "Duplicate gate pass attempt! Vehicle with plate number $plate_number is already signed in";
        }
        if ($inRegisteredVehicles) {
            $errors[] = "Vehicle with plate number $plate_number is already registered. Please use the pass code instead.";
        }
    } else {
        $vehicle_type = $conn->real_escape_string($_POST['vehicleType']);
        $pass_code = generatePassCode($plate_number);
        $current_user = getCurrentUser($conn)['username'];

        // Generate temporary gate pass
        $stmt = $conn->prepare("INSERT INTO gate_passes (pass_code, plate_number, vehicle_type, issued_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $pass_code, $plate_number, $vehicle_type, $current_user);
        if ($stmt->execute()) {
            $success = "Temporary gate pass generated. The vehicle can proceed.";
        } else {
            $errors[] = "Error generating gate pass.";
        }
        $stmt->close();
    }

    $response = [
        'pass_code' => $pass_code,
        'vehicle_type' => $vehicle_type,
        'errors' => $errors,
        'success' => $success
    ];
    
    echo json_encode($response);
}

// Registered vehicle gate pass generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'registerGatePass') {
    $pass_code = strtoupper($conn->real_escape_string($_POST['passCode']));
    
    $inGatePasses = passCodeExists($conn, 'gate_passes', $pass_code, "verification_status = 'Valid'");
    $inRegisteredVehicles = passCodeExists($conn, 'vehicles', $pass_code);

    if ($inRegisteredVehicles) {
        if ($inGatePasses) {
            $errors[] = "Duplicate gate pass attempt! Vehicle with pass code $pass_code is already signed in";
        } else {
            $vehicle = getVehicle($conn, $pass_code);

            $plate_number = $vehicle['plate_number'];
            $vehicle_type = $vehicle['vehicle_type'];
            $owner_name = $vehicle['owner_name'];
            $owner_phone = $vehicle['owner_phone'];
            $current_user = getCurrentUser($conn)['username'];

            // Generate temporary gate pass
            $stmt = $conn->prepare("INSERT INTO gate_passes (pass_code, plate_number, vehicle_type, issued_by) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $pass_code, $plate_number, $vehicle_type, $current_user);
            if ($stmt->execute()) {
                $success = "Pass code is valid. The vehicle can proceed.";
            } else {
                $errors[] = "Error verifying pass code.";
            }
            $stmt->close();
        }
    } else {
        if ($inGatePasses) {
            $errors[] = "Duplicate gate pass attempt! Vehicle with pass code $pass_code is already signed in";
        } else {
            $errors[] = "No registered vehicle found with this pass code.";
        }
    }

    $response = [
        'plate_number' => $plate_number,
        'vehicle_type' => $vehicle_type,
        'owner_name' => $owner_name,
        'owner_phone' => $owner_phone,
        'errors' => $errors,
        'success' => $success
    ];
    
    echo json_encode($response);
}

// Function to execute a query and return the formatted result
function executeQuery($conn, $query, $params, $counter) {
    $stmt = $conn->prepare($query);
    $errors = [];
    $output = '';

    if ($stmt) {
        // Bind the parameters dynamically based on their types and values
        $types = $params['types'];
        $values = $params['values'];
        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                foreach ($result as $row) {
                    if ($row['verification_status'] === 'Valid') {
                        $output .= "<tr>
                            <td>" . $counter++ . "</td>
                            <td>" . $row['pass_code'] . "</td>
                            <td>" . $row['plate_number'] . "</td>
                            <td>" . $row['vehicle_type']   . "</td>
                            <td>" . $row['issued_by']   . "</td>
                            <td>" . formatDateTimeToCustom12Hour($row['issued_date'])   . "</td>
                            <td><span class='text-success'><i class='bi bi-circle-fill me-2' style='font-size: 0.5rem;'></i><span class='fw-medium'>IN</span></span></td>
                            <td>
                                <button onclick='signOutVehicle(\"" . $row['pass_code'] . "\")' class='btn btn-success btn-sm d-grid py-0 w-75'>Sign out</button>
                            </td>
                        </tr>";
                    } else {
                        $output .= "<tr>
                            <td>" . $counter++ . "</td>
                            <td>" . $row['pass_code'] . "</td>
                            <td>" . $row['plate_number'] . "</td>
                            <td>" . $row['vehicle_type']   . "</td>
                            <td>" . $row['issued_by']   . "</td>
                            <td>" . formatDateTimeToCustom12Hour($row['issued_date'])   . "</td>
                            <td><span class='text-danger'><i class='bi bi-circle-fill me-2' style='font-size: 0.5rem;'></i><span class='fw-medium'>OUT</span></span></td>
                            <td>
                                <button onclick='signOutVehicle(" . $row['pass_code'] . ")' class='disabled btn btn-success btn-sm d-grid py-0 w-75'>Sign out</button>
                            </td>
                        </tr>";
                    }
                }
            } else {
                $output .= "<tr>
                        <td colspan='8' class='text-center py-3 fw-light fs-6'>No pass code found</td>
                    </tr>";
            }

            $response = [
                'errors' => $errors,
                'success' => "Pass codes fetched successfully",
                'data' => $output
            ];
        } else {
            $errors[] = "Error executing query.";
            $response = [
                'errors' => $errors
            ];
        }
    } else {
        $errors[] = "Error preparing statement.";
        $response = [
            'errors' => $errors
        ];
    }

    return $response;
}

// Handling POST request for searchPassCode
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'searchPassCode') {
    $plate_number = $conn->real_escape_string($_POST['plateNumber']);

    // Define the query with a placeholder
    $query = "SELECT * FROM gate_passes WHERE plate_number LIKE ? ORDER BY pass_id DESC";

    // Define the parameters to be bound to the query
    $params = [
        'types' => 's', // 's' for string
        'values' => ['%' . $plate_number . '%']
    ];

    // Execute the query
    $response = executeQuery($conn, $query, $params, $counter);
    echo json_encode($response);
}

// Handling POST request for fetchPassCodes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'fetchPassCodes') {
    $limit = $rows_per_page;
    $offset = $skip;

    // Define the query with placeholders for LIMIT and OFFSET
    $query = "SELECT * FROM gate_passes ORDER BY pass_id DESC LIMIT ? OFFSET ?";

    // Define the parameters to be bound to the query
    $params = [
        'types' => 'ii', // 'i' for integer
        'values' => [$limit, $offset]
    ];

    // Execute the query
    $response = executeQuery($conn, $query, $params, $counter);
    echo json_encode($response);
}

// Close the database connection
$conn->close();

?>