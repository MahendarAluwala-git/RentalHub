<?php
include 'db.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=property_report.csv');

$output = fopen("php://output", "w");
fputcsv($output, ['ID', 'Title', 'Location', 'Price', 'Description']);

$result = mysqli_query($conn, "SELECT id, title, location, price, description FROM properties");
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
fclose($output);
exit;
