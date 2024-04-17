<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data sent via POST
    $roomid = $_POST['roomid'];
    $password = $_POST['password'];

    // Prepare and execute SQL INSERT statement
    $sql = "INSERT INTO room (roomid, password) VALUES ('$roomid', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "New room added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
