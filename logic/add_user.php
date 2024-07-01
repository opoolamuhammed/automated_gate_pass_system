<?php

// Start session
session_start();

// Check if the user is logged in and is a manager
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    header("Location: index.php");
    exit();
}

// Include database connection
require '../require/db.php';

// Define variables for errors and status message
$errors = array();
$success = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $first_name = ucfirst(mysqli_real_escape_string($conn, $_POST['first_name']));
    $last_name = ucfirst(mysqli_real_escape_string($conn, $_POST['last_name']));
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);

    // Validate data (add more validation as required)
    if (empty($username) || empty($password) || empty($first_name) || empty($last_name) || empty($role) || empty($phone_number)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        // Check if the username already exists
        $check_query = "SELECT * FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows === 0) {
            // If username does not exist, insert the new user
            $insert_query = "INSERT INTO users (username, password, first_name, last_name, role, phone_number) VALUES (?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ssssss", $username, $password, $first_name, $last_name, $role, $phone_number);

            if ($insert_stmt->execute()) {
                $success = "User added successfully";
            } else {
                $errors[] = "Error: " . $insert_stmt->error;
            }

            $insert_stmt->close();
        } else {
            $errors[] = "A user with this username already exists.";
        }

        $check_stmt->close();
    }
}

// Close the database connection
$conn->close();

$data = array(
    "errors" => $errors,
    "success" => $success
);

echo json_encode($data);

?>
