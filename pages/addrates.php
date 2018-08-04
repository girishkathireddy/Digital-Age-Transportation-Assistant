<?php
include("./includes/db_connection.php");
include("./includes/functions.php");
?>


<?php
$query_zone  = "SELECT * FROM lpr_zones";
$result_zone = mysqli_query($connection, $query_zone);

?>

<?php
include("./includes/htmlheader.php");
include("./includes/nav.php");
?>

<div id="page-wrapper">
    <div class="container-fluid">
        <form method="post" enctype="multipart/form-data" id="rates_zones" action="rates.php" name="rates_zones" class="form-horizontal">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">Add Rates</h1>
                <div class="form-group">
                    <label>Zone-Type</label>
                    <select class="form-control " id="zone_id_rates" name="zone_name" required style="width:400px">
                        <?php
                        // 3. Use returned data (if any)
                        while($subject_zone = mysqli_fetch_assoc($result_zone)) {
                            // output data from each row
                            ?>
                            <option value="<?php echo $subject_zone["zone_id"]; ?>" ><?php echo $subject_zone["zone_loc"]; ?></option>
                            <?php
                        }
                        ?>

                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Amount</label>
                    <div>
                        <input class="form-control" type="number" step="any" placeholder="Enter Amount" name="rate_amount" required value="0" style="width:400px">
                        <p class="help-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label>Select item</label>
                    <select class="form-control" style="width:400px" name="rate_item">
                        <option value="inzone">Inzone</option>
                        <option value="outzone">Outzone</option>
                        <option value="wheelchair-in">Wheelchair-Inzone</option>
                        <option value="wheelchair-out">Wheelchair-OutZone</option>
                    </select>
                </div>



            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-lg"  style="margin-top: 20px" name="rates_submit">Submit</button>
                </div>
            </div>
        </div>

        </form>

    </div>     <!-- /Container Fluid -->
</div>  <!-- /Page Wrapper -->






















<?php
require_once("./includes/footer.php");
?>
