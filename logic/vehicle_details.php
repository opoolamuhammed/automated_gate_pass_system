<?php

    require '../require/db.php';

    // Retrieve vehicle_id from the URL query string
    $vehicle_id = isset($_GET['id']) ? $_GET['id'] : die('Vehicle ID not specified.');

    // SQL to fetch the vehicle details
    $sql = "SELECT * FROM vehicles WHERE vehicle_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicle = $result->fetch_assoc();

    echo json_encode($vehicle);

    // Close connection
    $stmt->close();
    $conn->close();

    // Check if the vehicle was found
    if (!$vehicle) {
        die('Vehicle not found.');
    }

?>