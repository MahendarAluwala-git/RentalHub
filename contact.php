<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact RentalHub</title>
    <link rel="stylesheet" href="css/ContactPage.css">
</head>
<body>
    <header>
    <?php include 'user_navbar.php'; ?>
    </header>

    <section class="contact">
        <div class="container">
            <h1>Contact Us</h1>
            <form method="POST" action="send_contact.php">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea name="message" rows="5" required></textarea>
                </div>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>

    
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
