<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About RentalHub</title>
    <link rel="stylesheet" href="css/AboutusPage.css">
</head>
<body>
    <header>
        <?php include 'user_navbar.php'; ?>
    </header>

    <main>
        <section class="about-us">
            <div class="container">
                <h1>About RentalHub</h1>
                <p>At RentalHub, we are dedicated to connecting tenants and landlords in
                     the simplest and most effective way possible. Our platform is designed to make the rental 
                     process seamless if you're looking for a new home.</p>
                <p>With a wide range of rental properties across major cities, we aim to cater to everyone’s needs.
                     From cozy studio apartments to spacious villas, RentalHub
                      is your one-stop solution for all rental requirements.</p>

                <div class="team">
                    <h2>Meet Our Team</h2>
                    <div class="team-grid">
                        <div class="team-member">
                            <img src="img/worker1.jpg" alt="Team Member 1">
                            <h3>Abhay Salunkhe</h3>
                            <p>Founder & Landlord</p>
                        </div>
                        <div class="team-member">
                            <img src="img/worker2.jpg" alt="Team Member 2">
                            <h3>Mahendar Aluwala</h3>
                            <p>Operations Manager</p>
                        </div>
                        <div class="team-member">
                            <img src="img/worker3.jpg" alt="Team Member 3">
                            <h3>Rohan Pawar</h3>
                            <p>Technical Lead</p>
                        </div>
                        <div class="team-member">
                            <img src="img/worker4.jpg" alt="Team Member 4">
                            <h3>Aryan Kamble</h3>
                            <p>Lead Developer</p>
                        </div>
                    </div>
                </div>

                <div class="mission-vision">
                    <h2>Our Mission & Vision</h2>
                    <p><strong>Mission:</strong> To simplify the rental process for everyone, making it transparent and hassle-free.</p>
                    <p><strong>Vision:</strong> To become the most trusted rental platform globally, offering innovative solutions for landlords and tenants alike.</p>
                </div>

                <div class="gallery">
                    <h2>Our Office & Community</h2>
                    <div class="gallery-grid">
                        <img src="img/office1.jpg" alt="Office Image 1">
                        <img src="img/office2.jpg" alt="Office Image 2">
                        <img src="img/office3.jpg" alt="Office Image 3">
                    </div>
                </div>

                <div class="contact-us">
    <h2>Contact Us</h2>
    <div class="contact-content">
        <div class="contact-info">
            <p><strong>Address:</strong> Flat No. 303, Bldg No. 6, Atlanta Residency, Anjurphata, Bhiwandi, Dist Thane, 421305</p>
            <p><strong>Phone:</strong> 9960259300 / +91 1234567890</p>
            <p><strong>Email:</strong> rentalhub@gmail.com</p>

            <!-- Social Media Links -->
            <div class="social-links">
                <h3>Follow Us</h3>
                <a href="#"><img src="img/facebook.png" alt="Facebook"></a>
                <a href="#"><img src="img/instagram.png" alt="Instagram"></a>
                <a href="#"><img src="img/twitter.png" alt="Twitter"></a>
                <a href="#"><img src="img/linkedin.png" alt="LinkedIn"></a>
            </div>
        </div>

        <!-- Google Maps Embed with Updated Location -->
        <div class="map-container">
            <h3>Find Us on Google Maps</h3>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3767.6201519030517!2d73.04537991536929!3d19.277537350401327!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7be5a3a02b7d1%3A0x1eabf40cf92673d1!2sOswal%20College%2C%20Kamatghar%2C%20Bhiwandi%2C%20Maharashtra%20421305!5e0!3m2!1sen!2sin!4v1711381942043!5m2!1sen!2sin" 
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>
</div>
 </div>
    </section>
    </main>
   
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
