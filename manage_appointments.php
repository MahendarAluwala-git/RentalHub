<?php
session_start();
include 'db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Access Denied! Admins only.'); window.location.href='login.php';</script>";
    exit;
}

// Handle appointment status updates
if (isset($_POST['update_status'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $new_status = $_POST['status'];

    $update_query = "UPDATE appointments SET status = '$new_status' WHERE id = $appointment_id";
    mysqli_query($conn, $update_query);

    // Fetch user details for notification
    $user_query = "SELECT user_name FROM appointments WHERE id = $appointment_id";
    $user_result = mysqli_query($conn, $user_query);
    $user_row = mysqli_fetch_assoc($user_result);
    $user_name = $user_row['user_name'];

    // Notification message to be stored for user
    if ($new_status === 'Accepted') {
        $notification_message = "Your appointment is accepted! You will be contacted by the landlord.";
    } else {
        $notification_message = "Your appointment has been rejected by the admin.";
    }

    // Store notification
    $notification_query = "INSERT INTO notifications (user_name, message) 
                           VALUES ('$user_name', '$notification_message')";
    mysqli_query($conn, $notification_query);

    // Final alert to admin only
    echo "<script>alert('Appointment updated successfully!'); window.location.href='manage_appointments.php';</script>";
}


// Get the selected status filter
// Get the selected status filter
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'Pending';

// Fetch appointments based on selected status
$query = "SELECT appointments.id, appointments.user_name, properties.title, 
                 appointments.mobile, appointments.address, appointments.message, appointments.status
          FROM appointments
          JOIN properties ON appointments.property_id = properties.id
          WHERE appointments.status = '$status_filter'";

$result = mysqli_query($conn, $query);
$total_appointments = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Appointments</title>
    <link rel="stylesheet" href="css/Admin-Styles.css">
</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <h2>Manage Appointments</h2>

   <!-- Filter Buttons -->
<div class="filter-buttons">
    <a href="manage_appointments.php?status=Pending" class="filter-btn <?php echo ($status_filter == 'Pending') ? 'active' : ''; ?>">View Pending</a>
    <a href="manage_appointments.php?status=Accepted" class="filter-btn <?php echo ($status_filter == 'Accepted') ? 'active' : ''; ?>">View Accepted</a>
    <a href="manage_appointments.php?status=Rejected" class="filter-btn <?php echo ($status_filter == 'Rejected') ? 'active' : ''; ?>">View Rejected</a>
</div>


<table>
    <tr>
        <th>User</th>
        <th>Property</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Message</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    
    <?php if ($total_appointments > 0) { ?>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <?php if ($row['status'] == 'Pending') { ?>
                        <form method="POST">
                            <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                            <select name="status">
                                <option value="Accepted">Accept</option>
                                <option value="Rejected">Reject</option>
                            </select>
                            <button type="submit" name="update_status">Update</button>
                        </form>
                    <?php } else { ?>
                        <span class="status-<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></span>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="7" class="no-data">No <?php echo $status_filter; ?> appointments found.</td>
        </tr>
    <?php } ?>
</table>


</body>
</html>
