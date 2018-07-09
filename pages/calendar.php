<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>








<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Calendar</h1>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        All Rows
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div id='calendar'></div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <div class="form-group">
                    <a href="addevent.php" class="btn btn-primary btn-lg btn-block" role="button">Add Event</a>
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
