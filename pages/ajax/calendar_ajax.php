<?php
include("../includes/db_connection.php");
include("../includes/functions.php");
?>
<?php
error_log("Get event\n", 3, "C:/xampp/apache/logs/error.log");
$result_events = get_event();
$events = array();

 while ($row = mysqli_fetch_assoc($result_events)) {

        $e = array();
        $e['title'] = $row['title']." ".$row['client_abr'];
        $e['start'] = $row['startdate'];
        $e['end'] = $row['enddate'];
        $e['allDay'] = true;

        // Merge the event array into the return array
        array_push($events, $e);

    }

    // Output json for our calendar
    echo json_encode($events);
    exit();
?>