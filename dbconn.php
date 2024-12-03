<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "user_registration"; // Updated database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>