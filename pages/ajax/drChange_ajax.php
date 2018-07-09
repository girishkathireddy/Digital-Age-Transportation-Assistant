<?php
include("../includes/db_connection.php");
include("../includes/functions.php");
?>
<?php
$obj = $_POST['myData'];

if ($obj["mode"]=="dr_changestatus") {
    $d_id = (int)$obj["d_id"];
    $status = $obj["status"];
    dr_changestatus($d_id,$status);
}
?>