<?php

    require '../require/db.php';

    // Retrieve user_id from the URL query string
    $user_id = isset($_GET['id']) ? $_GET['id'] : die('User ID not specified.');

    // SQL to fetch the user details
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    echo json_encode($user);

    // Close connection
    $stmt->close();
    $conn->close();

    // Check if the user was found
    if (!$user) {
        die('Vehicle not found.');
    }

?>