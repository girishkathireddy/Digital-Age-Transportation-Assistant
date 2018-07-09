<?php
include("./includes/db_connection.php");
include("./includes/functions.php");

?>
<?php
$driver_name="";
$cashToPrint="";
if(isset($_POST['ca_submit'])) {
    $driver_id= $_POST['driver_id'];
    $driver_name=$_POST['ca_drivername'];
    $result_advance = getAdvanceDeatils($driver_id);
    $print_sheet= getAdvanceDeatils($driver_id);
    $cash= getCashAdvance($driver_id);
    $cashToPrint=$cash['cashAdvance'];
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


    .s1height{
        line-height: 0.1em;
        font-family: Georgia, "Times New Roman", Times, serif;
        padding-bottom: 50px;
        text-align: center;
    }

    .footer {
        position: absolute;
        left: 0;
        bottom: 0;
        height: 100px;
        width: 100%;
        overflow:hidden;
        text-align: center;
    }
</style>

<div id="page-wrapper" class="toprint" style="height: auto">
    <div class="container-fluid" >
       <div class="row">
           <div class="col-lg-3">
               <img src="../images/bus1.jpg" alt="Bus" style="width:150px;height:100px;">
               <h6 style="float:right"><span>LPR Transportation</span><br>
                   3455 Azalea Garden Rd,<br>
                   Norfolk,VA 23513</h6>
           </div>
           <div class="col-lg-3">
               <h2 class="s1height"><span>LPR TRANSPORTATION</span></h2>
          </div>
       </div>
        <div class="row" style="padding-bottom: 20px">
            <div class="col-lg-12">
                <h5>Advance Details: <span><?php echo $driver_name; ?></span></h5>
            </div>
        </div>

        <table style="padding-bottom: 30px">
            <!--                    <col width="400">-->
            <!--                    <col width="200">-->
            <thead>
            <tr>
                <th style="padding-left:20px;text-align: center" class="col-xs-4"> <span>Date</span></th>
                <th style="padding-left:20px;text-align: center" class="col-xs-4"> <span>Amount($)</span></th>
                <th style="padding-left:20px;text-align: center" class="col-xs-4"> <span>Transaction Type</span></th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Use returned data (if any)
            // Use returned data (if any)
            if(isset($_POST['ca_submit'])) {
                while ($sheet = mysqli_fetch_assoc($print_sheet)) {
                    ?>

                    <tr>
                        <td style="padding-left:10px;padding-right:20px" class="col-xs-4">
                            <span><?php echo $sheet['c_Date']; ?></span>
                        </td>
                        <td style="padding-left:10px;padding-right:20px" class="col-xs-4">
                            <span><?php echo $sheet['c_payable']; ?></span>
                        </td>
                        <td style="padding-left:10px;padding-right:20px" class="col-xs-4">
                            <span><?php echo $sheet['c_type']; ?></span>
                        </td>

                    </tr>
                <?php }
            }?>

            </tbody>
        </table>

        <div class="row" style="padding-top: 40px">
            <div class="col-lg-12">
                Balance Due: $ <?php echo $cashToPrint; ?>
            </div>
        </div>

    </div>


</div>



<div id="page-wrapper" class="dontprint">
    <div class="container-fluid">
        <div class="row" >
            <div class="col-lg-12">

                <h1 class="page-header">Advance Records</h1>
                <div class="btn-group form-group">
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#cashmodal">
                        Filters
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" onclick="printAdvance()">
                        Print
                    </button>
                </div>
                <!--Start Modal  -->
                <div class="modal fade" id="cashmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">Select Driver</h4>
                                </div>

                                <div class="modal-body">
                                    <label>Driver</label>
                                    <div class="input-group">
                                        <input id="drivername" name="ca_drivername" required class="form-control typeahead" placeholder="" style="width: 500px" >
                                        <input class="form-control" id="driver_id" name="driver_id" type="hidden" placeholder="">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" id="driverBillFilter" name="ca_submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div style="display: inline-block">
                        <span>Records : </span>

                    </div>
                    <div style="display: inline-block" >
                        <h4 id="db_drivername"><?php echo $driver_name; ?>  </h4>
                    </div>


                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-fixed dataTab">
                            <thead>
                            <tr>
                                <th class="col-xs-3">Date</th>
                                <th class="col-xs-3">Amount($)</th>
                                <th class="col-xs-4">Transaction Type</th>
                                <th class="col-xs-2">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Use returned data (if any)
                            if(isset($_POST['ca_submit'])) {
                                // Use returned data (if any)
                                while ($advance = mysqli_fetch_assoc($result_advance)) {
                                    ?>
                                    <tr>
                                        <td class="col-xs-3"><?php echo $advance['c_Date']; ?></td>
                                        <td class="col-xs-3"> <?php echo $advance['c_payable']; ?></td>
                                        <td class="col-xs-4"> <?php echo $advance['c_type']; ?></td>
                                        <td class="col-xs-2"> <button type="button" class="btn btn-danger dstatus" onclick="ca_delete(this);">Click to Delete</button></td>
                                        <input type="hidden" data-transid="<?php echo $advance['c_advanceid']; ?>">
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


            </div>
        </div>
    </div>














<?php
require_once("./includes/footer.php");
?>
