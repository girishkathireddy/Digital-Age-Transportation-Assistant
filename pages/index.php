<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php

$query  = "SELECT count(*) AS students FROM lpr_student ";
$result = mysqli_query($connection, $query);
error_log("\nIndex" . $query , 3, "C:/xampp/apache/logs/error.log");
confirm_query($result);
if($result_students = mysqli_fetch_assoc($result)) {
        $studentcount=$result_students;
    } else {
        $studentcount= 0;
    }

$query  = "SELECT count(*) AS drivers FROM lpr_driver ";
$result = mysqli_query($connection, $query);
error_log("\nIndex" . $query , 3, "C:/xampp/apache/logs/error.log");
confirm_query($result);
if($result_driver = mysqli_fetch_assoc($result)) {
        $drivercount=$result_driver;
    } else {
        $drivercount= 0;
    }

$query  = "SELECT count(*) AS trips FROM `lpr_triplog` WHERE triplog_date-CURRENT_DATE < 8 ";
$result = mysqli_query($connection, $query);
error_log("\nIndex" . $query , 3, "C:/xampp/apache/logs/error.log");
confirm_query($result);
if($result_trips = mysqli_fetch_assoc($result)) {
        $tripcount=$result_trips;
    } else {
        $tripcount= 0;
    }
$query  = "SELECT count(*) AS ctrips FROM `lpr_triplog` WHERE triplog_date-CURRENT_DATE < 8 AND triplog_status = 'cancel' ";
$result = mysqli_query($connection, $query);
error_log("\nIndex" . $query , 3, "C:/xampp/apache/logs/error.log");
confirm_query($result);
if($result_ctrips = mysqli_fetch_assoc($result)) {
        $ctripcount=$result_ctrips;
    } else {
        $ctripcount= 0;
    }
?>
<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Snapshot</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $studentcount["students"]; ?></div>
                                    <div>Students</div>
                                </div>
                            </div>
                        </div>
                        <a href="changeorder.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $drivercount["drivers"]; ?></div>
                                    <div>Drivers</div>
                                </div>
                            </div>
                        </div>
                        <a href="drivers.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $tripcount["trips"]; ?></div>
                                    <div>Rides</div>
                                </div>
                            </div>
                        </div>
                        <a href="manifest.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $ctripcount["ctrips"]; ?></div>
                                    <div>Cancellations</div>
                                </div>
                            </div>
                        </div>
                        <a href="manifest.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
                <!-- /.row --> <!-- /.col-lg-12 -->
            </div>
           <!-- /.container-fluid --> 
            </div>
             <!-- /#pagewrapper -->
<!-- <?php echo convert_number_to_money(111.00); ?> <br>
<?php echo convert_number_to_money(12225.33); ?>  -->

<?php
  require_once("./includes/footer.php");
?>