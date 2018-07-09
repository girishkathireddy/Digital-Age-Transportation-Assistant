<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>
<style type="text/css">
    ul.ui-autocomplete {
        z-index: 1100;
    }
    .ui-datepicker { position: relative; z-index: 10000 !important; }

</style>
<?php
$query_client  = "SELECT * FROM lpr_client";
$result_client = mysqli_query($connection, $query_client);


if(isset($_POST['cbillMod'])&& !empty($_POST['cbillMod'])) {
    $client= $_POST['cbillMod'];
    $start_date= $_POST['fstartdate'];
    $end_date= $_POST['fenddate'];
    $query ="SELECT * FROM lpr_invoice LEFT JOIN lpr_client ON lpr_invoice.cid=lpr_client.client_id WHERE '$start_date' <= startdate AND '$end_date' >=enddate AND lpr_invoice.cid=$client ";
    error_log("\nBill " . $query , 3, "C:/xampp/apache/logs/error.log");
    $result = mysqli_query($connection, $query);
    confirm_query($result);


}
elseif (isset($_POST['fstartdate'],$_POST['fenddate'])) {
    $start_date= $_POST['fstartdate'];
    $end_date= $_POST['fenddate'];
    $query ="SELECT * FROM lpr_invoice LEFT JOIN lpr_client ON lpr_invoice.cid=lpr_client.client_id WHERE '$start_date' <= startdate AND '$end_date' >=enddate";
    error_log("\nBill" . $query , 3, "C:/xampp/apache/logs/error.log");
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}
?>


<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Bill</h1>
                <div class="btn-group form-group">

                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                        Filters
                    </button>


                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Bills
                    </div>
                    <!-- /.panel-heading -->

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped dataTab">
                                <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Invoice Date</th>
                                    <th>Total Billable</th>
                                    <th>Edit</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                // Use returned data (if any)
                                if(isset($_POST['fstartdate'])) {
                                    while($subject = mysqli_fetch_assoc($result)) {

                                        ?>
                                        <tr>
                                            <td><?php echo $subject["client_name"];?></td>
                                            <td><?php echo $subject["startdate"]; ?></td>
                                            <td><?php echo $subject["enddate"]; ?></td>
                                            <td><?php echo $subject["invoice_date"]; ?></td>
                                            <td><?php echo $subject["totalpayable"]; ?></td>
                                            <td class="col-xs-1"><a href="<?php echo 'studentbilling.php?invoiceId=' . $subject['invoice_id'].'&clientName='.$subject['client_name'].'&cid='.$subject['cid'].'"'?>" class="size2" style="color: #5cb85c;margin: inherit;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                            <td><button type="button" class="btn btn-danger" onclick="deleteBill(this,<?php echo $subject["invoice_id"]; ?>);">Delete</button></td>.
                                        </tr>
                                    <?php } }?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
                <!--Start Modal  -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal" action="Bill.php">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">Bill</h4>
                                </div>

                                <div class="modal-body">

                                        <label>Client</label>
                                    <div class="input-group">
                                        <select class="form-control" id="cbillMod" name="cbillMod"  style="width:400px" >
                                            <option value="">Select</option>
                                            <?php
                                            // 3. Use returned data (if any)
                                            while($subject_client = mysqli_fetch_assoc($result_client)) {
                                                // output data from each row
                                                ?>
                                                <option data-zone_id="<?php echo $subject_client["zone_id"]; ?>" value="<?php echo $subject_client["client_id"]; ?>"><?php echo $subject_client["client_name"]; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <label>Start Date</label>
                                    <div class="input-group">
                                        <input type="text" name="fstartdate" class="form-control date" id="from" required="true"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                    </div>
                                    <label>End Date</label>
                                    <div class="input-group">
                                        <input type="text" name="fenddate" class="form-control date" id="to" required><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" id="driverBillFilter" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
            <!--End Modal  -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
</div>




























<?php
require_once("./includes/footer.php");
?>

