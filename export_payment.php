<?php
include 'db.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=payment_report.csv');

$output = fopen("php://output", "w");
fputcsv($output, ['Tenant Name', 'Property Title', 'Amount', 'Payment Date']);

$query = "SELECT t.name AS tenant, p.title AS property, rp.amount, rp.payment_date
          FROM rent_payments rp
          JOIN tenants t ON rp.tenant_id = t.id
          JOIN properties p ON rp.property_id = p.id
          ORDER BY rp.payment_date DESC";

$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
fclose($output);
exit;
