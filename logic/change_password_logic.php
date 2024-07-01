<?php

// Start the session
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Include database connection
require '../require/db.php';

// Initialize variables for errors and success messages
$errors = array();
$success = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $new_password = mysqli_real_escape_string($conn, $_POST['newPassword']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

    // Validate new password
    if (empty($new_password)) {
        $errors[] = "New password is required.";
    } elseif (strlen($new_password) < 8) {
        $errors[] = "New password must be at least 8 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "New password and confirm password do not match.";
    }

    // If no errors, update password in database
    // if (empty($errors)) {
    //     $user_id = $_SESSION['user_id'];
    //     $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
    //     $update_sql = "UPDATE users SET password = '$hashed_new_password' WHERE user_id = $user_id";
    //     if ($conn->query($update_sql) === TRUE) {
    //         $success = "Password changed successfully.";
    //     } else {
    //         $errors[] = "Error updating password: " . $conn->error;
    //     }
    // }
    


    // If no errors, update password in database
    if (empty($errors)) {
        $user_id = $_SESSION['user_id'];
        $update_sql = "UPDATE users SET password = '$new_password' WHERE user_id = $user_id";
        if ($conn->query($update_sql) === TRUE) {
            $success = "Password changed successfully.";
        } else {
            $errors[] = "Error updating password: " . $conn->error;
        }
    }
}

$data = [
    'errors' => $errors,
    'success' => $success
];

echo json_encode($data);

// Close the database connection
$conn->close();

?>
