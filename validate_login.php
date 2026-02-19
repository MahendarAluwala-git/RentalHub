<?php
include 'db.php';
include 'utils.php';
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    set_flash_message('Welcome back, ' . $user['name'] . '!');
    header('Location: index.php');
    exit();
} else {
    set_flash_message('Invalid email or password.');
    header('Location: login.php');
    exit();
}
$conn->close();
?>
