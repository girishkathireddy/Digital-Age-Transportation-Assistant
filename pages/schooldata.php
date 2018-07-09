<?php
  include("./includes/db_connection.php");
  include("./includes/functions.php"); 
?>
<?php
$query_client  = "SELECT * FROM lpr_client";
$result_client = mysqli_query($connection, $query_client);
confirm_query($result_client);

$query_zone= "Select * from lpr_zones";
$result_zone = mysqli_query($connection, $query_zone);
confirm_query($result_zone);
?>



<?php
  include("./includes/htmlheader.php");
  include("./includes/nav.php"); 
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                <h1 class="page-header">School Data</h1>
                    <div class="col-lg-4">
                        <!-- <h1 class="page-header">School Data</h1> -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Zones
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Zone</th>
                                            <th>Edit</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // 3. Use returned data (if any)
                                        while($subject_zone = mysqli_fetch_assoc($result_zone)) {
                                            // output data from each row
                                            ?>
                                            <tr>
                                                <td headers="zone"><span><?php echo $subject_zone["zone_loc"]; ?></span></td>
                                                <td><a href="#" onclick="editZone(event)" id="editZone"  class="size2" style="color: #5cb85c;margin: auto;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                                <input type="hidden" value="<?php echo $subject_zone['zone_id']; ?>" class="c_zoneId">
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
                            <button  class="btn btn-primary btn-lg btn-block " id="add_zone">Add Zone</button>
                        </div>
                        <!-- /.panel -->
                        <div class="form-group" id="zone_toggle" style="display:none">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                  <label for="zone_loc">Zone</label>
                                    <input type="text" class="form-control" id="zone_loc" value="" required><br>
                                    <button  class="btn btn-primary btn-block" id="zone_save">Save</button>
                                    <input type="hidden" class="form-control" id="c_zoneId" value="" >
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <!-- <h1 class="page-header">School Data</h1> -->
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                    Client Data
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                	   
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Client</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                    <th>Schools</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                // 3. Use returned data (if any)
                                                while($subject_client = mysqli_fetch_assoc($result_client)) {
                                                    // output data from each row
                                            ?>
                                                    <tr>
                                                    <td><?php echo $subject_client["client_abr"]; ?></td>
                                                    <td><a href="<?php echo 'addclient.php?client_id=' . $subject_client['client_id']; ?>" class="size2" style="color: #5cb85c;margin: auto;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                                    <td><span class="size2" style="color: red;margin: auto;"><i class="fa fa-times-circle" aria-hidden="true"></i></span></td>
                                                    <td><span id="" value="<?php echo $subject_client["client_id"]; ?>" class="size2 schools" style="color: #10c6da;margin: auto;"><i  onclick="show_schoolData(this)"  class="fa fa-caret-square-o-right" aria-hidden="true"></i></span></td>
                                                    <input type="hidden" data-clientid="<?php echo $subject_client["client_id"]; ?>">
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
                            <a href="addclient.php" class="btn btn-primary btn-lg btn-block" role="button">Add Client</a>
                        </div>
                            <!-- /.panel -->

                    </div>
                    <div class="col-lg-4">
							
						<!-- Accordion for schools-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Student Data
                            </div>
                            <div  class="panel-body"  >
                                <div class="panel panel-default" id="accordion" style="border:none">

                                    <h4 class="btn-lg panel-heading" style="font-size: 14px;font-weight: bold;">Elementary</h4>
                                    <div id="elementary" >

                                        <ul class="elemList list-group" >
                                            <li class="list-group-item" style="visibility: hidden">
                                                <h3 class="name"></h3>
                                            </li>
                                            <li class="list-group-item" style="visibility: hidden">
                                                <h3 class="name"></h3>
                                            </li>
                                            <li class="list-group-item" style="visibility: hidden">
                                                <h3 class="name"></h3>
                                            </li>
                                        </ul>

                                    </div>
                                    <h4 class="btn-lg panel-heading" style="font-size: 14px;font-weight: bold;" value="middle">Middle</h4>
                                    <div id="middle">
                                        <p>
                                        <ul class="middleList list-group">
                                            <li class="list-group-item" style="visibility: hidden">
                                                <h3 class="name"></h3>
                                            </li>
                                        </ul>
                                        </p>
                                    </div>
                                    <h4 class="btn-lg panel-heading" style="font-size: 14px;font-weight: bold;" value="high">High</h4>
                                    <div id="high">
                                        <p>
                                        <ul class="highList list-group">
                                            <li class="list-group-item" style="visibility: hidden">
                                                <h3 class="name"></h3>
                                            </li>
                                        </ul>
                                        </p>
                                    </div>
                                    <h4 class="btn-lg panel-heading alternative" style="font-size: 14px;font-weight: bold;" value="other">Alternative</h4>
                                    <div id="alternative">
                                        <p>
                                        <ul class="alternativeList list-group">
                                            <li class="list-group-item" style="visibility: hidden">
                                                <h3 class="name"></h3>
                                            </li>
                                        </ul>
                                        </p>
                                    </div>
                                    <h4 class="btn-lg panel-heading preschool" style="font-size: 14px;font-weight: bold;" value="other">Preschool</h4>
                                    <div id="preschool">
                                        <p>
                                        <ul class="preschoolList list-group">
                                            <li class="list-group-item" style="visibility: hidden">
                                                <h3 class="name"></h3>
                                            </li>
                                        </ul>
                                        </p>
                                    </div>
                                    <h4 class="btn-lg panel-heading special" style="font-size: 14px;font-weight: bold;" value="other">Special</h4>
                                    <div id="special">
                                        <p>
                                        <ul class="specialList list-group">
                                            <li class="list-group-item" style="visibility: hidden">
                                                <h3 class="name"></h3>
                                            </li>
                                        </ul>
                                        </p>
                                    </div>
<!--                                    <h4 class="btn-lg panel-heading other" style="font-size: 14px;font-weight: bold;" value="other">Other</h4>-->
<!--                                    <div id="other">-->
<!--                                        <p>-->
<!--                                        <ul class="otherList list-group">-->
<!--                                            <li class="list-group-item" style="visibility: hidden">-->
<!--                                                <h3 class="name"></h3>-->
<!--                                            </li>-->
<!--                                        </ul>-->
<!--                                        </p>-->
<!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="addschool.php" class="btn btn-primary btn-lg btn-block" role="button">Add School</a>
                        </div>
                        <!-- /.panel -->
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
