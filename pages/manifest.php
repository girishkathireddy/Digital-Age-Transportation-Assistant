<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>
<?php
$clck="AM";
$c=0;
$bydrivername="";
$daterequired=date("Y-m-d");
$cancellall=false;


if(isset($_POST["searchByDriverName"])){
    $byBriverId= $_POST["driver_id"];
    $bydrivername=$_POST["bydriverName"];
    $daterequired=$_POST["o_startdate"];
    $clck=$_POST["clockt"];
}

if(isset($_POST["clock"])){
    $clck= $_POST["clock"];
}
if(isset($_POST["dateValue"])){
    $daterequired=$_POST["dateValue"];
}

$cancelalldate=getcancelalldate($daterequired,$clck);
if($cancelalldate!=null){
    $cancellall= true;
}

$timestamp = strtotime($daterequired);
$day = date('l', $timestamp);
$query ="select * from  
 (select * from lpr_triplog where triplog_date='$daterequired' and triplog_clock='$clck')R1
  RIGHT outer join
 (select * from ( 
       (select t1.o_id,t1.o_status,t1.school_id,t1.driver_id,t1.client_id,t1.s_id,t1.school_city,t1.time,t1.s_fname,t1.s_city,t1.o_ampicktime as picktime,t1.o_ampickloc as pickloc,t1.o_amdroptime as droptime,'AM' as clockperiod, t1.o_days,t1.o_startdate,t1.o_enddate from ( 
           select lpr_order.o_id,lpr_order.o_status ,lpr_school.school_id,lpr_order.client_id,lpr_student.s_id,lpr_order.driver_id, lpr_school.school_city,lpr_order.o_ampicktime as time,GROUP_CONCAT(CONCAT(lpr_student.s_fname,' ',lpr_student.s_lname) order by CONCAT(lpr_student.s_fname,' ',lpr_student.s_lname)) as s_fname,lpr_student.s_city,lpr_order.o_ampicktime,lpr_order.o_ampickloc,lpr_order.o_amdroptime, lpr_order.o_days,lpr_order.o_startdate,lpr_order.o_enddate from 
           lpr_order,lpr_school,lpr_student where lpr_order.o_id=lpr_student.o_id and lpr_order.school_id=lpr_school.school_id and o_ampickloc not like 'null' group by lpr_order.o_id)t1 )
        UNION 
       (select t1.o_id,t1.o_status,t1.school_id,t1.driver_id,t1.client_id,t1.s_id,t1.school_city,t1.time,t1.s_fname,t1.s_city,t1.picktime,t1.pickloc,t1.droptime,'PM' as clockperiod, t1.o_days,t1.o_startdate,t1.o_enddate  from 
          (select lpr_order.o_id,lpr_order.o_status,lpr_school.school_id,lpr_order.client_id,lpr_student.s_id,lpr_order.pm_driver_id as driver_id, lpr_school.school_city,lpr_order.o_pmpicktime as time,GROUP_CONCAT(CONCAT(lpr_student.s_fname,' ',lpr_student.s_lname) order by CONCAT(lpr_student.s_fname,' ',lpr_student.s_lname) ) as s_fname,lpr_student.s_city,lpr_order.o_pmpicktime as picktime,lpr_order.o_pmpickloc as pickloc,'NA' as droptime,lpr_order.o_days,lpr_order.o_startdate,lpr_order.o_enddate from 
           lpr_order,lpr_school,lpr_student where lpr_order.o_id=lpr_student.o_id and lpr_order.school_id=lpr_school.school_id and o_pmdroploc not like 'null' group by lpr_order.o_id)t1))R
  )R2  on  R1.triplog_o_id=R2.o_id
  left join
 (select lpr_student.o_id,COUNT(lpr_student.s_id) as pax from lpr_student group BY lpr_student.o_id)R3   
  on R2.o_id=R3.o_id 
  left join (select CONCAT(driver_fname,' ',driver_lname) as driver_name,driver_id as driverid from lpr_driver)R4
  on R2.driver_id=R4.driverid
  where clockperiod='$clck' and o_startdate <='$daterequired' and o_enddate >='$daterequired' and o_days like '%$day%' and o_status not like 'inactive'";
if(!empty($byBriverId)){
    $query .=" and R2.driver_id=$byBriverId";
}
$query.=" ORDER BY time";
//error_log("\nManifest" . $query , 3, "C:/xampp/apache/logs/error.log");
$result_triporder = mysqli_query($connection, $query);
confirm_query($result_triporder);
$result_triplogdata = getAllTripData();


