<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>

<?php
$query_client  = "SELECT * FROM lpr_client";
$result_client = mysqli_query($connection, $query_client);
$inZoneStats=0;
$OZoneStats=0;
$SpecialStats=0;

if(isset($_POST['stats'])) {

    $client= $_POST['cb_ctypeSelect'];
    $start_date= $_POST['fstartdate'];
    $end_date= $_POST['fenddate'];
    $getInZoneDetails= inzoneStats($client,$start_date,$end_date);
   if(!empty($getInZoneDetails['stud_count'])){
       $inZoneStats=$getInZoneDetails['stud_count'];
   }

    $getOutZoneDetails= outZoneStats($client,$start_date,$end_date);
    if(!empty($getOutZoneDetails['o_studCount'])){
        $OZoneStats=$getOutZoneDetails['o_studCount'];
    }

    $getSpecialEdDetails= specialEdStats($client,$start_date,$end_date);
    if(!empty($getSpecialEdDetails['splitbill'])){
        $SpecialStats=$getSpecialEdDetails['splitbill'];
    }

}


?>

<style type="text/css">
    ul.ui-autocomplete {
        z-index: 1100;
    }
    .ui-datepicker { position: relative; z-index: 10000 !important; }

</style>


<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="btn-group form-group" style="margin-top: 30px">
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModalstats">
                        Filters
                    </button>
                </div>
                <!--Start Modal  -->
                <div class="modal fade" id="myModalstats" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal" action="stats.php">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">Client</h4>
                                </div>

                                <div class="modal-body">
                                        <label>Client</label>
                                        <select class="form-control" id="ctypeSelect" name="cb_ctypeSelect" required>
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
                                    <button type="submit" id="stats" name="stats" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <div class="col-lg-12">
                <h1 class="page-header">Statistics</h1>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Statistics
                    </div>
                    <div class="panel-body">
                    <!-- /.panel-heading -->

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Inzone:</label>
                            <span><?php echo $inZoneStats; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Outzone Zone:</label>
                            <span><?php echo $OZoneStats; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Split Bill Students:</label>
                            <span><?php echo $SpecialStats; ?></span>
                        </div>

                        </div>




                    </div>     <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
                <!--Start Modal  -->

            <!--End Modal  -->
        </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->












<?php
require_once("./includes/footer.php");
?>
