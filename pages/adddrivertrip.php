<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>


<?php
if(isset($_GET['driver_id']))
{
    $driverid=$_GET['driver_id'] ;
    $drivername=$_GET['drivername'];
}

?>

<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>

<div id="page-wrapper">
    <div class="container-fluid">
        <form method="post" enctype="multipart/form-data" id="newDriverTrip" action="drivers.php" name="newDriverTrip" class="form-horizontal" onsubmit="return validateForm()">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">Additional Trip</h1>
                    <div class="form-group" >
                            <label class="control-label col-sm-2">Driver Name</label>
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="First Name" name="d_name" value="<?php echo $drivername;?>"  disabled >
                                <input class="form-control" id="driver_id" name="additnl_driverid" type="hidden" placeholder="" value="<?php echo $driverid;?>">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Student</label>
                        <div class="col-sm-6">
                        <input class="form-control typeahead_student" required  placeholder="">
                        <input class="form-control" name="stu_id" type="hidden" placeholder="">
                        </div>
                    </div>
                    <div class="form-group" >
                                    <label class="control-label col-sm-2">Trip Date</label>
                                    <div class="input-group col-sm-6" style="padding-left: 15px">
                                        <input type="text" name="additnl_tripDate" class="form-control" id="addtripdate" placeholder="Select Date"  value="" required><span class="input-group-addon"><i class="glyphicon glyphicon-th" ></i></span>
                                     </div>
                                    <p class="help-block"></p>\

                    </div>
                    <div class="form-group">
                            <label class="control-label col-sm-2">Payable to driver</label>
                            <div class="col-sm-6">
                            <input class="form-control" type="number" placeholder="Enter Amount" name="additnl_payable" required value="">
                            <p class="help-block"></p>
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Tip</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" placeholder="Enter Amount" name="additnl_tip" required value="">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="driver_yes_no_radio" class="control-label col-sm-3">Is driver payable ?</label>
                        <label class="radio-inline">
                            <input type="radio" name="driver_yes_no" value="Yes" checked>Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="driver_yes_no" value="No">No
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="client_yes_no_radio" class="control-label col-sm-3">Is client payable ?</label>
                        <label class="radio-inline">
                            <input type="radio" name="client_yes_no" value="Yes" checked>Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="client_yes_no" value="No">No
                        </label>
                    </div>
                </div>
            </div>
                <!-- /.col-lg-12 -->

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-6">
                        <button type="submit" class="btn btn-primary btn-lg"  style="margin-top: 20px" name="adtnl_trip">Submit</button>
                    </div>
                </div>
        </form>

    </div>
    <!-- /Container Fluid -->
</div>
<!-- /Page Wrapper -->
<?php
require_once("./includes/footer.php");
?>

