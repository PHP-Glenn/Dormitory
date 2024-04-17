<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Feedback Viewer</title>
<style>
    body {
        background-color: #f4f4f4;
    }
    .container {
        font-family: Arial, sans-serif;
        max-width: 1200px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .title {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
    }
    .filter-input {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    .navigation {
        text-align: center;
        margin-top: 20px;
    }
    .navigation button {
        padding: 10px 20px;
        margin: 0 5px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .navigation button:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
<div class="container">
    <div class="title">Feedback Viewer</div>
  
    <table>
        <thead>
            <tr>
                <th>Date Sent</th>
                <th>Name of Sender</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include database connection
            include 'db_connect.php';

            // Fetch feedback data from the database in ascending order of date_sent
            $sql = "SELECT * FROM feedback ORDER BY date_sent ASC";
            $result = $conn->query($sql);

            // Display feedback data in the table
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['date_sent'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['content'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No feedback available</td></tr>";
            }

            // Close database connection
            $conn->close();
            ?>
        </tbody>
    </table>
   
</div>
</body>
</html>