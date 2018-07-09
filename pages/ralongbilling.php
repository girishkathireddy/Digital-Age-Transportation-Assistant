<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php
$tripCount="";
$ra_name="";
$amountpertrip=7;
$deductions=0;

if(isset($_POST['submit_ra'])) {
    $ra_name=$_POST['o_ridealong_text'];
$ra_id=$_POST['ra_id'];
$start_date=$_POST['ra_startdate'];
$end_date=$_POST['ra_enddate'];
$tripdata= getRaData($ra_id,$start_date,$end_date);
    $tripdata_print=getRaData($ra_id,$start_date,$end_date);
}

if(isset($_GET['payId'])){
    $editPayRoll=getPayroll_raById($_GET['payId']);
    $pay_id=$_GET['payId'];
    $driver_id=$editPayRoll['ra_id'];
    $ra_name=$editPayRoll['ra_fname'].' '.$editPayRoll['ra_lname'];
    $amount=$editPayRoll['amount'];
    $start_date=$editPayRoll['startdate'];
    $end_date=$editPayRoll['enddate'];
    $tips = $editPayRoll['tips'];
    $amountpertrip=$editPayRoll['amountpertrip'];
    $deductions=$editPayRoll['deductions'];
}

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

        .toprint
        { display: none; }

    </style>
    <style type="text/css" media="print">
        .dontprint
        { display: none; }
        .toprint
        { display: inline; }
        .to4Columns {
            -webkit-column-count: 4; /* Chrome, Safari, Opera */
            -moz-column-count: 4; /* Firefox */
            column-count: 4;

        }
        .to3Columns {
            -webkit-column-count: 3; /* Chrome, Safari, Opera */
            -moz-column-count: 3; /* Firefox */
            column-count: 3;
            -webkit-column-fill: balance; /* Chrome, Safari, Opera */
            -moz-column-fill: balance; /* Firefox */
            column-fill: balance;
        }
        .to2Columns {
            -webkit-column-count: 2; /* Chrome, Safari, Opera */
            -moz-column-count: 2; /* Firefox */
            column-count: 2;
        }


        .gap {
            -webkit-column-gap: 10px; /* Chrome, Safari, Opera */
            -moz-column-gap: 10px; /* Firefox */
            column-gap: 10px;
        }
        .gap2 {
            -webkit-column-gap: 30px; /* Chrome, Safari, Opera */
            -moz-column-gap: 30px; /* Firefox */
            column-gap: 30px;
        }
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0.5cm;  /* this affects the margin in the printer settings */
        }

        /*body { margin: 20mm 25mm 20mm 25mm; }*/

        table { page-break-inside:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
        /*.print:last-child {*/
        /*page-break-after: auto;*/
        /*}*/

        table, th, td {
            border: 1px solid black;
        }

        .s1height{
            line-height: 0.2em;
            font-family: Georgia, "Times New Roman", Times, serif;
            padding-bottom: 50px;
            text-align: center;
        }

        .footer {
            left: 0;
            bottom: 0;
            height: 100px;
            width: 100%;
            overflow:hidden;
            text-align: center;
        }

    </style>

    <div class="toprint" style="padding-left: 100px;height: auto;page-break-after: avoid">
        <div id="page-wrapper">
            <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <img src="../images/bus1.jpg" alt="Bus" style="width:150px;height:100px;">
                        </div>
                        <div class="col-lg-6">
                            <h1 class="s1height"><span>LPR TRANSPORTATION</span></h1>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <span style="float:right;padding-right: 15px">Date: <span class="ra_date">_______</span></span>
                        </div>
                    </div>
                <br><br>
                <div class="row">
                    <div class="col-lg-12">
                   <span>Payable to</span>
                                <span class="cb_client"><?php echo $ra_name; ?></span><br>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                    <span>Total Trips:</span>
                    <span id="ra_Trips">0</span><br>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                    <span>Amount per Ride Along:</span>
                    $ <span id="r_amount">0</span>

                    </div>
                </div>
                <br><br>
                <div>
                    <table>
                        <!--                    <col width="400">-->
                        <!--                    <col width="200">-->
                        <thead>
                        <tr>
                            <th style="padding-left:20px;text-align: center" class="col-xs-6"> <span>Ride along Description</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Use returned data (if any)
                        // Use returned data (if any)
                       if(isset($_POST['submit_ra'])) {
                           while ($bill = mysqli_fetch_assoc($tripdata_print)) {

                               $originalDate = $bill['triplog_date'];
                               $newDate = date("F j, Y", strtotime($originalDate));
                               ?>

                               <tr>
                                   <td style="padding-left:10px;padding-right:20px" class="col-xs-6">
                                       <span><?php echo $newDate; ?>-</span>
                                       with
                                       <span><?php echo $bill["s_fname"]; ?> <?php echo $bill["s_lname"]; ?></span>
                                       and driver
                                       <span><?php echo $bill["driver_fname"]; ?> <?php echo $bill["driver_lname"]; ?>&nbsp;</span>
                                   </td>
                               </tr>
                           <?php }
                       }?>

                        </tbody>
                    </table>
                    <br>
                    <div>
                   <span> It's been pleasure working with you!</span><br>
                  <b>  Total:&nbsp;&nbsp;$<span id="ra_pay">0</span></b>
                    </div>
                </div>


            </div>
        </div>

        <div class="row footer" style="padding-top: 30px;">
            <div class="col-lg-12">
                <h5> LPR Transportation,
                    3455 Azalea Garden Rd,
                    Norfolk,VA 23513
                </h5>
            </div>

        </div>
    </div>

    <div class="dontprint">
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Ride Along Billing</h1>
                        <div class="btn-group form-group">

                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#ra_myModal">
                                Filters
                            </button>


                            <button type="button" class="btn btn-primary btn-lg" onclick="printRABill();">
                                Print
                            </button>
                            <a href="payroll_ra.php" type="button" class="btn btn-primary btn-lg">
                                Payroll
                            </a>
                        </div>
                        <!--Start Modal  -->
                        <div class="modal fade" id="ra_myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <form method="post" enctype="multipart/form-data" action="ralongbilling.php">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title" id="myModalLabel">Filters</h4>
                                        </div>

                                        <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input required class="form-control typeahead_ra" name="o_ridealong_text" placeholder="Enter Ride Along Name" >
                                                    <input class="form-control" name="ra_id" type="hidden" placeholder="">
                                                </div>
                                            <label>Start Date</label>
                                            <div class="input-group">
                                                <input type="text" name="ra_startdate" class="form-control date" id="from" required><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                            </div>
                                            <label>End Date</label>
                                            <div class="input-group">
                                                <input type="text" name="ra_enddate" class="form-control date" id="to" required><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="submit_ra" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--End Modal  -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div style="display: inline-block">
                                    <h4>  Trips:</h4>
                                </div>
                                <div style="display: inline-block;padding-left: 10px" >
                                    <h4><?php echo $ra_name; ?>  <?php echo (isset($_GET['payId'])? $start_date . ' to ' . $end_date : '');?></h4>
                                </div>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <table class="table table-fixed dataTab">
                                    <thead>
                                    <tr>
                                        <th  class="col-xs-1">Date</th>
                                        <th  class="col-xs-2">Student Name</th>
                                        <th  class="col-xs-2">Driver Name</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    <?php
                                    // Use returned data (if any)
                                    if(isset($_POST['submit_ra'])) {
                                        // Use returned data (if any)
                                        $tripCount=0;
                                        while ($data = mysqli_fetch_assoc($tripdata)) {
                                            $tripCount++;
                                            ?>
                                            <tr>
                                                <td class="col-xs-4"><?php echo $data["triplog_date"]; ?></td>
                                                <td class="col-xs-4"><?php echo $data["s_fname"]; ?>&nbsp; &nbsp;<?php echo $data["s_lname"]; ?></td>
                                                <td class="col-xs-4"><?php echo $data["driver_fname"]; ?>&nbsp; &nbsp;<?php echo $data["driver_lname"]; ?></td>

                                            </tr>

                                        <?php }  } ?>
                                    </tbody>
                                </table>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                        <h3 class="page-header">Ride Along Billing Information</h3>
                        <div class="col-lg-4">

                            <div class="form-group">
                                <label>Total Trips</label>
                                <input class="form-control" id="ratotal_trips" value="<?php echo (isset($_GET['payId'])? $tips : '');?>">
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Amount Per Trip</label>
                                <input class="form-control" type="number" step="any" id="ra_amount"  placeholder=""  value="<?php echo $amountpertrip; ?>">
                            </div>
                            <div class="form-group">
                                <label>Total Deductions</label>
                                <input class="form-control" type="number" step="any" id="ra_deductions"  placeholder="" value="<?php echo $deductions; ?>">
                            </div>
                            <div class="form-group">
                                <label>Total Payable</label>
                                <input class="form-control" id="ra_total"  placeholder=""  value="<?php echo (isset($_GET['payId'])? $amount : '');?>" >
                            </div>
                            <button type="button" class="btn btn-primary" onclick="setRideAlongBill()" style="margin-bottom: 10px">
                                Calculate
                            </button>

                        </div>
                        <div class="col-lg-8">
                            <?php
                            if(isset($_GET['payId'])) { ?>
                             <button type="submit" class="btn btn-primary btn-lg"  id="updateraBill" name="updateraBill" style="margin-left: 25px">Update</button>
                             <input type="hidden" class="form-control" data-plra_id="<?php echo $_GET['payId']; ?>" data-startdate="<?php echo $start_date; ?>" data-enddate="<?php echo $end_date; ?>" />
                        <?php
                           } else if(isset($_POST['submit_ra']) && checkpayroll_ra($ra_id,$start_date,$end_date)) {
                             ?>
                                <div class="form-group hidebox" id="savepl">
                                    <button type="submit" class="btn btn-primary btn-lg"  id="savera_button" style="margin-left: 20px">Save to Payroll</button>
                                    <input type="hidden" class="form-control" data-ra_id="<?php echo $ra_id; ?>" data-startdate="<?php echo $start_date; ?>" data-enddate="<?php echo $end_date; ?>" />
                                </div>

                            <?php } else {?>
                                <div class="form-group hidebox" id="savepl">
                                    <button type="submit" class="btn btn-primary btn-lg"  disabled id="savepl_button">Saved</button>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
    </div>


































<?php
require_once("./includes/footer.php");
?>