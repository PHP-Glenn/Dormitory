<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve room ID sent via POST
    $roomid = $_POST['roomid'];

    // Prepare and execute SQL DELETE statement
    $sql = "DELETE FROM room WHERE roomid='$roomid'";
    if ($conn->query($sql) === TRUE) {
        echo "Room deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>