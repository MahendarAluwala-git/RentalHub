<?php
include 'db.php';
session_start();

// Handle Add, Update, and Delete operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_property'])) {
        $stmt = $conn->prepare("INSERT INTO properties (title, location, price, description, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $_POST['title'], $_POST['location'], $_POST['price'], $_POST['description'], $_POST['image_url']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Property added successfully!";
    } elseif (isset($_POST['delete_property'])) {
        $stmt = $conn->prepare("DELETE FROM properties WHERE id = ?");
        $stmt->bind_param("i", $_POST['property_id']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Property deleted successfully!";
    } elseif (isset($_POST['edit_property'])) {
        $stmt = $conn->prepare("UPDATE properties SET title=?, location=?, price=?, description=?, image_url=? WHERE id=?");
        $stmt->bind_param("ssdssi", $_POST['title'], $_POST['location'], $_POST['price'], $_POST['description'], $_POST['image_url'], $_POST['property_id']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Property updated successfully!";
    }

    // Redirect to prevent form resubmission
    header("Location: manage_properties.php");
    exit();
}

// Fetch properties
$result = $conn->query("SELECT * FROM properties");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Properties</title>
    <link rel="stylesheet" href="css/Admin-Styles.css">
    <script>
        
        window.onload = function() {
            <?php if(isset($_SESSION['message'])): ?>
                alert("<?php echo $_SESSION['message']; ?>");
                <?php unset($_SESSION['message']); ?> 
            <?php endif; ?>
        };
    </script>
</head>
<body>
<?php include('admin_navbar.php'); ?>

<h1>Add Properties</h1>
<!-- Add Property Form -->
<form method="POST">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="location" placeholder="Location" required>
    <input type="number" name="price" placeholder="Price" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="text" name="image_url" placeholder="Image URL" required>
    <button type="submit" name="add_property">Add Property</button>
</form>

<h2>Existing Properties</h2>
<table>
    <tr>
        <th>Title</th>
        <th>Location</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo htmlspecialchars($row['title']); ?></td>
        <td><?php echo htmlspecialchars($row['location']); ?></td>
        <td>₹<?php echo htmlspecialchars($row['price']); ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="property_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="delete_property">Delete</button>
            </form>
            <button onclick="openEditForm('<?php echo $row['id']; ?>', '<?php echo htmlspecialchars($row['title']); ?>', '<?php echo htmlspecialchars($row['location']); ?>', '<?php echo htmlspecialchars($row['price']); ?>', '<?php echo htmlspecialchars($row['description']); ?>', '<?php echo htmlspecialchars($row['image_url']); ?>')">Edit</button>
        </td>
    </tr>
    <?php } ?>
</table>

<!-- Edit Property Form (Hidden Modal) -->
<div id="editForm" style="display:none;">
    <h2>Edit Property</h2>
    <form method="POST">
        <input type="hidden" name="property_id" id="editPropertyId">
        <input type="text" name="title" id="editTitle" required>
        <input type="text" name="location" id="editLocation" required>
        <input type="number" name="price" id="editPrice" required>
        <textarea name="description" id="editDescription" required></textarea>
        <input type="text" name="image_url" id="editImageUrl" required>
        <button type="submit" name="edit_property">Update Property</button>
    </form>
</div>

<script>
function openEditForm(id, title, location, price, description, imageUrl) {
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('editPropertyId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editLocation').value = location;
    document.getElementById('editPrice').value = price;
    document.getElementById('editDescription').value = description;
    document.getElementById('editImageUrl').value = imageUrl;
}
</script>
</body>
</html>
