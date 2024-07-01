<?php

// Include database connection
require '../require/db.php';

// Define variables for errors and status message
$errors = array();
$success = "";
$pass_code = "";
$vehicle_type = "";

// Function to generate pass code
function appendRandomNumberToPlate($plateNumber) {
    // Generate a random 4-digit number
    $randomNumber = rand(1000, 9999);
    
    // Append the random number to the plate number
    $newPlateNumber = $plateNumber . $randomNumber;
    
    return $newPlateNumber;
}

// Generic function to execute SQL queries
function executeQuery($conn, $query, $params, $types) {
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        return ['success' => false, 'error' => $conn->error];
    }

    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        return ['success' => true, 'result' => $stmt->get_result()];
    } else {
        return ['success' => false, 'error' => $stmt->error];
    }
}

// Check if the registerVehicle form is submitted
if (isset($_POST['action']) && $_POST['action'] == 'registerVehicle') {
    // Sanitize input data
    $plate_number = strtoupper(mysqli_real_escape_string($conn, $_POST['plateNumber']));
    $vehicle_type = mysqli_real_escape_string($conn, $_POST['vehicleType']);
    $owner_name = mysqli_real_escape_string($conn, $_POST['ownerName']);
    $owner_phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Validate data (add more validation as required)
    if (empty($plate_number) || empty($vehicle_type) || empty($owner_name) || empty($owner_phone)) {
        $errors[] = "All fields are required.";
    }

    // Check for existing plate number before inserting
    $check_query = "SELECT * FROM vehicles WHERE plate_number = ?";
    $check_result = executeQuery($conn, $check_query, [$plate_number], 's');

    // If no errors and plate number doesn't exist, insert the data
    if (empty($errors) && $check_result['success'] && $check_result['result']->num_rows === 0) {
        $pass_code = appendRandomNumberToPlate($plate_number);
        $insert_query = "INSERT INTO vehicles (plate_number, vehicle_type, owner_name, owner_phone, pass_code) VALUES (?, ?, ?, ?, ?)";
        $insert_result = executeQuery($conn, $insert_query, [$plate_number, $vehicle_type, $owner_name, $owner_phone, $pass_code], 'sssss');

        if ($insert_result['success']) {
            $success = "Vehicle registered successfully.";
        } else {
            $errors[] = "Error: " . $insert_result['error'];
        }
    } else {
        $errors[] = "A vehicle with this plate number already exists.";
    }

    $data = [
        'errors' => $errors,
        'success' => $success,
        'vehicle_type' => $vehicle_type,
        'pass_code' => $pass_code
    ];
    
    echo json_encode($data);
}

// Check if the update form is submitted
if (isset($_POST['action']) && $_POST['action'] == 'updateVehicle') {
    // Sanitize input data
    $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);
    $plate_number = strtoupper(mysqli_real_escape_string($conn, $_POST['plate_number']));
    $vehicle_type = mysqli_real_escape_string($conn, $_POST['vehicle_type']);
    $owner_name = mysqli_real_escape_string($conn, $_POST['owner_name']);
    $owner_phone = mysqli_real_escape_string($conn, $_POST['owner_phone']);

    // Validate data (add more validation as required)
    if (empty($vehicle_id) || empty($plate_number) || empty($vehicle_type) || empty($owner_name) || empty($owner_phone)) {
        $errors[] = "All fields are required.";
    }

    // If no errors and plate number doesn't exist, insert the data
    if (empty($errors)) {
        $pass_code = appendRandomNumberToPlate($plate_number);
        $update_query = "UPDATE vehicles SET plate_number = ?, vehicle_type = ?, owner_name = ?, owner_phone = ?, pass_code = ? WHERE vehicle_id = ?";
        $update_result = executeQuery($conn, $update_query, [$plate_number, $vehicle_type, $owner_name, $owner_phone, $pass_code, $vehicle_id], 'ssssss');

        if ($update_result['success']) {
            $success = "Vehicle registered successfully.";
        } else {
            $errors[] = "Error: " . $update_result['error'];
        }
    }

    $data = [
        'errors' => $errors,
        'success' => $success
    ];
    
    echo json_encode($data);
}



// Close the database connection
$conn->close();


?>