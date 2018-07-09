<?php
include("../includes/db_connection.php");
include("../includes/functions.php");
?>
<?php
$obj = $_POST['myData'];

if ($obj["mode"]=="typedropdown") {

$client_id = (int)$obj["client_id"];
$school_type = $obj["school_type"];
$return_obj = array();
$schools = select_schools($client_id,$school_type);
while($result = mysqli_fetch_assoc($schools)) {
	array_push($return_obj, $result);
}
echo json_encode($return_obj);
}
if ($obj["mode"]=="schooldropdown") {

$school_id = (int)$obj["school_id"];
//$return_obj = array();
$school = select_school($school_id);
echo json_encode($school);
}
if ($obj["mode"]=="getdriver") {

$driver = driverdetails();
$return_dobj = array();
while($result = mysqli_fetch_assoc($driver)) {
	array_push($return_dobj, $result);
}
echo json_encode($return_dobj);
}
if ($obj["mode"]=="getridealongs") {

$ra = ridealongdetails();
$return_dobj = array();
while($result = mysqli_fetch_assoc($ra)) {
	array_push($return_dobj, $result);
}
echo json_encode($return_dobj);
}
if ($obj["mode"]=="get_students") {

$ra = studentdetails();
$return_dobj = array();
while($result = mysqli_fetch_assoc($ra)) {
	array_push($return_dobj, $result);
}
echo json_encode($return_dobj);
}
if ($obj["mode"]=="tripcost") {

$zone_id = (int)$obj["zone_id"];
$additems = $obj["addons"];
$item = $obj['item'];
//$return_obj = array();
$rate = tripcost($zone_id,$item,$additems);
print_r($rate['SUM(amount)']);
}
if ($obj["mode"]=="tripcost_outzone") {

$zone_id = $obj["zone_id"];
$item = $obj['item'];
$additems = $obj["addons"];
$rate = tripcost_outzone($zone_id,$item,$additems);
print_r($rate['SUM(amount)']);
}
if ($obj["mode"]=="insert") {

$client_id = (int)$obj["ctypeSelect"];
$school_id = (int)$obj["sSelect"];
$o_startdate = $obj["o_startdate"];
$o_enddate = $obj["o_enddate"];
$o_status = "active";
$o_ampickloc = verify_in(trim($obj["o_ampickloc"])=='' ? 'NULL' : $obj["o_ampickloc"]);
$o_ampicktime = $obj["o_ampicktime"];
$o_amdroploc = verify_in($obj["o_amdroploc"]);
$o_amdroptime = $obj["o_amdroptime"];
$o_pmpickloc = verify_in($obj["o_pmpickloc"]);
$o_pmdroploc = trim($obj["o_pmdroploc"])=='' ? 'NULL' : $obj["o_pmdroploc"];
$o_pmpicktime = $obj["o_pmpicktime"];
$o_days = $obj["o_days"];
$o_fd = (isset($obj["o_fd"]) ? 'TRUE' : 'FALSE');
$o_ra = (isset($obj["o_ra"]) ? 'TRUE' : 'FALSE');
$o_wc = (isset($obj["o_wc"]) ? 'TRUE' : 'FALSE');
$o_as = (isset($obj["o_as"]) ? 'TRUE' : 'FALSE');
$o_cs = (isset($obj["o_cs"]) ? 'TRUE' : 'FALSE');
$o_bs = (isset($obj["o_bs"]) ? 'TRUE' : 'FALSE');
$driver_id = ((int)$obj["driver_id"]==0? 'NULL' : (int)$obj["driver_id"]);
$pm_driver_id = ((int)$obj["pm_driver_id"]==0? 'NULL' : (int)$obj["pm_driver_id"]);
$o_icomment = verify_in($obj["icomment"]);
$o_dcomment = verify_in($obj["dcomment"]);
$o_billable = (float)$obj["o_billable"];
$o_reqby = (int)$obj["optradio"];
$o_payable = (float)$obj["o_payable"];
$o_tip = (float)$obj["o_tip"];
$ra_id = ((int)$obj["ra_id"]==0? 'NULL' : (int)$obj["ra_id"]) ;
$order = createorder($client_id,$school_id,$o_startdate,$o_enddate,$o_status,$o_ampickloc,$o_ampicktime,$o_amdroploc,$o_amdroptime,$o_pmpickloc,$o_pmdroploc,$o_pmpicktime,$o_days,$o_fd,$o_ra,$o_wc,$o_as,$o_cs,$o_bs,$driver_id,$pm_driver_id,$o_icomment,$o_dcomment,$o_billable,$o_reqby,$o_payable,$o_tip,$ra_id);


$o_id = $order['o_id'];
$s_fname = $obj['s_fname'];
$s_lname = $obj['s_lname'];
$s_grade = $obj['s_grade'];
$s_gender = $obj['s_gender'];
$s_pfname = verify_in($obj['pfname']);
$s_plname = verify_in($obj['plname']);
$s_phone = verify_in($obj['phone']);
$s_altphone = verify_in($obj['altphone']);
$s_street = verify_in($obj['street']);
$s_address = verify_in($obj['address']);
$s_city = verify_in($obj['city']);
$s_state = verify_in($obj['state']);
$s_zip = verify_in($obj['zipcode']);
$s_country = verify_in($obj['country']);


$student = createstudnet($o_id, $s_fname, $s_lname, $s_grade, $s_gender, $s_pfname, $s_plname, $s_phone, $s_altphone, $s_street, $s_address, $s_city, $s_state, $s_zip, $s_country);

$billtype = $obj['billingradio'];

	if ($billtype=='outzone') {
		$billsplit = $obj['billsplit'];
		$billsplitvalue = $obj['billsplitvalue'];
		$billtable = insertbill_outzone($o_id,$billsplit,$billsplitvalue);
		print_r($o_id);
	}
	if ($billtype=='inzone') {
		
		$billtable = insertbill_inzone($o_id,$o_billable,$o_reqby);
		print_r($o_id);
	}
}
?>