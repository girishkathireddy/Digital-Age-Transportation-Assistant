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
    .toprint
    { display: none; }

</style>
<?php
if(isset($_POST['ra_id'])&& !empty($_POST['ra_id'])) {
    $ra_id= $_POST['ra_id'];
    $start_date= $_POST['fstartdate'];
    $end_date= $_POST['fenddate'];

    $query ="SELECT * FROM lpr_payroll_ra LEFT JOIN lpr_ridealong ON lpr_payroll_ra.ra_id=lpr_ridealong.id WHERE '$start_date' <= startdate AND '$end_date' >=enddate AND lpr_ridealong.id=$ra_id ";

    error_log("\nChange Order" . $query , 3, "C:/xampp/apache/logs/error.log");
    $result = mysqli_query($connection, $query);
    $result_p= mysqli_query($connection, $query);

    confirm_query($result);


}
elseif (isset($_POST['fstartdate'],$_POST['fenddate'])) {
    $start_date= $_POST['fstartdate'];
    $end_date= $_POST['fenddate'];
    $query ="SELECT * FROM lpr_payroll_ra LEFT JOIN lpr_ridealong ON lpr_payroll_ra.ra_id=lpr_ridealong.id WHERE '$start_date' <= startdate AND '$end_date' >=enddate ";
    error_log("\nChange Order" . $query , 3, "C:/xampp/apache/logs/error.log");
    $result = mysqli_query($connection, $query);
    $result_p = mysqli_query($connection, $query);
    confirm_query($result);
}
?>

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


<?php  if(!empty($result_p)) { while($sheets = mysqli_fetch_assoc($result_p)) {  ?>
    <div class="toprint" style="page-break-before: always;">
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="padding-top: 40px;">
                    <div class="col-lg-12">
                        <h6><b><span style="float:right"><?php echo date("m/d/Y"); ?></span></b></h6>
                    </div>
                </div>
                <div class="row" style="padding-top: 25px;padding-left: 60px">
                    <div class="col-lg-12">
                        <h5 style="padding-left: 60px"><b><span><?php echo $sheets['ra_fname'];?></span> <span><?php echo $sheets['ra_lname'];?></span>
                            <span style="float:right;padding-right: 20px">** <?php echo $sheets['amount'];?></span></b></h5>
                    </div>
                </div>
                <div class="row" style="padding-top: 0px;">
                    <div class="col-lg-12">
                        <h5 style="padding-left: 60px"><b><span><?php echo convert_number_to_money($sheets['amount']);?></span> </b></h5>
                    </div>
                </div>
                <div class="row" style="padding-top:10px";>
                    <div class="col-lg-12">
                        <h6 style="padding-left: 60px;line-height: 0.5em">
                            <b><span><?php echo $sheets['ra_fname'];?></span> <span><?php echo $sheets['ra_lname'];?></span>
                        </h6>
                        <h5 style="padding-left: 60px;line-height: 0.5em"><span><?php echo $sheets['ra_street'];?></span> <span><?php echo $sheets['address'];?></span></h5>
                        <h5 style="padding-left: 60px;line-height: 0.5em"><span><?php echo $sheets['ra_city'];?></span>,<span><?php echo $sheets['ra_state'];?></span>  <span><?php echo $sheets['ra_zip'];?></span></h5>
                    </div>
                </div>

                <div class="row" style="padding-top:10px";>
                    <div class="col-lg-12">
                        <h6 style="padding-left: 100px;line-height: 0.3em">Independent<span>  <?php echo $sheets["startdate"];?></span> - <span><?php echo $sheets["enddate"];?></span></h6>
                    </div>
                </div>
                <div class="row" style="padding-top:70px";>
                    <div class="col-lg-12">
                        <h6 style="padding-left: 60px;line-height: 0.5em">
                            <span><?php echo $sheets['ra_fname'];?></span> <span><?php echo $sheets['ra_lname'];?></span>
                                <span style="float:right;padding-right: 80px"><?php echo date("m/d/Y"); ?></span>
                        </h6>
                        <h6><span style="float:right;padding-right: 20px"><?php echo $sheets['amount'];?></span></h6>
                    </div>
                </div>
                <div class="row" style="padding-top:230px";>
                    <div class="col-lg-12">
                        <h6 style="padding-left: 150px;line-height: 0.3em">Independent<span>  <?php echo $sheets["startdate"];?></span> - <span><?php echo $sheets["enddate"];?> </span>
                            <span style="float:right;padding-right: 20px"><?php echo $sheets['amount'];?></span></h6>
                    </div>
                </div>
                <div class="row" style="padding-top:50px";>
                    <div class="col-lg-12">
                        <h6 style="padding-left: 60px;line-height: 0.5em">
                            <span><?php echo $sheets['ra_fname'];?></span> <span><?php echo $sheets['ra_lname'];?></span>
                                <span style="float:right;padding-right: 80px"><?php echo date("m/d/Y"); ?></span>
                        </h6>
                        <h6><span style="float:right;padding-right: 20px"><?php echo $sheets['amount'];?></span></h6>
                    </div>
                </div>
                <div class="row" style="padding-top: 240px;";>
                    <div class="col-lg-12">
                        <h6 style="padding-left: 150px;line-height: 0.3em">Independent<span>  <?php echo $sheets["startdate"];?></span> - <span><?php echo $sheets["enddate"];?> </span>
                            <span style="float:right;padding-right: 20px"><?php echo $sheets['amount'];?></span></h6>
                    </div>
                </div>


            </div>
        </div>

        <div style="page-break-after: always">


        </div>
    </div>

<?php  }} ?>


<div id="page-wrapper" class="dontprint">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Payroll</h1>
                        <div class="btn-group form-group">

                                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                                        Filters
                                    </button>


                                    <button type="button" class="btn btn-primary btn-lg" onclick="printCheck();">
                                        Print
                                    </button>


                                </div>
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                    Payroll
                                </div>
                                <!-- /.panel-heading -->
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped dataTab">
                                            <thead>
                                                <tr>
                                                    <th>Driver Name</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Amount</th>
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
                                                    <td><?php echo $subject["ra_fname"]. " ".$subject["ra_lname"]; ?></td>
                                                    <td><?php echo $subject["startdate"]; ?></td>
                                                    <td><?php echo $subject["enddate"]; ?></td>
                                                    <td><?php echo $subject["amount"]; ?></td>
                                                    <td class="col-xs-1"><a href="<?php echo 'ralongbilling.php?payId=' . $subject['plra_id']; ?>" class="size2" style="color: #5cb85c;margin: inherit;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                                    <td><button type="button" class="btn btn-danger" onclick="deletepay_ra(this,<?php echo $subject["plra_id"]; ?>);">Delete</button></td>
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
                                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title" id="myModalLabel">Ride Along Bill</h4>
                                        </div>

                                        <div class="modal-body">
                                            <label>Ride Along</label>
                                            <div class="input-group">
                                                <input id="drivername" name="db_drivername" class="form-control typeahead_ra" placeholder="" style="width: 500px" >
                                                <input class="form-control" id="ra_id" name="ra_id" type="hidden">
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
