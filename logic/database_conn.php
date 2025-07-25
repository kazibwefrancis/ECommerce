<?php
$servername = "localhost";  // Adjust if using a different host
$username = "root";         // Change this to your DB username
$password = "";             // Your DB password
$dbname = "ecommerce2";  // Replace with your actual database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update your existing database insertion code to include quantity
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity']; // Add this line
    $description = $_POST['description'];
    
    // Update your INSERT query to include quantity
    $sql = "INSERT INTO items (name, price, quantity, description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdis", $name, $price, $quantity, $description);
    
    if ($stmt->execute()) {
        echo "<script>alert('Item added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding item: " . $conn->error . "');</script>";
    }
}
?>
