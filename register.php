<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $check_query = "SELECT * FROM users WHERE email = '$email' OR mobile = '$mobile'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Email or mobile already exists.'); window.location.href='register.php';</script>";
        exit;
    }

    $query = "INSERT INTO users (name, email, mobile, password) VALUES ('$name', '$email', '$mobile', '$password')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Registration successful! Please login now.'); window.location.href='login.php';</script>";
        exit;
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/Registerpage.css">
</head>
<body>
    <?php include 'user_navbar.php'; ?>

    <div class="register-form">
        <h2>Register</h2>
        <form action="register.php" method="POST">
    <label for="name">Full Name</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <label for="mobile">Mobile Number</label>
    <input type="text" id="mobile" name="mobile" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Register</button>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</form>

    </div>
    
    <footer>
    <div class="footer-container">
        <div class="footer-left">
        <div class="logo">RentalHub</div>
            <p> Flat No. 303, Bldg No. 6, Atlanta Residency, Anjurphata, Bhiwandi, Dist Thane, 421305</p>
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
</footer>

</body>
</html>
