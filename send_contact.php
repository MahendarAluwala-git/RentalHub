<?php
session_start(); // Make sure this is at the very top with no space or HTML before it
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('You must be logged in to send a message.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Message sent successfully!');
            window.location.href='index.php';
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
