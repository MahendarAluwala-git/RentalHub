<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['property_id'];
    $title = $_POST['title'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    $query = "UPDATE properties SET title='$title', location='$location', price='$price', description='$description', image_url='$image_url' WHERE id=$id";
    mysqli_query($conn, $query);
    echo "<script>alert('Property updated successfully'); window.location='manage_properties.php';</script>";
}

// Fetch property details
$id = $_GET['id'];
$query = "SELECT * FROM properties WHERE id=$id";
$result = mysqli_query($conn, $query);
$property = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Property</title>
    <link rel="stylesheet" href="css/Admin-Styles.css">
</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <h2>Edit Property</h2>
    <form method="POST">
        <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
        <input type="text" name="title" value="<?php echo $property['title']; ?>" required>
        <input type="text" name="location" value="<?php echo $property['location']; ?>" required>
        <input type="number" name="price" value="<?php echo $property['price']; ?>" required>
        <textarea name="description" required><?php echo $property['description']; ?></textarea>
        <input type="text" name="image_url" value="<?php echo $property['image_url']; ?>" required>
        <button type="submit">Update Property</button>
    </form>
</body>
</html>
