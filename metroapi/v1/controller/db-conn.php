<?php
$servername = "eon_bazar";
$username = "roni";
$password = "roni";

// Create connection
$conn = new mysqli("localhost", "roni", "roni", "eon_bazar");
// $conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
