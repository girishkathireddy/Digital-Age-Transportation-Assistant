<?php
include("../includes/db_connection.php");
include("../includes/functions.php");
?>
<?php
$obj = $_POST['myData'];
$driver_id = (int)$obj["driver_id"];
    $payable = $obj["cashAdvance"];
    $return = insertCashAdvance($driver_id,$payable,'credit');

?>
