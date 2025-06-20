<?php
include("database_conn.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $fullname = $conn->real_escape_string($_POST["fullname"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Encrypt password
    $usertype = $_POST["usertype"];

    // Validate inputs
    if (empty($fullname) || empty($email) || empty($_POST["password"]) || empty($usertype)) {
        echo "All fields are required!";
        exit();
    }

    // Check if email already exists
    $check_email = "SELECT * FROM Users WHERE email='$email'";
    $result = $conn->query($check_email);
    if ($result->num_rows > 0) {
        echo "Email already registered!";
        exit();
    }

    // Insert data
    $sql = "INSERT INTO Users (fullname, email, password, usertype)
            VALUES ('$fullname', '$email', '$password', '$usertype')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
