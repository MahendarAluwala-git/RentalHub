<?php
session_start();
include 'db.php';

// Ensure the user is an admin
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Access Denied! Admins only.'); window.location.href='login.php';</script>";
    exit;
}

// Delete payments from the previous month
$last_month = date('Y-m-d', strtotime('first day of last month'));
$next_month = date('Y-m-d', strtotime('first day of next month'));

$delete_last_month_query = "
    DELETE FROM rent_payments
    WHERE payment_date < '$last_month' AND payment_date >= '$next_month'
";

mysqli_query($conn, $delete_last_month_query);

// Handle delete payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_payment'])) {
    $payment_id = intval($_POST['payment_id']);
    $delete_query = "DELETE FROM rent_payments WHERE id = $payment_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Payment deleted successfully.'); window.location.href='tenant_payment_history.php';</script>";
        exit;
    } else {
        die("Error deleting payment: " . mysqli_error($conn));
    }
}

// Handle new payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    $tenant_id = intval($_POST['tenant_id']);
    $amount = floatval($_POST['amount']);
    $payment_date = $_POST['payment_date'];

    $property_query = "SELECT rental_property_id FROM tenants WHERE id = '$tenant_id'";
    $property_result = mysqli_query($conn, $property_query);
    $property = mysqli_fetch_assoc($property_result);
    $property_id = $property['rental_property_id'];

    $query = "INSERT INTO rent_payments (tenant_id, property_id, payment_date, amount) 
              VALUES ('$tenant_id', '$property_id', '$payment_date', '$amount')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Payment added successfully!'); window.location.href='tenant_payment_history.php';</script>";
        exit;
    } else {
        die("Error adding payment: " . mysqli_error($conn));
    }
}

// Fetch current month payment history
$query_current_month = "
SELECT rent_payments.id, tenants.name, properties.title, properties.price AS monthly_rent,
       rent_payments.amount, rent_payments.payment_date,
       COALESCE((
           SELECT SUM(amount)
           FROM rent_payments
           WHERE tenant_id = tenants.id
           AND MONTH(payment_date) = MONTH(CURDATE())
           AND YEAR(payment_date) = YEAR(CURDATE())
       ), 0) AS total_paid_this_month
FROM rent_payments
JOIN tenants ON rent_payments.tenant_id = tenants.id
JOIN properties ON tenants.rental_property_id = properties.id
WHERE MONTH(rent_payments.payment_date) = MONTH(CURDATE()) 
AND YEAR(rent_payments.payment_date) = YEAR(CURDATE())
ORDER BY rent_payments.payment_date DESC
";

$result_current_month = mysqli_query($conn, $query_current_month);
if (!$result_current_month) {
    die("Error fetching current month payments: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tenant Payment History</title>
    <link rel="stylesheet" href="css/Admin-Styles.css">
</head>
<body>
<?php include 'admin_navbar.php'; ?>
<div class="form-container">
    <h2>Add New Payment</h2>
    <form method="POST">
        <label>Select Tenant:</label>
        <select name="tenant_id" required>
            <option value="">Select Tenant</option>
            <?php
            $tenant_result = mysqli_query($conn, "SELECT id, name FROM tenants");
            while ($tenant = mysqli_fetch_assoc($tenant_result)) {
                echo "<option value='{$tenant['id']}'>{$tenant['name']}</option>";
            }
            ?>
        </select><br><br>
        <label>Amount Paid:</label>
        <input type="number" name="amount" step="0.01" required>
        <label>Payment Date:</label>
        <input type="date" name="payment_date" required>
        <button type="submit" name="add_payment">Add Payment</button>
    </form>
</div>
<h2>Tenant Payment History</h2>
<table>
    <tr>
        <th>Tenant</th>
        <th>Property</th>
        <th>Amount Paid</th>
        <th>Payment Date</th>
        <th>Status (This Month)</th>
        <th>Action</th>
    </tr>
    <?php while ($payment = mysqli_fetch_assoc($result_current_month)) {
        $due = $payment['monthly_rent'];
        $paid = $payment['total_paid_this_month'];
        $balance = $due - $paid;
        ?>
        <tr>
            <td><?= htmlspecialchars($payment['name']); ?></td>
            <td><?= htmlspecialchars($payment['title']); ?></td>
            <td>₹<?= number_format($payment['amount'], 2); ?></td>
            <td><?= htmlspecialchars($payment['payment_date']); ?></td>
            <td>
                <?php
                if ($balance > 0 && $paid > 0) {
                    echo "<span style='color: orange;'>Partial (₹" . number_format($balance, 2) . ")</span>";
                } elseif ($balance == 0) {
                    echo "<span style='color: green;'>Paid</span>";
                } elseif ($balance < 0) {
                    echo "<span style='color: blue;'>Overpaid (₹" . number_format(abs($balance), 2) . ")</span>";
                } else {
                    echo "<span style='color: red;'>Pending (₹" . number_format($due, 2) . ")</span>";
                }
                ?>
            </td>
            <td>
                <form method="POST" onsubmit="return confirm('Delete this payment?');">
                    <input type="hidden" name="payment_id" value="<?= $payment['id']; ?>">
                    <button type="submit" name="delete_payment" style="background: red; color: white;">Delete</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
</body>
</html>
