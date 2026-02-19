<?php
session_start();
include 'db.php';

// Fetch all contact messages
$sql = "SELECT * FROM contacts ORDER BY created_at DESC"; // Check table name
$result = $conn->query($sql);

// Check for SQL errors
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Feedbacks</title>
    <link rel="stylesheet" href="Admin-Styles.css">
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="admin-container">
    <h2>Feedback & Contact Messages</h2>

    <table class="feedback-table">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Submitted At</th>
        </tr>

        <?php 
        while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        <?php } ?>

    </table>
</div>

</body>
</html>
