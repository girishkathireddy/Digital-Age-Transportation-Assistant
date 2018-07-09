<?php
include("../includes/db_connection.php");
include("../includes/functions.php");
?>
<?php

if(isset($_POST['myData'])) {
    $obj = $_POST['myData'];
    $transactionid = $obj["t_id"];
    delete_catransaction($transactionid);
}



?>