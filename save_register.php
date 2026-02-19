<?php
include 'db.php';
include 'utils.php';
session_start();

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];  // Plain-text password

$sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    set_flash_message('Registration successful! Please log in.');
    header('Location: login.php');
    exit();
} else {
    set_flash_message('Error: ' . $conn->error);
    header('Location: register.php');
    exit();
}

$conn->close();
?>
