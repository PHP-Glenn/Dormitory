<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $announcementContent = $_POST['announcement_content'];

    // Insert the announcement into the database
    $insertQuery = "INSERT INTO announcement (announce) VALUES ('$announcementContent')";

    if ($conn->query($insertQuery) === true) {
        echo 'Announcement saved successfully.';

        // Get the Semaphore API key and message content
        $apiKey = $_POST['apikey'];
        $number = $_POST['number'];
        $message = $_POST['message'];

        // Send the message using Semaphore API
        $parameters = [
            'apikey' => $apiKey,
            'number' => $number,
            'message' => $message,
        ];

        $ch = curl_init('https://api.semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));

        $response = curl_exec($ch);
        curl_close($ch);

        // Check the response from the Semaphore API
        if ($response === false) {
            echo 'Error sending message through Semaphore API.';
        } else {
            echo 'Message sent successfully through Semaphore API.';
        }
    } else {
        echo 'Error saving announcement to the database: ' . $conn->error;
    }

    exit; // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement Dashboard For SMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       body {
        
        margin: 50px;
        overflow-x: hidden; /* Add this to hide the horizontal scrollbar */
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        margin-right: 100px;
    }

    #messageform {
        max-width: 80%; /* Change 'width' to 'max-width' */
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-left: 50px;
    }

    label, input, textarea {
        display: block;
        margin: 1rem 0;
    }

    textarea {
        width: 100%;
    }

    button {
        margin-top: 10px;
    }
</style>



</head>
<body>
    <link rel="shortcut icon" href="/" type="image/x-icon">
    <h1>Announcement for SMS</h1>
    <form id="messageform">
    <label for="apikey">API KEY</label>
    <input type="text" id="apikey" name="apikey" class="form-control" required>
    <label for="number">Number</label>
    <input type="text" id="number" name="number" class="form-control">
    <label for="message">Message</label>
    <textarea id="message" name="message" class="form-control" rows="5"></textarea>
    <button type="submit" class="btn btn-primary">Send Message</button>
</form>
    <script src="sema.js"></script>
  
    <script>
    function submitAnnouncement() {
        // Get the form data
        var formData = $('#announcement-form').serialize();

        // Using AJAX to submit the form data
        $.ajax({
            type: 'POST',
            url: 'announce.php',
            data: formData,
            success: function (response) {
                alert(response); // Display the response from the server
                $('#announcement-form')[0].reset();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
</script>

</body>
</html>
