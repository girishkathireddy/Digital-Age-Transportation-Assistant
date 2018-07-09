<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>
<?php

$query ="SELECT lpr_order.o_id, lpr_client.client_abr,lpr_school.school_abr,lpr_student.s_fname,lpr_student.s_lname,lpr_driver.driver_fname,lpr_driver.driver_lname,lpr_driver.driver_dname,lpr_order.o_startdate,lpr_order.o_enddate,lpr_order.o_ampickloc,lpr_order.o_status FROM `lpr_order` LEFT JOIN lpr_client ON lpr_order.o_reqby = lpr_client.client_id LEFT JOIN lpr_school ON lpr_order.school_id = lpr_school.school_id LEFT JOIN lpr_student ON lpr_order.o_id = lpr_student.o_id LEFT JOIN lpr_driver ON lpr_order.driver_id = lpr_driver.driver_id LEFT JOIN (SELECT lpr_billing.o_id,count(lpr_billing.o_id) as zones ,SUM(lpr_billing.amount) as clientbill from lpr_billing GROUP by lpr_billing.o_id) AS bill ON lpr_order.o_id = bill.o_id GROUP by lpr_order.o_id";

//error_log("\nChange Order" . $query , 3, "C:/xampp/apache/logs/error.log");
$result = mysqli_query($connection, $query);

confirm_query($result);



?>
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">

                        <h1 class="page-header">Orders</h1>
                        <div class="row" style="padding-bottom: 10px">
                            <div style="padding-right: 60px; float:right;display: inline">
                        <a type="button" class="btn btn-primary btn-lg" href="http://localhost.org/phpmyadmin/sql.php?server=1&db=lpr_portal&table=lpr_student_audit" target="_blank">
                            Track address changes
                        </a>
                        </div>
                        </div>
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                    All Orders
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped dataTab">
                                            <thead>
                                                <tr>
                                                    <th>Client</th>
                                                    <th>School</th>
                                                    <th>Student Name</th>
                                                    <th>Driver Name</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Pick Up</th>
                                                    <th>Order Status</th>
                                                    <th>Edit Trip</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            // Use returned data (if any)
                                            while($subject = mysqli_fetch_assoc($result)) {

                                            ?>
                                                <tr>
                                                    <td><?php echo $subject["client_abr"]; ?></td>
                                                    <td><?php echo $subject["school_abr"]; ?></td>
                                                    <td><?php echo $subject["s_fname"] . " ".$subject["s_lname"]; ?></td>
                                                    <?php if ($subject["driver_dname"] == NULL) { ?>
                                                    <td><?php echo $subject["driver_fname"]. " ".$subject["driver_lname"]; ?></td>
                                                    <?php } else {?>
                                                    <td><?php echo $subject["driver_dname"]; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $subject["o_startdate"]; ?></td>
                                                    <td><?php echo $subject["o_enddate"]; ?></td>
                                                    <td><?php echo $subject["o_ampickloc"]; ?></td>
                                                    <?php if ($subject["o_status"] == "active") { ?>
                                                    <td><button type="button" class="btn btn-success ostatus" onclick="changestatus(this);">Active</button></td>
                                                    <?php } else {?>
                                                    <td><button type="button" class="btn btn-danger ostatus" onclick="changestatus(this);">Inactive</button></td>
                                                    <?php } ?>
                                                    <td><a type="button" href="editorder.php?oid=<?php echo $subject['o_id']; ?>" class="btn btn-primary">Edit</a></td>
                                                    <input type="hidden" name="" data-orderid="<?php echo $subject["o_id"]; ?>">
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->

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
