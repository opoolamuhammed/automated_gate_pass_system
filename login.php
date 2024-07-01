<?php

session_start();
require 'require/db.php'; // Include database connection

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']); // In a real-world scenario, ensure you hash passwords!

// Prepare SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // if (password_verify($password, $user['password'])) {
    //     // Correct password
    //     $_SESSION['user_id'] = $user['user_id'];
    //     $_SESSION['role'] = $user['role'];

    //     if ($user['role'] === 'Manager') {
    //         header("Location: manager_dashboard.php"); // Redirect to Security Manager Dashboard
    //         exit();
    //     } else {
    //         header("Location: guard_dashboard.php"); // Redirect to Security Personnel Dashboard
    //         exit();
    //     }
    // } else {
    //     // Incorrect password
    //     $_SESSION['error'] = "Invalid username or password";
    //     header("Location: index.php");
    //     exit();
    // }
            
    if ($password === $user['password']) {
        // Correct password
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
    
        if ($user['role'] === 'Manager') {
        header("Location: manager_dashboard.php"); // Redirect to Security Manager Dashboard
        exit();
        } else {
        header("Location: guard_dashboard.php"); // Redirect to Security Personnel Dashboard
        exit();
        }
    } else {
        // Incorrect password
        $_SESSION['error'] = "Invalid username or password";
        header("Location: index.php");
        exit();
    }


} else {
    // No user found
    $_SESSION['error'] = "Invalid username or password";
    header("Location: index.php");
    exit();
}

$stmt->close();
$conn->close();

?>