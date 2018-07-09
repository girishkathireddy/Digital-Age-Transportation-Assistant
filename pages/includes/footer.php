
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
<!--     <script src="../vendor/jquery/jquery.min.js"></script> -->
    <script src="../vendor/jquery-3.2.1.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>


    <script src="../dist/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/dataTables.buttons.min.js"></script>
    <script src="../dist/js/buttons.print.min.js"></script>


    <script src="./src/clockpicker.js"></script>
    <script src="../vendor/jquery-ui-1.12.1/jquery-ui.js"></script>
    <!-- <script src="../vendor/bootstrap-datepicker-1.6.4-dist/js/bootstrap-datepicker.js"></script> -->
    <script type="text/javascript" src="../dist/js/bootstrapValidator.js"></script>
    <script src="./src/bootstrap-switch.js"></script>

    <script src="./src/moment.min.js"></script>
    <script src="./src/fullcalendar.js"></script>
    <script src="../dist/js/bootstrap-formhelpers.min.js"></script>



    <script src="../dist/js/functions.js?v=8"></script>


    <script type="text/javascript">
        $('.clockpicker').clockpicker({
            placement: 'top',
            align: 'left',
            donetext: 'Done'
        });
        
        $('input[type="checkbox"]').click(function(){
            if($(this).attr("value")=="split"){
                $(".split").toggle();
            }
            
        });

    $('input[type="checkbox"]').click(function(){
            if($(this).attr("value")=="splitbill"){
                $(".splitbill").toggle();
            }
            
        });

        $('input[type="radio"]').click(function(){
            if($('input[name="billingradio"]:checked').data('value')=="outzone"){
                $(".splitcheckbox").show();
            }
            if($('input[name="billingradio"]:checked').data('value')=="inzone"){
                $(".splitcheckbox").hide();
            }
            
        });
        var date = new Date();
        var currentMonth = date.getMonth();
        var currentDate = date.getDate();
        var currentYear = date.getFullYear();

        $('.date').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $(function(){
            $("#to").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#manifestdate").datepicker({ dateFormat: 'yy-mm-dd' });
		   $("#addtripdate").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#fstartdate").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#fenddate").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#dbillsavedate").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#from").datepicker({ dateFormat: 'yy-mm-dd' }).bind("change",function(){
                var minValue = $(this).val();
                minValue = $.datepicker.parseDate("yy-mm-dd", minValue);
                minValue.setDate(minValue.getDate()+1);
                $("#to").datepicker( "option", "minDate", minValue );
            })
            $('.date-picker').datepicker( {
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy-mm',
                onClose: function(dateText, inst) { 
                    $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                }
            });
        });

        $("#addstudent").click(function () {
          $("#studentdetails").append('<span class="xbox" style="padding:0 0 0 0"><label>Additional Student</label><div class="form-group"> <label>First Name</label> <input class="form-control" name="s_fname" placeholder="First Name" required> <p class="help-block"></p> </div> <div class="form-group"> <label>Last Name</label> <input class="form-control" name="s_lname" placeholder="Last Name" required> <p class="help-block"></p> </div> <div class="form-group"> <label>Grade</label> <input class="form-control" name="s_grade" placeholder="Enter Grade"> </div> <div class="form-group"><label>Gender</label><select class="form-control" name="s_gender" required><option value="">Select</option><option value="Female" >Female</option><option value="Male" >Male</option><option value="Transgender" >Transgender</option><option value="Other">Other</option><option value="Not Specified">Not Specified</option></select></div> <div class="form-group"><button type="button" id ="removestudent" class="btn btn-danger">Delete Student</button></div></span>'); });

        // function removestudent() {
        //     alert("remove");
        //     $(this).siblings('.p').remove(); 
        // }
        $('body').on('click', '#removestudent', function () {
            console.log($(this).parents('.xbox'));
            $(this).parents('.xbox').remove();
        });

        // var aTags = ["Deidra Burchard ", "Paz Nealey ", "Katia Sartain ", "Mammie Baughman ", "Norbert Gularte ", "Romeo Saur ", "Bula Snelson ", "Reita Norden ", "Pete Beverly ", "Erik Esquilin ", "Oswaldo Porcaro ", "Josephina Kautz ", "Merlene Rhone ", "Mark Luckey ", "Rikki Weber ", "Gwyneth Tinajero ", "Lavonia Ryman ", "Emily Cornwall ", "Rolland Ledesma ", "Tawanda Mcnitt ", "Daniela Walford ", "Jennette Theberge ", "Jackson Holleman ", "Ezekiel Bouldin ", "Krishna Beedle ", "Bert Sandridge ", "Ines Abeyta ", "Rivka Seabolt ", "Cortney Gassett ", "Georgina Walts"];
        // $( ".typeahead" ).autocomplete({
        //     source: aTags
        // });



        </script>

</body>

</html>
<!-- <?php
  // 5. Close database connection
  if (isset($connection)) {
	  mysqli_close($connection);
	}
?> -->