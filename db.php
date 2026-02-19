<?php
$host = "sql305.infinityfree.com";
$user = "if0_41177681";  // Default XAMPP username
$password = "VkdYtXERGkiYz2v";  // Default XAMPP password is empty
$database = "if0_41177681_rentalhub"; // Your database name

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
