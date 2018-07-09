<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>
<?php
$query_client  = "SELECT * FROM lpr_driver";
$result_client = mysqli_query($connection, $query_client);
$counter=0;
confirm_query($result_client);

if(isset($_POST['driver_id'])) {
    $driver_id= $_POST['driver_id'];
    $cash_advance=$_POST['d_cashAdvance'];
    insertCashAdvance($driver_id,$cash_advance,'debit');
}

if(isset($_POST['additnl_driverid'])) {
    error_log("\nDriver aditnl trip query ", 3, "C:/xampp/apache/logs/error.log");
    $driverid=$_POST['additnl_driverid'] ;
    $ad_payable=$_POST['additnl_payable'];
    $ad_tip=$_POST['additnl_tip'];
    $ad_tripdate=$_POST['additnl_tripDate'];
    $ad_studentId=$_POST['stu_id'];
    $ad_driver_yes_no=$_POST['driver_yes_no'];
    $ad_client_yes_no=$_POST['client_yes_no'];
    error_log("\nDriver aditnl trip query ".$ad_driver_yes_no, 3, "C:/xampp/apache/logs/error.log");
    if($ad_driver_yes_no=='Yes'){
    insert_additnlTrip($driverid,$ad_payable,$ad_tip,$ad_tripdate);}
    if($ad_client_yes_no=='Yes'){
        $details=getAdtnlTripDetails($ad_studentId);
        $ad_oid=$details["o_id"];
        $ad_scId=$details["school_id"];
        insertAdtnlTripClient($driverid,$ad_tripdate,$ad_studentId,$ad_oid,$ad_scId);
    }

}
?>

<style>


    ul.ui-autocomplete {
        z-index: 1100;
    }
    .ui-datepicker { position: relative; z-index: 10000 !important; }

    /*.table-fixed thead {*/
        /*width: 97%;*/
    /*}*/
    /*.table-fixed tbody {*/
        /*height: 500px;*/
        /*overflow-y: auto;*/
        /*width: 100%;*/
    /*}*/
    /*.table-fixed thead, .table-fixed tbody, .table-fixed tr, .table-fixed td, .table-fixed th {*/
        /*display: block;*/
    /*}*/
    /*.table-fixed tbody td, .table-fixed thead > tr> th {*/
        /*float: left;*/
        /*border-bottom-width: 0;*/
    /*}*/



</style>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                <div style="height: 100px">
                     <div class="page-headers" style="display:inline">
                        <h1> Drivers</h1>
                     </div>
                    <div style="padding-right: 60px; float:right;display: inline">
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" style="display:inline;height:42px;text-align: center;padding-top:7px">
                            Advance
                        </button>
                        <a href="adddriver.php" class="btn btn-primary btn-lg btn-block" role="button" style="display:inline;">Add Driver</a>
                    </div>
                </div>
                    <!--Start Modal  -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <form method="post" action="drivers.php" enctype="multipart/form-data" class="form-horizontal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel">Cash Advance</h4>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                        <label for="drivername" class="control-label col-sm-3">Driver</label>
                                            <div class="col-sm-9">
                                            <input id="drivername" name="db_drivername" required class="form-control typeahead" placeholder="" >
                                            <input class="form-control" id="driver_id" name="driver_id" type="hidden" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label for="d_cashAdvance" class="control-label col-sm-3">Cash Advance</label>
                                            <div class="col-sm-9">
                                              <input type="number" name="d_cashAdvance" class="form-control" id="d_cashAdvance" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" id="d_modalAdvance" class="btn btn-primary" onclick="cash_modal()">Submit</button>
                                        <p class="cashAd_comments" style="color:#cd0a0a"></p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        Drivers Data
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped dataTabDrivers">
                                <thead>
                                <tr>
                                    <th class="col-xs-1">#</th>
                                    <th class="col-xs-2">Driver Name</th>
                                    <th class="col-xs-2">Mobile Number</th>
                                    <th class="col-xs-2" style="padding-left: 30px">City</th>
                                    <th class="col-xs-2">Commision</th>
                                    <th class="col-xs-1" style="text-align:left; padding-left: 45px;">Edit</th>
                                    <th class="col-xs-1" style="padding-left: 45px">State</th>
                                    <th class="col-xs-1" style="padding-left: 30px">Trip</th>
                                </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    // Use returned data (if any)
                                    while($subject_client = mysqli_fetch_assoc($result_client)) {
                                    // output data from each row
                                    ?>
                                    <tr>
                                        <td class="col-xs-1"><?php echo ++$counter; ?></td>
                                        <td class="col-xs-2"><?php echo $subject_client["driver_fname"]; ?> <?php echo $subject_client["driver_lname"]; if(!empty($subject_client['driver_dname'])){ ?>
                                                (<?php echo $subject_client['driver_dname']; ?>)<?php }?></td>
                                        <td class="col-xs-2"><?php echo $subject_client["driver_contact_no"]; ?></td>
                                        <td class="col-xs-2"><?php echo $subject_client["driver_city"]; ?></td>
                                        <td class="col-xs-2"><?php echo $subject_client["driver_commision"]; ?></td>
                                        <td class="col-xs-1"><a href="<?php echo 'adddriver.php?driver_id=' . $subject_client['driver_id']; ?>" class="size2" style="color: #5cb85c;margin: inherit;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                        <?php if ($subject_client["driver_status"] == "active") { ?>
                                            <td class="col-xs-1"><button type="button" class="btn btn-success dstatus " onclick="dr_changestatus(this);">Active</button></td>
                                        <?php } else {?>
                                            <td class="col-xs-1"><button type="button" class="btn btn-danger dstatus" onclick="dr_changestatus(this);">Inactive</button></td>
                                        <?php } ?>
                                        <td class="col-xs-1"> <a href="<?php echo 'adddrivertrip.php?driver_id=' . $subject_client['driver_id'].'&drivername='.$subject_client["driver_fname"]." ". $subject_client["driver_lname"]; ?>" class="btn btn-warning " role="button">Add Trip</a></td>
                                       <input type="hidden" data-driverid="<?php echo  $subject_client['driver_id']; ?>">
                                     </tr>

                                        <?php
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
<!--                <div class="form-group">-->
<!--                    <a href="adddriver.php" class="btn btn-primary btn-lg btn-block" role="button">Add Driver</a>-->
<!--                </div>-->
                <!-- /.panel -->

            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->










<?php
require_once("./includes/footer.php");
?>