$query_print=
    "select * from (
(select o_id,CASE WHEN triplog_time is null then picktime else triplog_time end as picktime,o_wc,o_fd,o_bs,o_cs,CASE WHEN did is null then driver_id else did end as driver_id,o_startdate,o_enddate,pickloc,droploc,clockP,o_days,o_dcomment,o_icomment,o_payable,o_tip,o_ra,client_name,student_name,s_pname,s_phone,s_altphone,triplog_o_id,triplog_date,triplog_status,triplog_clock,triplog_driver_id,oid,did,start_date,end_date,period from (select * from (select * from  (select * from
(select * from (SELECT lpr_order.o_id,o_wc,o_fd,lpr_order.o_bs,lpr_order.o_cs,lpr_order.driver_id,lpr_order.o_startdate,lpr_order.o_enddate,o_ampickloc as pickloc,o_ampicktime as picktime,concat(o_amdroploc,', ',school_name) as droploc,'AM' as clockP,lpr_order.o_days,lpr_order.o_dcomment,lpr_order.o_icomment,lpr_order.o_payable,lpr_order.o_tip,lpr_order.o_ra,lpr_client.client_name,GROUP_CONCAT(concat(lpr_student.s_fname,' ',lpr_student.s_lname) order by concat(lpr_student.s_fname,' ',lpr_student.s_lname)) as student_name,concat(s_pfname,' ',s_plname) as s_pname,s_phone,s_altphone
FROM lpr_order,lpr_client,lpr_student,lpr_school where lpr_order.o_reqby=lpr_client.client_id and lpr_order.o_id=lpr_student.o_id and lpr_school.school_id=lpr_order.school_id and o_startdate <='$daterequired' and o_enddate >='$daterequired' and o_days like '%$day%' and lpr_order.o_status in ('active') group by o_id ) t1
left join
(select triplog_o_id,triplog_time,triplog_date,triplog_status,triplog_clock,triplog_driver_id from  lpr_triplog  where triplog_clock='AM' group by triplog_o_id,triplog_date ) t2 on t1.o_id=t2.triplog_o_id and t2.triplog_date='$daterequired') t3 
where t3.triplog_status is null or t3.triplog_status not like '%cancel%' ) t4 where pickloc not like 'NULL')d1 left join (select o_id oid,driver_id  did,start_date,end_date,period from lpr_driver_contract)d2  on d1.o_id=d2.oid and d1.clockP=d2.period and (('$daterequired' between d2.start_date and
d2.end_date) or ('$daterequired'>= d2.start_date and d2.end_date is null) ) )a1)
union 
(select o_id,CASE WHEN triplog_time is null then picktime else triplog_time end as picktime,o_wc,o_fd,o_bs,o_cs,CASE WHEN did is null then driver_id else did end as driver_id,o_startdate,o_enddate,pickloc,droploc,clockP,o_days,o_dcomment,o_icomment,o_payable,o_tip,o_ra,client_name,student_name,s_pname,s_phone,s_altphone,triplog_o_id,triplog_date,triplog_status,triplog_clock,triplog_driver_id,oid,did,start_date,end_date,period from (select * from(select * from(select * from
(select * from (SELECT lpr_order.o_id,o_wc,o_fd,lpr_order.o_bs,lpr_order.o_cs,lpr_order.pm_driver_id as driver_id,lpr_order.o_startdate,lpr_order.o_enddate,concat(o_pmpickloc,', ',school_name) as pickloc,o_pmpicktime as picktime,o_pmdroploc as droploc,'PM' as clockP,lpr_order.o_days,lpr_order.o_dcomment,lpr_order.o_icomment,lpr_order.o_payable,lpr_order.o_tip,lpr_order.o_ra,lpr_client.client_name,GROUP_CONCAT(concat(lpr_student.s_fname,' ',lpr_student.s_lname) order by concat(lpr_student.s_fname,' ',lpr_student.s_lname)) as student_name,concat(s_pfname,' ',s_plname) as s_pname,s_phone,s_altphone
FROM lpr_order,lpr_client,lpr_student,lpr_school where lpr_order.o_reqby=lpr_client.client_id and lpr_order.o_id=lpr_student.o_id and lpr_school.school_id=lpr_order.school_id and o_startdate <='$daterequired' and o_enddate >='$daterequired' and o_days like '%$day%' and lpr_order.o_status in ('active') group by o_id ) t1
left join
(select triplog_o_id,triplog_time,triplog_date,triplog_status,triplog_clock,triplog_driver_id from  lpr_triplog  where triplog_clock='PM' group by triplog_o_id,triplog_date ) t2 on t1.o_id=t2.triplog_o_id and t2.triplog_date='$daterequired') t3 
where t3.triplog_status is null or t3.triplog_status not like '%cancel%' ) t4 where droploc not like 'NULL')d1 left join (select o_id oid,driver_id  did,start_date,end_date,period from lpr_driver_contract)d2 on d1.o_id=d2.oid and d1.clockP=d2.period and (('$daterequired' between d2.start_date and
d2.end_date) or ('$daterequired'>= d2.start_date and d2.end_date is null) ))a1))u1  left join lpr_driver on lpr_driver.driver_id=u1.driver_id";

