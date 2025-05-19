<?php
$host = "localhost"; // Usually "localhost"
$user = "root"; // Database username
$password = ""; // Database password
$database = "scentaura"; // Database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>