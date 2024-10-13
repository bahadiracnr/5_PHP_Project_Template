<?php
$servername = "localhost";
$username = "root";
$password = "web364web";
$dbname = "taspa_yatirim";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
