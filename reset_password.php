<?php
include('db.php');
include 'utils.php';
session_start();

if (isset($_GET['email'])) {
    $email = mysqli_real_escape_string($conn, $_GET['email']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $query = "UPDATE users SET password='$new_password' WHERE email='$email'";
        if (mysqli_query($conn, $query)) {
            set_flash_message('Password reset successful!');
            header('Location: login.php');
            exit;
        } else {
            set_flash_message('Error resetting password.');
        }
    }
} else {
    set_flash_message('Invalid request.');
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/Loginpage.css">
</head>
<body>
    <?php include 'user_navbar.php'; ?>
    <div class="login-form">
        <h2>Reset Password</h2>
        <form method="POST">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
