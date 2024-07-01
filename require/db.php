<?php

$servername = "localhost"; // Your hostname
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "automated_gate_pass_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>