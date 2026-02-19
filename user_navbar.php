<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="css/UserNavbarPage.css">

<nav class="navbar">
    <div class="container">
        <div class="logo-container">
            <img src="img/home.png" alt="RentalHub Logo" class="logo-img">
            <span class="logo-text">RentalHub</span>
        </div>

        <div class="menu-toggle" aria-label="Toggle navigation" role="button" tabindex="0">
            &#8942;
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>

            <?php if (isset($_SESSION['user'])): ?>
                <li><span class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</span></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<script src="script.js"></script>