if(!empty($byBriverId)){
    $query_print .=" where u1.driver_id=$byBriverId";
}
$query_print .=" order by driver_fname,picktime";
//error_log("\nManifest print query " . $query_print , 3, "C:/xampp/apache/logs/error.log");
$result_tripPrint = mysqli_query($connection, $query_print);
confirm_query($result_tripPrint);
?>
<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>



<style type="text/css">
    .toprint
    { display: none; }

    .button1 {
        display: block;
        width: 115px;
        height: 25px;
        background: #4E9CAF;
        padding: 10px;
        text-align: center;
        border-radius: 5px;
        color: white;
        font-weight: bold;
    }
</style>

<style type="text/css" media="print">
    .dontprint
    { display: none; }
    .toprint
    { display: inline;
    }
    .to4Columns {
        -webkit-column-count: 4; /* Chrome, Safari, Opera */
        -moz-column-count: 4; /* Firefox */
        column-count: 4;
    }
    .to3Columns {
        -webkit-column-count: 3; /* Chrome, Safari, Opera */
        -moz-column-count: 3; /* Firefox */
        column-count: 3;
        -webkit-column-fill: balance; /* Chrome, Safari, Opera */
        -moz-column-fill: balance; /* Firefox */
        column-fill: balance;
    }
    .to2Columns {
        -webkit-column-count: 2; /* Chrome, Safari, Opera */
        -moz-column-count: 2; /* Firefox */
        column-count: 2;
    }
    .gap {
        -webkit-column-gap: 10px; /* Chrome, Safari, Opera */
        -moz-column-gap: 10px; /* Firefox */
        column-gap: 10px;
    }
    .gap2 {
        -webkit-column-gap: 30px; /* Chrome, Safari, Opera */
        -moz-column-gap: 30px; /* Firefox */
        column-gap: 30px;
    }
    @page {
        size: auto;   /* auto is the initial value */
        margin:.5cm;
        /* this affects the margin in the printer settings */
    }
    /*body { margin: 20mm 25mm 20mm 25mm; }*/
    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
    /*.print:last-child {*/
    /*page-break-after: auto;*/
    /*}*/
    table, th, td {
        border: 1px solid black;
    }
    .dinline{display:inline-block;}
    .hschool {
        line-height: 0;
        text-align: right;
    }
    .hspace{
        margin-top: 3px
    }
    .inborder{
        border-style: solid;
        padding-top: 10px;
      }
    .s2height{
        font-family: Georgia, "Times New Roman", Times, serif;
        line-height: 0.3em;
        padding-top: 5px;
        padding-bottom: 8px;
    }
    .s1height{
        line-height: 0.2em;
        font-family: Georgia, "Times New Roman", Times, serif;
        padding-bottom: 50px;
        text-align: center;
    }
    .s3height{
        padding-left: 15px;
        padding-bottom: 0;
        line-height: 0.2em;
    }
    .touppercase{
        text-transform: uppercase;
    }
    .elementleft{
      padding-left: 10px;
        padding-top: 0px;
  }
    .s_address{
        padding-left: 5px;
    }
    .s_border{
        border-top: 2px dotted;
    }
    .sheetf {
        font-family:  Arial, Helvetica, sans-serif;
        font-size: 14px !important;
    }

    .sheetText{
        display: table-cell;
        width: 50px;
    }
    .sheetUnderline{
        display: table-cell; border-bottom: 1px solid black; width:700px
    }

    .elementPadding{
        padding-bottom: 15px !important;
    }

</style>


<?php

//$all_data[]='';
//while ($row = mysqli_fetch_array($result_tripPrint, MYSQLI_NUM)) {
//    $all_data[] = $row;
//}

