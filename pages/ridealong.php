<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>
<?php
$query_ra  = "SELECT * FROM lpr_ridealong";
$result_ra = mysqli_query($connection, $query_ra);
$counter=0;
confirm_query($result_ra);

if(isset($_POST['driver_id'])) {
    $driver_id= $_POST['driver_id'];
    $cash_advance=$_POST['d_cashAdvance'];
    insertCashAdvance($driver_id,$cash_advance,'debit');
}

if(isset($_POST['additnl_driverid'])) {

    $driverid=$_POST['additnl_driverid'] ;
    $ad_payable=$_POST['additnl_payable'];
    $ad_tip=$_POST['additnl_tip'];
    $ad_tripdate=$_POST['additnl_tripDate'];
    insert_additnlTrip($driverid,$ad_payable,$ad_tip,$ad_tripdate);
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
                        <h1> Ride Along</h1>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Ride Along Data
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped dataTab">
                                <thead>
                                <tr>
                                    <th class="col-xs-1">#</th>
                                    <th class="col-xs-4">Name</th>
                                    <th class="col-xs-4">Mobile Number</th>
                                    <th class="col-xs-3" style="padding-left: 30px">Edit</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                // Use returned data (if any)
                                while($subject_client = mysqli_fetch_assoc($result_ra)) {
                                    // output data from each row
                                    ?>
                                    <tr>
                                        <td class="col-xs-1"><?php echo ++$counter; ?></td>
                                        <td class="col-xs-4"><?php echo $subject_client["ra_fname"]; ?>-<?php echo $subject_client["ra_lname"]; ?></td>
                                        <td class="col-xs-4"><?php echo $subject_client["phone"]; ?></td>
                                        <td class="col-xs-3"><a href="<?php echo 'addridealong.php?ra_id=' . $subject_client['id']; ?>" class="size2" style="color: #5cb85c;margin: inherit;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                        <input type="hidden" data-driverid="<?php echo  $subject_client['id']; ?>">
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
                <div class="form-group">
                    <a href="addridealong.php" class="btn btn-primary btn-lg btn-block" role="button">Add Ride Along</a>
                </div>
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
