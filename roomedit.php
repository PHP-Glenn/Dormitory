<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roomid = $_POST['roomid'];
    $newPassword = $_POST['new_password'];

    // Update the password in the database
    $updateSql = "UPDATE room SET password = '$newPassword' WHERE roomid = '$roomid'"; // Added quotes around $roomid
    if ($conn->query($updateSql) === TRUE) {
        echo "Password updated successfully";
    } else {
        echo "Error updating password: " . $conn->error;
    }
} else {
    echo "Invalid request";
}

$conn->close();
?>
