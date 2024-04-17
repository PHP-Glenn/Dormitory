<?php
// Include the database connection file
include 'db_connect.php';

// Check if the ID parameter is set
if(isset($_GET['id'])) {
    // Sanitize the ID parameter to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // SQL query to delete the announcement with the given ID
    $sql = "DELETE FROM announcement WHERE id = '$id'";

    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the page where announcements are displayed
        header("Location: index.php?page=localannounce");
        exit();
    } else {
        // If deletion fails, display an error message
        echo "Error deleting record: " . $conn->error;
    }
} else {
    // If ID parameter is not set, display an error message
    echo "ID parameter is missing.";
}

// Close the database connection
$conn->close();
?>