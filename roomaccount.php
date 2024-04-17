<?php

include 'db_connect.php'; 
if (!isset($_SESSION['login_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            font-family: "Open Sans", sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }

        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeInDown 1s ease;
        }

        .table-container {
            width: 80%;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            white-space: nowrap;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-column {
            width: 30%;
        }

        .action-buttons {
            display: flex;
            justify-content: space-around;
        }

        .modal-dialog {
            max-width: 400px;
        }

        .modal-body label {
            font-weight: bold;
        }

        .new-room-button {
            margin-bottom: 10px;
            text-align: right;
        }

        .new-room-button button {
            background-color: green;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 700px;
            animation: slideInRight 1s ease;
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

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
<main>
    <h2 class="text-center">Room Data</h2>

    <div class="new-room-button">
        <button type="button" class="btn btn-success animate__animated animate__fadeInRight" data-toggle="modal" data-target="#addRoomModal">Add New Room</button>
    </div>

    <div class="table-container animate__animated animate__fadeIn">
        <?php
        $sql = "SELECT roomid, password FROM room";
        $result = $conn->query($sql);
        ?>

        <table class="table table-bordered">
            <thead class="thead-light">
            <tr>
                <th>Room #</th>
                <th>Password</th>
                <th class="action-column">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["roomid"] . "</td>";
                    echo "<td>" . $row["password"] . "</td>";
                    echo '<td class="action-buttons">
                            <button type="button" class="btn btn-primary edit-room" data-toggle="modal" data-target="#editModal" data-roomid="' . $row["roomid"] . '" data-password="' . $row["password"] . '">Edit Room</button>
                            <button type="button" class="btn btn-danger delete-room" data-roomid="' . $row["roomid"] . '">Delete Room</button>
                          </td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>0 results</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoomModalLabel">Add New Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addRoomForm">
                    <div class="form-group">
                        <label for="roomId">Room ID:</label>
                        <input type="text" class="form-control" id="roomId" name="roomId" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="text" class="form-control" id="password" name="password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addRoomBtn">Add Room</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="editRoomId">Room ID:</label>
                <input type="text" id="editRoomId" class="form-control" required>

                <label for="editPassword">Password:</label>
                <input type="text" id="editPassword" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveEdit()">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this room?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
    $('#addRoomBtn').click(function() {
        var roomId = $('#roomId').val();
        var password = $('#password').val();

        $.ajax({
            type: 'POST',
            url: 'addroom.php',
            data: {
                roomid: roomId, // Make sure this matches the name attribute of the input field
                password: password
            },
            success: function(response) {
                alert(response);
                $('#addRoomModal').modal('hide');
                location.reload(); // Reload the page to reflect changes
            },
            error: function(xhr, status, error) {
                alert("Error: " + error);
            }
        });
    });
});

$(document).ready(function () {
    $('.edit-room').on('click', function () {
        editRoomId = $(this).data('roomid');
        var editPassword = $(this).data('password');

        // Set values in the edit modal
        $('#editRoomId').val(editRoomId);
        $('#editPassword').val(editPassword);

        // Show the edit modal
        $('#editModal').modal('show');
    });
});

function saveEdit() {
    // Get the edited password value
    var editedPassword = $('#editPassword').val();

    // Perform the edit action using AJAX post
    $.ajax({
        type: 'POST',
        url: 'roomedit.php', // Change this to the actual PHP file that updates the database
        data: {
            roomid: editRoomId,
            new_password: editedPassword
        },
        success: function (response) {
            // Handle the response from the server
            alert(response);

            // Check if the response indicates success
            if (response.includes('successfully')) {
                // Close the edit modal
                $('#editModal').modal('hide');

                // Update the password in the specific row without reloading the page
                $('tr[data-roomid="' + editRoomId + '"] td:nth-child(2)').text(editedPassword);

                // Reload the page after changing the password
                location.reload();
            } else {
                // Display an error message if the update was not successful
                alert("Error updating password: " + response);
            }
        },
        error: function (xhr, status, error) {
            // Handle the error if the AJAX request fails
            alert("AJAX request failed: " + error);
        }
    });
}

    var deleteRoomId; // Variable to store the room ID for deletion

    $(document).ready(function () {
        $('.edit-room').on('click', function () {
            var roomid = $(this).data('roomid');
            var password = $(this).data('password');
            $('#editRoomId').val(roomid);
            $('#editPassword').val(password);
        });

        $('.delete-room').on('click', function () {
            deleteRoomId = $(this).data('roomid');

            // Show the delete modal
            $('#deleteModal').modal('show');
        });
    });

    function confirmDelete() {
        if (!deleteRoomId) {
            alert("Invalid Room ID");
            return;
        }

        // Perform the delete action using AJAX post
        $.ajax({
            type: 'POST',
            url: 'deleteroom.php', // Change this to the actual PHP file that deletes the room
            data: {
                roomid: deleteRoomId
            },
            success: function (response) {
                // Handle the response from the server
                alert(response);

                // Check if the response indicates success
                if (response.includes('successfully')) {
                    location.reload();
                    // Close the delete modal
                    $('#deleteModal').modal('hide');
                 

                    // Remove the deleted row from the table without reloading the page
                    $('tr[data-roomid="' + deleteRoomId + '"]').remove();
                } else {
                    // Display an error message if the deletion was not successful
                    alert("Error deleting room: " + response);
                }
            },
            error: function (xhr, status, error) {
                // Handle the error if the AJAX request fails
                alert("AJAX request failed: " + error);
            }
        });
    }
    $(document).ready(function() {
        // Hide/show password functionality
        $(".hide-password").on("click", function() {
            var passwordField = $(this).closest("tr").find(".password-field");
            var icon = $(this).find("i");

            if (passwordField.attr("type") === "password") {
                passwordField.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                passwordField.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
    });
</script>

<?php
// Close the database connection
$conn->close();
?>
</body>
</html>