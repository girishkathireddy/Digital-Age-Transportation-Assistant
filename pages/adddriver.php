<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php
$update_driver=false;
$first_name="";
$middle_name ="";
$last_name = "";
$display_name ="";
$contact_number ="" ;
$e_contact_number ="";
$e_contact_name ="";
$e_contact_reltion ="";
$dssn = "";
$dl_number ="";
$dl_state ="";
$commision ="";
$street ="";
$address ="";
$city ="";
$state ="";
$zip_code ="";
$country = "";
$dl_hiredate="";
$dl_termdate="";
$dl_carnumber="";
$dl_comments="";
if(isset($_POST['adddriver']))
{
    $first_name = $_POST['firstname'];
    $middle_name = $_POST['middlename'];
    //  echo "<script type='text/javascript'>alert('$middle_name');</script>";
    $last_name = $_POST['lastname'];
    $display_name = $_POST['displayname'];
    $contact_number = $_POST['contactnumber'];
    $e_contact_number = $_POST['econtactnumber'];
    $e_contact_name = $_POST['econtactname'];
    $e_contact_reltion = $_POST['econtactrelation'];
    $dssn = $_POST['dssn'];
    $dl_number = $_POST['dlnumber'];
    $dl_state = $_POST['dlstate'];
    $commision = $_POST['commision'];
    $street = $_POST['street'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip'];
    $country = $_POST['country'];
    $dl_hiredate=$_POST['hiredate'];
    $dl_termdate=$_POST['termdate'];
    $dl_carnumber=$_POST['carnumber'];
    $dl_comments=$_POST['comments'];

    insert_driver($first_name,$middle_name,$last_name,$street,$address,$city,$zip_code,$contact_number,$dssn,$dl_number,$state,$e_contact_number,$commision,$display_name,$dl_hiredate,$dl_termdate,$dl_carnumber,$dl_comments,$e_contact_name,$e_contact_reltion,$dl_state,$country);
}

if(isset($_GET['driver_id'])) {
    $update_driver=true;
    $result_client = select_driver($_GET['driver_id']);
    $first_name=$result_client['driver_fname'];
    $middle_name =$result_client['driver_mname'];
    $last_name =$result_client['driver_lname'];
    $display_name =$result_client['driver_dname'];
    $contact_number =$result_client['driver_contact_no'];
    $e_contact_number =$result_client['driver_emg_contact'];
    $e_contact_name =$result_client['driver_emg_cname'];
    $e_contact_reltion =$result_client['driver_emgcontact_relationship'];
    $dssn = $result_client['driver_ssn'];
    $dl_number =$result_client['driver_dl_no'];
    $dl_state =$result_client['driver_dl_state'];
    $commision =$result_client['driver_commision'];
    $street =$result_client['driver_street'];
    $address =$result_client['driver_address'];
    $city =$result_client['driver_city'];
    $state =$result_client['driver_state'];
    $zip_code =$result_client['driver_zip'];
    $country = $result_client['driver_country'];
    $dl_hiredate= $result_client['driver_hiredate'];
    $dl_termdate= $result_client['driver_termdate'];
    $dl_carnumber= $result_client['driver_carnumber'];
    $dl_comments= $result_client['comments'];

}
if(isset($_POST['updatedriver'])) {

    $first_name = $_POST['firstname'];
    $middle_name = $_POST['middlename'];
    //  echo "<script type='text/javascript'>alert('$middle_name');</script>";
    $last_name = $_POST['lastname'];
    $display_name = $_POST['displayname'];
    $contact_number = $_POST['contactnumber'];
    $e_contact_number = $_POST['econtactnumber'];
    $e_contact_name = $_POST['econtactname'];
    $e_contact_reltion = $_POST['econtactrelation'];
    $dssn = $_POST['dssn'];
    $dl_number = $_POST['dlnumber'];
    $dl_state = $_POST['dlstate'];
    $commision = $_POST['commision'];
    $street = $_POST['street'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip'];
    $country = $_POST['country'];
    $dl_hiredate=$_POST['hiredate'];
    $dl_termdate=$_POST['termdate'];
    $dl_carnumber=$_POST['carnumber'];
    $dl_comments=$_POST['comments'];
    update_driver($first_name,$middle_name,$last_name,$street,$address,$city,$zip_code,$contact_number,$dssn,$dl_number,$state,$e_contact_number,$commision,$display_name,$dl_hiredate,$dl_termdate,$dl_carnumber,$dl_comments,$e_contact_name,$e_contact_reltion,$dl_state,$country,$_GET['driver_id']);

}
?>

<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>
<div id="page-wrapper">
    <div class="container-fluid">
        <form method="post" enctype="multipart/form-data" id="newDriver" name="newDriver" class="form-horizontal" onsubmit="return validateForm()">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">Add Driver</h1>
                    <div class="form-group row" >
                       <div class="col-md-6">
                          <label>First Name</label>
                          <input class="form-control" placeholder="First Name" name="firstname" value="<?php echo $first_name; ?>" required >
                          <p class="help-block"></p>
                       </div>
                        <div class="col-md-6">
                          <label>Middle Name</label>
                          <input class="form-control" placeholder="Middle Name" name="middlename" value="<?php echo $middle_name; ?>">
                          <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                          <label>Last Name</label>
                          <input class="form-control" placeholder="Last Name" name="lastname" required value="<?php echo $last_name; ?>">
                          <p class="help-block"></p>
                        </div>
                        <div class="col-md-6">
                          <label>Display Name</label>
                          <input class="form-control" placeholder="Enter Display Name" name="displayname"  value="<?php echo $display_name; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                         <label>Phone</label>
                         <input class="form-control" placeholder="Enter Phone" name="contactnumber" required value="<?php echo $contact_number; ?>">
                       </div>
                        <div class="col-md-6">
                         <label>Emergency Contact Number</label>
                         <input class="form-control" placeholder="Enter Emergency Contact Number" name="econtactnumber" value="<?php echo $e_contact_number; ?>">
                       </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Emergency Contact Name</label>
                            <input class="form-control" placeholder="Enter Emergency Contact Name" name="econtactname" required value="<?php echo $e_contact_name; ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Emergency Contact Relationship</label>
                            <input class="form-control" placeholder="Enter Emergency Contact Number" name="econtactrelation" value="<?php echo $e_contact_reltion; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                         <label>SSN</label>
                         <input class="form-control" placeholder="Enter SSN" name="dssn" required value="<?php echo $dssn; ?>">
                        </div>
                        <div class="col-md-6">
                         <label>Driving License Number</label>
                         <input class="form-control" placeholder="Enter DL Number" name="dlnumber" value="<?php echo $dl_number; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                         <label>Driving License State</label>
                         <input class="form-control" placeholder="Enter DL State" name="dlstate" value="<?php echo $dl_state; ?>">
                       </div>
                        <div class="col-md-6">
                         <label>Commission</label>
                         <input class="form-control" placeholder="Enter commision" name="commision" type="number" step="any" required value="<?php echo $commision; ?>">
                       </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Hire Date</label>
                            <div class="input-group">
                                <input type="text" name="hiredate" class="form-control" id="from" value="<?php echo $dl_hiredate; ?>"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Term Date</label>
                            <div class="input-group">
                                <input type="text" name="termdate" class="form-control" id="to" value="<?php echo $dl_termdate; ?>" ><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Car Number</label>
                            <input class="form-control" placeholder="Enter Car Number" name="carnumber" value="<?php echo $dl_carnumber; ?>">
                        </div>
                    </div>
                    <label class="control-label" for="inputSuccess">Residing Address</label>
                    <div class="form-group row">
                        <div class="col-md-6">
                         <input type="text" class="form-control" id="exampleInputEmail" placeholder="Enter Street" name="street" required value="<?php echo $street; ?>">
                        </div>
                       <div class="col-md-6">
                         <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Addresss line 2" name="address" value="<?php echo $address; ?>">
                       </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                         <input type="text" class="form-control" id="exampleInputEmail3" placeholder="City" name="city" required value="<?php echo $city; ?>">
                       </div>
                        <div class="col-md-6">
                         <input type="text" class="form-control" id="exampleInputEmail3" placeholder="State / Province / Region" name="state" value="<?php echo $state; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                         <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Postal / Zip Code" name="zip" required value="<?php echo $zip_code; ?>">
                       </div>
                        <div class="col-md-6">
                          <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Country" name="country" value="<?php echo $country; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label>Comments</label>
                            <textarea class="form-control"  placeholder="Enter Comments" rows="5" name="comments"><?php echo $dl_comments; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- /.panel -->


            </div>
            <?php
            if(!$update_driver) {
                ?>
                <!-- /.col-lg-12 -->
                <div class="form-group">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-lg" name="adddriver">Submit</button>
                    </div>
                </div>
                <?php
            }else{
             ?>
                <div class="form-group">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-lg" name="updatedriver">Submit</button>
                    </div>
                </div>
         <?php
            }
            ?>



        </form>

    </div>
    <!-- /Container Fluid -->
</div>
<!-- /Page Wrapper -->
<?php
require_once("./includes/footer.php");
?>

