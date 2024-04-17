<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Boarders!</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }

        .top-bar {
            background-color: #343a40;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar h2 {
            margin: 0;
            color: white;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-button {
            color: white;
            padding: 10px 20px;
            cursor: pointer;
            background-color: #343a40;
            border: none;
            outline: none;
            display: flex;
            align-items: center;
        }

        .dropdown-button::before {
            margin-right: 5px;
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
            cursor: pointer;
        }

        .dropdown-content a:hover {
            background-color: #6c757d;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .announcements-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .announcement-box {
            margin-bottom: 20px;
            border-left: 5px solid #343a40;
            padding-left: 10px;
        }

        .announcement-content {
            margin-bottom: 10px;
        }

        .announcement-time {
            font-style: italic;
            color: #6c757d;
        } #logout-button {
            color: white; /* Change the text color to white */
        }
    
    </style>
</head>

<body>
    <div class="top-bar">
        <h2>Checking Announcement as of <?php echo date('F, Y') ?></h2>
        <div class="dropdown">
            <button class="dropdown-button"><b>Menu</b><i class="fas fa-chevron-down ml-2"></i></button>
            <div class="dropdown-content">
                <a href="clienthome.php">Status</a>
                <a href="sendfeedback.php">Feedback</a>
                <a id="logout-button">Log Out</a>
            </div>
        </div>
    </div>

    <div class="announcements-container">
        <h1 class="text-center mb-4">Announcements:</h1>

        <?php
        // Include the database connection file
        include 'db_connect.php';

        // Fetch and display announcements
        $result = $conn->query("SELECT * FROM announcement ORDER BY id DESC");

        if ($result === false) {
            echo 'Error executing the query: ' . $conn->error;
        } else {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Display each announcement in a box with the creation time
                    echo '<div class="announcement-box">';
                    echo '<p class="announcement-content">' . $row['announce'] . '</p>';
                    echo '<p class="announcement-time">Created on: ' . $row['created_at'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">No announcements available.</p>';
            }
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        // Logout functionality using AJAX
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