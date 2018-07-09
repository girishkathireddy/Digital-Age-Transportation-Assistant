<?php
include("../includes/db_connection.php");
include("../includes/functions.php");
?>
<?php


if(isset($_POST['myData'])) {
    $obj = $_POST['myData'];
    $zoneid = $obj['zone'];
    $item = $obj['item'];
    $amount = $obj['amount'];
    insertRate($zoneid, $item, $amount);
}

if(isset($_POST['r_ChangeData'])) {
    $obj = $_POST['r_ChangeData'];
    $rateId = $obj['rateid'];
    $rate = $obj['rate'];
    updateRate($rateId,$rate);
}
?>