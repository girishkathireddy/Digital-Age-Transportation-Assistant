<?php
  include("./includes/db_connection.php");
  include("./includes/functions.php"); 
?>
<?php
$o_id = (int)$_GET["oid"];
$query  = "SELECT * FROM `lpr_order` LEFT JOIN lpr_client ON lpr_order.client_id = lpr_client.client_id LEFT JOIN lpr_school ON lpr_order.school_id = lpr_school.school_id LEFT JOIN lpr_student ON lpr_order.o_id = lpr_student.o_id LEFT JOIN lpr_driver ON lpr_order.driver_id = lpr_driver.driver_id LEFT JOIN lpr_ridealong ON ra_id=lpr_ridealong.id LEFT JOIN (SELECT lpr_billing.o_id,count(lpr_billing.o_id) as zones ,SUM(lpr_billing.amount) as clientbill from lpr_billing GROUP by lpr_billing.o_id) AS bill ON lpr_order.o_id = bill.o_id WHERE lpr_order.o_id=$o_id GROUP by lpr_order.o_id ";
$result = mysqli_query($connection, $query);
// $result_client2 = mysqli_query($connection, $query_client);
// $result_client3 = mysqli_query($connection, $query_client);
//error_log("\nEditorder" . $query , 3, "C:/xampp/apache/logs/error.log");
confirm_query($result);
$subject = mysqli_fetch_assoc($result);

$query_client  = "SELECT * FROM lpr_client";
$result_client = mysqli_query($connection, $query_client);
$result_client2 = mysqli_query($connection, $query_client);
$result_client3 = mysqli_query($connection, $query_client);
confirm_query($result_client);

$query_student  = "SELECT * FROM lpr_student WHERE o_id=$o_id";
$result_student = mysqli_query($connection, $query_student);

