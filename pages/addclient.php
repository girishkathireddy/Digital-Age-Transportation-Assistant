<?php
  include("./includes/db_connection.php");
  include("./includes/functions.php"); 
?>
<?php 
$query_client  = "SELECT * FROM lpr_zones";
$result_client = mysqli_query($connection, $query_client);
$result_client3 = mysqli_query($connection, $query_client);

if(isset($_POST['addclient']))
{
    $client_name = $_POST['clientname'];
    $client_abr = $_POST['abbrevation'];
    $client_street = $_POST['street'];
    $client_address = $_POST['address'];
    $client_city = $_POST['city'];
    $client_state = $_POST['state'];
    $client_zip = $_POST['zipcode'];
    $client_country = $_POST['country'];
    $client_contact = $_POST['contact'];
    $client_zone = (int)$_POST['zone'];

insert_client($client_name, $client_abr, $client_street, $client_address, $client_city, $client_state, $client_zip, $client_country, $client_contact,$client_zone);
}
if(isset($_POST['updateclient']))
{

    $client_name = $_POST['clientname'];
    $client_abr = $_POST['abbrevation'];
    $client_street = $_POST['street'];
    $client_address = $_POST['address'];
    $client_city = $_POST['city'];
    $client_state = $_POST['state'];
    $client_zip = $_POST['zipcode'];
    $client_country = $_POST['country'];
    $client_contact = $_POST['contact'];
    $client_zone = (int)$_POST['zone'];

update_client($client_name, $client_abr, $client_street, $client_address, $client_city, $client_state, $client_zip, $client_country, $client_contact, $_GET['client_id'],$client_zone);
}
if(isset($_GET['client_id'])){
    $client = (int)$_GET['client_id'];
    $query_client2  = "SELECT * FROM lpr_client where client_id=$client";
    $result_client2 = mysqli_query($connection, $query_client2);
     while($subject_client2 = mysqli_fetch_assoc($result_client2)) {
        $zone_id = $subject_client2['zone_id'];
     }
}
?>
<?php
  include("./includes/htmlheader.php");
  include("./includes/nav.php"); 
?>
<?php  if(isset($_GET['client_id']))
{    //$client_id = $_GET['client_id'];
    //echo "clientid" . $client_id; 
    $result_client = select_client($_GET['client_id']);
    //var_dump($result_client);
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                    <form method="post" enctype="multipart/form-data" id="newuser" class="form-horizontal">
                        <h1 class="page-header">Update Client</h1>
                            <div class="form-group">
                                <label>Client Name</label>
                                <input class="form-control" placeholder="Client Name" name="clientname" value="<?php echo $result_client["client_name"]; ?>">
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Abbrevation</label>
                                <input class="form-control" placeholder="Client Abbrevation" name="abbrevation" value="<?php echo $result_client["client_abr"]; ?>">
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess">Address</label>
                                <input type="text" class="form-control"  placeholder="Enter Street" name="street" value="<?php echo $result_client["client_street"]; ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Addresss line 2" name="address" value="<?php echo $result_client["client_address"]; ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="City" name="city" value="<?php echo $result_client["client_city"]; ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="State / Province / Region" name="state" value="<?php echo $result_client["client_state"]; ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Postal / Zip Code" name="zipcode" value="<?php echo $result_client["client_zip"]; ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Country" name="country" value="<?php echo $result_client["client_country"]; ?>">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input class="form-control" placeholder="Contact Number" name="contact" value="<?php echo $result_client["client_contact"]; ?>">
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Zone-Type</label>
                                    <select class="form-control" id="stypeSelect" name="zone" required>
                                        <?php
                                                // 3. Use returned data (if any)
                                                while($subject_client = mysqli_fetch_assoc($result_client3)) {
                                                    // output data from each row
                                            ?>
                                                    <option value="<?php echo $subject_client["zone_id"]; ?>" <?php if ($zone_id == $subject_client["zone_id"]) { ?>selected="true" <?php }; ?> ><?php echo $subject_client["zone_loc"]; ?></option>
                                            <?php
                                                }
                                            ?>

                                    </select>
                                </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg" name="updateclient">Submit</button>
                            </div>
                        </form>
                    </div> <!-- /.col-lg-12 -->
                            <!-- /.panel -->


                    </div><!-- /.row -->
                   
                    
                </div><!-- /.container-fluid -->
        </div>
                 
        <!-- /#page-wrapper -->
<?php
}
else{
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                    <form method="post" enctype="multipart/form-data" id="newuser" class="form-horizontal">
                        <h1 class="page-header">Add Client</h1>
                            <div class="form-group">
                                <label>Client Name</label>
                                <input class="form-control" placeholder="Client Name" name="clientname">
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Abbrevation</label>
                                <input class="form-control" placeholder="Client Abbrevation" name="abbrevation">
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess">Address</label>
                                <input type="text" class="form-control"  placeholder="Enter Street" name="street">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Addresss line 2" name="address">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="City" name="city">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="State / Province / Region" name="state">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Postal / Zip Code" name="zipcode" >
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Country" name="country" value="United States">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input class="form-control" placeholder="Contact Number" name="contact">
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Zone-Type</label>
                                    <select class="form-control" id="stypeSelect" name="zone" required>
                                        <option value="">Select</option>
                                             <?php
                                                // 3. Use returned data (if any)
                                                while($subject_client = mysqli_fetch_assoc($result_client)) {
                                                    // output data from each row
                                            ?>
                                                    <option value="<?php echo $subject_client["zone_id"]; ?>"><?php echo $subject_client["zone_loc"]; ?></option>
                                            <?php
                                                }
                                            ?>

                                    </select>
                                </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg" name="addclient">Submit</button>
                            </div>
                        </form>
                    </div> <!-- /.col-lg-12 -->
                            <!-- /.panel -->


                    </div><!-- /.row -->
                   
                    
                </div><!-- /.container-fluid -->
        </div>
                 
        <!-- /#page-wrapper -->
<?php
}
?>
<?php
  require_once("./includes/footer.php");
?>

