<?php
session_start();
include 'db.php';

// Fetch data for dashboard overview
$total_properties = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM properties"))['total'];
$total_tenants = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tenants"))['total'];
$total_rent_due_query = "
    SELECT COUNT(*) AS total FROM tenants 
    WHERE rental_property_id IN (
        SELECT id FROM properties 
        WHERE id NOT IN (
            SELECT DISTINCT property_id FROM rent_payments WHERE status = 'Paid'
        )
    )";
    
$total_rent_due_result = mysqli_query($conn, $total_rent_due_query);
if (!$total_rent_due_result) {
    die("Error fetching total rent due: " . mysqli_error($conn));
}

$total_rent_due = mysqli_fetch_assoc($total_rent_due_result)['total'];
$total_agreements = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tenants WHERE rental_agreement IS NOT NULL"))['total'];

// Fetch recent tenant applications
$recent_tenants = mysqli_query($conn, "SELECT name, phone, agreement_start_date FROM tenants ORDER BY id DESC LIMIT 5");

// Fetch recent rent payments
$recent_payments = mysqli_query($conn, "SELECT tenants.name, rent_payments.amount, rent_payments.payment_date 
                                        FROM rent_payments 
                                        JOIN tenants ON rent_payments.tenant_id = tenants.id 
                                        ORDER BY rent_payments.payment_date DESC LIMIT 5");

// Fetch overdue rent payments
$overdue_payments = mysqli_query($conn, "SELECT tenants.name, properties.price, 
                                            COALESCE((SELECT SUM(amount) FROM rent_payments 
                                                      WHERE rent_payments.tenant_id = tenants.id), 0) AS total_paid
                                         FROM tenants 
                                         JOIN properties ON tenants.rental_property_id = properties.id
                                         WHERE COALESCE((SELECT SUM(amount) FROM rent_payments 
                                                         WHERE rent_payments.tenant_id = tenants.id), 0) < properties.price
                                         ORDER BY properties.price DESC LIMIT 5");

if (!$overdue_payments) {
    die("Error fetching overdue payments: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/Admin-Styles.css">
</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <div class="admin-overview">
        <h2>Dashboard Overview</h2>
        <div class="overview-cards">
            <div class="card">
                <h3>Total Properties</h3>
                <p><?php echo $total_properties; ?></p>
            </div>
            <div class="card">
                <h3>Total Tenants</h3>
                <p><?php echo $total_tenants; ?></p>
            </div>
            <div class="card">
                <h3>Rent Due</h3>
                <p><?php echo $total_rent_due; ?></p>
            </div>
            <div class="card">
                <h3>Total Agreements</h3>
                <p><?php echo $total_agreements; ?></p>
            </div>
        </div>
    </div>
<br>
    <div class="quick-actions">
<h3>Generate Reports</h3>
    <button onclick="window.location.href='export_tenants.php'">Download Tenant Report</button>
    <button onclick="window.location.href='export_properties.php'">Download Property Report</button>
    <button onclick="window.location.href='export_payment.php'">Download Payment Report</button>
</div>
<br>
    <div class="dashboard-sections">
        <!-- Recent Tenants Section -->
        <div class="dashboard-box">
            <h3>Recent Tenant Applications</h3>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Agreement Start Date</th>
                </tr>
                <?php while ($tenant = mysqli_fetch_assoc($recent_tenants)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($tenant['name']); ?></td>
                    <td><?php echo htmlspecialchars($tenant['phone']); ?></td>
                    <td><?php echo htmlspecialchars($tenant['agreement_start_date']); ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Recent Rent Payments Section -->
        <div class="dashboard-box">
            <h3>Recent Rent Payments</h3>
            <table>
                <tr>
                    <th>Tenant Name</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                </tr>
                <?php while ($payment = mysqli_fetch_assoc($recent_payments)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($payment['name']); ?></td>
                    <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                    <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Overdue Rent Payments -->
        <div class="dashboard-box">
    <h3>Overdue Rent Payments</h3>
    <table>
        <tr>
            <th>Tenant Name</th>
            <th>Amount Due</th>
        </tr>
        <?php while ($due = mysqli_fetch_assoc($overdue_payments)) { 
            $amount_due = $due['price'] - $due['total_paid'];
            ?>
            <tr>
                <td style="color: red;"><?php echo htmlspecialchars($due['name']); ?></td>
                <td style="color: red;">₹<?php echo number_format($amount_due, 2); ?> (Due)</td>
            </tr>
        <?php } ?>
    </table>
</div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Quick Actions</h3>
        <button onclick="window.location.href='manage_properties.php'">Manage Property</button>
        <button onclick="window.location.href='rental_agreements.php'">Manage Tenants</button>
    </div>
    <br><br>
</body>
</html>
