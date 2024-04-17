<?php
session_start();
include 'db_connect.php'; 
if (!isset($_SESSION['roomid'])) {
    header("Location: loginclient.php"); // Redirect to the login page
    exit();
}

$message = ''; // Variable to store alert message

// Fetch the name of the tenant corresponding to the room ID
$roomid = $_SESSION['roomid'];
$tenant_name = '';
$result = $conn->query("SELECT CONCAT(firstname, ' ', middlename, ' ', lastname) AS name FROM tenants WHERE house_id = '$roomid'");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tenant_name = $row['name'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['feedback'])) {
    $name = $_POST['name'];
    $feedback = $_POST['feedback'];

    // Prepare and execute SQL statement to insert feedback into the database
    $stmt = $conn->prepare("INSERT INTO feedback (name, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $feedback);
    if ($stmt->execute()) {
        // Feedback inserted successfully
        $message = "Feedback submitted successfully";
    } else {
        // Error occurred while inserting feedback
        $message = "Error: Feedback submission failed";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Feedback</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control {
            border-radius: 20px;
        }
        .btn-submit {
            background-color: #007bff;
            color: #fff;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            width: 100%;
            margin-top: 20px;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .top-bar {
            background-color: #343a40;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-bar h3 {
            margin: 0;
            padding-left: 10px;
        }
        .dropdown {
            position: relative;
        }
        .dropdown-button {
            color: white;
            padding: 10px 20px;
            cursor: pointer;
            background-color: transparent;
            border: none;
            outline: none;
            display: flex;
            align-items: center;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #343a40;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            right: 0;
            border-radius: 5px;
            z-index: 1;
        }
        .dropdown-content a {
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            color: white;
        }
        .dropdown-content a:hover {
            background-color: #495057;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <h3>Send Feedback</h3>
        <div class="dropdown">
            <button class="dropdown-button"><b>Menu</b><i class="fas fa-chevron-down ml-2"></i></button>
            <div class="dropdown-content">
                <a href="clienthome.php">Status</a>
                <a href="announcecont.php">Announcement</a>
                <a id="logout-button">Log Out</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>Submit Your Feedback</h2>
        <?php if ($message): ?>
            <div class="alert alert-success" role="alert"><?php echo $message; ?></div>
        <?php endif; ?>
        <form id="feedback-form" method="POST">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $tenant_name; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="feedback">Feedback:</label>
                <textarea id="feedback" name="feedback" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-submit">Submit Feedback</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#feedback-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'sendfeedback.php',
                data: formData,
                success: function(response) {
                    // Reset form
                    $('#feedback-form')[0].reset();
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Feedback Sent',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(error) {
                    console.log(error);
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                }
            });
        });

        $('#logout-button').click(function () {
            $.ajax({
                type: 'POST',
                url: 'logoutclient.php',
                success: function (response) {
                    window.location.href = 'loginclient.php';
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    </script>
</body>
</html>
