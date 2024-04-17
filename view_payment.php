<?php include 'db_connect.php' ?>

<?php 
$tenants = $conn->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.id = {$_GET['id']} ");
foreach($tenants->fetch_array() as $k => $v){
    if(!is_numeric($k)){
        $$k = $v;
    }
}
$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($date_in." 23:59:59"));
$months = floor(($months) / (30*60*60*24));
$payable = $price * $months;
$paid = $conn->query("SELECT SUM(amount) as paid FROM payments where tenant_id =".$_GET['id']);
$last_payment = $conn->query("SELECT * FROM payments where tenant_id =".$_GET['id']." order by unix_timestamp(date_created) desc limit 1");
$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
$last_payment = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
$outstanding = $payable - $paid;
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-4">
                <div id="details">
                    <large><b>Details</b></large>
                    <hr>
                    <p>Tenant: <b><?php echo ucwords($name) ?></b></p>
                    <p>Monthly Rental Rate: <b><?php echo number_format($price,2) ?></b></p>
                    <p>Rental Balance: <b><?php echo number_format($outstanding,2) ?></b></p>
                    <p>Total Paid: <b><?php echo number_format($paid,2) ?></b></p>
                    <p>Rent Started: <b><?php echo date("M d, Y",strtotime($date_in)) ?></b></p>
                </div>
            </div>
            <div class="col-md-4">
                <large><b>Payment List</b></large>
                <hr>
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $payments = $conn->query("SELECT * FROM payments where tenant_id = $id");
                        if($payments->num_rows > 0):
                        while($row=$payments->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo date("M d, Y",strtotime($row['date_created'])) ?></td>
                            <td class='text-right'><?php echo number_format($row['amount'],2) ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <?php endif; ?>
                        
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <div id="emergency">
                    <large><b>Contact of Emergency</b></large>
                    <hr>
                    <p><b>First Name:</b> <?php echo isset($cfirstname) ? $cfirstname : 'N/A' ?></p>
                    <p><b>Last Name:</b> <?php echo isset($clastname) ? $clastname : 'N/A' ?></p>
                    <p><b>Contact Number:</b> <?php echo isset($ccontact) ? $ccontact : 'N/A' ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #details p {
        margin: unset;
        padding: unset;
        line-height: 1.3em;
    }
    td, th{
        padding: 3px !important;
    }
    .modal-dialog {
        max-width: 1200px !important; /* Adjust the width as per your preference */
        max-height:800px;
    }
</style>