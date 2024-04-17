<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli('localhost', 'root', '', 'house_rental_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user input
    $roomid = $_POST['roomid'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $query = "SELECT * FROM room WHERE roomid=? AND password=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $roomid, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, set session and redirect
        $_SESSION['roomid'] = $roomid;
        header("Location: clienthome.php");
        exit();
    } else {
        // Invalid credentials
        $_SESSION['login_error'] = "Invalid Room ID or Password";
        $_SESSION['roomid'] = $roomid; // Store entered roomid for displaying in the form
        header("location: loginclient.php");
        exit();
    }

    // Close the prepared statement
    $stmt->close();
} 
?>
