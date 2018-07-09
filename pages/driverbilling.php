<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
$datebillsaved=date("Y-m-d");
$driver_payable=0;
$driver_tip = 0;
$driver_name="";
$additions=0;
$fuel_advance=0;
$toll=0;
$advance=0;
$other=0;
$start_date="";
$end_date="";
$driver_id="";
$total_earnings=0;
$contractorsPerc=25;
$leasePerc=0;
$pay_id=0;
$checkNumber=0;
?>
<?php

if(isset($_GET['payId'])){
    $editPayRoll=getPayrollById($_GET['payId']);
    $datebillsaved=$editPayRoll['savedate'];
    $pay_id=$_GET['payId'];
    $driver_id=$editPayRoll['driver_id'];
    $toll=$editPayRoll['toll'];
    $driver_name=$editPayRoll['driver_fname'].' '.$editPayRoll['driver_lname'];
    $additions=$editPayRoll['additions'];
    $fuel_advance=$editPayRoll['fuelAdvance'];
    $other=$editPayRoll['others'];
    $checkNumber=$editPayRoll['checkNumber'];
    $driver_pay=$editPayRoll['tBill'];
    $driverTips=$editPayRoll['tips'];
    $driverCtotal=$editPayRoll['contractorsTotal'];
    $driverFamount=$editPayRoll['amount'];
    $contractorsPerc=$editPayRoll['payToContractorsPerc'];
    $leasePerc=$editPayRoll['leasePerc'];
    $lease=$editPayRoll['lease'];
    $start_date=$editPayRoll['startdate'];
    $end_date= $editPayRoll['enddate'];
    $payContractors=$editPayRoll['payToContractors'];
    $cashAdvanceDetails= getCashAdvance($driver_id);
    $advance=$cashAdvanceDetails['cashAdvance'];
    $earnings_data= getTotalEarnings($driver_id);
    $total_earnings=$earnings_data['earnings'];

}

