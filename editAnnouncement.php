<?php
// Include the database connection file
include 'db_connect.php';

// Check if the ID and content parameters are set
if(isset($_POST['id']) && isset($_POST['content'])) {
    // Sanitize the ID and content parameters to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // SQL query to update the announcement with the given ID
    $sql = "UPDATE announcement SET announce = '$content' WHERE id = '$id'";

    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        // Send a response back to the client
        echo json_encode(array("success" => true, "message" => "Announcement updated successfully"));
    } else {
        // If update fails, send an error response back to the client
        echo json_encode(array("success" => false, "message" => "Error updating record: " . $conn->error));
    }
} else {
    // If ID or content parameter is missing, send an error response back to the client
    echo json_encode(array("success" => false, "message" => "ID or content parameter is missing"));
}

// Close the database connection
$conn->close();
?>