$query_billing  = "SELECT * FROM lpr_billing WHERE o_id=$o_id";
$result_billing = mysqli_query($connection, $query_billing);

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
                        <h1 class="page-header">Edit Order</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Edit Order
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <form role="form-inline" id="updateorder">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                        <input class="form-control" name="o_id" type="hidden" value="<?php echo $o_id; ?>">
                                        <label>School System</label>
                                            <select class="form-control" id="ctypeSelect" name="ctypeSelect" required disabled>
                                            <!-- <option value="">Select</option> -->
                                             <?php
                                                // 3. Use returned data (if any)
                                                while($subject_client = mysqli_fetch_assoc($result_client)) {
                                                    // output data from each row
                                            ?>
                                                    <option data-zone_id="<?php echo $subject["zone_id"]; ?>" value="<?php echo $subject["client_id"]; ?>"><?php echo $subject["client_name"]; ?></option>
                                            <?php
                                                }
                                            ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                        <label>School-Type</label>
                                            <select class="form-control" id="stypeSelect" name="stypeSelect" required disabled>
                                                <option><?php echo $subject["school_type"]; ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                        <label>School-Name</label>
                                            <select class="form-control" id="sSelect" name="sSelect" required disabled>
                                                <option><?php echo $subject["school_name"]; ?></option>
                                            </select>
                                        </div>
                                        <div id="studentdetails">
                                        <?php
                                                // 3. Use returned data (if any)
                                                while($subject_student = mysqli_fetch_assoc($result_student)) {
                                                    // output data from each row
                                        ?>
                                        
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input class="form-control" name="s_fname" placeholder="First Name" value="<?php echo $subject_student["s_fname"]; ?>" required>
                                                <p class="help-block"></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input class="form-control" name="s_lname" placeholder="Last Name" value="<?php echo $subject_student["s_lname"]; ?>" required>
                                                <p class="help-block"></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Grade</label>
                                                <input class="form-control" name="s_grade" placeholder="Enter Grade" value="<?php echo $subject_student["s_grade"]; ?>">
                                            </div>
                                            <div class="form-group">
                                            <label>Gender</label>
                                                <select class="form-control" name="s_gender" required>
                                                    <option value="">Select</option>
                                                    <option value="Female" <?php echo($subject_student["s_gender"]=="Female"? 'selected' : ''); ?> >Female</option>
                                                    <option value="Male" <?php echo($subject_student["s_gender"]=="Male"? 'selected' : ''); ?> >Male</option>
                                                    <option value="Transgender" <?php echo($subject_student["s_gender"]=="Transgender"? 'selected' : ''); ?> >Transgender</option>
                                                    <option value="Other"<?php echo($subject_student["s_gender"]=="Other"? 'selected' : ''); ?> >Other</option>
                                                    <option value="Not Specified" <?php echo($subject_student["s_gender"]=="Not Specified"? 'selected' : ''); ?> >Not Specified</option>
                                                </select>
                                            </div>
                                        <?php
                                                }
                                            ?>
                                            <div class="form-group" >
                                            <label>Start Date</label>
                                            <div class="input-group">
                                              
                                              <input type="text" name="o_startdate" class="form-control" id="from" value="<?php echo $subject["o_startdate"]; ?>" disabled required><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                            </div>
                                            </div>
                                            <div class="form-group">
                                            <label>End Date</label>
                                            <div class="input-group">
                                            
                                              <input type="text" name="o_enddate" class="form-control" id="to" value="<?php echo $subject["o_enddate"]; ?>" required><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                            </div>
                                            </div>
                                        
                                        </div>
                                        <div class="form-group">
                                        <button type="button" id ="addstudent" class="btn btn-primary">Additional Student</button>
                                        </div>
										<div class="form-group">
                                            <label class="control-label" for="inputSuccess">Residing Address</label>
                                            <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Enter Street" name="street" value="<?php echo $subject["s_street"]; ?>" required>
                                          </div>
                                          <div class="form-group">
                                            <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Addresss line 2" name="address" value="<?php echo $subject["s_address"]; ?>">
                                          </div>
                                          <div class="form-group">
                                            <input type="text" class="form-control" id="exampleInputEmail3" placeholder="City" name="city" value="<?php echo $subject["s_city"]; ?>" required>
                                          </div>
                                          <div class="form-group">
                                            <input type="text" class="form-control" id="exampleInputEmail3" placeholder="State / Province / Region" name="state" value="<?php echo $subject["s_state"]; ?>" required>
                                          </div>
                                          <div class="form-group">
                                            <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Postal / Zip Code" name="zipcode" value="<?php echo $subject["s_zip"]; ?>">
                                          </div>
                                          <div class="form-group">
                                            <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Country" name="country" value="<?php echo $subject["s_country"]; ?>" required>
                                          </div>
                                        <div class="form-group">
                                        <label>Days Needed</label>
                                        <?php $days_needed = explode(",", $subject["o_days"]);
                                            $days =  array("Monday", "Tuesday", "Wednesday","Thursday","Friday");
                                            //$days_needed (array_diff($days,$days_needed));
                                            for ($i=0; $i < sizeof($days); $i++) { ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="dayscheckbox" value="<?php echo $days[$i]; ?>" <?php echo(in_array($days[$i],$days_needed)? 'checked' : ''); ?>><?php echo $days[$i]; ?>
                                                    </label>
                                                </div>


                                                 <?php } ?>
                                        </div>
                                        <h4>AM Trip</h4>
                                        <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="amcheck" value="am" checked>AM Trip
                                                </label>
                                        </div>
                                         <div name="amdiv">
                                            <div class="form-group">
                                                <label>Pickup Location</label>
                                                <input class="form-control" name="o_ampickloc" placeholder="Enter Location" value="<?php echo $subject["o_ampickloc"]; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Pickup Time</label>
                                               <!--  <div class="input-group clockpicker">
                                                    <input type="text" class="form-control clockformat" name="o_ampicktime" value="<?php echo $subject["o_ampicktime"]; ?>" required>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                                </div> -->
                                                <div class="bfh-timepicker" name="o_ampicktime" data-time="<?php echo substr($subject["o_ampicktime"], 0, 5); ?>" data-align="right">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Drop Location</label>
                                                <input class="form-control" id="amDropLoc" name="o_amdroploc" placeholder="Enter Location" value="<?php echo $subject["o_amdroploc"]; ?>"required>
                                            </div>
                                            <div class="form-group">
                                                <label>Drop Time</label>
                                                <!-- <div class="input-group clockpicker">
                                                    <input type="text" class="form-control clockformat" name="o_amdroptime" value="<?php echo $subject["o_amdroptime"]; ?>" required>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                                </div> -->
                                                <div class="bfh-timepicker" name="o_amdroptime" data-time="<?php echo substr($subject["o_amdroptime"], 0, 5); ?>" data-align="right">
                                                </div>
                                            </div>
                                        </div>
                                            <h4>PM Trip</h4>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="pmcheck" value="pm" checked>PM Trip
                                                </label>
                                            </div>
                                            <div name="pmdiv">
                                            <div class="form-group">
                                                <label>Pickup Location</label>
                                                <input class="form-control" id="pmDropLoc" name="o_pmpickloc" placeholder="Enter Location" value="<?php echo $subject["o_pmpickloc"]; ?>"required>
                                            </div>
                                            <div class="form-group">
                                                <label>Pickup Time</label>
                                                <!-- <div class="input-group clockpicker">
                                                    <input type="text" class="form-control clockformat" name="o_pmpicktime" value="<?php echo $subject["o_pmpicktime"]; ?>" required>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                                </div> -->
                                                <div class="bfh-timepicker" name="o_pmpicktime" data-time="<?php echo substr($subject["o_pmpicktime"], 0, 5); ?>" data-align="right">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Drop Location</label>
                                                <input class="form-control" placeholder="Enter Location" name="o_pmdroploc" value="<?php echo $subject["o_pmdroploc"]; ?>" required>
                                            </div>
                                        </div>
                                        
                                    
                                    </div>    
                                
                                
                                    <!-- /.col-lg-6 (nested) -->
                                    <div class="col-lg-6">
                                        
                                          <h4>Parent Details</h4>
                                          <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control" placeholder="First Name" name="pfname" value="<?php echo $subject["s_pfname"]; ?>"required>
                                            <p class="help-block"></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" placeholder="Last Name" name="plname" value="<?php echo $subject["s_plname"]; ?>" required>
                                            <p class="help-block"></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input class="form-control" placeholder="Enter Phone" name="phone" value="<?php echo $subject["s_phone"]; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Alternate Phone</label>
                                            <input class="form-control" placeholder="Enter Phone" name="altphone" value="<?php echo $subject["s_altphone"]; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Add-ons</label>

                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="o_fd" class="aocheckbox" value="femaledriver"<?php echo($subject["o_fd"]=="TRUE"? 'checked' : ''); ?>>Female Driver Only
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="o_ra" class="aocheckbox" value="ridealong" <?php echo($subject["o_ra"]=="TRUE"? 'checked' : ''); ?> >Ride Along
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="o_as" class="aocheckbox" value="addnlstop" <?php echo($subject["o_as"]=="TRUE"? 'checked' : ''); ?> >Additional Stop
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="o_cs" class="aocheckbox" value="carseat" <?php echo($subject["o_cs"]=="TRUE"? 'checked' : ''); ?> >Car Seat
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="o_bs" class="aocheckbox" value="boosterseat" <?php echo($subject["o_bs"]=="TRUE"? 'checked' : ''); ?> >Booster Seat
                                                </label>
                                            </div>
                                            <div name="ridealongdiv" <?php echo($subject["o_ra"]=="TRUE"? '' : 'class="hidebox"'); ?> >
                                            <div class="form-group">
                                                <label>Ride Along</label>
                                                <input class="form-control typeahead_ra" name="o_ridealong_text" value="<?php echo($subject["o_ra"]=="TRUE"? $subject["ra_fname"] .' '.$subject["ra_lname"] : ''); ?>">
                                                <input class="form-control" name="ra_id" type="hidden" value="<?php echo($subject["o_ra"]=="TRUE"? $subject["id"] : ''); ?>">
                                            </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleTextarea">Internal Comment</label>
                                                <textarea class="form-control input-sm" name="icomment" rows="3" value="<?php echo $subject["o_icomment"]; ?>"><?php echo $subject["o_icomment"]; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleTextarea">Driver Comment</label>
                                                <textarea class="form-control input-sm" name="dcomment" rows="3" value="<?php echo $subject["o_dcomment"]; ?>"><?php echo $subject["o_dcomment"]; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Requested By</label>
                                             <?php
                                                // 3. Use returned data (if any)
                                                while($subject_client = mysqli_fetch_assoc($result_client2)) {
                                                    // output data from each row
                                            ?>
                                                <div class="radio">
                                                    <label><input type="radio" name="optradio" data-zone_id="<?php echo $subject_client["zone_id"]; ?>"  value="<?php echo $subject_client["client_id"]; ?>" <?php echo($subject["o_reqby"]==$subject_client["client_id"]? 'checked' : ''); ?> ><?php echo $subject_client["client_name"]; ?></label>
                                                </div>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="splitbill">Billing
                                                </label>
                                            </div>
                                                <div class ="splitbill box">
                                                    <h5>Select the type of billing</h5>
                                                <div class="form-group">
                                                 <?php
                                                 $zone = ((int)$subject["zones"]>=2? 'outzone' : 'inzone');
                                                 //print_r($zone);
                                                // 3. Use returned data (if any)
                                                
                                                    // output data from each row
                                                ?>
                                                <div class="radio">
                                                <label>
                                                    <input type="radio" data-value="inzone" value="inzone" name="billingradio" <?php echo($zone=="inzone"? 'checked' : ''); ?>>In Zone</input>
                                                </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" data-value="outzone" value="outzone" name="billingradio" <?php echo($zone=="outzone"? 'checked' : ''); ?>>Out Zone</input>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="o_wc" class="wccheckbox" value="wheelchair" <?php echo($subject["o_as"]=="TRUE"? 'checked' : ''); ?> >Wheel Chair
                                                    </label>
                                                </div>
                                                </div>
                                                <div class="splitcheckbox box">
                                                <label>
                                                    Split Bills
                                                </label>
                                                <div class ="split">
                                                    <h5>Select the cities where the bills needs to be split</h5>
                                                <?php
                                                // 3. Use returned data (if any)
                                                    function array_push_assoc($array, $key, $value){
                                                        $array[$key] = $value;
                                                        return $array;
                                                    }
                                                    $outzone=array();
                                                    while($subject_billing = mysqli_fetch_assoc($result_billing)) {
                                                        //array_merge($outzone,($subject_billing["client_id"]=>$subject_billing["amount"]);
                                                            $outzone = array_push_assoc($outzone, $subject_billing["client_id"], $subject_billing["amount"]);
                                                    }
                                                    //var_dump($outzone);
                                                    while($subject_client = mysqli_fetch_assoc($result_client3)) {
                                                    // output data from each row
                                                ?>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="billsplit" value="<?php echo $subject_client["client_id"]; ?>" <?php echo(is_numeric (array_search($subject_client["client_id"],array_keys($outzone)))? 'checked' : ''); ?> ><?php echo $subject_client["client_name"]; ?>
                                                            <input type="text" name="billsplitvalue" value="<?php echo(is_numeric (array_search($subject_client["client_id"],array_keys($outzone)))? $outzone[$subject_client["client_id"]] : ''); ?>" <?php echo $subject_client["client_id"]; ?>" <?php echo(is_numeric (array_search($subject_client["client_id"],array_keys($outzone)))? '' : 'disabled'); ?>>
                                                            
                                                        </label>
                                                    </div>
                                                <?php
                                                    }
                                                ?>
                                                </div>
                                                </div>
                                                </div>
                                            
                                        </div>
                                        <div class="form-group">
                                            <label>Billable to the client</label>
                                            <div class="input-group">
                                            <input type="text" id="billtext" name="o_billable" class="form-control" value="<?php echo $subject["o_billable"]; ?>" required><span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                            </div>
                                            <p class="help-block"></p>
                                        </div>
                                        <div class="form-group">
                                            <a class="btn btn-primary" name="calculate_bill">Calculate</a>
                                        </div>
                                        <div class="form-group">
                                            <label>Driver</label>
                                            <input class="form-control typeahead" placeholder="" value="<?php echo($subject["driver_dname"]!=NULL? $subject["driver_dname"] : $subject["driver_fname"].' '.$subject["driver_lname"]); ?>">
                                            <input class="form-control" name="driver_id" type="hidden" placeholder="" value="<?php echo $subject["driver_id"]; ?>">
                                        </div>
                                        <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="add_driver" class="aocheckbox" value="ridealong" <?php echo($subject["pm_driver_id"]!=NULL? 'checked' : ''); ?> >Add PM Driver
                                                </label>
                                        </div>

                                        <?php
                                        $pm_driver_id = (int)$subject['pm_driver_id'];
                                        $adddriver_query = "SELECT * FROM lpr_driver WHERE driver_id=$pm_driver_id";
                                        $result_adddriver = mysqli_query($connection, $adddriver_query);
                                        $subject_addstudent = mysqli_fetch_assoc($result_adddriver);
                                        ?>

                                        <div class="form-group <?php echo($subject["pm_driver_id"]!=NULL? '' : 'hidebox'); ?>" name="add_driver_div" >
                                            <label>PM Driver</label>
                                            <input class="form-control pmtypeahead" value="<?php echo($subject_addstudent["driver_dname"]!=NULL? $subject_addstudent["driver_dname"] : $subject_addstudent["driver_fname"].' '.$subject_addstudent["driver_lname"]); ?>">
                                            <input class="form-control" name="pm_driver_id" type="hidden" value="<?php echo $subject_addstudent["driver_id"]; ?>">
                                        </div>

                                         <div class="form-group">
                                            <label>Payable to the driver</label>
                                            <div class="input-group">
                                            <input type="text" name="o_payable" class="form-control" value="<?php echo $subject["o_payable"]; ?>" required><span class="input-group-addon" ><i class="glyphicon glyphicon-usd"></i></span>

                                            </div>
                                            <p class="help-block"></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Tip</label>
                                            <div class="input-group">
                                            <input type="text" name="o_tip" class="form-control" value="<?php echo $subject["o_tip"]; ?>" required ><span class="input-group-addon" ><i class="glyphicon glyphicon-usd"></i></span>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg" form="updateorder" id="submitcreateorder">Submit</button>
                                        </div>
                                    
                                    </div>
                                    <!-- /.col-lg-6 (nested) -->
                                    
                                </form>
                            </div>
                            <!-- /.row (nested) -->
                            
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
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

   

<?php
  require_once("./includes/footer.php");
?>
