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
    $user_id = mysqli_real_escape_string($conn, $_POST["user_id"]);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);

    // Validate data (add more validation as required)
    if (empty($user_id) || empty($username) || empty($password) || empty($first_name) || empty($last_name) || empty($role) || empty($phone_number)) {
        $errors[] = "All fields are required.";
    }

    // If no errors, update the data
    $query = "UPDATE users SET username = ?, password = ?, first_name = ?, last_name = ?, role = ?, phone_number = ? WHERE user_id = ?";
    $stmt = $conn -> prepare($query);
    $stmt -> bind_param("sssssss", $username, $password, $first_name, $last_name, $role, $phone_number, $user_id);

    if ($stmt->execute()) {
        $success = "User updated successfully.";
    } else {
        $errors[] = "Error: " . $stmt->error;
    }

    $stmt->close();

}

// Close the database connection
$conn->close();

$data = array(
    "errors" => $errors,
    "success" => $success
);

echo json_encode($data);

?>