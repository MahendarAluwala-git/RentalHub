<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Access Denied! Admins only.'); window.location.href='login.php';</script>";
    exit;
}

// Handle tenant deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_tenant'])) {
    $tenant_id = intval($_POST['tenant_id']);
    $delete_query = "DELETE FROM tenants WHERE id = $tenant_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Tenant deleted successfully.'); window.location.href='rental_agreements.php';</script>";
        exit;
    } else {
        die("Error deleting tenant: " . mysqli_error($conn));
    }
}

// Fetch tenant data with current month rent summary
$tenants_query = "
SELECT 
    tenants.id, tenants.name, tenants.email, tenants.phone,
    tenants.agreement_start_date, tenants.agreement_end_date,
    tenants.rental_agreement, tenants.inquiries,
    properties.title, properties.price AS monthly_rent,
    COALESCE((
        SELECT SUM(amount) 
        FROM rent_payments 
        WHERE tenant_id = tenants.id 
        AND MONTH(payment_date) = MONTH(CURDATE()) 
        AND YEAR(payment_date) = YEAR(CURDATE())
    ), 0) AS paid_this_month
FROM tenants
JOIN properties ON tenants.rental_property_id = properties.id
";

$tenants_result = mysqli_query($conn, $tenants_query);
if (!$tenants_result) {
    die("Error fetching tenants: " . mysqli_error($conn));
}

// Handle new tenant addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_tenant'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $rental_property_id = intval($_POST['rental_property_id']);
    $start_date = $_POST['agreement_start_date'];
    $end_date = $_POST['agreement_end_date'];
    $rental_agreement = mysqli_real_escape_string($conn, $_POST['rental_agreement']);
    $inquiries = mysqli_real_escape_string($conn, $_POST['inquiries']);

    $insert_query = "INSERT INTO tenants (name, email, phone, rental_property_id, agreement_start_date, agreement_end_date, rental_agreement, inquiries)
                     VALUES ('$name', '$email', '$phone', '$rental_property_id', '$start_date', '$end_date', '$rental_agreement', '$inquiries')";

    if (mysqli_query($conn, $insert_query)) {
        echo "<script>alert('Tenant added successfully!'); window.location.href='rental_agreements.php';</script>";
        exit;
    } else {
        die("Error adding tenant: " . mysqli_error($conn));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rental Agreements</title>
    <link rel="stylesheet" href="css/Admin-Styles.css">
</head>
<body>
<?php include 'admin_navbar.php'; ?>
<div class="form-container">
        <h2>Add New Tenant</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Tenant Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone" required>
            <select name="rental_property_id" required>
                <option value="">Select Property</option>
                <?php
               $properties_query = "SELECT * FROM properties WHERE id NOT IN (SELECT rental_property_id FROM tenants)";

                $properties_result = mysqli_query($conn, $properties_query);
                while ($property = mysqli_fetch_assoc($properties_result)) {
                    echo "<option value='{$property['id']}'>{$property['title']}</option>";
                }
                ?>
            </select>
            <br><br>
            <label for="agreement_start_date">Agreement Start Date:</label>
            <input type="date" name="agreement_start_date" required>

            <label for="agreement_end_date">Agreement End Date:</label>
            <input type="date" name="agreement_end_date" required>

            <textarea name="rental_agreement" placeholder="Rental Agreement Details" required></textarea><br>
            <textarea name="inquiries" placeholder="Inquiries (Optional)"></textarea>

            <button type="submit" name="add_tenant">Add Tenant</button>
        </form>
    </div>
    <br>
<h2>Existing Tenants</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Property</th>
        <th>Agreement Start</th>
        <th>Agreement End</th>
        <th>Rental Agreement</th>
        <th>Payment Status (This Month)</th>
        <th>Inquiries</th>
        <th>Action</th>
    </tr>
    <?php while ($tenant = mysqli_fetch_assoc($tenants_result)) {
        $due = $tenant['monthly_rent'];
        $paid = $tenant['paid_this_month'];
        $balance = $due - $paid;
        ?>
        <tr>
            <td><?= htmlspecialchars($tenant['name']); ?></td>
            <td><?= htmlspecialchars($tenant['email']); ?></td>
            <td><?= htmlspecialchars($tenant['phone']); ?></td>
            <td><?= htmlspecialchars($tenant['title']); ?></td>
            <td><?= htmlspecialchars($tenant['agreement_start_date']); ?></td>
            <td><?= htmlspecialchars($tenant['agreement_end_date']); ?></td>
            <td><?= htmlspecialchars($tenant['rental_agreement']); ?></td>
            <td>
                <?php
                if ($balance > 0 && $paid > 0) {
                    echo "<span style='color: orange; font-weight: bold;'>Partial (₹" . number_format($balance, 2) . ")</span>";
                } elseif ($balance == 0) {
                    echo "<span style='color: green; font-weight: bold;'>Paid</span>";
                } elseif ($balance < 0) {
                    echo "<span style='color: blue; font-weight: bold;'>Overpaid (₹" . number_format(abs($balance), 2) . ")</span>";
                } else {
                    echo "<span style='color: red; font-weight: bold;'>Pending (₹" . number_format($due, 2) . ")</span>";
                }
                ?>
            </td>
            <td><?= htmlspecialchars($tenant['inquiries']); ?></td>
            <td>
                <form method="POST" onsubmit="return confirm('Delete this tenant?');">
                    <div>
                    <input type="hidden" name="tenant_id" value="<?= $tenant['id']; ?>">
                    <button type="submit" name="delete_tenant" style="background: red; color: white; margin-right:75px;">Delete</button>
                    </div>
                    </form>
            </td>
        </tr>
    <?php } ?>
</table>
</body>
</html>
