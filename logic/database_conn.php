<?php
$servername = "localhost";  // Adjust if using a different host
$username = "root";         // Change this to your DB username
$password = "";             // Your DB password
$dbname = "ecommerce";  // Replace with your actual database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
