<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php
if(isset($_POST['addevent']))
{
    $title = $_POST['title'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
    $client_id = $_POST['ctypeSelect'];
print_r("expression");
insert_event($title, $startdate, $enddate, $client_id);
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
$query_client  = "SELECT * FROM lpr_client";
$result_client = mysqli_query($connection, $query_client);
$result_client2 = mysqli_query($connection, $query_client);
confirm_query($result_client);
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
                        <h1 class="page-header">Add Event</h1>
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Event Name</label>
                                <input class="form-control" placeholder="Event Name" name="title">
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Client</label>
                                    <select class="form-control" id="ctypeSelect" name="ctypeSelect" required>
                                    <?php
                                    $client_ids = array();
                                        while($subject_client = mysqli_fetch_assoc($result_client2)) {
                                             array_push($client_ids, $subject_client["client_id"]);
                                        }
                                        // var_dump(trim(implode(', ', $client_ids)));
                                        ?> 
                                        <option value="<?php echo trim(implode(', ', $client_ids)); ?>">All Clients</option>
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
                            <div class="form-group" >
                                <label>Start Date</label>
                                <div class="input-group">
                                  
                                  <input type="text" name="startdate" class="form-control" id="from" required><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <div class="input-group">
                                
                                  <input type="text" name="enddate" class="form-control" id="to"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                            </div>

                    </div>
                            <!-- /.panel -->


                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg" name="addevent" role="button">Submit</button>
                    </div>
                    </form>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->

<?php
require_once("./includes/footer.php");
?>