if(isset($_POST['driver_id'])) {
   $driver_id= $_POST['driver_id'];
   $start_date= $_POST['fstartdate'];
   $end_date= $_POST['fenddate'];
   $driver_name=$_POST['db_drivername'];
   $check=getCheckNumber();
   $checkNumber=$check["checkNum"]+1;

  $driverBill=getDriverBill($driver_id,$start_date,$end_date);
  $d_print=getDriverBill($driver_id,$start_date,$end_date);
  $cashAdvanceDetails= getCashAdvance($driver_id);
  $advance=$cashAdvanceDetails['cashAdvance'];
  $earnings_data= getTotalEarnings($driver_id);
  $total_earnings=$earnings_data['earnings'];

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


</style>

<div class="toprint">
    <div id="page-wrapper">
        <br><br><br>
        <div class="container-fluid" style="border-style: solid;">
            <br>
            <h5 style="text-align:center"><b>CONTRACTOR SETTLEMENT SUMMARY</b></h5>
                            <br>
                           <div class="to4Columns" >
                               <div >&nbsp;</div>
                               <div>&nbsp;</div>
                               <div><h6><b>SETTLEMENT PERIOD</b></h6></div>
                               <div>&nbsp;</div>
                           </div>
                           <div class="to2Columns">
                               <div>
                                   <b> <span>CONTRACTOR :</span></b>
                                   <span  style="padding-left: 4px" class="db_contractor">________</span>
                               </div>
                               <div>
                                   <span style="text-align: left">BEGINNING :</span>
                                   <span class="db_startdate" style="text-align: left">________</span>
                                   <span style="text-align: right;padding-left: 5px;">ENDING :</span>
                                   <span class="db_enddate" style="text-align: right">________</span>
                               </div>
                           </div>
            <br>
            <div class="to2Columns">
                <div style="text-align: left">
                    <span>TOTAL EARNINGS TILL DATE :</b></span>
                </div>
                <div style="text-align: left">$<span class="total_erng">0</span></div>
            </div>
            <div class="to2Columns">
               <div style="text-align: left">
                   <b> <span>TOTAL BILLINGS FOR PERIOD :</b></span></b>
               </div>
                <div style="text-align: center">$<span class="db_beforeDeductions">________</span></div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right">
                    <span>CONTRACTOR RATE:</span>
                </div>
                <div style="text-align: center"><span class="db_contractorRate">________</span>%</div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right">
                    <span>PAYABLE TO CONTRACTOR:</span>
                </div>
                <div style="text-align: center">$<span class="db_payable">________</span></div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right"><span>ADDITIONAL FEES PAYABLE TO CONTRACTOR :</span></div>
                <div style="text-align: center">$<span class="db_additionalFee">________</span></div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right"><span>CONTRACTOR TIP :</span></div>
                <div style="text-align: center">$<span class="db_tip">________</span></div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right"><h5><b>CONTRACTOR'S TOTAL BEFORE DEDUCTIONS :</b></h5></div>
                <div style="text-align: center"><b>$<span class="db_totalBeforeDeductions">________</span></b></div>
            </div>
            <br>
            <div class="to2Columns">
                <div style="text-align: left">
                    <b> <span>CHARGEABLE TO CONTRACTOR :</b></span></b>
                </div>
                <div>&nbsp;</div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right">
                    <span>FUEL ADVANCE :</span>
                </div>
                <div style="text-align: center">$<span class="db_fuelAdvance">________</span></div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right">
                    <span>TOLL ADVANCE :</span>
                </div>
                <div style="text-align: center">$<span class="db_tollAdvance">________</span></div>
            </div>
            <div class="to3Columns">
                <div style="text-align: right"><span>&nbsp</span></div>
                <div style="text-align: center">LEASE :<span style="padding-left:40px" class="db_leasePercentage">________</span>%</div>
                <div style="text-align: center;padding-right: 120px">$<span class="db_lease">________</span></div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right"><span>COMMISSION ADVANCE:</span></div>
                <div style="text-align: center">$<span  class="db_commisionAdvance">________</span></div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right"><span>OTHER:</span></div>
                <div style="text-align: center">$<span class="db_other">________</span></div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right"><h5><b>TOTAL DUE FROM CONTRACTOR :</b></h5></div>
                <div style="text-align: center"><b>$<span class="db_totalDue">________</span></b></div>
            </div>
            <br>
            <div class="to2Columns">
                <div style="text-align: left">
                    <b> <span>TOTAL PAYABLE TO CONTRACTOR THIS PERIOD</b></span></b>
                </div>
                <div style="text-align: center;padding-left:150px"><b>$<span class="db_totalPayable">________</span></b></div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right">
                    <span>CHECK :</span>
                </div>
                <div style="text-align: left"><span class="db_check">________</span></div>
            </div>
            <div class="to2Columns">
                <div style="text-align: right">
                    <span>ISSUED :</span>
                </div>
                <div style="text-align: left"><span class="db_issuedDate">________</span></div>
            </div>
            <br>


        </div>
        <br><br>
        <table >
            <!--                    <col width="400">-->
            <!--                    <col width="200">-->
            <thead>
            <tr>
                <th style="padding-left:20px;text-align: center" class="col-xs-8"> <span>Description</span></th>
                <th style="padding-left:20px;text-align: center" class="col-xs-2"> <span>Amount</span></th>
                <th style="padding-left:20px;text-align: center" class="col-xs-2"> <span>Tip</span></th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Use returned data (if any)
            // Use returned data (if any)
            if(isset($_POST['driver_id'])) {
            while ($cbill = mysqli_fetch_assoc($d_print)) {

                $originalDate = $cbill['triplog_date'];
                $newDate = date("F j, Y", strtotime($originalDate));
                ?>

                <tr>
                    <td style="padding-left:10px;padding-right:20px" class="col-xs-8">
                        <span><?php echo $newDate; ?>-</span>
                        <span><?php if($cbill['s_name']==null ) echo 'Additional Trip'; else echo $cbill['s_name']; ?></span>
                        <span><?php echo date ('H:i',strtotime($cbill["triplog_picktime"]))  ?>&nbsp;</span>
                        <?php if($cbill['triplog_clock']=='AM') {?>
                            <span>res to</span>
                            <span><?php echo  $cbill["school_abr"];?></span>
                        <?php } ?>
                        <?php if($cbill['triplog_clock']=='PM') {?>
                            <span><?php echo  $cbill["school_abr"];?></span>
                            <span>to res</span>
                        <?php } ?>
                    </td>
                    <td style="text-align: right" class="col-xs-2">
                        $<span ><?php echo $cbill["o_payable"]; ?></span>
                    </td>
                    <td style="text-align: right" class="col-xs-2">
                        $<span ><?php echo $cbill["o_tip"]; ?></span>
                    </td>
                </tr>
            <?php }}?>
            <tr style="height:40px">
                <td style="padding-left:20px"  class="col-xs-8"> It's been pleasure working with you!</td>
                <td style="padding-right:20px;text-align: center" colspan="2">Total:&nbsp;&nbsp;<span class="db_totalPayable"> </span></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Page Content -->
<div id="page-wrapper" class="dontprint">
    <div class="container-fluid">

        <div class="row" >
            <div class="col-lg-12">
                <h1 class="page-header">Contractors Settlement Record</h1>
                <div class="btn-group form-group">

                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                        Filters
                    </button>


                    <button type="button" class="btn btn-primary btn-lg" onclick="printDriverBill();">
                        Print
                    </button>

                    <a href="payroll.php" type="button" class="btn btn-primary btn-lg">
                        Payroll
                    </a>

                </div>
                <!--Start Modal  -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal" action="driverbilling.php">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Driver Bill</h4>
                            </div>

                            <div class="modal-body">
                                <label>Driver</label>
                                <div class="input-group">
                                    <input id="drivername" name="db_drivername" required class="form-control typeahead" placeholder="" style="width: 500px" >
                                    <input class="form-control" id="driver_id" name="driver_id" type="hidden" placeholder="">
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div>
                            <span>Settlement Record : </span>
                              <h4 id="db_drivername" style="display: inline-block"><?php echo $driver_name; ?>  </h4>
                            <span style="padding-left: 10px">From:</span>
                              <h5 id="db_startdate" style="display: inline-block"><?php echo $start_date; ?>  </h5>
                            <span style="padding-left: 10px">To:</span>
                              <h5 id="db_enddate" style="display: inline-block"><?php echo $end_date; ?>  </h5>
                        </div>


                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-fixed dataTab">
                                <thead>
                                <tr>
                                    <th class="col-xs-1">Date</th>
                                    <th class="col-xs-2">Student Name</th>
                                    <th class="col-xs-2">Total</th>
                                    <th class="col-xs-1">Tip</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Use returned data (if any)
                                if(isset($_POST['driver_id'])) {
                                    // Use returned data (if any)
                                    while ($bill = mysqli_fetch_assoc($driverBill)) {
                                        // output data from each row
                                             $driver_tip += $bill['o_tip'];
                                             $driver_payable+=$bill['o_payable'];

                                        // output data from each row
                                        ?>
                                        <tr>
                                            <td class="col-xs-1"><?php echo $bill['triplog_date']; ?></td>
                                            <td class="col-xs-2"><?php if($bill['s_name']==null ) echo 'Additional Trip'; else echo $bill['s_name']; ?></td>
                                            <td class="col-xs-2"><?php echo $bill['o_payable']; ?></td>
                                            <td class="col-xs-1"><?php echo $bill['o_tip']; ?></td>
                                        </tr>

                                        <?php
                                    }
                                }
                                ?>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
                <h3 class="page-header" style="display:inline">Contractors Settlement Summary</h3>
            <div class="form-group" style="width: 10%;margin-left:68%">
                <div class="input-group" >
                    <input type="text" name="dbillsavedate" class="form-control date"  id="dbillsavedate" value="<?php echo $datebillsaved; ?>" required="true"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
            </div>
                <div class="col-lg-4">
                    <input class="form-control" id="db_from"  type="hidden" placeholder="" value="<?php echo $start_date;?>">
                    <input class="form-control" id="db_to"  type="hidden" placeholder="" value="<?php echo $end_date;?>">
                    <input class="form-control" id="cashad_driverid"  type="hidden" placeholder="" value="<?php echo $driver_id;?>">
                    <input class="form-control" id="cashad_initial"  type="hidden" placeholder="" value="<?php echo $advance;?>">
                    <input class="form-control" id="total_earnings"  type="hidden" placeholder="" value="<?php echo $total_earnings;?>">
                    <input class="form-control" id="pay_id"  type="hidden" placeholder="" value="<?php echo $pay_id;?>">


                    <div class="form-group">
                            <label>Total Billing</label>
                    <?php    if(isset($_GET['payId'])){ ?>
                        <p style="height:40px;">$<span id="d_payable"><?php echo $driver_pay; ?></span></p>
                    <?php } else{?>
                        <p style="height:40px;">$<span id="d_payable"><?php echo $driver_payable; ?></span></p>
                     <?php } ?>
                    </div>
                    <div class="form-group">
                        <select id="d_contractorsPay">
                            <option value="5" <?php if ($contractorsPerc == 5) { ?>selected="true" <?php }; ?> > 5%</option>
                            <option  value="10" <?php if ($contractorsPerc == 10) { ?>selected="true" <?php }; ?> > 10%</option>
                            <option  value="15" <?php if ($contractorsPerc == 15) { ?>selected="true" <?php }; ?> > 15%</option>
                            <option  value="20" <?php if ($contractorsPerc == 20) { ?>selected="true" <?php }; ?> > 20%</option>
                            <option  value="25" <?php if ($contractorsPerc == 25) { ?>selected="true" <?php }; ?> > 25%</option>
                            <option  value="30" <?php if ($contractorsPerc == 30) { ?>selected="true" <?php }; ?> > 30%</option>
                            <option  value="35" <?php if ($contractorsPerc == 35) { ?>selected="true" <?php }; ?> > 35%</option>
                            <option  value="40" <?php if ($contractorsPerc ==40) { ?>selected="true" <?php }; ?> > 40%</option>
                        </select>
                        <label>Pay To Contractors</label>
                        <?php    if(isset($_GET['payId'])){ ?>
                            <p>$<span id="d_pay"><?php echo $driver_pay*($contractorsPerc/100.0); ?></span></p>
                        <?php } else{?>
                            <p>$<span id="d_pay"><?php echo $driver_payable*0.25; ?></span></p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label>Tips</label>
                        <?php    if(isset($_GET['payId'])){ ?>
                            <p style="height:25px;">$<span  id="d_tip"><?php echo $driverTips; ?></span></p>
                        <?php } else{?>
                            <p style="height:25px;">$<span  id="d_tip"><?php echo $driver_tip; ?></span></p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label>Additions</label>
                        <input class="form-control" name="d_additions" id="d_additions" type="number" value="<?php echo $additions; ?>" >
                        <p class="help-block"></p>
                    </div>
                    <div class="form-group">
                        <label style="height:30px;">Contactors Total</label>
                        <?php    if(isset($_GET['payId'])){ ?>
                            <p> $<span id="d_contractorTotal"><?php echo $driverCtotal; ?></span></p>
                        <?php } else{?>
                            <p> $<span id="d_contractorTotal"><?php echo $driver_payable*0.25+$driver_tip; ?></span></p>
                        <?php } ?>
                    </div>

                </div>
                <div class="col-lg-4" id="d_deductions">

                    <div class="form-group">
                        <label>Fuel Advance($)</label>
                        <input class="form-control fuel_advance"  type="number" value="<?php echo $fuel_advance; ?>"/>
                    </div>
                    <div class="form-group">
                        <label>Tolls($)</label>
                        <input class="form-control d_tolls"  type="number" value="<?php echo $toll; ?>"/>
                    </div>
                    <div class="form-group">
                        <select class="d_leasepercentage">
                            <option   value="0" <?php if ($leasePerc == 0) { ?>selected="true" <?php }; ?> > 0%</option>
                            <option  value="5" <?php if ($leasePerc == 5) { ?>selected="true" <?php }; ?> > 5%</option>
                            <option  value="10" <?php if ($leasePerc == 10) { ?>selected="true" <?php }; ?> > 10%</option>
                            <option  value="15" <?php if ($leasePerc == 15) { ?>selected="true" <?php }; ?> > 15%</option>
                            <option  value="20" <?php if ($leasePerc == 20) { ?>selected="true" <?php }; ?> > 20%</option>
                            <option  value="25" <?php if ($leasePerc == 25) { ?>selected="true" <?php }; ?> > 25%</option>
                            <option  value="30" <?php if ($leasePerc == 30) { ?>selected="true" <?php }; ?> > 30%</option>
                            <option  value="35" <?php if ($leasePerc == 35) { ?>selected="true" <?php }; ?> > 35%</option>
                            <option  value="40" <?php if ($leasePerc ==40) { ?>selected="true" <?php }; ?> > 40%</option>
                        </select>
                        <label>Lease</label>
                        <?php    if(isset($_GET['payId'])){ ?>
                            <p>$<span class="d_lease"><?php echo $lease; ?></span></p>
                        <?php } else{?>
                            <p>$<span class="d_lease">0</span></p>
                        <?php } ?>
                    </div>

                        <div class="form-group">
                            <label>Advances($)</label><br>
                            <input class="form-control cash_advance" style="width:200px;display: inline-block" type="number" value="<?php echo $advance; ?>"/>
                            <button type="button" class="btn btn-primary" style="display: inline-block" onclick="updateCashAdvance()">
                                Update
                            </button>
                            <p style="display: inline-block;color:crimson" class="advance_result"></p>
                        </div>
                        <div class="form-group">
                            <label>Other($)</label>
                            <input class="form-control d_others"  type="number" value="<?php echo $other; ?>"/>
                        </div>


                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="form-group">
                            <label>Check Number</label>
                            <input class="form-control check_number"  style="width:200px" value="<?php echo $checkNumber; ?>"/>
                        </div>
                        <label>Total Settlement Check</label><br>
                        <?php    if(isset($_GET['payId'])){ ?>
                            <p style="display:inline-block;padding-right: 140px">$<span id="d_finalCheck"><?php echo $driverFamount; ?></span></p>
                        <?php } else{?>
                            <p style="display:inline-block;padding-right: 140px">$<span id="d_finalCheck"><?php echo $driver_payable*0.25+$driver_tip-$advance ?></span></p>
                        <?php } ?>
                        <button type="button" class="btn btn-primary" style="display: inline-block" onclick="setDriverBill()">
                            Calculate
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        <?php
        if(isset($_GET['payId'])) { ?>
                 <button type="submit" class="btn btn-primary btn-lg"  id="updateDriverBill" name="updateDriverBill" style="margin-left: 25px">Update</button>
            <?php
               } else if(isset($_POST['driver_id']) && checkpayroll($driver_id,$start_date,$end_date)) {
             ?>
                <div class="form-group hidebox" id="savepl">
                    <button type="submit" class="btn btn-primary btn-lg"  id="savepl_button" style="margin-left: 20px">Save to Payroll</button>
                    <input type="hidden" class="form-control" data-driver_id="<?php echo $driver_id; ?>" data-startdate="<?php echo $start_date; ?>" data-enddate="<?php echo $end_date; ?>" />
                </div>

            <?php } else {?>
                <div class="form-group hidebox" id="savepl">
                    <button type="submit" class="btn btn-primary btn-lg"  disabled id="savepl_button">Saved</button>
                </div>
            <?php }?>
        </div>

        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>

<!-- /#page-wrapper -->



<?php
require_once("./includes/footer.php");
?>
