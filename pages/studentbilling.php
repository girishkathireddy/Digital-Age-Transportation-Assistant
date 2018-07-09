<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>

<?php
$query_client  = "SELECT * FROM lpr_client";
$result_client = mysqli_query($connection, $query_client);
$totaltrips="";
$totalpayable="";
$client_name="";
$invoice_number=0;
confirm_query($result_client);
$cb_startdate="";
$cb_enddate="";
?>
<?php
if(isset($_POST['cb_ctypeSelect'])) {
$cb_client=$_POST['cb_ctypeSelect'];
$cb_stypeSelect=$_POST['cb_stypeSelect'];
$cb_sSelect=$_POST['cb_sSelect'];
$cb_startdate=$_POST['cb_startdate'];
$cb_enddate=$_POST['cb_enddate'];
$cb_sname=(int)$_POST['stu_id'];
$cb_details= getClientBill($cb_client,$cb_stypeSelect ,$cb_sSelect,$cb_sname,$cb_startdate,$cb_enddate);
$cb_forPrint=getClientBill($cb_client,$cb_stypeSelect ,$cb_sSelect,$cb_sname,$cb_startdate,$cb_enddate);
$cb_payment=getClientPayement($cb_client,$cb_stypeSelect ,$cb_sSelect,$cb_sname,$cb_startdate,$cb_enddate);

$invoice_data=getInvoiceNumber();
$invoice_number=$invoice_data['invoice']+1;

while ($c_pay = mysqli_fetch_assoc($cb_payment)) {
    $totaltrips = $c_pay['tripcount'];
    $totalpayable =round($c_pay['totalbillable'],2);
    $client_name=$c_pay['client_name'];
    $client_street=$c_pay['client_street'];
    $client_address=$c_pay['client_address'];
    $client_city=$c_pay['client_city'];
    $client_state=$c_pay['client_state'];
    $client_zip=$c_pay['client_zip'];
}
}
?>
<?php
if(isset($_GET['invoiceId'])) {
    $invoice_number=$_GET['invoiceId'];
    $billData = getInvoiceById($invoice_number);
    $totaltrips=$billData['totaltrips'];
    $totalpayable=$billData['totalpayable'];
    $cb_startdate=$billData['startdate'];
    $cb_enddate=$billData['enddate'];
    $client_name=$_GET['clientName'];
    $cb_client=$_GET['cid'];
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
    { display: inline;
    }
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
        margin:.5cm;
        /* this affects the margin in the printer settings */
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
<style>
    @media print {
        html, body {
            height: 99%;
        }
    }
</style>

<?php
if(isset($_POST['cb_ctypeSelect'])) {
?>
<div class="toprint" style="padding-left: 100px;height: auto;page-break-after: avoid">
    <div id="page-wrapper">
        <br><br>
        <div class="container-fluid">
            <div class="to2Columns" >
                <div style="page-break-inside:avoid;">
                         <b>LPR Ground Transportation</b><br>
                         <b> 3455 Azalea Garden Rd<br>
                             Norfolk,VA 23513
                         </b>
                </div>

                <div style="page-break-inside:avoid;padding-top: 5px;float: right">
                    <b>
                        <span style="padding-left:40px">INVOICE
                        </span>
                    </b>
                 <table>
                 <thead>
                      <tr style="width: 100px">
                          <th style="width:10px;padding-left:20px" > <span>Date</span></th>
                          <th style="width:10px;padding-left:10px;padding-right:20px"> <span >Invoice</span></th>
                      </tr>
                 </thead>
                  <tbody>
                      <tr>
                          <td style="width:100px;padding-left:10px;padding-right:20px"> <span class="cb_date">_______</span></td>
                          <td style="width:100px;padding-left:10px;padding-right:20px"><span><?php echo $invoice_number; ?><span></td>
                      </tr>
                  </tbody>
                 </table>
                </div>

            </div>
            <br><br>
            <div>
                <table width="400">
                    <thead>
                    <tr>
                        <th style="padding-left:20px;text-align: center"> <span>Bill T0</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="padding-left:10px;padding-right:20px">
                            <span class="cb_client"><?php echo $client_name; ?></span><br>
                            <span><?php echo $client_address; ?></span>
                            <span><?php echo $client_street; ?></span><br>
                            <span><?php echo $client_city; ?>,</span>
                            <span><?php echo $client_state; ?>,</span>
                            <span><?php echo $client_zip; ?></span>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <br><br>
            <div>
                <table >
<!--                    <col width="400">-->
<!--                    <col width="200">-->
                    <thead>
                    <tr>
                        <th style="padding-left:20px;text-align: center" class="col-xs-8"> <span>Description</span></th>
                        <th style="padding-left:20px;text-align: center" class="col-xs-4"> <span>Amount</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Use returned data (if any)
                    // Use returned data (if any)
                    while ($cbill = mysqli_fetch_assoc($cb_forPrint)) {

                        $originalDate = $cbill['triplog_date'];
                        $newDate = date("F j, Y", strtotime($originalDate));
                    ?>

                    <tr>
                        <td style="padding-left:10px;padding-right:20px" class="col-xs-8">
                            <span><?php echo $newDate; ?>-</span>
                            <span><?php echo $cbill["s_name"]; ?></span>
                            <span><?php echo date ('H:i',strtotime($cbill["triplog_time"]))  ?>&nbsp;</span>
                            <?php if($cbill['triplog_clock']=='AM') {?>
                                <span>res to</span>
                                <span><?php echo  $cbill["school_abr"];?></span>
                            <?php } ?>
                            <?php if($cbill['triplog_clock']=='PM') {?>
                                <span><?php echo  $cbill["school_abr"];?></span>
                                <span>to res</span>
                            <?php } if($cbill['triplog_status']=='noshow'  || ($cbill['triplog_status'] =="cancel" && $cbill['triplog_client_payable'] =="TRUE" ) ) {?>
                                <span>   Trip Status: No Show</span>
                                <?php } ?>
                        </td>
                        <td style="text-align: right" class="col-xs-4">
                            $<span ><?php echo $cbill["o_billable"]; ?></span>


                        </td>
                    </tr>
                    <?php }?>
                    <tr style="height:40px">
                        <td style="padding-left:20px"> It's been pleasure working with you!</td>
                        <td style="padding-right:20px;text-align: right">Total:<span> &nbsp;&nbsp;$<?php echo $totalpayable; ?> </span></td>
                    </tr>
                    </tbody>
                </table>

            </div>


        </div>
    </div>

</div>

<?php
}
?>

<div class="dontprint">
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Client Billing</h1>
                <div class="btn-group form-group">

                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#cb_myModal">
                        Filters
                    </button>


                    <button type="button" class="btn btn-primary btn-lg" onclick="printClientBill();">
                        Print
                    </button>
                    <a href="Bill.php" type="button" class="btn btn-primary btn-lg">
                        Bills
                    </a>

                </div>
                <!--Start Modal  -->
                <div class="modal fade" id="cb_myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <form method="post" enctype="multipart/form-data" action="studentbilling.php">
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
                                </div>
                                <div class="form-group">
                                    <label>School-Type</label>
                                    <select class="form-control" id="stypeSelect" name="cb_stypeSelect">
                                        <option value="">Select</option>
                                        <option>Elementary</option>
                                        <option>Preschool</option>
                                        <option>Middle</option>
                                        <option>High</option>
                                        <option>Special</option>
                                        <option>Alternative</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>School-Name</label>
                                    <select class="form-control" id="sSelect" name="cb_sSelect">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Student</label>
                                    <input class="form-control typeahead_student" placeholder="">
                                    <input class="form-control" name="stu_id" type="hidden" placeholder="">
                                </div>

                                <label>Start Date</label>
                                <div class="input-group">
                                    <input type="text" name="cb_startdate" class="form-control date" id="from" required><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                                <label>End Date</label>
                                <div class="input-group">
                                    <input type="text" name="cb_enddate" class="form-control date" id="to" required><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <!--End Modal  -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Trips: </span>
                        <h5  style="display: inline-block"><?php echo $client_name; ?>  </h5>
                        <span style="padding-left: 10px">From:</span>
                        <h5  style="display: inline-block"><?php echo $cb_startdate; ?>  </h5>
                        <span style="padding-left: 10px">To:</span>
                        <h5  style="display: inline-block"><?php echo $cb_enddate; ?>  </h5>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                            <table class="table table-fixed dataTab">
                                <thead>
                                <tr>
                                    <th  class="col-xs-1">Date</th>
                                    <th  class="col-xs-2">Student Name</th>
                                    <th  class="col-xs-2">Driver Name</th>
                                    <th  class="col-xs-2">Pick Up Time</th>
                                    <th class="col-xs-2">Pick Up Loc</th>
                                    <th  class="col-xs-2">Drop off Loc</th>
                                    <th  class="col-xs-1">Amount</th>
                                </tr>

                                </thead>
                                <tbody>
                                <?php
                                // Use returned data (if any)
                                if(isset($_POST['cb_ctypeSelect'])) {
                                    // Use returned data (if any)
                                    while ($cbill = mysqli_fetch_assoc($cb_details)) {
                                        ?>
                               <tr>
                                <td class="col-xs-1"><?php echo $cbill["triplog_date"]; ?></td>
                                <td class="col-xs-2"><?php echo $cbill["s_name"]; ?></td>
                                <td class="col-xs-2"><?php echo $cbill["d_name"]; ?></td>
                                <td class="col-xs-2"><?php echo $cbill["triplog_time"]; ?></td>
                                <td class="col-xs-2"><?php echo $cbill["pickloc"]; ?></td>
                                <td class="col-xs-2"><?php echo $cbill["droploc"]; ?></td>
                                <td class="col-xs-1"><?php echo $cbill["o_billable"]; ?></td>
                               </tr>

                              <?php }  } ?>
                                </tbody>
                            </table>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
                <h3 class="page-header">Billing Information</h3>
                <div class="col-lg-4">

                    <div class="form-group">
                        <label>Total Trips</label>
                        <input id="totaltrips" class="form-control"  value="<?php echo $totaltrips; ?>">
                        <p class="help-block"></p>
                    </div>

                    <div class="form-group">
                        <label>Total Payable</label>
                        <input id="totalbillable" class="form-control" value="<?php echo $totalpayable; ?>">
                    </div>
                </div>
            <?php    if(isset($_GET['invoiceId'])) { ?>
                <button type="submit" class="btn btn-primary btn-lg"  id="updateClientBill" name="updateClientBill" style="margin-left: 20px;margin-top: 90px">Update</button>
                <input type="hidden" class="form-control" data-clientid="<?php echo $cb_client; ?>"  data-invoicenumber="<?php echo $invoice_number; ?>" data-startdate="<?php echo $cb_startdate; ?>" data-enddate="<?php echo $cb_enddate; ?> " />
                <?php
                } else if(isset($_POST['cb_ctypeSelect']) && checkClientBill($cb_client,$cb_startdate,$cb_enddate)) {
                    ?>
                    <div class="form-group" id="savecb">
                        <button type="submit" class="btn btn-primary btn-lg"  id="savecb_button" style="margin-left: 20px;margin-top: 90px">Save to Payroll</button>
                        <input type="hidden" class="form-control" data-clientid="<?php echo $cb_client; ?>" data-startdate="<?php echo $cb_startdate; ?>" data-enddate="<?php echo $cb_enddate; ?>" />
                    </div>

                <?php } ?>

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
