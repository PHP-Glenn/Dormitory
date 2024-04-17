<?php
// Include your database connection file here
include 'db_connect.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if announcement content is set
    if (isset($_POST['announcement_content'])) {
        $announcementContent = $_POST['announcement_content'];
        
        // Insert into the database
        $sql = "INSERT INTO announcement (announce) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $announcementContent);
        
        if ($stmt->execute()) {
            echo json_encode(array('status' => 'success'));
            exit(); // Terminate script after echoing JSON response
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-x6tkfi+D0lgOszWPS0fTI5yGvzlk84xi9FolH4J8/45gTKuBXzF8Zo+2BIlJKwfu4vwu0/5gRXSbQW1ef/wu6Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11"></link>
    <title>Announcement</title>

    <style>
        .container {
            max-width: 800px; 
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            animation: fadeInDown 1s ease;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-container {
            text-align: center;
        }

        .form-textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            resize: vertical;
            margin-bottom: 20px;
            height: 300px;
            font-size: 16px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .submit-button,
        .view-button {
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            animation: slideInLeft 1s ease;
        }

        .submit-button {
            background-color: #007bff;
            color: #fff;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }

        .view-button {
            background-color: #28a745;
            color: #fff;
        }

        .view-button:hover {
            background-color: #218838;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>  
    <div class="container">
        <div class="title">Send Announcement</div>
        <div class="form-container">
            <form id="announcement-form" method="post">
                <textarea class="form-textarea" name="announcement_content" placeholder="Write your announcement here..." required></textarea>
                <div class="button-container">
                    <button class="submit-button" type="button" onclick="submitAnnouncement()">Send Announcement</button>
                    <a href="view.php" class="view-button">View Announcements</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function submitAnnouncement() {
        // Get the form data
        var formData = $('#announcement-form').serialize();

        // Using AJAX to submit the form data
        $.ajax({
            type: 'POST',
            url: 'localannounce.php',
            data: formData,
            dataType: 'json', // Expect JSON response
            success: function (response) {
                if (response.status === 'success') {
                    // Display success message using SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Announcement Sent',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // Clear the form
                    $('#announcement-form')[0].reset();
                } else {
                    // Display error message using SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                }
            },
            error: function (error) {
                console.log(error);
                // Display error message using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                });
            }
        });
    }
</script> 
</body>
</html>