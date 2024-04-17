<?php include 'db_connect.php' ?>

<style>
     .summary-card {
        margin-bottom: 20px;
        opacity: 0; /* Initially hide the cards */
        animation: fadeInDown 1s ease forwards; /* Apply fadeInDown animation */
    }

    .summary-card .card-body {
        padding: 15px;
    }

    .summary-icon {
        font-size: 3rem;
    }

    /* Define the fadeInDown animation */
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
</style>

<?php
// Fetch the total number of rooms
$total_rooms_query = $conn->query("SELECT COUNT(*) as total_rooms FROM houses");
$total_rooms_result = $total_rooms_query->fetch_assoc();
$total_rooms = $total_rooms_result['total_rooms'];

// Fetch the number of occupied rooms
$occupied_rooms_query = $conn->query("SELECT COUNT(*) as occupied_rooms FROM tenants WHERE status = 1");
$occupied_rooms_result = $occupied_rooms_query->fetch_assoc();
$occupied_rooms = $occupied_rooms_result['occupied_rooms'];

// Calculate the number of available rooms
$available_rooms = $total_rooms - $occupied_rooms;

// Fetch rental balance report
$rental_balance_query = $conn->query("SELECT t.*, CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) AS name, h.house_no, h.price FROM tenants t INNER JOIN houses h ON h.id = t.house_id WHERE t.status = 1 ORDER BY h.house_no DESC");
?>

<div class="container-fluid">
    <div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <?php echo "Welcome back " . $_SESSION['login_name'] . "!" ?>
                    <hr>
                    <div class="row">
                        <!-- Total Rooms Occupied Card -->
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary summary-card">
                                <div class="card-body bg-primary">
                                    <div class="card-body text-white">
                                        <span class="float-right summary-icon"><i class="fa fa-home "></i></span>
                                        <h4><b><?php echo $total_rooms ?></b></h4>
                                        <p><b>Total Rooms </b></p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-12">
                                        <a href="index.php?page=houses" class="text-black float-right">View List <span class="fa fa-angle-right"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Boarders Card -->
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning summary-card">
                                <div class="card-body bg-warning">
                                    <div class="card-body text-white">
                                        <span class="float-right summary-icon"><i class="fa fa-user-friends "></i></span>
                                        <h4><b><?php echo $occupied_rooms ?></b></h4>
                                        <p><b>Total Boarders</b></p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <a href="index.php?page=tenants" class="text-primary float-right">View List <span class="fa fa-angle-right"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payments This Month Card -->
                        <div class="col-md-4 mb-3">
                            <div class="card border-success summary-card">
                                <div class="card-body bg-success">
                                    <div class="card-body text-white">
                                        <span class="float-right summary-icon"><i class="fa fa-file-invoice "></i></span>
                                        <h4><b>
                                            <?php
                                            $current_month = date('Y-m');
                                            $payment_query = $conn->query("SELECT SUM(amount) as paid FROM payments WHERE DATE_FORMAT(date_created, '%Y-%m') = '$current_month'");
                                            $payment_result = $payment_query->fetch_assoc();
                                            $total_payment = $payment_result['paid'] ?? 0;
                                            echo number_format($total_payment, 2);
                                            ?>
                                        </b></h4>
                                        <p><b>Payments This Month</b></p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <a href="index.php?page=invoices" class="text-primary float-right">View Payments <span class="fa fa-angle-right"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- New Card for Total Available Rooms -->
                        <div class="col-md-4 mb-3">
                            <div class="card border-info summary-card">
                                <div class="card-body bg-info">
                                    <div class="card-body text-white">
                                        <span class="float-right summary-icon"><i class="fas fa-door-open "></i></span>
                                        <h4><b><?php echo $total_rooms - $occupied_rooms ?></b></h4>
                                        <p><b>Total Available Rooms</b></p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <a href="index.php?page=tenants" class="text-primary float-right">View Total Rooms <span class="fa fa-angle-right"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Rental Balance Card -->
                        <div class="col-md-4 mb-3-md-4">
                            <div class="card border-danger summary-card">
                                <div class="card-body bg-danger">
                                    <div class="card-body text-white">
                                        <span class="float-right summary-icon"><i class="fa fa-money-bill "></i></span>
                                        <h4><b>
                                        <?php 
$total_outstanding = 0; // Initialize total outstanding balance variable
$tenants = $conn->query("SELECT t.*, CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) AS name, h.house_no, h.price 
                         FROM tenants t 
                         INNER JOIN houses h ON h.id = t.house_id 
                         WHERE t.status = 1 
                         ORDER BY h.house_no DESC");
if ($tenants->num_rows > 0) {
    while ($row = $tenants->fetch_assoc()) {
        $months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($row['date_in']." 23:59:59"));
        $months = floor(($months) / (30*60*60*24));
        $payable = $row['price'] * $months;
        // Fetch paid amount
        $paid_result = $conn->query("SELECT SUM(amount) AS paid FROM payments WHERE tenant_id = ".$row['id']);
        $paid = $paid_result->num_rows > 0 ? $paid_result->fetch_array()['paid'] : 0;
        // Calculate outstanding balance
        $outstanding = $payable - $paid;
        $total_outstanding += $outstanding; // Add outstanding balance to total
    }
}
echo number_format($total_outstanding, 2);
?>
                                        </b></h4>
                                        <p><b>Rental Balance This Month</b></p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <a href="index.php?page=balance_report" class="text-primary float-right">View Rental Balance <span class="fa fa-angle-right"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#manage-records').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                resp = JSON.parse(resp)
                if (resp.status == 1) {
                    alert_toast("Data successfully saved", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 800)
                }
            }
        })
    })

    $('#tracking_id').on('keypress', function(e) {
        if (e.which == 13) {
            get_person()
        }
    })

    $('#check').on('click', function(e) {
        get_person()
    })

    function get_person() {
        start_load()
        $.ajax({
            url: 'ajax.php?action=get_pdetails',
            method: "POST",
            data: {
                tracking_id: $('#tracking_id').val()
            },
            success: function(resp) {
                if (resp) {
                    resp = JSON.parse(resp)
                    if (resp.status == 1) {
                        $('#name').html(resp.name)
                        $('#address').html(resp.address)
                        $('[name="person_id"]').val(resp.id)
                        $('#details').show()
                        end_load()
                    } else if (resp.status == 2) {
                        alert_toast("Unknown tracking id.", 'danger');
                        end_load();
                    }
                }
            }
        })
    }
    
</script>