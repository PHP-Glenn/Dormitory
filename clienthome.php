<?php
session_start();
include 'db_connect.php'; 
if (!isset($_SESSION['roomid'])) {
    header("Location: loginclient.php"); // Redirect to the login page
    exit();
}
?>
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
        }
        .top-bar {
            background-color: #343a40;
           
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: fadeInDown 1s ease forwards;
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
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            background-color: white;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <h3>Rental Balances Report of <?php echo date('F, Y') ?></h3>
        <div class="dropdown">
            <button class="dropdown-button"><b>Menu</b><i class="fas fa-chevron-down ml-2"></i></button>
            <div class="dropdown-content">
                <a href="announcecont.php">Announcement</a>
                <a href="sendfeedback.php">Feedback</a>
                <a id="logout-button">Log Out</a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div id="report"></div>
                        <div class="row">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Boarder's Name</th>
                                        <th>Room #</th>
                                        <th>Monthly Rate</th>
                                        <th>Payable Months</th>
                                        <th>Payable Amount</th>
                                        <th>Paid</th>
                                        <th>Outstanding Balance</th>
                                        <th>Last Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $roomid = $_SESSION['roomid'];
                                    echo "<h1><b>Welcome User, $roomid!</b></h1>";
                                    $i = 1;
                                    $roomid = $_SESSION['roomid'];
                                    $tenants = $conn->query("SELECT t.*, concat(t.lastname, ', ', t.firstname, ' ', t.middlename) as name, h.house_no, h.price FROM tenants t 
                                        INNER JOIN houses h ON h.id = t.house_id 
                                        WHERE t.status = 1 AND h.house_no = '$roomid' 
                                        ORDER BY h.house_no DESC");
                                    if ($tenants->num_rows > 0) :
                                        while ($row = $tenants->fetch_assoc()) :
                                            $months = abs(strtotime(date('Y-m-d') . " 23:59:59") - strtotime($row['date_in'] . " 23:59:59"));
                                            $months = floor(($months) / (30 * 60 * 60 * 24));
                                            $payable = $row['price'] * $months;
                                            $paid = $conn->query("SELECT SUM(amount) as paid FROM payments where tenant_id =" . $row['id']);
                                            $last_payment = $conn->query("SELECT * FROM payments where tenant_id =" . $row['id'] . " order by unix_timestamp(date_created) desc limit 1");
                                            $paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
                                            $last_payment = $last_payment->num_rows > 0 ? date("M d, Y", strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
                                            $outstanding = $payable - $paid;
                                    ?>
                                            <tr>
                                                <td><?php echo ucwords($row['name']) ?></td>
                                                <td><?php echo $row['house_no'] ?></td>
                                                <td class="text-right"><?php echo number_format($row['price'], 2) ?></td>
                                                <td class="text-right"><?php echo $months . ' mo/s' ?></td>
                                                <td class="text-right"><?php echo number_format($payable, 2) ?></td>
                                                <td class="text-right"><?php echo number_format($paid, 2) ?></td>
                                                <td class="text-right"><?php echo number_format($outstanding, 2) ?></td>
                                                <td><?php echo date('M d, Y', strtotime($last_payment)) ?></td>
                                            </tr>
                                    <?php endwhile; ?>
                                    <?php else : ?>
                                        <tr>
                                            <th colspan="8"><center>No Data.</center></th>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
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