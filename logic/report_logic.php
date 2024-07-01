<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    header("Location: index.php"); // Redirect users who are not logged in or not Security Guard
    exit();
}

// Include database connection
require '../require/db.php';

// Initialize variables for errors and success messages and others
$errors = array();
$success = '';
$signed_out_time = date("Y-m-d H:i:s");

// Setting the limit (number of row per fetch) and offset (number of records to skip) for pagination
$skip = 0;
$rows_per_page = 20;

// Getting the total number of records (pages)
$sql = "SELECT COUNT(*) as total FROM gate_passes";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row['total'];

$pages = ceil($total_records / $rows_per_page);

// Getting the page to print
if (isset($_GET['page_number'])) {
    $page = (int)$_GET['page_number'] - 1;
    $skip = $page * $rows_per_page;
}

// Display records with serial numbers
$counter = $skip + 1;

// Function to get the username of the currently logged in user
function getCurrentUser($conn) {
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        return null;
    }

    $query = "SELECT username FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    $stmt->close();
    return $user;
}

// Function to get the gate pass
function getGatePass($conn, $pass_code) {
    $query = "SELECT * FROM gate_passes WHERE pass_code = ? AND verification_status = 'Valid'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $pass_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $gate_pass = $result->fetch_assoc();
    $stmt->close();

    return $gate_pass;
}

// Format Date and time
function formatDateTimeToCustom12Hour($datetime_string) {
    $datetime = new DateTime($datetime_string);
    return $datetime->format('j-n-Y, g:i A');
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
                    $output .= "<tr class='d-print-table-row'>
                            <td class='d-print-table-cell'>" . $counter++ . "</td>
                            <td class='d-print-table-cell'>" . $row['plate_number'] . "</td>
                            <td class='d-print-table-cell'>" . $row['vehicle_type'] . "</td>
                            <td class='d-print-table-cell'>" . $row['issued_by']   . "</td>
                            <td class='d-print-table-cell text-success fw-medium'>" . formatDateTimeToCustom12Hour($row['issued_time'])   . "</td>
                            <td class='d-print-table-cell'>" . $row['signed_out_by']   . "</td>
                            <td class='d-print-table-cell text-danger fw-medium'>" . formatDateTimeToCustom12Hour($row['signed_out_time'])   . "</td>
                        </tr>";
                }
            } else {
                $output .= "<tr>
                        <td colspan='8' class='text-center py-3 fw-light fs-6'>No gate activity found</td>
                    </tr>";
                $errors[] = "No gate activity found.";
            }
            
            $response = [
                'errors' => $errors,
                'success' => empty($errors) ? "Gate activities fetched successfully" : "",
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

// Handling POST request for fetchGateActivities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'fetchGateActivities') {
    $limit = $rows_per_page;
    $offset = $skip;

    // Define the query with placeholders for LIMIT and OFFSET
    $query = "SELECT * FROM gate_activities ORDER BY activity_id DESC LIMIT ? OFFSET ?";

    // Define the parameters to be bound to the query
    $params = [
        'types' => 'ii', // 'i' for integer
        'values' => [$limit, $offset]
    ];

    // Execute the query
    $response = executeQuery($conn, $query, $params, $counter);
    echo json_encode($response);
}

// Handling POST request for searchGateActivity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'searchGateActivity') {
    $plate_number = $conn->real_escape_string($_POST['plateNumber']);

    // Define the query with a placeholder
    $query = "SELECT * FROM gate_activities WHERE plate_number LIKE ? ORDER BY activity_id DESC";

    // Define the parameters to be bound to the query
    $params = [
        'types' => 's', // 's' for string
        'values' => ['%' . $plate_number . '%']
    ];

    // Execute the query
    $response = executeQuery($conn, $query, $params, $counter);
    echo json_encode($response);
}

// Handling POST request for createNewReport
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'createNewReport') {
    $start_date = $_POST['startDate'];
    $end_date = $_POST['endDate'];
    
    // Ensure the date range covers the entire day for the end_date
    $start_date .= ' 00:00:00';
    $end_date .= ' 23:59:59';

    // Define the query with placeholders for the date range
    $query = "SELECT * FROM gate_activities WHERE signed_out_time BETWEEN ? AND ? ORDER BY activity_id DESC";

    // Define the parameters to be bound to the query
    $params = [
        'types' => 'ss', 
        'values' => [$start_date, $end_date]
    ];

    // Redefining counter variable
    $counter = 1;

    // Execute the query
    $response = executeQuery($conn, $query, $params, $counter);
    echo json_encode($response);
}

// Close the database connection
$conn->close();

?>
