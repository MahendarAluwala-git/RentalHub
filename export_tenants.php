<?php
include 'db.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=tenant_report.csv');

$output = fopen("php://output", "w");
fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Agreement Start', 'Agreement End', 'Property ID']);

$result = mysqli_query($conn, "SELECT id, name, email, phone, agreement_start_date, agreement_end_date, rental_property_id FROM tenants");
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
fclose($output);
exit;
