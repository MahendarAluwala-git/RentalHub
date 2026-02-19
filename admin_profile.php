<?php
session_start();
include 'db.php';

$admin = $_SESSION['admin'];

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['password'];

    // Update admin password
    $query = "UPDATE admins SET password='$new_password' WHERE username='$admin'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Profile updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating profile');</script>";
    }
}

// Fetch admin details
$query = "SELECT * FROM admins WHERE username='$admin'";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="css/Admin-Styles.css">
</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <div class="admin-profile">
        <h2>Admin Profile</h2>
        <form method="POST">
            <label>Username</label>
            <input type="text" value="<?php echo htmlspecialchars($admin['username']); ?>" readonly>
            <label>New Password</label>
            <input type="password" name="password" required>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
