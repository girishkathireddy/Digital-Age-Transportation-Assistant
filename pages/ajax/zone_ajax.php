<?php
include("../includes/db_connection.php");
include("../includes/functions.php");
?>
<?php

if(isset($_POST['myData'])) {
    $obj = $_POST['myData'];
    $zone_loc = $obj["zoneloc"];
    addZone($zone_loc);
}

if(isset($_POST['updateZone'])) {
    $obj = $_POST['updateZone'];
    $zone_loc = $obj["zoneloc"];
    $zone_id = $obj["zoneid"];
    updateZone($zone_loc,$zone_id);

}

?>p