<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Redirect to reset password with email as GET parameter
        header("Location: reset_password.php?email=" . urlencode($email));
        exit;
    } else {
        echo "<script>alert('Email not found.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/Loginpage.css">
</head>
<body>
    <?php include 'user_navbar.php'; ?>
    <div class="login-form">
        <h2>Forgot Password</h2>
        <form method="POST">
            <label for="email">Enter Your Registered Email</label>
            <input type="email" name="email" required>
            <button type="submit">Continue</button>
        </form>
    </div>
</body>
</html>
