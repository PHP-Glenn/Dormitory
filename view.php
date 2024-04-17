<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-x6tkfi+D0lgOszWPS0fTI5yGvzlk84xi9FolH4J8/45gTKuBXzF8Zo+2BIlJKwfu4vwu0/5gRXSbQW1ef/wu6Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyW8fVkRx6a3PLZLzn3lEyExlkd+6S+XJj"
        crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checking of Announcements!</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #096CFF;
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid #084EB9;
            animation: fadeInDown 1s ease;
        }

        .header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 0;
        }

        .announcements-container {
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
        }

        .announcement-box {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease, opacity 1s ease; /* Add opacity transition */
            opacity: 0; /* Initially set opacity to 0 */
        }

        .announcement-box:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .announcement-content {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .announcement-time {
            font-size: 14px;
            color: #666;
        }

        .announcement-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .announcement-actions button {
            margin-left: 10px;
        }

        .redirect-button {
            font-weight: bold;
            font-size: 18px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .redirect-button:hover {
            background-color: #218838;
        }

        /* Redesigned buttons */
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-edit {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .btn-edit:hover {
            background-color: #0056b3;
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

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 30px;
            border: 1px solid #888;
            width: 70%;
            border-radius: 10px;
            animation: fadeInDown 0.5s ease forwards; /* Apply fadeInDown animation */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        textarea {
            width: 100%;
            height: 300px;
            resize: none;
            font-family: 'Arial', sans-serif;
            font-size: 20px; /* Adjust the font size as per your preference */
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
            cursor: pointer;
        }
    </style>

</head>

<body>
    <div class="header">
        <h1>Welcome Admin!</h1>
        <h2 class="lead">Checking Announcement as of <b><?php echo date('F Y') ?></b></h2>
        <button class="redirect-button mx-auto d-block mb-4"
            onclick="window.location.href='index.php?page=localannounce'">Back to Home</button>
    </div>
    <!-- Display Announcements -->
    <div class="container">
        <div class="announcements-container">
         

            <?php
            // Include the database connection file
            include 'db_connect.php';

            // Function to delete announcement
            function deleteAnnouncement($conn, $id) {
                $sql = "DELETE FROM announcement WHERE id = $id";
                if ($conn->query($sql) === TRUE) {
                    echo "<p class='text-success'>Announcement deleted successfully</p>";
                } else {
                    echo "<p class='text-danger'>Error deleting announcement: " . $conn->error . "</p>";
                }
            }

            // Function to update announcement
            function updateAnnouncement($conn, $id, $newContent) {
                $sql = "UPDATE announcement SET announce='$newContent' WHERE id=$id";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>Swal.fire('Success', 'Announcement updated successfully', 'success')</script>";
                } else {
                    echo "<p class='text-danger'>Error updating announcement: " . $conn->error . "</p>";
                }
            }

            // Fetch and display announcements based on the selected date
            if (isset($_GET['filter_date'])) {
                $filter_date = $_GET['filter_date'];
                $result = $conn->query("SELECT * FROM announcement WHERE DATE_FORMAT(created_at, '%Y-%m') = '$filter_date' ORDER BY id DESC");
            } else {
                $result = $conn->query("SELECT * FROM announcement ORDER BY id DESC");
            }

            if ($result === false) {
                echo 'Error executing the query: ' . $conn->error;
            } else {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
            <div class="announcement-box" style="animation: fadeInDown 1s ease forwards;">
                <p class="announcement-content" id="announcement_<?php echo $row['id']; ?>"><?php echo $row['announce']; ?></p>
                <p class="announcement-time">Created on: <?php echo $row['created_at']; ?></p>
                <div class="announcement-actions">
                    <!-- Redesigned buttons -->
                    <button class="btn btn-delete" onclick="deleteAnnouncement(<?php echo $row['id']; ?>)">Delete</button>
                    <button class="btn btn-edit" onclick="openEditModal(<?php echo $row['id']; ?>)">Edit</button>
                </div>
            </div>
            <?php
                    }
                } else {
                    echo '<p class="text-center">No announcements available.</p>';
                }
            }

            // Close the database connection
            $conn->close();
            ?>

<script>
    function deleteAnnouncement(id) {
        if (confirm("Are you sure you want to delete this announcement?")) {
            // Send AJAX request to delete announcement
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "deleteAnnouncement.php?id=" + id, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Show success message
                    Swal.fire('Success', 'Announcement deleted successfully', 'success');
                    // Reload the page
                    location.reload();
                }
            };
            xhr.send();
        }
    }

    function openEditModal(id) {
        var announcementContent = document.getElementById("announcement_" + id).innerText;
        var editedContent = document.getElementById("editedContent");
        editedContent.value = announcementContent;
        var modal = document.getElementById("editModal");
        modal.style.display = "block";
        var span = document.getElementsByClassName("close")[0];
        span.onclick = function () {
            modal.style.display = "none";
        }

        // Add event listener to form submit
        var form = document.getElementById("editForm");
        form.onsubmit = function (event) {
            event.preventDefault(); // Prevent default form submission
            editAnnouncement(id); // Call editAnnouncement function with announcement id
        };
    }

    function editAnnouncement(id) {
        var newContent = document.getElementById("editedContent").value;
        // Send AJAX request to update announcement in database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "editAnnouncement.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Show success message
                Swal.fire('Success', 'Announcement updated successfully', 'success');
                // Reload the page
                location.reload();
            }
        };
        xhr.send("id=" + id + "&content=" + newContent);
    }
</script>
        </div>
    </div>

    <!-- The Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 style="font-family: 'Arial', sans-serif; font-weight: bold;">Edit Announcement</h2>
            <form id="editForm">
                <textarea id="editedContent" rows="8" cols="60" style="font-family: 'Arial', sans-serif;"></textarea>
                <button type="submit">Save changes</button>
            </form>
        </div>
    </div>

    <!-- Add Bootstrap JS and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-b1GxFfDl2d8Tm59cKiOf8d/z7bF/qfPR6c8EHRNGnR6YAyGtXGA3Y4N7S46yzFND"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.cm/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyW8fVkRx6a3PLZLzn3lEyExlkd+6S+XJj"
        crossorigin="anonymous"></script>
</body>
</html>