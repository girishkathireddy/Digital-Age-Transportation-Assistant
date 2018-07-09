<?php

	function confirm_query($result_set) {
		if (!$result_set) {
			die("Database query failed.");
		}
	}

	function redirect_to($new_location) {
	  	header("Location: " . $new_location);
	  	exit;
	}

	function verify_input($data) {
		global $connection;
  		$data = htmlspecialchars($data);
  		$data = mysqli_real_escape_string($connection,$data);
  		return $data;
    }
    function verify_in($data) {
		global $connection;
  		$data = mysqli_real_escape_string($connection,$data);
  		return $data;
    }
	
	function verify_output($data) {

  		$data = stripcslashes($data);
  		$data = htmlspecialchars_decode($data);
  		return $data;
    }
    function verify_out($data) {

  		$data = stripcslashes($data);
  		return $data;
    }

	
	function insert_client($client_name,  $client_abr, $client_street, $client_address, $client_city, $client_state, $client_zip, $client_country, $client_contact,$client_zone) {
		global $connection;
		
		$query  = "INSERT INTO lpr_client ";
		$query .= "(client_name, client_abr, client_street, client_address, client_city, client_state, client_zip, client_country, client_contact, zone_id) ";
		$query .= "VALUES ('$client_name',  '$client_abr', '$client_street', '$client_address', '$client_city', '$client_state', '$client_zip', '$client_country', '$client_contact', $client_zone) ";
		//echo $query;
		$result_id = mysqli_query($connection, $query);
		error_log("\ninsert_client" . $query , 3, "C:/xampp/apache/logs/error.log");
		confirm_query($result_id);
		redirect_to("schooldata.php");
		// if($result_id) {
		// 	$_SESSION["message"] = "User Created";
		// 	return true;

		// } else {
		// 	$_SESSION["message"] = "Database Error";
		// 	return false;
		// }
		
	}
	function select_client($client_id){
		global $connection;

		$query_client  = "SELECT * FROM lpr_client WHERE client_id = $client_id" ;
		$result_client = mysqli_query($connection, $query_client);
		confirm_query($result_client);
		if($result = mysqli_fetch_assoc($result_client)) {
			return $result;
		} else {
			return null;
		}
	}
	function update_client($client_name,  $client_abr, $client_street, $client_address, $client_city, $client_state, $client_zip, $client_country, $client_contact, $client_id,$client_zone) {
		global $connection;
		
		$query  = "UPDATE lpr_client SET ";
		$query .= "client_name = '$client_name', client_abr = '$client_abr', client_street = '$client_street', client_address = '$client_address' , client_city = '$client_city', client_state = '$client_state', client_zip = '$client_zip', client_country ='$client_country', client_contact ='$client_contact', zone_id= $client_zone ";
		$query .= "WHERE client_id = $client_id ";
		//echo $query;
		$result_id = mysqli_query($connection, $query);
		
		confirm_query($result_id);
		redirect_to("schooldata.php");

		
	}

	function select_schools($client_id,$school_type){
		global $connection;

		$query_client  = "SELECT * FROM lpr_client JOIN lpr_school ON lpr_client.client_id=lpr_school.client_id WHERE lpr_school.client_id=$client_id AND school_type='$school_type'" ;
		$result_client = mysqli_query($connection, $query_client);
		error_log("Inside query\n" . $query_client , 3, "C:/xampp/apache/logs/error.log");
		confirm_query($result_client);
		return $result_client;
	}

	function select_school($school_id){
		global $connection;

		$query_client  = "SELECT * FROM lpr_school WHERE school_id = $school_id" ;
		$result_client = mysqli_query($connection, $query_client);
		confirm_query($result_client);
		if($result = mysqli_fetch_assoc($result_client)) {
			return $result;
		} else {
			return null;
		}
	}

	function tripcost($zone_id,$item,$additems){
		global $connection;

		$query = "SELECT SUM(amount) FROM lpr_rates WHERE (item  ='$item' AND zone_id=$zone_id) OR item IN ('$additems') ";
		error_log("Trip cost\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result_rate = mysqli_query($connection, $query);
		confirm_query($result_rate);
		if($result = mysqli_fetch_assoc($result_rate)) {
			return $result;
		} else {
			return null;
		}
	}

	function tripcost_outzone($zone_id,$item,$additems){
		global $connection;

		$query = "SELECT SUM(amount) FROM lpr_rates WHERE (item IN ('$item') AND zone_id IN (select zone_id from lpr_client where client_id in ($zone_id))) OR item IN ('$additems')" ;
		error_log("Trip ot cost\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result_rate = mysqli_query($connection, $query);
		confirm_query($result_rate);
		if($result = mysqli_fetch_assoc($result_rate)) {
			return $result;
		} else {
			return null;
		}
	}

	function createorder($client_id,$school_id,$o_startdate,$o_enddate,$o_status,$o_ampickloc,$o_ampicktime,$o_amdroploc,$o_amdroptime,$o_pmpickloc,$o_pmdroploc,$o_pmpicktime,$o_days,$o_fd,$o_ra,$o_wc,$o_as,$o_cs,$o_bs,$driver_id,$pm_driver_id,$o_icomment,$o_dcomment,$o_billable,$o_reqby,$o_payable,$o_tip,$ra_id){

		global $connection;
		$query = "INSERT INTO lpr_order(client_id, school_id, o_startdate, o_enddate, o_status, o_ampickloc, o_ampicktime, o_amdroploc, o_amdroptime, o_pmpickloc, o_pmdroploc, o_pmpicktime, o_days, o_fd, o_ra, o_wc, o_as, o_cs, o_bs, driver_id, pm_driver_id, o_icomment, o_dcomment, o_billable, o_reqby, o_payable, o_tip, ra_id) ";
		$query .= "VALUES ($client_id,  $school_id, '$o_startdate', '$o_enddate', '$o_status', '$o_ampickloc', '$o_ampicktime', '$o_amdroploc', '$o_amdroptime', '$o_pmpickloc', '$o_pmdroploc', '$o_pmpicktime', '$o_days', '$o_fd',  '$o_ra', '$o_wc', '$o_as', '$o_cs','$o_bs', $driver_id, $pm_driver_id, '$o_icomment', '$o_dcomment', $o_billable, $o_reqby, $o_payable, $o_tip, $ra_id) ";
		error_log("Insert order\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result = mysqli_query($connection, $query);
		
		confirm_query($result);
		$query_id = "SELECT LAST_INSERT_ID() AS o_id ";
		$result_id = mysqli_query($connection, $query_id);
		confirm_query($result_id);
		if($result_oid = mysqli_fetch_assoc($result_id)) {
			return $result_oid;
		} else {
			return null;
		}
		//redirect_to("schooldata.php");
	}
	function createstudnet($o_id, $s_fname, $s_lname, $s_grade, $s_gender, $s_pfname, $s_plname, $s_phone, $s_altphone, $s_street, $s_address, $s_city, $s_state, $s_zip, $s_country)
	{
		global $connection;

		if (is_array($s_fname)){
			for ($i=0; $i < sizeof($s_fname); $i++) {

			$fname = $s_fname[$i];
			$lname = $s_lname[$i];
			$grade = $s_grade[$i];
			$gender = $s_gender[$i];

			$query = "INSERT INTO lpr_student(o_id, s_fname, s_lname, s_grade, s_gender, s_pfname, s_plname, s_phone, s_altphone, s_street, s_address, s_city, s_state, s_zip, s_country) ";
			$query .= "VALUES ($o_id, '$fname', '$lname', '$grade', '$gender', '$s_pfname', '$s_plname', '$s_phone',  '$s_altphone', '$s_street', '$s_address', '$s_city',  '$s_state', '$s_zip', '$s_country') ";
			error_log("Insert student\n" . $query.sizeof($s_fname) , 3, "C:/xampp/apache/logs/error.log");
			$result = mysqli_query($connection, $query);
			
			confirm_query($result);
			}
		}
		else{

			$query = "INSERT INTO lpr_student(o_id, s_fname, s_lname, s_grade, s_gender, s_pfname, s_plname, s_phone, s_altphone, s_street, s_address, s_city, s_state, s_zip, s_country) ";
			$query .= "VALUES ($o_id, '$s_fname', '$s_lname', '$s_grade', '$s_gender', '$s_pfname', '$s_plname', '$s_phone',  '$s_altphone', '$s_street', '$s_address', '$s_city',  '$s_state', '$s_zip', '$s_country') ";
			error_log("Insert student\n" . $query.sizeof($s_fname) , 3, "C:/xampp/apache/logs/error.log");
			$result = mysqli_query($connection, $query);
			
			confirm_query($result);
		}
		
	}


	function insertbill_outzone($o_id,$billsplit,$billsplitvalue)
	{
		global $connection;
		$j = 0;
		for ($i=0; $i < sizeof($billsplitvalue); $i++) {
			if ((float)$billsplitvalue[$i] >= 0) {
			 
			$client_id = (int)$billsplit[$j];
			$amount = (float)$billsplitvalue[$i];
			$query = "INSERT INTO lpr_billing(o_id, client_id, amount) VALUES ($o_id,$client_id,$amount) ";
			error_log("Insert bill\n" . $query , 3, "C:/xampp/apache/logs/error.log");
			$result = mysqli_query($connection, $query);
			
			confirm_query($result);
			$j = $j + 1;	
			}
		} 
	}

	function insertbill_inzone($o_id,$o_billable,$o_reqby)
	{
		global $connection;
		$query = "INSERT INTO lpr_billing(o_id, client_id, amount) VALUES ($o_id,$o_reqby,$o_billable) ";
		error_log("Insert bill\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result = mysqli_query($connection, $query);
		
		confirm_query($result);


	}


	function driverdetails()
	{
		global $connection;
		$query = "SELECT driver_id AS value,CASE WHEN driver_dname = ' ' THEN CONCAT(driver_fname,' ', driver_lname) ELSE CONCAT(driver_fname,' ', driver_lname,'(',driver_dname,')') END AS label FROM `lpr_driver` WHERE driver_status = 'active'";
		//error_log("Insert bill\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result_client = mysqli_query($connection, $query);
		
		confirm_query($result_client);
		return $result_client;
	}
	function ridealongdetails()
	{
		global $connection;
		$query = "SELECT id AS value, CONCAT(`ra_fname`,' ',`ra_lname`) as label FROM `lpr_ridealong`";
		//error_log("Insert bill\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result_client = mysqli_query($connection, $query);
		
		confirm_query($result_client);
		return $result_client;
	}
	
	function studentdetails()
	{
		global $connection;
		$query = "SELECT `s_id` AS value, CONCAT(`s_fname`,' ',`s_lname`) as label FROM `lpr_student` LEFT JOIN lpr_order on lpr_student.o_id=lpr_order.o_id WHERE lpr_order.o_status = 'active'";
		//error_log("Insert bill\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result_client = mysqli_query($connection, $query);
		
		confirm_query($result_client);
		return $result_client;
	}
	function update_orderdriver($orderid,$driverid,$period,$trip_date)
	{
		global $connection;

			$query  = "UPDATE lpr_driver_contract SET ";

			$query .= "end_date= '$trip_date' - interval 1 day,status='close'";
			$query .= "WHERE o_id = $orderid AND period='$period' AND status='open' LIMIT 1";
			error_log("Insert contract\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result = mysqli_query($connection, $query);
		
		confirm_query($result);

		// elseif ($period == 'PM') {
		// 	$query  = "UPDATE lpr_order SET ";

		// 	$query .= "pm_driver_id= $driverid ";
		// 	$query .= "WHERE o_id = $orderid ";
		// 	//echo $query;
		// $result = mysqli_query($connection, $query);
		
		// confirm_query($result);
		// }

		$insert_query = "INSERT INTO `lpr_driver_contract`(`o_id`, `driver_id`, `start_date`, `period`,status) VALUES ($orderid,$driverid,'$trip_date','$period','open')";
		$result_insert = mysqli_query($connection, $insert_query);
		
		
		
	}


	/**  Functions by Girish For Manifest */
	function insert_driver($driver_fname,$driver_mname,$driver_lname,$driver_street,$driver_address,$driver_city,$driver_zip,$driver_contact_no,$driver_ssn,$driver_dl_no,$driver_state,$driver_emg_contact,	$driver_commision,$driver_dname,$dl_hiredate,$dl_termdate,$dl_carnumber,$dl_comments,$e_contact_name,$e_contact_reltion,$dl_state,$country){
        global $connection;
        $query  = "INSERT INTO lpr_driver ";
        $query .="(driver_fname,driver_mname,driver_lname,driver_street,driver_address,driver_city,driver_zip,driver_contact_no,driver_ssn,driver_dl_no,driver_state,driver_emg_contact,driver_commision,driver_dname,driver_hiredate,driver_termdate,driver_carnumber,comments,driver_emg_cname,driver_emgcontact_relationship,driver_dl_state,driver_country) ";
        $query.="VALUES ('$driver_fname','$driver_mname','$driver_lname','$driver_street','$driver_address','$driver_city','$driver_zip','$driver_contact_no',$driver_ssn,'$driver_dl_no','$driver_state','$driver_emg_contact',$driver_commision,'$driver_dname','$dl_hiredate','$dl_termdate','$dl_carnumber','$dl_comments','$e_contact_name','$e_contact_reltion','$dl_state','$country')";
        $result_id = mysqli_query($connection, $query);
//        //echo $query;
//        //error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
        confirm_query($result_id);
        redirect_to("drivers.php");

    }


function select_driver($driver_id){
    global $connection;

    $query_client  = "SELECT * FROM lpr_driver WHERE driver_id = $driver_id" ;
    $result_client = mysqli_query($connection, $query_client);
    confirm_query($result_client);
    if($result = mysqli_fetch_assoc($result_client)) {
        return $result;
    } else {
        return null;
    }
}

function update_driver($driver_fname,$driver_mname,$driver_lname,$driver_street,$driver_address,$driver_city,$driver_zip,$driver_contact_no,$driver_ssn,$driver_dl_no,$driver_state,$driver_emg_contact,$driver_commision,$driver_dname,$dl_hiredate,$dl_termdate,$dl_carnumber,$dl_comments,$e_contact_name,$e_contact_reltion,$dl_state,$country,$driver_id){
    global $connection;
    $query  = "UPDATE lpr_driver SET ";
    $query.="driver_fname='$driver_fname',driver_mname='$driver_mname',driver_lname='$driver_lname',driver_street='$driver_street',driver_address='$driver_address',driver_city='$driver_city',driver_zip='$driver_zip',driver_contact_no='$driver_contact_no',driver_ssn=$driver_ssn,driver_dl_no='$driver_dl_no',driver_state='$driver_state',driver_emg_contact='$driver_emg_contact',driver_commision=$driver_commision,driver_dname='$driver_dname',";
    $query.="driver_hiredate='$dl_hiredate',driver_termdate='$dl_termdate',driver_carnumber='$dl_carnumber',comments='$dl_comments',driver_emg_cname='$e_contact_name',driver_emgcontact_relationship='$e_contact_reltion',driver_dl_state='$dl_state',driver_country='$country'";
    $query .= "WHERE driver_id = $driver_id ";
    $result_id = mysqli_query($connection, $query);
    //error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result_id);
    redirect_to("drivers.php");
}

function  insert_trip($orderid,$clientid,$schoolid,$driverid,$s_id,$city,$time,$pickloc,$picktime,$droptime,$pax,$status,$trip_date,$clockperiod,$current_date,$driver_payable,$client_payable){
    global $connection;
    $trip_city=trim($city);
    $checkQuery="SELECT * FROM `lpr_triplog` WHERE triplog_o_id='$orderid' and triplog_date='$trip_date' and triplog_clock='$clockperiod'";
    $result_check = mysqli_query($connection, $checkQuery);
    confirm_query($result_check);
    if($result_oid = mysqli_fetch_assoc($result_check)) {
        return null;
    } else {
        $query  = "INSERT INTO lpr_triplog ";
        $query.="(`triplog_o_id`, `triplog_client_id`, `triplog_school_id`, `triplog_driver_id`, `triplog_studentid`,`triplog_city`, `triplog_time`, `triplog_pickloc`, `triplog_picktime`, `triplog_droptime`, `triplog_pax`, `triplog_status`, `triplog_date`, triplog_clock, triplog_date_updated, triplog_driver_payable, triplog_client_payable ) ";
        $query.="VALUES ('$orderid', '$clientid', '$schoolid', '$driverid', '$s_id', '$trip_city', '$time', '$pickloc', '$picktime', '$droptime', '$pax', '$status', '$trip_date', '$clockperiod', '$current_date', '$driver_payable', '$client_payable')";
        error_log("\nInside insert_trip" . $query , 3, "C:/xampp/apache/logs/error.log");
        $result_id = mysqli_query($connection, $query);
        confirm_query($result_id);

        //redirect_to("manifest.php");
        $query_id = "SELECT LAST_INSERT_ID() AS id ";
        $result_id = mysqli_query($connection, $query_id);
        confirm_query($result_id);
        if($result_oid = mysqli_fetch_assoc($result_id)) {
            return $result_oid;
        } else {
            return null;
        }
    }
}

function update_trip($orderid, $clientid, $schoolid, $driverid, $s_id, $city, $time, $pickloc, $picktime, $droptime, $pax, $status, $trip_date,$trip_id,$driver_payable,$client_payable){
    global $connection;
    $city=trim($city);

    $query  = "UPDATE lpr_triplog SET ";
    $query.="triplog_client_id=$clientid,triplog_school_id=$schoolid,triplog_driver_id=$driverid,triplog_studentid=$s_id,triplog_city='$city',triplog_time='$time',triplog_pickloc='$pickloc',triplog_picktime='$picktime',triplog_droptime='$droptime',triplog_pax='$pax',triplog_status='$status',triplog_o_id=$orderid , triplog_driver_payable='$driver_payable' , triplog_client_payable = '$client_payable'";
    $query .= "WHERE triplog_o_id = $orderid and triplog_date='$trip_date' and triplog_tripid=$trip_id";
    $result_id = mysqli_query($connection, $query);
    //error_log("\nInside query" . $query , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result_id);
    //redirect_to("manifest.php");

}

function delete_trip($trip_id){
	global $connection;
	$checkQuery1="DELETE FROM `lpr_triplog` WHERE triplog_tripid=$trip_id";
    $result_check1 = mysqli_query($connection, $checkQuery1);
    error_log("\nInside query" . $checkQuery1 , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result_trip);
}

function getAllTripData(){
    global $connection;
    $query_trip = "SELECT * FROM lpr_triplog";
    $result_trip = mysqli_query($connection, $query_trip);
    confirm_query($result_trip);
    return $result_trip;

}

function get_studentname($s_id){
    global $connection;
    $query = "SELECT s_fname FROM lpr_student where s_id=$s_id";
    $result_studentname = mysqli_query($connection, $query);
    confirm_query($result_studentname);
    if($result = mysqli_fetch_assoc($result_studentname)) {
        return $result;
    } else {
        return null;
    }
}

function get_drivername($d_id){
    global $connection;
    $query = "SELECT CONCAT(driver_fname,' ',driver_lname) as driver_name FROM lpr_driver where driver_id=$d_id";
    //error_log("\nInside driver name query " . $query , 3, "C:/xampp/apache/logs/error.log");
    $result_drivername = mysqli_query($connection, $query);
    confirm_query($result_drivername);
    if($result = mysqli_fetch_assoc($result_drivername)) {
        return $result;
    } else {
        return null;
    }

}

//Changeorder

function changeorderstatus($o_id,$status){
	global $connection;
    $query = "UPDATE lpr_order SET o_status = '$status' WHERE o_id = $o_id";
    $result = mysqli_query($connection, $query);
    error_log("\nInside changeorderstatus" . $query , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result);
    return $result;
}

	function updateorder($o_enddate,$o_status,$o_ampickloc,$o_ampicktime,$o_amdroploc,$o_amdroptime,$o_pmpickloc,$o_pmdroploc,$o_pmpicktime,$o_days,$o_fd,$o_ra,$o_wc,$o_as,$o_cs,$o_bs,$driver_id,$pm_driver_id,$o_icomment,$o_dcomment,$o_billable,$o_reqby,$o_payable,$o_tip,$o_id,$ra_id){

		global $connection;
		$query = "UPDATE lpr_order SET o_enddate='$o_enddate', o_status='$o_status', o_ampickloc='$o_ampickloc', o_ampicktime='$o_ampicktime', o_amdroploc='$o_amdroploc', o_amdroptime='$o_amdroptime',o_pmpickloc='$o_pmpickloc', o_pmdroploc='$o_pmdroploc', o_pmpicktime='$o_pmpicktime', o_days='$o_days', o_fd='$o_fd', o_ra='$o_ra', o_wc='$o_wc', o_as='$o_as',o_cs='$o_cs',o_bs='$o_bs', driver_id=$driver_id,pm_driver_id=$pm_driver_id, o_icomment='$o_icomment', o_dcomment='$o_dcomment', o_billable=$o_billable, o_reqby=$o_reqby, o_payable=$o_payable, o_tip= $o_tip, ra_id=$ra_id WHERE o_id = $o_id";
		error_log("Insert order\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result = mysqli_query($connection, $query);
		
		confirm_query($result);
		$query_id = "SELECT LAST_INSERT_ID() AS o_id ";
		$result_id = mysqli_query($connection, $query_id);
		confirm_query($result_id);
		if($result_oid = mysqli_fetch_assoc($result_id)) {
			return $result_oid;
		} else {
			return null;
		}
		//redirect_to("schooldata.php");
	}
	function updatestudnet($o_id, $s_fname, $s_lname, $s_grade, $s_gender, $s_pfname, $s_plname, $s_phone, $s_altphone, $s_street, $s_address, $s_city, $s_state, $s_zip, $s_country)
	{
		global $connection;

		if (is_array($s_fname)){

			$query_del = "DELETE FROM `lpr_student` WHERE o_id = $o_id";
			error_log("Insert bill\n" . $query_del , 3, "C:/xampp/apache/logs/error.log");
			$result_del = mysqli_query($connection, $query_del);

			for ($i=0; $i < sizeof($s_fname); $i++) {

			$fname = $s_fname[$i];
			$lname = $s_lname[$i];
			$grade = $s_grade[$i];
			$gender = $s_gender[$i];

			$query = "INSERT INTO lpr_student(o_id, s_fname, s_lname, s_grade, s_gender, s_pfname, s_plname, s_phone, s_altphone, s_street, s_address, s_city, s_state, s_zip, s_country) ";
			$query .= "VALUES ($o_id, '$fname', '$lname', '$grade', '$gender', '$s_pfname', '$s_plname', '$s_phone',  '$s_altphone', '$s_street', '$s_address', '$s_city',  '$s_state', '$s_zip', '$s_country') ";
			error_log("Insert student\n" . $query.sizeof($s_fname) , 3, "C:/xampp/apache/logs/error.log");
			$result = mysqli_query($connection, $query);
			
			confirm_query($result);
			}
		}
		else{

			$query = "UPDATE lpr_student SET s_fname='$s_fname', s_lname='$s_lname', s_grade='$s_grade', s_gender='$s_gender', s_pfname='$s_pfname', s_plname='$s_plname', s_phone='$s_phone', s_altphone='$s_altphone', s_street='$s_street', s_address='$s_address', s_city='$s_city', s_state='$s_state', s_zip='$s_zip', s_country='$s_country' WHERE o_id = $o_id";
			error_log("Insert student\n" . $query.sizeof($s_fname) , 3, "C:/xampp/apache/logs/error.log");
			$result = mysqli_query($connection, $query);
			
			confirm_query($result);
		}
		
	}


	function updatetbill_outzone($o_id,$billsplit,$billsplitvalue)
	{
		global $connection;

		$query_del = "DELETE FROM `lpr_billing` WHERE o_id = $o_id";
		error_log("Insert bill\n" . $query_del , 3, "C:/xampp/apache/logs/error.log");
		$result_del = mysqli_query($connection, $query_del);
		
		confirm_query($result_del);

		$j = 0;
		for ($i=0; $i < sizeof($billsplitvalue); $i++) {
			if ((float)$billsplitvalue[$i] >= 0) {
			 
			$client_id = (int)$billsplit[$j];
			$amount = (float)$billsplitvalue[$i];
			$query = "INSERT INTO lpr_billing(o_id, client_id, amount) VALUES ($o_id,$client_id,$amount)" ;
			error_log("Insert bill\n" . $query , 3, "C:/xampp/apache/logs/error.log");
			$result = mysqli_query($connection, $query);
			
			confirm_query($result);
			$j = $j + 1;	
			}
		} 
	}

	function updatebill_inzone($o_id,$o_billable,$o_reqby)
	{
		global $connection;

		$query_del = "DELETE FROM `lpr_billing` WHERE o_id = $o_id";
		error_log("Insert bill\n" . $query_del , 3, "C:/xampp/apache/logs/error.log");
		$result_del = mysqli_query($connection, $query_del);
		
		$query = "INSERT INTO lpr_billing(o_id, client_id, amount) VALUES ($o_id,$o_reqby,$o_billable) ";
		error_log("Insert bill\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result = mysqli_query($connection, $query);
		
		confirm_query($result);


	}

	//Calendar

	function insert_event($title, $startdate, $enddate, $client_id){
		global $connection;
		$query = "INSERT INTO lpr_dates(title,startdate,enddate,client_id) VALUES ('$title', '$startdate', '$enddate', '$client_id') ";
		error_log("Insert event\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result = mysqli_query($connection, $query);
		
		confirm_query($result);
	}

	function get_event(){
		global $connection;
		$query = "SELECT * FROM lpr_dates LEFT JOIN lpr_client ON lpr_dates.client_id LIKE lpr_client.client_id ";
		error_log("Insert event\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		$result = mysqli_query($connection, $query);
		
		confirm_query($result);
		return $result;
	}


	// Driver Billing rates and client billing



function  getDriverBill($driver_id,$start_date,$end_date){
    global $connection;
//    $query = "select * from ((select triplog_date,o_payable,o_tip,CONCAT(s_fname,\" \",s_lname) s_name,triplog_clock, CASE WHEN triplog_clock='AM' then o_ampickloc else o_pmpickloc end pickloc,CASE WHEN triplog_clock='AM' then o_amdroploc else o_pmdroploc end as droploc,triplog_time as triplog_picktime,lpr_school.school_abr from lpr_triplog,lpr_order,lpr_student,lpr_school  WHERE
//     lpr_triplog.triplog_o_id=lpr_order.o_id and lpr_triplog.triplog_studentid=lpr_student.s_id and lpr_triplog.triplog_driver_id=$driver_id and triplog_date between '$start_date' and '$end_date' and lpr_school.school_id=lpr_triplog.triplog_school_id AND triplog_driver_payable in ('TRUE') order by triplog_date ASC)
//    union
//    (select ad_tripdate, ad_payable,ad_tip ,\"Additional Trip\" as s_name,'NA' as triplog_clock,'NA' as pickloc,'NA' as droploc,'NA' as triplog_picktime,'NA' as school_abr from lpr_additnltrip where ad_driverid=$driver_id and ad_tripdate between '$start_date' and '$end_date'))t1 order by t1.triplog_date";
    $query="select * from ((select triplog_date,triplog_o_id,o_payable,o_tip,triplog_clock, CASE WHEN triplog_clock='AM' then o_ampickloc else o_pmpickloc end pickloc,
CASE WHEN triplog_clock='AM' then o_amdroploc else o_pmdroploc end as droploc,triplog_time as triplog_picktime,lpr_school.school_abr from lpr_triplog,lpr_order,
lpr_school  WHERE
     lpr_triplog.triplog_o_id=lpr_order.o_id and lpr_triplog.triplog_driver_id=$driver_id and
	 triplog_date between '$start_date' and '$end_date' and lpr_school.school_id=lpr_triplog.triplog_school_id AND triplog_driver_payable in ('TRUE') and triplog_status not in ('none') order by triplog_date ASC) 
	 union
    (select ad_tripdate,\"Additional Trip\" as triplog_o_id,  ad_payable,ad_tip ,'NA' as triplog_clock,'NA' as pickloc,'NA' as droploc,'NA' as triplog_picktime,
	'NA' as school_abr from lpr_additnltrip where ad_driverid=$driver_id and ad_tripdate between '$start_date' and '$end_date'))t1 left join (select CONCAT(s_fname,\" \",s_lname) s_name,o_id from lpr_student group by lpr_student.o_id)t2 on t1.triplog_o_id=t2.o_id  order by t1.triplog_date";
    error_log("\nDriver Billing query " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_bill = mysqli_query($connection, $query);
    confirm_query($result_bill);
    return $result_bill;

//    select triplog_date,o_payable,o_tip,CONCAT(s_lname,\" \",s_fname) s_name from lpr_triplog,lpr_order,lpr_student  WHERE
//lpr_triplog.triplog_o_id=lpr_order.o_id and lpr_triplog.triplog_studentid=lpr_student.s_id and lpr_triplog.triplog_driver_id=$driver_id and triplog_date between '$start_date' and '$end_date' AND triplog_status in ('success')
}

function insertCashAdvance($driver_id,$cash_advance,$type){
    global $connection;
    $query= "INSERT INTO lpr_cashadvance(`c_driverid`, `c_payable`, `c_Date`, `c_type`) VALUES ($driver_id,$cash_advance,CURRENT_DATE,'$type')";
    error_log("\ninsert Driver cash Advance  " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
}

function insert_additnlTrip($driverid,$ad_payable,$ad_tip,$ad_tripdate){
    global $connection;
    $query= "INSERT INTO lpr_additnltrip (ad_driverid, ad_payable, ad_tip, ad_tripdate) VALUES ($driverid,$ad_payable,$ad_tip,'$ad_tripdate')";
    error_log("\ninsert Driver additnal trip  " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
}

// Addtinl trip Details System
function getAdtnlTripDetails($ad_studentId){
    global $connection;
    $query="SELECT * FROM `lpr_student`,lpr_order where lpr_student.s_id=$ad_studentId  and lpr_student.o_id=lpr_order.o_id";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    if($resultvalue = mysqli_fetch_assoc($result)) {
        return $resultvalue;
    } else {
        return null;
    }
}//Addtinl trip Details System ends

//Addtinl trip Details System
function  insertAdtnlTripClient($driverid,$ad_tripdate,$ad_studentId,$ad_oid,$ad_scId){
    global $connection;
    $query= "INSERT INTO `lpr_triplog`(`triplog_o_id`, `triplog_school_id`, `triplog_driver_id`, `triplog_studentid`, `triplog_city`,
 `triplog_time`, `triplog_pickloc`, `triplog_picktime`, `triplog_droptime`, `triplog_status`, `triplog_date`, `triplog_clock`, `triplog_date_updated`, `triplog_driver_payable`, `triplog_client_payable`)
  VALUES($ad_oid,$ad_scId,$driverid,$ad_studentId,'Adtnl Trip',CURRENT_TIME,'Adtnl Trip',CURRENT_TIME,CURRENT_TIME,'success','$ad_tripdate' ,'AM',CURRENT_DATE,'FALSE','TRUE') ";
    error_log("\ninsert Driver additnal trip to triplog" . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
}//Addtinl trip Details System ends




function  getCashAdvance($driver_id){
    global $connection;
    $query="select coalesce(t1.debit,0)-coalesce(t2.credit,0) as cashAdvance from
(SELECT sum(c_payable) as debit from lpr_cashadvance where c_driverid=$driver_id and c_type='debit' )t1,
(SELECT sum(c_payable) as credit from lpr_cashadvance where c_driverid=$driver_id and c_type='credit')t2";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    if($resultvalue = mysqli_fetch_assoc($result)) {
        return $resultvalue;
    } else {
        return 0;
    }

}

function getTotalEarnings($driver_id){
    global $connection;
    $query="SELECT sum(amount) as earnings FROM `lpr_payroll` WHERE driver_id=$driver_id and year(enddate)=year(CURRENT_DATE)";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    if($resultvalue = mysqli_fetch_assoc($result)) {
        return $resultvalue;
    } else {
        return 0;
    }

}
//payroll
function savepayroll($savedate,$driver_id,$amount,$startdate,$enddate,$totalBilling,$payToContractorsPerc,$payToContractors,$tips,$additions,$contractorsTotal,$fuelAdvance,$toll,$leasePerc,$lease,$others,$checkNumber){
    global $connection;
    $query= "INSERT INTO lpr_payroll (startdate, enddate, savedate, amount,driver_id,`tBill`, `payToContractorsPerc`, `payToContractors`, `tips`, `additions`, `contractorsTotal`, `fuelAdvance`, `toll`, `leasePerc`, `lease`, `others`, `checkNumber`) 
      VALUES ('$startdate','$enddate','$savedate',$amount,$driver_id,$totalBilling,$payToContractorsPerc,$payToContractors,$tips,$additions,$contractorsTotal,$fuelAdvance,$toll,$leasePerc,$lease,$others,$checkNumber)";
   // error_log("\nSaving payroll  " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
    return true;
}

//payroll for ride alongs
function savepayroll_ra($ra_id,$amount,$startdate,$enddate,$tips,$amountpertrip,$deducts){
    global $connection;
    $query= "INSERT INTO lpr_payroll_ra (startdate, enddate, savedate, amount,ra_id,tips,amountpertrip,deductions) 
      VALUES ('$startdate','$enddate',CURRENT_DATE,$amount,$ra_id,$tips,$amountpertrip,$deducts)";
    error_log("\nSaving payroll  " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
    return true;
}

//Client Bill save
function saveClientBill($c_id,$startdate,$enddate,$totaltrips,$totalbillable){
    global $connection;
    $query= "INSERT INTO `lpr_invoice`(invoice_date, `startdate`, `enddate`, `cid`, `totaltrips`, `totalpayable`) VALUES (CURRENT_DATE,'$startdate','$enddate',$c_id,$totaltrips,$totalbillable)";
   // error_log("\nSaving Client Bill  " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
    return true;
}

function updateClientBill($invoiceid,$totaltrips,$totalbillable){
    global $connection;
    $query ="UPDATE `lpr_invoice` SET totaltrips=$totaltrips,`totalpayable`=$totalbillable WHERE `invoice_id`=$invoiceid";
    $result_id = mysqli_query($connection, $query);
    error_log("Inside update cleinet bill query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result_id);

}




function checkpayroll($driver_id,$startdate,$enddate){
    global $connection;
    $query= "SELECT * FROM `lpr_payroll` WHERE (('$startdate' BETWEEN startdate AND enddate) OR ('$enddate' BETWEEN startdate AND enddate)) AND driver_id =$driver_id";
    //error_log("\nSaving payroll  " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
    if(mysqli_num_rows($result_id)) {
        return false;
    } else {
        return true;
    }
}

function checkpayroll_ra($ra_id,$startdate,$enddate){
    global $connection;
    $query= "SELECT * FROM `lpr_payroll_ra` WHERE (('$startdate' BETWEEN startdate AND enddate) OR ('$enddate' BETWEEN startdate AND enddate)) AND ra_id =$ra_id";
    error_log("\nSaving payroll  " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
    if(mysqli_num_rows($result_id)) {
        return false;
    } else {
        return true;
    }
}

function checkClientBill($cb_ctypeSelect,$cb_startdate,$cb_enddate){
    global $connection;
    $query= "SELECT * FROM `lpr_invoice` WHERE (('$cb_startdate' BETWEEN startdate AND enddate) OR ('$cb_enddate' BETWEEN startdate AND enddate)) AND cid =$cb_ctypeSelect";
    //error_log("\nSaving payroll  " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
    if(mysqli_num_rows($result_id)) {
        return false;
    } else {
        return true;
    }
}


function deletepayroll($id){
    global $connection;
    $query= "DELETE FROM lpr_payroll where pl_id=$id";
    error_log("\ndelete payroll  " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
    return true;
}

function deletepayroll_ra($id){
    global $connection;
    $query= "DELETE FROM lpr_payroll_ra where plra_id=$id";
    error_log("\ndelete payroll  " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
    return true;
}

//Delete Bill
function deleteBill($id){
    global $connection;
    $query= "DELETE FROM lpr_invoice where invoice_id=$id";
    error_log("\ndelete payroll  " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
    return true;
}

//Update Payroll -Driver Billing System
function updatePayroll($savedate,$pay_id,$amount,$startdate,$enddate,$payToContractorsPerc,$payToContractors,$additions,$contractorsTotal,$fuelAdvance,$toll,$leasePerc,$lease,$others,$checkNumber){
    global $connection;
    $query ="UPDATE `lpr_payroll` SET  savedate='$savedate', `startdate`='$startdate',`enddate`='$enddate',`amount`=$amount,
`payToContractorsPerc`=$payToContractorsPerc,`payToContractors`=$payToContractors,`additions`=$additions,`contractorsTotal`=$contractorsTotal,`fuelAdvance`=$fuelAdvance,`toll`=$toll,`leasePerc`=$leasePerc,
`lease`=$lease,`others`=$others,`checkNumber`=$checkNumber WHERE pl_id=$pay_id ";
    $result_id = mysqli_query($connection, $query);
    error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result_id);
}
//Update Payroll - Driver Billing System

function updatepayroll_ra($plra_id,$amount,$tips,$amountpertrip,$deducts){
    global $connection;
    $query ="UPDATE `lpr_payroll_ra` SET `amount`=$amount,tips=$tips,amountpertrip=$amountpertrip,deductions=$deducts WHERE plra_id=$plra_id ";
    $result_id = mysqli_query($connection, $query);
    error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result_id);
}


function getClientBill($cb_client,$cb_stypeSelect ,$cb_sSelect,$cb_sname,$cb_startdate,$cb_enddate){
    global $connection;
//    $query=" SELECT `triplog_o_id`, lpr_billing.client_id as triplog_client_id, `triplog_school_id`, `triplog_driver_id`, `triplog_studentid`,`triplog_time`, `triplog_pickloc`, `triplog_date`,lpr_billing.amount as o_billable,
// CONCAT(driver_lname,' ',driver_fname) as d_name,client_name,CONCAT(s_fname,' ',s_lname) as s_name,school_name, CASE WHEN triplog_clock='AM' then o_ampickloc else o_pmpickloc end pickloc,
// CASE WHEN triplog_clock='AM' then o_amdroploc else o_pmdroploc end as droploc,triplog_picktime,triplog_clock,lpr_school.school_abr
// FROM `lpr_triplog`,lpr_order,lpr_driver,lpr_student,lpr_client,lpr_school,lpr_billing
// WHERE
// triplog_o_id=lpr_order.o_id and  triplog_driver_id=lpr_driver.driver_id and triplog_studentid=lpr_student.s_id
// and lpr_billing.o_id=triplog_o_id and lpr_billing.client_id=$cb_client and lpr_client.client_id=lpr_billing.client_id and
// triplog_school_id=lpr_school.school_id and triplog_date between '$cb_startdate' and '$cb_enddate' and
// triplog_client_payable in ('TRUE') and lpr_order.o_status in ('active') and lpr_billing.amount  not in ('0')";

    $query="select * from(select * from (SELECT `triplog_o_id`,triplog_status,triplog_client_payable,triplog_studentid, lpr_billing.client_id as triplog_client_id, `triplog_school_id`, `triplog_driver_id`,`triplog_time`, `triplog_pickloc`,
	`triplog_date`,lpr_billing.amount as o_billable,
 CONCAT(driver_lname,' ',driver_fname) as d_name,client_name,school_name,
 CASE WHEN triplog_clock='AM' then o_ampickloc else o_pmpickloc end pickloc,
 CASE WHEN triplog_clock='AM' then o_amdroploc else o_pmdroploc end as droploc,triplog_picktime,triplog_clock,lpr_school.school_abr
 FROM `lpr_triplog`,lpr_order,lpr_driver,lpr_client,lpr_school,lpr_billing
 WHERE
 triplog_o_id=lpr_order.o_id and  triplog_driver_id=lpr_driver.driver_id 
 and lpr_billing.o_id=triplog_o_id and lpr_billing.client_id=$cb_client and lpr_client.client_id=lpr_billing.client_id and
 triplog_school_id=lpr_school.school_id and triplog_date between '$cb_startdate' and '$cb_enddate' and
 triplog_client_payable in ('TRUE') and lpr_order.o_status in ('active')  and  lpr_billing.amount  not in ('0')and triplog_status not in ('none')";


    if(!empty($cb_stypeSelect)){
        $query .=" and school_type='$cb_stypeSelect'";
    }
    if(!empty($cb_sSelect)){
        $query .=" and lpr_school.school_id=$cb_sSelect";
    }
    if(!empty($cb_sname)){
        $query .=" and triplog_studentid=$cb_sname";
    }
    $query .=" )t1 join 
 (select CONCAT(s_fname,\" \",s_lname) s_name,o_id from lpr_student group by lpr_student.o_id)t2 on t1.triplog_o_id=t2.o_id)t4 order by s_name,triplog_date";
    $result = mysqli_query($connection, $query);
    error_log("\nClient Billing Query " . $query, 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result);
    return $result;
}

function getClientPayement($cb_client,$cb_stypeSelect ,$cb_sSelect,$cb_sname,$cb_startdate,$cb_enddate){
     global $connection;
//    $query="select count(triplog_o_id) as tripcount ,sum(o_billable)as totalbillable,client_name,client_street,client_address,client_city,client_state,client_zip from
// ( SELECT `triplog_o_id`, lpr_billing.client_id as triplog_client_id, `triplog_school_id`, `triplog_driver_id`, `triplog_studentid`,`triplog_time`, `triplog_pickloc`, `triplog_date`,lpr_billing.amount as o_billable,
// CONCAT(driver_lname,' ',driver_fname) as d_name,client_name,client_street,client_address,client_city,client_state,client_zip,CONCAT(s_fname,' ',s_lname) as s_name,school_name,
// CASE WHEN triplog_clock='AM' then o_ampickloc else o_pmpickloc end pickloc,
// CASE WHEN triplog_clock='AM' then o_amdroploc else o_pmdroploc end as droploc,triplog_picktime,triplog_clock,lpr_school.school_abr
// FROM `lpr_triplog`,lpr_order,lpr_driver,lpr_student,lpr_client,lpr_school,lpr_billing
// WHERE
// triplog_o_id=lpr_order.o_id and  triplog_driver_id=lpr_driver.driver_id and triplog_studentid=lpr_student.s_id
// and lpr_billing.o_id=triplog_o_id and lpr_billing.client_id=$cb_client and lpr_client.client_id=lpr_billing.client_id and
// triplog_school_id=lpr_school.school_id and triplog_date between '$cb_startdate' and '$cb_enddate' and
// triplog_client_payable in ('TRUE') and lpr_order.o_status in ('active') and lpr_billing.amount  not in ('0')";

    $query="select count(triplog_o_id) as tripcount ,sum(o_billable)as totalbillable,client_name,client_street,client_address,client_city,client_state,client_zip from
 (select * from (SELECT `triplog_o_id`,triplog_studentid, lpr_billing.client_id as triplog_client_id, `triplog_school_id`, `triplog_driver_id`,`triplog_time`, `triplog_pickloc`, `triplog_date`,lpr_billing.amount as o_billable,
 CONCAT(driver_lname,' ',driver_fname) as d_name,client_name,client_street,client_address,client_city,client_state,client_zip,school_name,
 CASE WHEN triplog_clock='AM' then o_ampickloc else o_pmpickloc end pickloc,
 CASE WHEN triplog_clock='AM' then o_amdroploc else o_pmdroploc end as droploc,triplog_picktime,triplog_clock,lpr_school.school_abr
 FROM `lpr_triplog`,lpr_order,lpr_driver,lpr_client,lpr_school,lpr_billing
 WHERE
 triplog_o_id=lpr_order.o_id and  triplog_driver_id=lpr_driver.driver_id
 and lpr_billing.o_id=triplog_o_id and lpr_billing.client_id=$cb_client and lpr_client.client_id=lpr_billing.client_id and
 triplog_school_id=lpr_school.school_id and triplog_date between '$cb_startdate' and '$cb_enddate' and
 triplog_client_payable in ('TRUE') and lpr_order.o_status in ('active') and lpr_billing.amount  not in ('0') and triplog_status not in ('none')";


    if(!empty($cb_stypeSelect)){
        $query .=" and school_type='$cb_stypeSelect'";
    }
    if(!empty($cb_sSelect)){
        $query .=" and lpr_school.school_id=$cb_sSelect";
    }
    if(!empty($cb_sname)){
        $query .=" and triplog_studentid=$cb_sname";
    }
    $query.=" )t1 join 
 (select CONCAT(s_fname,\" \",s_lname) s_name,o_id from lpr_student group by lpr_student.o_id)t2 on t1.triplog_o_id=t2.o_id )t3";
    $result = mysqli_query($connection, $query);
    error_log("\nClient Billing Payment Query " . $query, 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result);
    return $result;
}

function getInvoiceNumber(){
    global $connection;
    $query= "SELECT max(lpr_invoice.invoice_id)as invoice from lpr_invoice";
    error_log("\ninsert Invoice Number " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    if($results = mysqli_fetch_assoc($result)) {
        return $results;
    } else {
        return null;
    }
}

//Get Invoice Details
function  getInvoiceById($invoice){
    global $connection;
    $query= "SELECT * from lpr_invoice where invoice_id=$invoice";
    error_log("\ninsert Invoice Number " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    if($results = mysqli_fetch_assoc($result)) {
        return $results;
    } else {
        return null;
    }
}




function addZone($zone_loc){
    global $connection;
    $query= "INSERT INTO lpr_zones(zone_loc) VALUES ('$zone_loc')";
    error_log("\ninsert Zones " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}

function insertRate($zoneid,$item,$amount){
    global $connection;
    $query= "INSERT INTO lpr_rates( zone_id, amount, item) VALUES ($zoneid,$amount,'$item')";
    error_log("\ninsert rates " . $query, 3, "C:/xampp/apache/logs/error.log");
    $result = mysqli_query($connection, $query);
    confirm_query($result);

}

function updateRate($rateId,$rate){
    global $connection;
    $query  = "UPDATE lpr_rates SET ";
    $query .= "amount =$rate WHERE rate_id = $rateId ";
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
}

function updateZone($zone_loc,$zone_id){
    global $connection;
    $query  = "UPDATE lpr_zones SET ";
    $query .= "zone_loc ='$zone_loc' WHERE zone_id = $zone_id ";
    $result_id = mysqli_query($connection, $query);
    confirm_query($result_id);
}

//driver_status change
function dr_changestatus($d_id,$status){
    global $connection;
    $query = "UPDATE lpr_driver SET driver_status = '$status' WHERE driver_id = $d_id";
    $result = mysqli_query($connection, $query);
    error_log("\nInside  driver changestatus" . $query , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result);
    if ($status=="inactive") {
    	$query2 = "UPDATE `lpr_order` SET `driver_id` = NULL WHERE driver_id=$d_id";
	    $result2 = mysqli_query($connection, $query2);
	    error_log("\nInside  driver changestatus updating order table" . $query2 , 3, "C:/xampp/apache/logs/error.log");
	    confirm_query($result2);
	    $query3 = "UPDATE lpr_driver SET driver_termdate = CURRENT_DATE WHERE driver_id = $d_id";
	    $result3 = mysqli_query($connection, $query3);
	    error_log("\nInside  driver changestatus" . $query3 , 3, "C:/xampp/apache/logs/error.log");
	    confirm_query($result);
    }
    elseif ($status=="active") {
      	$query3 = "UPDATE lpr_driver SET driver_termdate = NULL WHERE driver_id = $d_id";
	    $result3 = mysqli_query($connection, $query3);
	    error_log("\nInside  driver changestatus" . $query3 , 3, "C:/xampp/apache/logs/error.log");
	    confirm_query($result);
      }  

}

function inserNewSchool($sc_name,$sc_ctype,$sc_abr,$sc_cnumber,$sc_cname,$sc_stype,$sc_steet,$sc_city,$sc_state,$sc_zip,$sc_country,$sc_address){
    global $connection;
    $query ="INSERT INTO lpr_school(`client_id`, `school_name`, `school_abr`, `school_street`,`school_city`, `school_state`, `school_zip`, `school_country`, `school_contact_name`, `school_contact_no`, `school_type`,`school_address`) 
             VALUES ($sc_ctype,'$sc_name','$sc_abr','$sc_steet','$sc_city','$sc_state',$sc_zip,'$sc_country','$sc_cname','$sc_cnumber','$sc_stype','$sc_address')";
    $result_id = mysqli_query($connection, $query);
//        //echo $query;
   error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result_id);
    redirect_to("schooldata.php");
}

function getSchoolData($schoolid){
    global $connection;
    $query = "select * from lpr_school where school_id=$schoolid";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    if($results = mysqli_fetch_assoc($result)) {
        return $results;
    } else {
        return null;
    }
}

function updateSchool($sc_id,$sc_name,$sc_ctype,$sc_abr,$sc_cnumber,$sc_cname,$sc_stype,$sc_steet,$sc_city,$sc_state,$sc_zip,$sc_country,$sc_address){
    global $connection;
    $query ="UPDATE `lpr_school` SET `client_id`=$sc_ctype,`school_name`='$sc_name',`school_abr`='$sc_abr',`school_street`='$sc_steet',`school_city`='$sc_city',`school_state`='$sc_state',
           `school_zip`=$sc_zip,`school_country`='$sc_country',`school_contact_name`='$sc_cname',`school_contact_no`='$sc_cnumber',`school_type`='$sc_stype',school_address='$sc_address' WHERE school_id=$sc_id";
    $result_id = mysqli_query($connection, $query);
//        //echo $query;
  error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result_id);
    redirect_to("schooldata.php");
}

function getRaData($ra_id,$startdate,$end_date){
    global $connection;
    $query="SELECT DISTINCT *  FROM lpr_triplog join (SELECT triplog_date,triplog_driver_id FROM lpr_triplog WHERE triplog_o_id IN (SELECT lpr_order.o_id FROM lpr_order WHERE ra_id = $ra_id)) as t1
ON lpr_triplog.triplog_date = t1.triplog_date AND lpr_triplog.triplog_driver_id = t1.triplog_driver_id
join lpr_driver on lpr_driver.driver_id=lpr_triplog.triplog_driver_id
join lpr_student on lpr_student.s_id=lpr_triplog.triplog_studentid and lpr_triplog.triplog_date between '$startdate' and '$end_date'";
    error_log("Inside RA query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
    $result = mysqli_query($connection, $query);
    confirm_query($result);
        return $result;


}


function insert_ra($first_name, $middle_name, $last_name, $contact_number, $street, $address, $city, $state, $zip,$ssn){
    global $connection;
    $query ="INSERT INTO `lpr_ridealong`(`ra_fname`, `ra_mname`, `ra_lname`, `phone`, `ra_street`, `address`, `ra_city`, `ra_state`, `ra_zip`,`ra_ssn`) VALUES ('$first_name','$middle_name','$last_name','$contact_number','$street','$address','$city','$state','$zip','$ssn')";
    $result_id = mysqli_query($connection, $query);
    error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result_id);
    redirect_to("ridealong.php");
}

function update_ra($raid,$first_name, $middle_name, $last_name, $contact_number, $street, $address, $city, $state, $zip,$ssn){
    global $connection;
    $query ="UPDATE `lpr_ridealong` SET `ra_fname`='$first_name',`ra_mname`='$middle_name',`ra_lname`='$last_name',`ra_street`='$street',`address`='$address',`ra_city`='$city',`ra_state`='$state',`ra_zip`='$zip',`phone`='$contact_number',`ra_ssn`='$ssn' WHERE id=$raid";
    $result_id = mysqli_query($connection, $query);
    error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
    confirm_query($result_id);
    redirect_to("ridealong.php");
}

function getRalongdata($ra_id){
    global $connection;
    $query_ra  = "SELECT * FROM lpr_ridealong where id=$ra_id";
    $result_ra = mysqli_query($connection, $query_ra);
    confirm_query($result_ra);
    if($results = mysqli_fetch_assoc($result_ra)) {
        return $results;
    } else {
        return null;
    }
}


function delete_catransaction($transactionid){
    global $connection;
    $query_ra  = "DELETE FROM `lpr_cashadvance` WHERE c_advanceid=$transactionid";
    $result_ra = mysqli_query($connection, $query_ra);
    confirm_query($result_ra);
}

function convert_number_to_words($number) {

    $hyphen      = ' ';
    $conjunction = ' ';
    $separator   = ' ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
function convert_number_to_money($number){
	$words = convert_number_to_words($number);

	if(strpos($words, 'point') === false){
		return  ucfirst($words." and 00/100");
	}
	else{
		$s = explode("point",$words);
		unset($s[1]);
		$words = $s[0];
		$words .= 'and '.substr($number, -2).'/100';
		return  ucfirst($words);
	}
}

function getAdvanceDeatils($driver_id){
    global $connection;
    $query_advance  = "SELECT * FROM lpr_cashadvance where c_driverid=$driver_id";
    $result_advance = mysqli_query($connection, $query_advance);
    confirm_query($result_advance);
    return $result_advance;
}

//Get Payroll Details for Payroll Edit System
function getPayrollById($payId){
    global $connection;
    $query_advance  = "SELECT * FROM lpr_payroll left join lpr_driver on lpr_payroll.driver_id=lpr_driver.driver_id where lpr_payroll.pl_id=$payId";
    $result_advance = mysqli_query($connection, $query_advance);
    confirm_query($result_advance);
    if($results = mysqli_fetch_assoc($result_advance)) {
        return $results;
    } else {
        return null;
    }

}

function getPayroll_raById($payId){
    global $connection;
    $query_advance  = "SELECT * FROM lpr_payroll_ra left join lpr_ridealong on lpr_ridealong.id=lpr_payroll_ra.ra_id where lpr_payroll_ra.plra_id=$payId";
    $result_advance = mysqli_query($connection, $query_advance);
    confirm_query($result_advance);
    if($results = mysqli_fetch_assoc($result_advance)) {
        return $results;
    } else {
        return null;
    }

}
//Get Payroll Details for Payroll Edit System Ends

// Get Check Number : Driver Billing System
function getCheckNumber(){
    global $connection;
    $query_advance  = "SELECT max(checkNumber) as checkNum FROM lpr_payroll";
    $result_advance = mysqli_query($connection, $query_advance);
    confirm_query($result_advance);
    if($results = mysqli_fetch_assoc($result_advance)) {
        return $results;
    } else {
        return null;
    }
}
//End of  Get Check Number



function getDriverForM($oid,$period,$daterequired){
    global $connection;
    $query_advance  = "SELECT * FROM lpr_driver_contract
WHERE start_date <= '$daterequired' and o_id=$oid and period='$period'
ORDER BY start_date DESC LIMIT 1";
    //error_log("Inside driver M query\n" . $query_advance , 3, "C:/xampp/apache/logs/error.log");
    $result_advance = mysqli_query($connection, $query_advance);
    confirm_query($result_advance);
    $results=  mysqli_fetch_assoc($result_advance);
    if(!empty($results)){
//        $name=get_drivername($results['driver_id']);
//        error_log("Inside driver Manifest query 1 \n" . $name['driver_name'] , 3, "C:/xampp/apache/logs/error.log");
        return $results['driver_id'];
    }else{
        return null;
    }

}

function inzoneStats($client,$start_date,$end_date){
    global $connection;
    $query_advance  = "select sum(triplog_pax) stud_count from (select t2.o_id,count(t2.o_id) count ,triplog_pax from  (select * from  (select distinct triplog_o_id,triplog_pax from lpr_triplog where triplog_date between '$start_date' and '$end_date' and triplog_status='success')t1  join lpr_billing on t1.triplog_o_id=lpr_billing.o_id)t2 
group by t2.o_id having count=1)t3 join lpr_billing on t3.o_id=lpr_billing.o_id WHERE client_id=$client";
    $result_advance = mysqli_query($connection, $query_advance);
    confirm_query($result_advance);
    if($results = mysqli_fetch_assoc($result_advance)) {
        return $results;
    } else {
        return null;
    }

}

function outZoneStats($client,$start_date,$end_date){
    global $connection;
    $query_advance  = "
select sum(triplog_pax) o_studCount from
 (select t5.o_id,t5.client_id,triplog_pax from  
   (select t4.o_id,COUNT(t4.o_id) counto,client_id from 
     (select * from lpr_billing where lpr_billing.o_id in 
       (select t3.o_id ref_oid from 
          (select t2.o_id,count(t2.o_id) count from  
            (select * from  
              (select distinct triplog_o_id from lpr_triplog where triplog_date between '$start_date' and '$end_date' and triplog_status='success')t1  join lpr_billing on 
                       t1.triplog_o_id=lpr_billing.o_id
                           )t2 group by t2.o_id having count >=2
                             )t3
                              ) and amount!=0
                                )t4 group by t4.o_id having counto=1
                                 ) t5 join lpr_triplog on lpr_triplog.triplog_o_id=t5.o_id group by t5.o_id having client_id=$client) t6
    ";
    $result_advance = mysqli_query($connection, $query_advance);
    confirm_query($result_advance);
    if($results = mysqli_fetch_assoc($result_advance)) {
        return $results;
    } else {
        return null;
    }
}


function specialEdStats($client,$start_date,$end_date){
    global $connection;
    $query_advance  = "
select sum(triplog_pax) splitbill from 
   (select t4.o_id,triplog_pax from
     (select * from lpr_billing where lpr_billing.o_id in 
        (select t3.o_id ref_oid from 
          (select t2.o_id,count(t2.o_id) count from  
             (select * from  
                  (select distinct triplog_o_id from lpr_triplog where triplog_date between '$start_date' and '$end_date' and triplog_status='success'
                      )t1  join lpr_billing on  t1.triplog_o_id=lpr_billing.o_id and amount!=0
                         )t2 group by t2.o_id having count >=2 
                             )t3 
                                 ) and client_id=$client
                                    )t4 join lpr_triplog on lpr_triplog.triplog_o_id=t4.o_id group by t4.o_id
                                        )t6
    ";
    $result_advance = mysqli_query($connection, $query_advance);
    confirm_query($result_advance);
    if($results = mysqli_fetch_assoc($result_advance)) {
        return $results;
    } else {
        return null;
    }
}


//cancel all trips
function insertcanceldays($date,$clck){
    global $connection;
    $query = "INSERT INTO `lpr_AllCancellations`(cancel_date, `clck`) VALUES ('$date','$clck') ";
    error_log("Insert cancell all days\n" . $query , 3, "C:/xampp/apache/logs/error.log");
    $result = mysqli_query($connection, $query);

    confirm_query($result);
    $query_id = "SELECT LAST_INSERT_ID() AS c_id ";
    $result_id = mysqli_query($connection, $query_id);
    confirm_query($result_id);
    if($result_oid = mysqli_fetch_assoc($result_id)) {
        return $result_oid;
    } else {
        return null;
    }
}

function getcancelalldate($daterequired,$clck){
    global $connection;
    $query_advance  = "select * from lpr_allcancellations where lpr_allcancellations.cancel_date='$daterequired' and lpr_allcancellations.clck='$clck'";
    $result_advance = mysqli_query($connection, $query_advance);
    confirm_query($result_advance);
    if($results = mysqli_fetch_assoc($result_advance)) {
        return $results;
    } else {
        return null;
    }
}


?>