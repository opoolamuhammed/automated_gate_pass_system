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
    $vehicle_id = mysqli_real_escape_string($conn, $_POST["vehicle_id"]);

    // Validate data (add more validation as required)
    if (empty($vehicle_id)) {
        $errors[] = "Unknown vehicle";
    }

    // If no errors, delele the data
    $query = "DELETE FROM vehicles WHERE vehicle_id = ?";
    $stmt = $conn -> prepare($query);
    $stmt -> bind_param("s", $vehicle_id);

    if ($stmt->execute()) {
        $success = "Vehicle deleted successfully.";
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