<?php
session_start();
include 'db.php';

// Check if property ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid property selection.'); window.location.href='index.php';</script>";
    exit;
}

$property_id = intval($_GET['id']);

// Fetch property details
$query = "SELECT * FROM properties WHERE id = $property_id";
$result = mysqli_query($conn, $query);
$property = mysqli_fetch_assoc($result);

// If property not found, redirect to home
if (!$property) {
    echo "<script>alert('Property not found.'); window.location.href='index.php';</script>";
    exit;
}

// Handle booking request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    if (!isset($_SESSION['user'])) {
        echo "<script>alert('You must be logged in to book an appointment.'); window.location.href='login.php';</script>";
        exit;
    }

    $name = mysqli_real_escape_string($conn, $_POST['name']); // Capture name input
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $user_name = $_SESSION['user']; // Logged-in username

    $query = "INSERT INTO appointments (user_name, name, property_id, mobile, address, message, status) 
    VALUES ('$user_name', '$name', $property_id, '$mobile', '$address', '$message', 'Pending')";


if (mysqli_query($conn, $query)) {
    echo "<script>alert('Appointment request sent successfully! You will be notified when the admin responds.'); window.location.href='index.php?id=$property_id';</script>";
} else {
    die("Error booking appointment: " . mysqli_error($conn)); // Show MySQL error
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Property Details</title>
    <link rel="stylesheet" href="css/ViewDetailsPage.css">
</head>
<body>
    <?php include 'user_navbar.php'; ?>

    <div class="property-details-container">
        <h2><?php echo htmlspecialchars($property['title']); ?></h2>
        <img src="<?php echo $property['image_url']; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
        <p><strong>Location:</strong> <?php echo htmlspecialchars($property['location']); ?></p>
        <p><strong>Price:</strong> Rs.<?php echo htmlspecialchars($property['price']); ?> per month</p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($property['description'])); ?></p>

        <h3>Book an Appointment</h3>
        <form method="POST">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="mobile">Mobile Number:</label>
            <input type="text" id="mobile" name="mobile" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit" name="book_appointment" class="book-btn">Book Appointment</button>
        </form>

        <a href="index.php" class="back-btn">🔙 Back to Home</a>
    </div>
</body>
</html>
