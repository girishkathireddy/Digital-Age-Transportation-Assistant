<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php
$school_id="";
$sc_name="";
$sc_ctype="";
$sc_abr="";
$sc_cnumber="";
$sc_cname="";
$sc_stype="";
$sc_steet ="";
$sc_city="";
$sc_state="";
$sc_zip="";
$sc_country="";
$sc_address="";

if(isset($_POST['submit'])) {
$sc_name=$_POST['sc_name'];
$sc_ctype=$_POST['sc_ctypeSelect'];
$sc_abr=$_POST['sc_abr'];
$sc_cnumber=$_POST['sc_cnumber'];
$sc_cname=$_POST['sc_cname'];
$sc_stype=$_POST['sc_sctypeSelect'];
$sc_steet =$_POST['sc_stret'];
$sc_city=$_POST['sc_city'];
$sc_state=$_POST['sc_state'];
$sc_zip=$_POST['sc_zip'];
$sc_country=$_POST['sc_country'];
$sc_address=$_POST['sc_address'];
inserNewSchool($sc_name,$sc_ctype,$sc_abr,$sc_cnumber,$sc_cname,$sc_stype,$sc_steet,$sc_city,$sc_state,$sc_zip,$sc_country,$sc_address);

}

if(isset($_POST['update'])) {
    $sc_id=$_POST['school_id'];
    $sc_name=$_POST['sc_name'];
    $sc_ctype=$_POST['sc_ctypeSelect'];
    $sc_abr=$_POST['sc_abr'];
    $sc_cnumber=$_POST['sc_cnumber'];
    $sc_cname=$_POST['sc_cname'];
    $sc_stype=$_POST['sc_sctypeSelect'];
    $sc_steet =$_POST['sc_stret'];
    $sc_city=$_POST['sc_city'];
    $sc_state=$_POST['sc_state'];
    $sc_zip=$_POST['sc_zip'];
    $sc_country=$_POST['sc_country'];
    $sc_address=$_POST['sc_address'];
    updateSchool($sc_id,$sc_name,$sc_ctype,$sc_abr,$sc_cnumber,$sc_cname,$sc_stype,$sc_steet,$sc_city,$sc_state,$sc_zip,$sc_country,$sc_address);

}


if(isset($_GET['schoolid'])) {
$schoolid=$_GET['schoolid'];
$sc_data=getSchoolData($schoolid);
    $sc_name=$sc_data['school_name'];
    $sc_ctype=$sc_data['client_id'];
    $sc_abr=$sc_data['school_abr'];
    $sc_cnumber=$sc_data['school_contact_no'];
    $sc_cname=$sc_data['school_contact_name'];
    $sc_stype=$sc_data['school_type'];
    $sc_steet =$sc_data['school_street'];
    $sc_city=$sc_data['school_city'];
    $sc_state=$sc_data['school_state'];
    $sc_zip=$sc_data['school_zip'];
    $sc_country=$sc_data['school_country'];
    $sc_address=$sc_data['school_address'];
}
$query_client  = "SELECT * FROM lpr_client";
$result_client = mysqli_query($connection, $query_client);
confirm_query($result_client);
?>
<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>




<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">School</h1>
                <form method="post" enctype="multipart/form-data" id="addnSchool" name="addnSchool" class="form-horizontal" >
                <div class="form-group">
                    <label>School Name</label>
                    <input class="form-control" placeholder="School Name" name="sc_name" required value="<?php echo $sc_name;?>">
                    <p class="help-block"></p>
                </div>
                    <div class="form-group">
                        <label>Client</label>
                        <select class="form-control" id="add_typeSelect" name="sc_ctypeSelect" required>
                            <option value="<?php echo $sc_ctype;?>" selected>Select</option>
                            <?php
                            // 3. Use returned data (if any)
                            while($subject_client = mysqli_fetch_assoc($result_client)) {
                                // output data from each row
                                ?>
                                <option data-zone_id="<?php echo $subject_client["zone_id"]; ?>" <?php if ($sc_ctype == $subject_client["client_id"]) { ?>selected="true" <?php }; ?> value="<?php echo $subject_client["client_id"]; ?>"><?php echo $subject_client["client_name"]; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">School Abr</label>
                        <input type="text" class="form-control" id="exampleInputEmail3" name="sc_abr" placeholder="Enter Abbrevation" required value="<?php echo $sc_abr;?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">School Contact Number</label>
                        <input type="text" class="form-control" id="exampleInputEmail3" name="sc_cnumber" placeholder="Enter  Contact Number" required value="<?php echo $sc_cnumber;?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">School Contact Name</label>
                        <input type="text" class="form-control" id="exampleInputEmail3" name="sc_cname"  placeholder="Enter  Contact Name" required value="<?php echo $sc_cname;?>">
                    </div>
                    <div class="form-group">
                        <label>School-Type</label>
                        <select class="form-control" id="stypeSelect" name="sc_sctypeSelect" required>
                            <option value="">Select</option>
                            <option <?php if ($sc_stype == 'elementary') { ?>selected="true" <?php }; ?> > elementary</option>
                            <option <?php if ($sc_stype == 'preschool') { ?>selected="true" <?php }; ?> >preschool</option>
                            <option <?php if ($sc_stype == 'middle') { ?>selected="true" <?php }; ?> >middle</option>
                            <option <?php if ($sc_stype =='high') { ?>selected="true" <?php }; ?> >high</option>
                            <option <?php if ($sc_stype == 'special') { ?>selected="true" <?php }; ?> >special</option>
                            <option <?php if ($sc_stype == 'alternative') { ?>selected="true" <?php }; ?> >alternative</option>
                            <option <?php if ($sc_stype == 'pilot') { ?>selected="true" <?php }; ?> >pilot</option>
                        </select>
                    </div>
                <div class="form-group">
                        <label class="control-label" for="inputSuccess">Address</label>
                        <input type="text" class="form-control" id="exampleInputEmail3"  name="sc_stret" placeholder="Enter Street" required value="<?php echo $sc_steet;?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="exampleInputEmail3" name="sc_address"  placeholder="Address"  value="<?php echo $sc_address;?>">
                    </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputEmail3" name="sc_city"  placeholder="City" required value="<?php echo $sc_city;?>">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputEmail3" name="sc_state" placeholder="State / Province / Region" required value="<?php echo $sc_state;?>">
                </div>
                <div class="form-group">

                    <input type="text" class="form-control" id="exampleInputEmail3" name="sc_zip" type="number" placeholder="Postal / Zip Code" value="<?php echo $sc_zip;?>">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputEmail3" name="sc_country" placeholder="Country" value="United States">
                    <input type="hidden" class="form-control" id="exampleInputEmail3" name="school_id"  value="<?php echo $schoolid;?>">
                </div>
   <?php if(isset($_GET['schoolid'])) {?>
                    <div class="form-group">
                        <button type="submit" name="update" class="btn btn-primary btn-lg" >Update</button>
                    </div>
    <?php } else {?>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg" >Submit</button>
                    </div>
   <?php }?>
                </form>
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

</div>
<!-- /#wrapper -->





<?php
require_once("./includes/footer.php");
?>
