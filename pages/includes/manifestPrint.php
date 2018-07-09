
<?php    while($sheets = mysqli_fetch_assoc($result_tripPrint)) {  ?>
    <div class="toprint" style="page-break-before: always;">

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h6 style="text-align: right;padding-right: 100px;padding-top: 20px"> <?php if(!empty($sheets['o_icomment'])){?> *<?php echo $sheets['o_icomment']; ?> *<?php }?>  </h6>
                        <img src="../images/bus1.jpg" alt="Bus" style="width:150px;height:100px;">
                        <h1 class="s1height"><span>LPR TRANSPORTATION</span></h1>
                    </div>
                </div>
                <div style="margin-left: 20px">
                    <?php
                    $pieces_name= explode(",", $sheets['student_name']);
                    $Pax=count($pieces_name); ?>
                    <div class="row" style="padding-bottom: 15px">
                        <span class="sheetText">Name:</span> <span class="sheetUnderline"><?php echo $sheets['student_name']; ?></span>
                    </div>
                    <div class="row" style="padding-bottom: 15px">
                        <span class="sheetText" style="width:100px ">Date Needed:</span><span  class="sheetUnderline"> <?php echo date("F j, Y", strtotime($daterequired));  ?></span>
                    </div>
                    <div class="row" style="padding-bottom: 15px">
                        <span class="sheetText" style="width:100px ">Time Needed:</span><span  class="sheetUnderline" style="width:300px "> <?php echo date ('H:i',strtotime($sheets['picktime'])) ;  ?></span>
                        <span class="sheetText" style="width:100px ">#Passengers:</span><span  class="sheetUnderline" style="width:300px "><?php echo $Pax;  ?></span>
                    </div>
                    <div class="row" style="padding-bottom: 15px">
                        <span class="sheetText" style="width:100px ">Phone:</span> <span  class="sheetUnderline"><span> <?php echo $sheets['s_pname']; ?></span><span> <?php echo $sheets['s_phone']; ?></span>/<span> <?php echo $sheets['s_altphone']; ?></span> </span>
                    </div>
                    <div class="row" style="padding-bottom: 25px">
                        <span class="sheetText" style="width:150px ">Pick up Address:</span> <span  class="sheetUnderline" ><?php echo $sheets['pickloc']; ?></span>
                    </div>
                    <div class="row" style="padding-bottom: 25px">
                        <span  class="sheetUnderline" style="width:800px"></span>
                    </div>
                    <div class="row" style="padding-bottom: 35px">
                        <span  class="sheetUnderline" style="width:800px"></span>
                    </div>
                    <div class="row" style="padding-bottom: 25px">
                        <span class="sheetText" style="width:150px ">Drop off Address:</span> <span  class="sheetUnderline"><?php echo $sheets['droploc']; ?></span>
                    </div>
                    <div class="row" style="padding-bottom: 25px">
                        <span  class="sheetUnderline" style="width:800px"></span>
                    </div>
                    <div class="row" style="padding-bottom: 15px">
                        <span  class="sheetUnderline" style="width:800px"></span>
                    </div>
                    <div class="row" style="padding-bottom: 25px">
                        <span class="sheetText" style="width:150px ">Special Instructions:</span><span><?php echo wordwrap( $sheets['o_dcomment'],100,"<br>\n"); ?></span>
                    </div>
                    <div class="row" style="padding-bottom: 70px">
                        <span  class="sheetUnderline" style="width:800px"></span>
                    </div>
                    <div class="row" style="padding-bottom: 15px">
                        <span class="sheetText" style="width:50px ">Rate:</span> <span  class="sheetUnderline" style="width:150px "><?php echo $sheets['o_payable']; ?></span>
                        <span class="sheetText" style="width:60px;padding-left: 100px;padding-right: 10px ">Driver:</span> <span  class="sheetUnderline" style="width:400px ">
                            <?php if(!empty($sheets['driver_fname'])) {echo $sheets['driver_fname'];?> <?php echo $sheets['driver_lname'];}
                            else echo "";?></span>
                    </div>
                    <div class="row" style="padding-bottom: 30px">
                        <span class="sheetText" style="width:50px ">Tip:</span> <span  class="sheetUnderline" style="width:150px "><?php echo $sheets['o_tip']; ?></span>
                        <span class="sheetText" style="width:60px;padding-left: 100px;padding-right: 10px ">Assign:</span><span  class="sheetUnderline" style="width:400px "><?php echo $sheets['client_name']; ?></span>
                    </div>
                    <div class="row" style="padding-bottom: 15px">
                        <span class="sheetText" style="width:200px ">Car Seat Required:</span> <span  class="sheetUnderline" style="width:100px;text-align: left;padding-right: 10px "><?php if($sheets['o_cs']=="TRUE"){ echo 'YES';}else { echo 'NO';} ?></span>
                        <span class="sheetText" style="width:200px;padding-left: 10px ">  Booster Seat Required:</span> <span  class="sheetUnderline" style="width:100px;text-align: left "><?php if($sheets['o_bs']=="TRUE"){ echo 'YES';}else { echo 'NO';} ?></span>
                        <span class="sheetText" style="width:200px;padding-left: 20px ">Ride Along Required:</span><span  class="sheetUnderline" style="width:100px "><?php if($sheets['o_ra']=="TRUE"){ echo 'YES';}else { echo 'NO';} ?></span>
                    </div>
                    <div class="row" style="padding-bottom: 15px">
                        <span class="sheetText" style="width:200px ">Wheel Chair Required:</span> <span  class="sheetUnderline" style="width:150px "><?php if($sheets['o_wc']=="TRUE"){ echo 'YES';}else { echo 'NO';} ?></span>
                        <span class="sheetText" style="width:200px;padding-left: 50px;padding-right: 10px ">Female Driver Only:</span><span  class="sheetUnderline" style="width:150px "><?php if($sheets['o_fd']=="TRUE"){ echo 'YES';}else { echo 'NO';} ?></span>
                    </div>

                </div>
            </div>
        </div>




        <div style="page-break-after: always">


        </div>

    </div>

<?php  } ?>