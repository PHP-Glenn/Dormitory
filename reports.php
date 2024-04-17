<?php include 'db_connect.php'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4 bg-primary text-white" style="animation: fadeInDown 1.5s ease forwards;">
                <div class="card-body">
                    <h4 class="card-title"><b>Monthly Report</b></h4>
                </div>
                <div class="card-footer">
                    <a href="index.php?page=payment_report" class="btn btn-light d-block">View Report <i class="fa fa-chevron-circle-right ml-auto"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4 bg-success text-white" style="animation: fadeInDown 1.5s ease forwards;">
                <div class="card-body">
                    <h4 class="card-title"><b>Rental Balances Report</b></h4>
                </div>
                <div class="card-footer">
                    <a href="index.php?page=balance_report" class="btn btn-light d-block">View Report <i class="fa fa-chevron-circle-right ml-auto"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4 bg-warning text-white" style="animation: fadeInDown 1.5s ease forwards;">
                <div class="card-body">
                    <h4 class="card-title"><b>Boarder's Feedback</b></h4>
                </div>
                <div class="card-footer">
                    <a href="index.php?page=viewfeedback" class="btn btn-light d-block">View Feedback <i class="fa fa-chevron-circle-right ml-auto"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4 bg-info text-white" style="animation: fadeInDown 1.5s ease forwards;">
                <div class="card-body">
                    <h4 class="card-title"><b>Announcements</b></h4>
                </div>
                <div class="card-footer">
                    <a href="index.php?page=localannounce" class="btn btn-light d-block">View Announcement <i class="fa fa-chevron-circle-right ml-auto"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4 bg-danger text-white" style="animation: fadeInDown 1.5s ease forwards; ">
                <div class="card-body">
                    <h4 class="card-title"><b>Message through SMS</b></h4>
                </div>
                <div class="card-footer">
                    <a href="index.php?page=announce" class="btn btn-light d-block">Send Message SMS <i class="fa fa-chevron-circle-right ml-auto"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>