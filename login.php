<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Admin login check
    $admin_query = "SELECT * FROM admins WHERE username = '$identifier' AND password = '$password'";
    $admin_result = mysqli_query($conn, $admin_query);

    if ($admin_result && mysqli_num_rows($admin_result) === 1) {
        $row = mysqli_fetch_assoc($admin_result);
        $_SESSION['admin'] = $row['username'];
        header('Location: admin_dashboard.php');
        exit;
    }

    // User login check
    $user_query = "SELECT * FROM users WHERE email = '$identifier' AND password = '$password'";
    $user_result = mysqli_query($conn, $user_query);

    if ($user_result && mysqli_num_rows($user_result) === 1) {
        $row = mysqli_fetch_assoc($user_result);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user'] = $row['name'];
        header('Location: index.php');
        exit;
    }

    // Invalid credentials
    echo "<script>alert('Invalid credentials. Please try again.'); window.location.href='login.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/Loginpage.css">
</head>
<body>

<header>
    <?php include 'user_navbar.php'; ?>
</header>

<div class="login-form">
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="identifier">Email (User) or Username (Admin)</label>
        <input type="text" id="identifier" name="identifier" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>

<p>
    <a href="forgot_password.php" style="color: #007bff;">Forgot Password?</a>
</p>

        <p>New user? <a href="register.php">Register here</a></p>
    </form>
</div>

<footer>
    <div class="footer-container">
        <div class="footer-left">
            <div class="logo">RentalHub</div>
            <p>Flat No. 303, Bldg No. 6, Atlanta Residency, Anjurphata, Bhiwandi, Dist Thane, 421305</p>
            <p>📞 9960259300 / +917894561230</p>
            <p>📧 rentalhub@gmail.com</p>
        </div>

        <div class="footer-links">
            <div>
                <h3>About</h3>
                <ul>
                    <li><a href="about.php">About us</a></li>
                    <li><a href="contact.php">Contact us</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h3>More Information</h3>
                <ul>
                    <li><a href="#">All properties</a></li>
                    <li><a href="#">Houses for rent</a></li>
                </ul>
            </div>
            <div>
                <h3>News</h3>
                <ul>
                    <li><a href="#">Our Blogs</a></li>
                    <li><a href="#">Why Choose RentalHub?</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 RentalHub. All Rights Reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
