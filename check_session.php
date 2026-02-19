<?php
session_start();

$response = [];

if (isset($_SESSION['user'])) {
    $response['status'] = 'logged_in';
    $response['username'] = $_SESSION['user'];
} else {
    $response['status'] = 'logged_out';
}

echo json_encode($response);
?>