include("./includes/manifestPrint.php");

?>


<div class="dontprint">
<div id="page-wrapper">
    <div class="container-fluid">

    </div>
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Manifest</h1>
                <form class="form-horizontal" action="manifest.php" method="post">
                <div>
                    <div class="container-fluid">
                        <div class="row">
                        <div class="col-lg-8" style="padding-left: 0">
                            <select name="clockt" class="form-control" id="clock" onchange="getdata(id,this)" style="width:100px;">
                                    <option value="AM" <?php if($clck == 'AM'): ?> selected="selected"<?php endif; ?> >AM</option>
                                    <option value="PM" <?php if($clck == 'PM'): ?> selected="selected"<?php endif; ?>>PM</option>
                            </select>
                        </div>
                            <div class="col-lg-4 pull-right">
                            <button type="button" class="btn btn-primary btn-lg col-sm-6" onclick="printSheet();" style="display:inline;margin-right: 2px">
                                 Print Contractor Sheets
                             </button>
                                <button type="button" class="btn btn-primary btn-lg col-sm-3" onclick="togglebtnstat(this);" style="display:inline;">
                                    Cancel All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" style="width: 10%;" >
                    <div class="input-group" style="padding-left: 10px">
                        <input type="text" name="o_startdate" class="form-control" id="manifestdate" placeholder="Select Date" value="<?php echo $daterequired; ?>" required><span class="input-group-addon"><i class="glyphicon glyphicon-th" ></i></span>
                    </div>
                </div>
                <div>
                        <div class="form-group">
                            <label class="control-label col-sm-1" style="text-align: center">By Driver   :</label>
                            <div class="col-sm-3">
                                <div class="input-group"">
                                    <input id="drivername" name="bydriverName" required class="form-control typeahead" placeholder="" style="width: 400px;" value="<?php echo $bydrivername;?>">
                                    <input class="form-control" id="driver_id" name="driver_id" type="hidden" placeholder="">
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <button type="submit" id="driverSearch" name="searchByDriverName" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                </div>
                </form>
                <br>

                <div class="panel panel-default">

                    <div class="panel-heading">
                        Striped Rows
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <input type="hidden" id="cancelbtnclick" data-date="<?php echo $daterequired;?>" data-clck="<?php echo $clck;?>">
                        <div class="table-responsive">
                            <table id="manifest" class="table table-striped dataTabManifest">
                                <thead>
                                <tr>
                                    <th>City</th>
                                    <th>Time</th>
                                    <th>Student Name</th>
                                    <th>Driver Name</th>
                                    <th>Pick Up Loc</th>
                                    <th>Pick Up Time</th>
                                    <th>Drop Off Time</th>
                                    <th>Pax</th>
                                    <th>Trip Status</th>
                                    <th>Edit Trip</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Use returned data (if any)
                                while($subject_tripdata = mysqli_fetch_assoc($result_triporder)) {
                                    // output data from each row
                                    ++$c;
                                      if($subject_tripdata["picktime"]!= NULL && trim($subject_tripdata["picktime"])!=''){  
                                      //  echo "<script type='text/javascript'>alert('$totriplogdata'+'inside');</script>";
                                        if ($subject_tripdata['triplog_status'] != NULL) {
                                            // $sname=get_studentname($subject_tripdata["triplog_studentid"]);
                                            $drivername=get_drivername($subject_tripdata["triplog_driver_id"]);
                                            ?>
                                            <tr id ="<?php echo $subject_tripdata["triplog_o_id"]; ?>">
                                                <td class="col-xs-1" headers ="city">
                                                    <?php echo $subject_tripdata["triplog_city"]; ?>
                                                </td>
                                                <td class="col-xs-1" headers ="time"><?php echo $subject_tripdata["triplog_time"]; ?></td>
                                                <td class="col-xs-1"><?php echo $subject_tripdata["s_fname"]; ?></td>
                                                <td class="col-xs-1" headers ="dname"><?php echo $drivername["driver_name"]; ?></td>
                                                <td class="col-xs-1" headers ="pickloc"><?php echo $subject_tripdata["triplog_pickloc"]; ?></td>


<!--                                                 <td class="col-xs-1" headers ="picktime"><?php echo $subject_tripdata["triplog_picktime"]; ?>
                                                    <span class="input-group-clock" style="color: #68ca1b;"
                                                          id="<?php echo $c.$c.$c.$c; ?>"><i
                                                            class="glyphicon glyphicon-time"></i></span></td> -->

                                                <td class="col-xs-1" headers ="picktime"><span><?php echo $subject_tripdata["triplog_picktime"]; ?></span>
                                                    <span class="input-group-clock" 
                                                      <?php if($subject_tripdata['triplog_status'] != 'success' && $subject_tripdata['triplog_status'] != 'pending'){?>
                                                          style="color: #bc2328;"
                                                          id="<?php echo $c.$c.$c.$c; ?>"
                                                          onclick="setColor(event,id,this)" ;>
                                                      <?php } else { echo ('style="color: #68ca1b;" <i class="glyphicon glyphicon-time"></i>');}?>
                                                          <i class="glyphicon glyphicon-time"></i></span></td>

                                                <td class="col-xs-1" headers ="droptime"><span><?php echo $subject_tripdata["triplog_droptime"]; ?></span>
                                                    <span class="input-group-clock" 
                                                      <?php if($subject_tripdata['triplog_status'] != 'success'){?>
                                                          style="color: #bc2328;"
                                                          id="<?php echo $c.$c.$c.$c.$c; ?>"
                                                          onclick="setColor(event,id,this)" ;>
                                                      <?php } else { echo ('style="color: #68ca1b;" <i class="glyphicon glyphicon-time"></i>');}?>
                                                          <i class="glyphicon glyphicon-time"></i></span></td>



                                                <td class="col-xs-1" headers ="pax"><?php echo $subject_tripdata["triplog_pax"]; ?></td>
                                                <td class="col-xs-1">
                                                    <?php if($subject_tripdata['triplog_status'] == 'success'){ ?>
                                                       <button type="button" class="btn btn-success btnstat">Success</button>
                                                    <?php } if($subject_tripdata['triplog_status'] == 'pending'){?>
                                                        <button type="button" class="btn btn-warning btnstat">Pending</button>
                                                    <?php } if($subject_tripdata['triplog_status'] == 'cancel'){?>
                                                        <button type="button" class="btn btn-primary btnstat">Cancelled</button>
                                                    <?php } if($subject_tripdata['triplog_status'] == 'noshow'){ ?>
                                                        <button type="button" class="btn btn-danger btnstat">No Show</button>
                                                    <?php } if($subject_tripdata['triplog_status'] == 'none'){ ?>
                                                        <button type="button" class="btn btn-warning btnstat">Pending</button>
                                                      <?php } ?>
                                                </td>
                                                <td class="col-xs-1">
                                                    <input type="checkbox" name="bill-checkbox" data-on-text="Edit" data-off-text="Save" checked>
                                                    
                                                </td>
                                                <td class="col-xs-1">
                                                <?php if($subject_tripdata['triplog_status'] == 'noshow'){ ?>
                                                <span class="noshow size2" style="color: #bc2328;" id ="<?php echo uniqid(); ?>"><i class="fa fa-male" aria-hidden="true"></i></span>
                                                <?php } else {?>
                                                <span class="noshow size2" id ="<?php echo uniqid(); ?>"><i class="fa fa-male" aria-hidden="true"></i></span>
                                                <?php } ?>

                                                <?php if($subject_tripdata['triplog_status'] == 'cancel'){ ?>
                                                <span class="cancel size2 pad" style="color: #bc2328;" id ="<?php echo uniqid(); ?>"><i class="fa fa-times-circle" aria-hidden="true"></i></span>
                                                <?php } else {?>
                                                <span class="cancel size2 pad" id ="<?php echo uniqid(); ?>"><i class="fa fa-times-circle" aria-hidden="true"></i></span>
                                                <?php } ?>
                                                 <span class="delete size2 pad" id ="<?php echo uniqid(); ?>" data-trip_id="<?php echo $subject_tripdata["triplog_tripid"]; ?>"><i class="fa fa-trash" aria-hidden="true"></i></span>
                                                </td>

                                                
                                                <input type="hidden" name="" data-orderid="<?php echo $subject_tripdata["triplog_o_id"]; ?>" data-schoolid="<?php echo $subject_tripdata["triplog_school_id"]; ?>" data-driverid="<?php echo $subject_tripdata["triplog_driver_id"]; ?>" data-clientid="<?php echo $subject_tripdata["triplog_client_id"]; ?>" data-sid="<?php echo $subject_tripdata["triplog_studentid"]; ?>" data-updated="true" data-trip_id="<?php echo $subject_tripdata["triplog_tripid"]; ?>" data-trip_period="<?php echo $subject_tripdata["clockperiod"]; ?>" data-trip_status = "<?php echo $subject_tripdata["triplog_status"]; ?>" data-trip_date="<?php echo $daterequired; ?>" >
                                            </tr>



                                <?php        } else {    
                                                    //error_log("\nInside else" . $subject_tripdata["o_id"] , 3, "C:/xampp/apache/logs/error.log");
                                                    ?>

                                            <tr>
                                                <td class="col-xs-1" headers ="city">

                                                    <?php
                                                    if($clck=='AM') {
                                                        echo $subject_tripdata["s_city"];
                                                    }
                                                    if($clck=='PM'){
                                                         echo $subject_tripdata["school_city"];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="col-xs-1" headers ="time"><?php echo $subject_tripdata["time"]; ?></td>
                                                <td class="col-xs-1"><?php echo $subject_tripdata["s_fname"]; ?></td>
                                               <?php
                                            $driverMid=getDriverForM( $subject_tripdata["o_id"], $subject_tripdata["clockperiod"],$daterequired);
                                               ?>
                                                <td class="col-xs-1" headers ="dname"><?php
                                                    if(!empty($driverMid))
                                                    {
                                                        $drivername=get_drivername($driverMid);
                                                       echo $drivername["driver_name"];
                                                    }
                                                    else {
                                                    echo $subject_tripdata["driver_name"]; }
                                                    ?>
                                                </td>
                                                <td class="col-xs-1" headers ="pickloc"><?php echo $subject_tripdata["pickloc"]; ?></td>
                                                <td class="col-xs-1" headers ="picktime" ><span><?php echo $subject_tripdata["picktime"]; ?></span>
                                                    <span class="input-group-clock" style="color: #bc2328;"
                                                          id="<?php echo $c; ?>" data-updated="false"
                                                          onclick="setColor(event,id,this)" ;><i
                                                            class="glyphicon glyphicon-time"></i></span></td>
                                                <td class="col-xs-1" headers ="droptime"><span><?php echo $subject_tripdata["droptime"]; ?></span>
                                                    <span class="input-group-clock" style="color: #bc2328;"
                                                          id="<?php echo $c.$c.$c; ?>"><i
                                                            class="glyphicon glyphicon-time"></i></span></td>
                                                <td class="col-xs-1" headers ="pax"><?php echo $subject_tripdata["pax"]; ?></td>
                                                <td class="col-xs-1">
                                              <?php
                                                   if($cancellall==true){ ?>
                                                       <button type="button" class="btn btn-primary btnstat">Cancelled</button>
                                                      <?php }else{?>
                                                     <button type="button" class="btn btn-warning btnstat">Pending</button>
                                                       <?php } ?>
                                                </td>
                                                <td class="col-xs-1">
                                                    <input type="checkbox" name="bill-checkbox" data-on-text="Edit" data-off-text="Save" checked>
                                                </td>
                                                <td class="col-xs-1"><span class="noshow size2" id ="<?php echo uniqid(); ?>"><i class="fa fa-male" aria-hidden="true"></i></span><span class="cancel size2 pad" id ="<?php echo uniqid(); ?>"><i class="fa fa-times-circle" aria-hidden="true"></i></span></td>
                                                <input type="hidden" name="" data-orderid="<?php echo $subject_tripdata["o_id"] ?>" data-schoolid="<?php echo $subject_tripdata["school_id"]; ?>" data-driverid="<?php  if(!empty($driverMid)) {echo $driverMid;} else {echo $subject_tripdata["driver_id"]; }?>" data-clientid="<?php echo $subject_tripdata["client_id"]; ?>" data-sid="<?php echo $subject_tripdata["s_id"]; ?>" data-updated="false" data-trip_id="0" data-trip_period="<?php echo $subject_tripdata["clockperiod"]; ?>" data-trip_status ="none" data-trip_date="<?php echo $daterequired; ?>">
                                            </tr>


                               <?php         }
                             }
                                        ?>


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
                <!-- /.panel -->

            </div>
            <!-- /.col-lg-12 -->
            <div id="dialog-confirm" title="Is driver payable?">
                <div>

            <div id="dialog-confirm2" title="Is client billable?" class="ui-helper-hidden">
                <div class="checkbox">
              <label><input type="checkbox" name="check_driver" value="">Driver Payable</label>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" name="check_client" value="">Client Billable</label>
            </div>
            </div>

        </div>
        <!-- /.row -->
    </div>

    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<?php
require_once("./includes/footer.php");
?>
</div>