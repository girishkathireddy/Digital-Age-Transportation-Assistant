function log(val){
	console.log(val);
}



$('.dataTab').DataTable(
    {
        dom: 'Bfrtip',
        buttons: [
            'print'
        ],
        "pageLength": 1000,
        "order": []
    }

);

$('.dataTabDrivers').DataTable(
    {
        dom: 'Bfrtip',
        buttons: [
            'print'
        ],
        "pageLength": 1000,
        "order": [[ 6, "asc" ]]
    }

);

$('.dataTabManifest').DataTable(
    {
        dom: 'Bfrtip',
        buttons: [
            'print'
        ],
        "pageLength": 1000,
         "order": [[ 1, "asc" ]]
    }

);
$('.buttons-excel, .buttons-print').each(function() {
    $(this).removeClass('dt-button');
    $(this).addClass('btn')
    $(this).addClass('btn-primary')
})


$('#ctypeSelect').change(function(){
	var client = $('#ctypeSelect').val();
	$('input[name="optradio"]').filter(function (index){
	return $( this ).attr( "value" ) === client;
	}).attr('checked',true);
});

$('#stypeSelect').change(function(){
	//log("inside click");
	var sdata = {};
	sdata['mode'] = "typedropdown";
	sdata['client_id'] = $('#ctypeSelect').val();
	sdata['school_type'] = $('#stypeSelect').val().toLowerCase();
	$.ajax({
        url: 'ajax/neworder_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            var schoolObj = $.parseJSON(data);
            append_schools(schoolObj);
            log(schoolObj);
          
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
	
});

function append_schools(myObj) {
	$("#sSelect").children().remove();
	$("#sSelect").append('<option value="">'+"Select"+'</option>');
	for (var i = 0; i < myObj.length; i++) {
		$("#sSelect").append('<option value='+myObj[i].school_id+'>'+myObj[i].school_name+'</option>');
	}
	
}


$('#sSelect').change(function(){
	//log("inside click");
	var sdata = {};
	sdata['mode'] = "schooldropdown";
	sdata['school_id'] = $('#sSelect').val();
	$.ajax({
        url: 'ajax/neworder_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            var schoolObj = $.parseJSON(data);
            fillLocation(schoolObj);
            log(schoolObj);
          
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call

});
function checknull(data){
    if (data != null) {
        return data;
    }
    else return "";
}

function fillLocation(data){
	$('#amDropLoc').val(data.school_street+" "+checknull(data.school_address)+" "+data.school_city);
	$('#pmDropLoc').val(data.school_street+" "+checknull(data.school_address)+" "+data.school_city);
}

 $('input[name="billsplit"]').change(function(element) {
    var ele = element.target;
    if(this.checked) {
        
        $(ele).siblings('input').attr('disabled',false);
    }
    else {
    	$(ele).siblings('input').attr('disabled',true);
    }
});

// $('.aocheckbox').click(function(){
// 	verifytrip();
// });
// $('input[name="billsplit"]').click(function(){
// 	verifytrip();
// });
$('a[name="calculate_bill"]').click(function(){
    log("clicked");
	verifytrip();
});
// $('#billtext').on('focus', function(){
// 	verifytrip();
// });
// $('input[name="optradio"]').click(function(){
// 	verifytrip();
// });

function verifytrip() {
	var bill_zone_id = $('input[name="optradio"]').filter(":checked").data('zone_id');
	var dest_zone_id = $('#ctypeSelect').children("option").filter(":selected").data('zone_id');
	var type = $('input[name="billingradio"]:checked').val();
	log(bill_zone_id,dest_zone_id);
	if (type=="inzone") {
		tripcost();
	}
	if (type=="outzone") {
		tripcostOutzone();
	}
}	
function tripcost() {
	var sdata = {};
	sdata['mode'] = "tripcost";
	// sdata['item'] = $('.aocheckbox:checked').map(function() {
	// 			    return this.value;
	// 			}).get().join("','");
	if ($('input[name="o_wc"]').is(":checked")==true) {
		sdata['item'] = 'wheelchair-in';
	}
	else {sdata['item'] = 'inzone';}
	
	sdata['zone_id'] = $('input[name="optradio"]').filter(":checked").data('zone_id');
	sdata['type'] = $('input[name="billingradio"]:checked').val();
	sdata['addons'] = $('.aocheckbox:checked').map(function() {
				    return this.value;
				}).get().join("','");

	$.ajax({
        url: 'ajax/neworder_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            $('#billtext').val(data);
            log(data);
          
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
}
function tripcostOutzone() {
	var sdata = {};
	sdata['mode'] = "tripcost_outzone";
	sdata['addons'] = $('.aocheckbox:checked').map(function() {
				    return this.value;
				}).get().join("','");
	sdata['zone_id'] = $('input[name="billsplit"]:checked').map(function() {
				    return this.value;
				}).get().join(",");
	sdata['type'] = $('input[name="billingradio"]:checked').val();

	if ($('input[name="o_wc"]').is(":checked")==true) {
		sdata['item'] = 'wheelchair-out';
	}
	else {sdata['item'] = 'outzone';}

	$.ajax({
        url: 'ajax/neworder_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            $('#billtext').val(data);
            log(data);
          
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
}
$( "#createorder" ).submit(function( event ) {
	var formData = $("#createorder").serializeObject();
	formData['o_days'] = $('input[name="dayscheckbox"]:checked').map(function() {
				    return this.value;
				}).get().join(",");
	formData['mode'] = "insert";
	formData['o_ampicktime'] = $("[name='o_ampicktime']").val() + ':00';
	formData['o_amdroptime'] = $("[name='o_amdroptime']").val() + ':00';
	formData['o_pmpicktime'] = $("[name='o_pmpicktime']").val() + ':00';
	event.preventDefault();
	log(formData);

	 if ((!matchsum(formData['billsplit'],formData['billsplitvalue'],formData["o_billable"],"billtext") )|| (!matchtotal(formData["o_billable"],formData["o_payable"],"o_payable") )){
	 	return false;
	 }
	 else {

	 
	$.ajax({
        url: 'ajax/neworder_ajax.php',
        type: 'post',
        data: {myData:formData},
        success: function(data) {
            //$('#billtext').val(data);
            log(data);
            $('#myModal').modal('toggle');
          
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
	}
});

$( "#updateorder" ).submit(function( event ) {
	var formData = $("#updateorder").serializeObject();
	formData['o_days'] = $('input[name="dayscheckbox"]:checked').map(function() {
				    return this.value;
				}).get().join(",");
	formData['mode'] = "update";
	// formData['o_ampicktime'] = formData['o_ampicktime'] + ':00';
	// formData['o_amdroptime'] = formData['o_amdroptime'] + ':00';
	// formData['o_pmpicktime'] = formData['o_pmpicktime'] + ':00';
    formData['o_ampicktime'] = $("[name='o_ampicktime']").val() + ':00';
    formData['o_amdroptime'] = $("[name='o_amdroptime']").val() + ':00';
    formData['o_pmpicktime'] = $("[name='o_pmpicktime']").val() + ':00';
	event.preventDefault();
	log(formData);

	 if ((!matchsum(formData['billsplit'],formData['billsplitvalue'],formData["o_billable"],"billtext") )|| (!matchtotal(formData["o_billable"],formData["o_payable"],"o_payable") )){
	 	return false;
	 }
	 else {

	 
	$.ajax({
        url: 'ajax/change_order_ajax.php',
        type: 'post',
        data: {myData:formData},
        success: function(data) {
            //$('#billtext').val(data);
            log(data);
            $("[name='modal_href']").attr('href','editorder.php?oid='+data);
            alert("Order updated! Please proceed to manifest");
          
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
	}
});


$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$('.typeahead').click(function(){
	getdrivers();
});

$('.pmtypeahead').click(function(){
    pmgetdrivers();
});

$('.typeahead_ra').click(function(){
    getridealongs();
});

$('.typeahead_student').click(function(){
    get_students();
});

function getdrivers() {

	var sdata = {};
	sdata['mode'] = "getdriver";
	$.ajax({
        url: 'ajax/neworder_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            var driverObj = $.parseJSON(data);
            log(data);

            $(".typeahead").autocomplete({
				source: driverObj,
				focus: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox
					$(this).val(ui.item.label);
				},
				select: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox and hidden field
					$(this).val(ui.item.label);
					$('input[name="driver_id"]').val(ui.item.value);
					log(ui.item.value);
				}
			});
          
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
}

function pmgetdrivers() {

    var sdata = {};
    sdata['mode'] = "getdriver";
    $.ajax({
        url: 'ajax/neworder_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            var driverObj = $.parseJSON(data);
            log(data);

            $(".pmtypeahead").autocomplete({
                source: driverObj,
                focus: function(event, ui) {
                    // prevent autocomplete from updating the textbox
                    event.preventDefault();
                    // manually update the textbox
                    $(this).val(ui.item.label);
                },
                select: function(event, ui) {
                    // prevent autocomplete from updating the textbox
                    event.preventDefault();
                    // manually update the textbox and hidden field
                    $(this).val(ui.item.label);
                    $('input[name="pm_driver_id"]').val(ui.item.value);
                    log(ui.item.value);
                }
            });
          
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
}

function getridealongs() {

    var sdata = {};
    sdata['mode'] = "getridealongs";
    $.ajax({
        url: 'ajax/neworder_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            var driverObj = $.parseJSON(data);
            log(data);

            $(".typeahead_ra").autocomplete({
                source: driverObj,
                focus: function(event, ui) {
                    // prevent autocomplete from updating the textbox
                    event.preventDefault();
                    // manually update the textbox
                    $(this).val(ui.item.label);
                },
                select: function(event, ui) {
                    // prevent autocomplete from updating the textbox
                    event.preventDefault();
                    // manually update the textbox and hidden field
                    $(this).val(ui.item.label);
                    $('input[name="ra_id"]').val(ui.item.value);
                    log(ui.item.value);
                }
            });
          
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
}

function get_students() {

    var sdata = {};
    sdata['mode'] = "get_students";
    //sdata['client_id'] = $('#ctypeSelect').children("option").filter(":selected").data('zone_id');

    $.ajax({
        url: 'ajax/neworder_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            var driverObj = $.parseJSON(data);
            log(data);

            $(".typeahead_student").autocomplete({
                source: driverObj,
                focus: function(event, ui) {
                    // prevent autocomplete from updating the textbox
                    event.preventDefault();
                    // manually update the textbox
                    $(this).val(ui.item.label);
                },
                select: function(event, ui) {
                    // prevent autocomplete from updating the textbox
                    event.preventDefault();
                    // manually update the textbox and hidden field
                    $(this).val(ui.item.label);
                    $('input[name="stu_id"]').val(ui.item.value);
                    log(ui.item.value);
                }
            });
          
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
}

$('input[name="o_ampickloc"]').on('focus', function(){
	if (this.value == ""||this.value == undefined) {
		this.value = $('input[name="street"]').val() +" "+ $('input[name="address"]').val() +" "+ $('input[name="city"]').val() +" "+ $('input[name="zipcode"]').val();
	}
});
$('input[name="o_pmdroploc"]').on('focus', function(){
	if (this.value == ""||this.value == undefined) {
		this.value = $('input[name="street"]').val() +" "+ $('input[name="address"]').val() +" "+ $('input[name="city"]').val() +" "+ $('input[name="zipcode"]').val();
	}
});

$('input[name="amcheck"]').click(function(){

if(!$('input[name="amcheck"]').is(":checked")){
    $('input[name="o_amdroploc"]').val("");
}

                $("[name='amdiv']").toggle();
            
        });
$('input[name="pmcheck"]').click(function(){

if(!$('input[name="pmcheck"]').is(":checked")){
    $('input[name="o_pmdroploc"]').val("");
}
                $("[name='pmdiv']").toggle();
            
        });
$('input[name="o_ra"]').click(function(){

                $("[name='ridealongdiv']").toggle();
            
        });

$('input[name="add_driver"]').click(function(){

                $("[name='add_driver_div']").toggle();
            
        });

// Validations

function matchsum(billsplit,billsplitvalue,bill,id) {
	var amount=0;
	if($('input[name="billingradio"]:checked').val()=='inzone')
	{return true;}

	for (i=0; i < billsplitvalue.length; i++) {
		if (parseInt(billsplitvalue[i]) > 0) { 
			amount = amount + parseFloat(billsplitvalue[i]);
		}
	}
    
	if(amount == parseFloat(bill)){
		$( '#'+id ).parent().siblings('.help-block').html("");
	return true;
	}
	else {
		$( '#'+id ).parent().siblings('.help-block').html("Distribution not equal to billed amount");
	return false;
	}
}

function matchtotal(bill,payable,id) {

	if(parseFloat(payable) > parseFloat(bill)){
		$( 'input[name='+id+']').parent().siblings('.help-block').html("More than billed amount");
		return false;
	}
	else{
		$( 'input[name='+id+']').parent().siblings('.help-block').html("");
		return true;
	}

}

//For Manifest
function gettime() {
	var now = new Date(Date.now());
	var formatted = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
	return formatted;
}
function getday() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();

	if(dd<10) {
	    dd='0'+dd
	} 

	if(mm<10) {
	    mm='0'+mm
	} 
	return (yyyy+'-'+mm+'-'+dd);
}
function gclock() {
	var gClock = '<span class="input-group-clock" style="color: #68ca1b;" data-updated="false"><i class="glyphicon glyphicon-time"></i></span>';
	return gClock;
}
function timeToSeconds(time) {
    time = time.split(/:/);
    return ((time[0] * 3600) + (time[1] * 60) + (time[2] * 1));
}

function five () {
    var triptime = $("[headers='time']").map(function() {
				    return this.innerText;
				}).get();

    if (getday() == $("[name='o_startdate']").val()) {
    	//console.log("five");
    	for (var i = 0; i < triptime.length; i++) {
    		//log(gettime()+","+triptime[i])
    		if((timeToSeconds(gettime())-timeToSeconds(triptime[i]))>420){
    			color_row(i);
    		}
    	}
    }
    

    

}
function refresh () {
    

    if ($(location).attr('href').indexOf('manifest') >=0) {
        //console.log("five");
        location.reload();
    }
    

    

}
setInterval(five, 5000);

setInterval(refresh, 60000);

function color_row(i) {
	var a = $("tr")[i+1];
    var stat = $.trim($(a).find('td:eq(8) button').text());
  //  console.log(stat);
	if ($(a).children('input').attr('data-updated') == "false" && stat!="Cancelled"){
		$(a).css('background-color','Red');
		$(a).css('color','white');
	}
	
}
  function trip_status(time1,time2) {
  	log(time1,time2);
  	if ( (timeToSeconds(time2)-timeToSeconds(time2)) > (2*3600) ){
  		return "noshow";
  	}
  	else return "success";
  }

 function setColor(event,id,th) {
 	//$(th).closest('td').html('<span>'+gettime()+'</span>'+gclock());
    // var an=th.getAttribute("data-updated");
    // property= event.target;
    // property.style.color = "#68ca1b";
    // th.setAttribute("data-updated","true");

    log($(th).parent().siblings('input').data());
    var id = $(th).attr('id');
    var sdata = {};
	
    sdata['orderid'] = $('#'+id).parent().siblings('input').data('orderid');
    sdata['clientid'] = $('#'+id).parent().siblings('input').data('clientid');
    sdata['schoolid'] = $('#'+id).parent().siblings('input').data('schoolid');
    sdata['driverid'] = $('#'+id).parent().siblings('input').data('driverid');
    sdata['s_id'] = $('#'+id).parent().siblings('input').data('sid');
    sdata['clockperiod'] = $('#'+id).parent().siblings('input').data('trip_period');
    sdata['city'] = $('#'+id).parent().siblings("[headers='city']").text();
    sdata['time'] = $('#'+id).parent().siblings("[headers='time']").text();
    sdata['pickloc'] = $('#'+id).parent().siblings("[headers='pickloc']").text();
    if ($('#'+id).parent().attr('headers') == 'picktime') {
    	sdata['picktime'] = gettime();
    	sdata['droptime'] = $('#'+id).parent().siblings("[headers='droptime']").children('span:first').text();	
    }
    if ($('#'+id).parent().attr('headers') == 'droptime') {
    	sdata['picktime'] = $('#'+id).parent().siblings("[headers='picktime']").children('span:first').text();
    	sdata['droptime'] = gettime(); 	
    }
    
   	
    sdata['pax']= $('#'+id).parent().siblings("[headers='pax']").text();

    
    sdata['current_date'] = getday();
    sdata['trip_date'] = $('#'+id).parent().siblings('input').data('trip_date');

    log($('#'+id).parent().siblings('input').data('updated'));

    if ($('#'+id).parent().siblings('input').data('updated') == false){
    sdata['mode'] = "insert_trip";
    sdata['status'] = "pending";
    log(sdata['mode']);
    $.ajax({
        url: 'ajax/manifest_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            log(data);
        	$('#'+id).parent().siblings('input').attr('data-trip_id',data);
        	$('#'+id).parent().siblings('input').attr('data-updated',"true");
        	location.reload();  
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
    }

    if ($('#'+id).parent().siblings('input').data('updated') == true){
    sdata['mode'] = "update_trip";
    sdata['trip_id'] = $('#'+id).parent().siblings('input').data('trip_id');
	    if ( $('#'+id).parent().siblings('input').data('trip_status') == "pending"||$('#'+id).parent().attr('headers') == 'droptime') {
	    	sdata['status'] = "success";
		}
		else if ( $('#'+id).parent().siblings('input').data('trip_status') == "none") {
	    	sdata['status'] = "pending";
		}
		else {
			sdata['status'] = $('#'+id).parent().siblings('input').data('trip_status')
		}
    log(sdata['mode']+sdata['trip_id']);
    $.ajax({
        url: 'ajax/manifest_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            log(data);
            location.reload();
          
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
    }


    }

$('.delete').click(function(element){
    ele = element.target;
    log($(ele).parent().data('trip_id'));
    var sdata = {};
    
    sdata['trip_id'] = $(ele).parent().data('trip_id');
    sdata['mode'] = "delete_trip";
    $.ajax({
            url: 'ajax/manifest_ajax.php',
            type: 'post',
            data: {myData:sdata},
            success: function(data) {
                location.reload();  
            },
            error: function(xhr, desc, err) {
              console.log(xhr);
              console.log("Details: " + desc + "\nError:" + err);
            }
          }); // end ajax call
});


$('.noshow').click(function(element){
	log(element.target);
	ele = element.target;
	log($(ele).parent().attr('id'));
	var id = $(ele).closest('td');
	var sdata = {};
	
    sdata['orderid'] = $(id).siblings('input').data('orderid');
    sdata['clientid'] = $(id).siblings('input').data('clientid');
    sdata['schoolid'] = $(id).siblings('input').data('schoolid');
    sdata['driverid'] = $(id).siblings('input').data('driverid');
    sdata['s_id'] = $(id).siblings('input').data('sid');
    sdata['clockperiod'] = $(id).siblings('input').data('trip_period');
    sdata['city'] = $(id).siblings("[headers='city']").text();
    sdata['time'] = $(id).siblings("[headers='time']").text();
    sdata['pickloc'] = $(id).siblings("[headers='pickloc']").text();
    sdata['picktime'] = gettime();
   	sdata['droptime'] = $(id).siblings("[headers='droptime']").children('span:first').text();
    sdata['pax']= $(id).siblings("[headers='pax']").text();

    
    sdata['current_date'] = getday();
    sdata['trip_date'] = $(id).siblings('input').data('trip_date');

	if ($(id).siblings('input').data('updated') == false){
		sdata['mode'] = "insert_trip";
	    sdata['status'] = "noshow";
	    log(sdata['mode']);
	    $.ajax({
	        url: 'ajax/manifest_ajax.php',
	        type: 'post',
	        data: {myData:sdata},
	        success: function(data) {
	            log(data);
	        	$(id).siblings('input').attr('data-trip_id',data);
	        	$(id).siblings('input').attr('data-updated',"true");
	        	location.reload();  
	        },
	        error: function(xhr, desc, err) {
	          console.log(xhr);
	          console.log("Details: " + desc + "\nError:" + err);
	        }
	      }); // end ajax call
		}

	if ($(id).siblings('input').data('updated') == true){
	    sdata['mode'] = "update_trip";
	    sdata['trip_id'] = $(id).siblings('input').data('trip_id');
	    sdata['status'] = "noshow";
	    log(sdata['mode']+sdata['trip_id']);
	    $.ajax({
	        url: 'ajax/manifest_ajax.php',
	        type: 'post',
	        data: {myData:sdata},
	        success: function(data) {
	            log(data);
	            location.reload();
	          
	        },
	        error: function(xhr, desc, err) {
	          console.log(xhr);
	          console.log("Details: " + desc + "\nError:" + err);
	        }
	      }); // end ajax call
    }


});
$('.cancel').click(function(element){
	log(element.target);
	ele = element.target;
	log($(ele).parent().attr('id'));
	var id = $(ele).closest('td');
	var sdata = {};
	
    sdata['orderid'] = $(id).siblings('input').data('orderid');
    sdata['clientid'] = $(id).siblings('input').data('clientid');
    sdata['schoolid'] = $(id).siblings('input').data('schoolid');
    sdata['driverid'] = $(id).siblings('input').data('driverid');
    sdata['s_id'] = $(id).siblings('input').data('sid');
    sdata['clockperiod'] = $(id).siblings('input').data('trip_period');
    sdata['city'] = $(id).siblings("[headers='city']").text();
    sdata['time'] = $(id).siblings("[headers='time']").text();
    sdata['pickloc'] = $(id).siblings("[headers='pickloc']").text();
    sdata['picktime'] = gettime();
   	sdata['droptime'] = $(id).siblings("[headers='droptime']").children('span:first').text();
    sdata['pax']= $(id).siblings("[headers='pax']").text();

    
    sdata['current_date'] = getday();
    sdata['trip_date'] = $(id).siblings('input').data('trip_date');


    if (getday() == $("[name='o_startdate']").val()) {
    	log((timeToSeconds(sdata['time'])-timeToSeconds(gettime())));

    		log(timeToSeconds(sdata['time'])+","+sdata['time']+","+timeToSeconds(gettime())+","+gettime());
    		if((timeToSeconds(sdata['time'])-timeToSeconds(gettime()))<7200 && ((timeToSeconds(sdata['time'])-timeToSeconds(gettime())))>0){
    			
                $( "#dialog-confirm" ).dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                    "Yes": function() {
                       sdata['driver_payable'] = "TRUE";
                       continue_cancel(id,sdata);
                    },
                    "No": function() {
                      sdata['driver_payable'] = "FALSE";
                      continue_cancel(id,sdata);
                    }
                    }
                });
			    
    		}
            else{
                sdata['driver_payable'] = "FALSE";
                sdata['client_payable'] = "FALSE";
                continue_cancel(id,sdata);
                }

    }
    else{
        sdata['driver_payable'] = "FALSE";
        sdata['client_payable'] = "FALSE";
        continue_cancel(id,sdata);
    }    



function testdialog() {
    var resp = confirm("Is driver payable?");
                if (resp == true) {
                    sdata['driver_payable'] = "TRUE";
                } else {
                    sdata['driver_payable'] = "FALSE";
                }
    // var result="";
    
    return result;
}
});

function continue_cancel(id,sdata){
    if ($(id).siblings('input').data('updated') == false){
        sdata['mode'] = "insert_trip";
        sdata['status'] = "cancel";
        log(sdata['mode']);
        $.ajax({
            url: 'ajax/manifest_ajax.php',
            type: 'post',
            data: {myData:sdata},
            success: function(data) {
                log(data);
                $(id).siblings('input').attr('data-trip_id',data);
                $(id).siblings('input').attr('data-updated',"true");
                location.reload();  
            },
            error: function(xhr, desc, err) {
              console.log(xhr);
              console.log("Details: " + desc + "\nError:" + err);
            }
          }); // end ajax call
        }

    if ($(id).siblings('input').data('updated') == true){
        sdata['mode'] = "update_trip";
        sdata['trip_id'] = $(id).siblings('input').data('trip_id');
        sdata['status'] = "cancel";
        log(sdata['mode']+sdata['trip_id']);
        $.ajax({
            url: 'ajax/manifest_ajax.php',
            type: 'post',
            data: {myData:sdata},
            success: function(data) {
                log(data);
                location.reload();
              
            },
            error: function(xhr, desc, err) {
              console.log(xhr);
              console.log("Details: " + desc + "\nError:" + err);
            }
          }); // end ajax call
    }
}
function continue_edit(sdata){

$.ajax({
            url: 'ajax/manifest_ajax.php',
            type: 'post',
            data: {myData:sdata},
            success: function(data) {
                //log(data);
                location.reload();
              
            },
            error: function(xhr, desc, err) {
              console.log(xhr);
              console.log("Details: " + desc + "\nError:" + err);
            }
          }); // end ajax call

}

$("[name='bill-checkbox']").bootstrapSwitch();
$("[name='bill-checkbox']").on('switchChange.bootstrapSwitch', function (event, state) {
	log(event.target+"with"+state);
	ele = event.target;
	//log($(ele).parent().attr('id'));
	var id = $(ele).closest('td');
	
	var sdata = {};
	if (state == true){
    sdata['orderid'] = $(id).siblings('input').data('orderid');
    sdata['clientid'] = $(id).siblings('input').data('clientid');
    sdata['schoolid'] = $(id).siblings('input').data('schoolid');
    var diver_temp = $(id).siblings('input').data('driverid');
    sdata['driverid'] = $(id).siblings("[headers='dname']").children('input[type="hidden"]').val();
    sdata['s_id'] = $(id).siblings('input').data('sid');
    sdata['clockperiod'] = $(id).siblings('input').data('trip_period');
    sdata['city'] = $(id).siblings("[headers='city']").text();
    sdata['time'] = $(id).siblings("[headers='time']").children('div').val();
    sdata['pickloc'] = $(id).siblings("[headers='pickloc']").text();
    sdata['picktime'] = gettime();
    //var dropt = $(id).siblings("[headers='picktime']").children().children('input').val();
    sdata['picktime'] = $(id).siblings("[headers='picktime']").children('div').val();
   	//sdata['droptime'] = $(id).siblings("[headers='droptime']").children().children('input').val();
    sdata['droptime'] = $(id).siblings("[headers='droptime']").children('div').val();
   	sdata['picktime'] = sdata['picktime']+ ':00';
   	sdata['droptime'] = sdata['droptime']+ ':00';
   	sdata['pax']= $(id).siblings("[headers='pax']").text();
   	sdata['current_date'] = getday();
   	sdata['trip_date'] = $(id).siblings('input').data('trip_date');
    if (sdata['driverid'] != diver_temp){
        sdata['period'] = $("[name='clockt']").children("option").filter(":selected").val();
    }
    //log(dropt);
    //sdata['pickloc'] = .children('input').val();
    log(sdata);

	    if ($(id).siblings('input').data('updated') == false){
			sdata['mode'] = "insert_trip";
		    sdata['status'] = "none";
		    log(sdata['mode']);
		    $.ajax({
		        url: 'ajax/manifest_ajax.php',
		        type: 'post',
		        data: {myData:sdata},
		        success: function(data) {
		         //    log(data);
		        	// $('#'+id).parent().siblings('input').attr('data-trip_id',data);
		        	// $('#'+id).parent().siblings('input').attr('data-updated',"true");
		        	location.reload();  
		        },
		        error: function(xhr, desc, err) {
		          console.log(xhr);
		          console.log("Details: " + desc + "\nError:" + err);
		        }
		      }); // end ajax call
			}

		if ($(id).siblings('input').data('updated') == true){
		    sdata['mode'] = "update_trip";
		    sdata['trip_id'] = $(id).siblings('input').data('trip_id');
		    sdata['status'] = $(id).siblings('input').data('trip_status');;
		    log(sdata['mode']+sdata['trip_id']);
        if (sdata['status'] == 'cancel') {
        
            if(getday() == $("[name='o_startdate']").val() && (timeToSeconds(sdata['time'])-timeToSeconds(sdata['picktime']))<7200 && (timeToSeconds(sdata['time'])-timeToSeconds(sdata['picktime']))>0){

                $( "#dialog-confirm" ).dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                    "Yes": function() {
                       sdata['driver_payable'] = "TRUE";
                       continue_edit(sdata);
                    },
                    "No": function() {
                      sdata['driver_payable'] = "FALSE";
                      continue_edit(sdata);
                    }
                    }
                });
                
                }
                else{
                    $( "#dialog-confirm2" ).dialog({
                        resizable: false,
                        height: "auto",
                        width: 400,
                        modal: true,
                        buttons: {
                        "Ok": function() {
                        if ($('input[name="check_driver"]').is(":checked")==true) {
                            sdata['driver_payable'] = "TRUE";
                        }
                        else {sdata['driver_payable'] = "FALSE";}

                       if ($('input[name="check_client"]').is(":checked")==true) {
                            sdata['client_payable'] = "TRUE";
                        }
                        else {sdata['client_payable'] = "FALSE";}
                       continue_edit(sdata);
                    }
                        }
                    });}

        }
        else{
            continue_edit(sdata);
        }
	    }
    }

	if (state == false){
        var time = $(id).siblings("[headers='time']").text().substr(0,5);
		var picktime = $(id).siblings("[headers='picktime']").children('span:first').text().substr(0,5);
   		var droptime = $(id).siblings("[headers='droptime']").children('span:first').text().substr(0,5);
		var diver_temp = $(id).siblings("[headers='dname']").text();
		 var diver_temp_id = $(id).siblings('input').data('driverid');
		log($(id).parents('tr').find('td'));
        $(id).parents('tr').find('td').eq(1).replaceWith('<td class="col-xs-1" headers="time"><div class="bfh-timepicker time" name="time" data-time="'+time+'" data-align="right"></div></td>');
		$(id).parents('tr').find('td').eq(6).replaceWith('<td class="col-xs-1" headers="droptime"><div class="bfh-timepicker pm" name="o_amtime" data-time="'+droptime+'" data-align="right"></div></td>');
		$(id).parents('tr').find('td').eq(5).replaceWith('<td class="col-xs-1" headers="picktime"><div class="bfh-timepicker am" name="o_amtime" data-time="'+picktime+'" data-align="right"></div></td>');
		$(id).parents('tr').find('td').eq(3).replaceWith('<td class="col-xs-1" headers="dname"><input class="form-control typeahead" placeholder="'+diver_temp+'"><input class="form-control" name="driver_id" type="hidden" value ="'+diver_temp_id+'"placeholder=""></td>');
		$('.clockpicker').clockpicker({
            placement: 'top',
            align: 'left',
            donetext: 'Done'

       });
        $('.bfh-timepicker').bfhtimepicker();
        $(id).siblings("[headers='time']").children('.bfh-timepicker.time').val(time);
        $(id).siblings("[headers='picktime']").children('.bfh-timepicker.am').val(picktime);
        $(id).siblings("[headers='droptime']").children('.bfh-timepicker.pm').val(droptime);
		$('.typeahead').click(function(){
			getdrivers();
		});
	}
});

//Change order


function changestatus(a){
		var ele = a;
	var par_ele = $(ele).parent();
	var id = $(ele).closest('td');
	var oid = $(id).siblings('input').data('orderid');
	//log(oid);
	if ($(ele).text() == "Active" ) {
		$(par_ele).children().remove();
		$(par_ele).append('<button type="button" class="btn btn-danger ostatus" onclick="changestatus(this)";>Inactive</button>');
		changestatus_ajax(oid,"inactive");
	}
	else{
		$(par_ele).children().remove();
		$(par_ele).append('<button type="button" class="btn btn-success ostatus" onclick="changestatus(this)";>Active</button>');
		changestatus_ajax(oid,"active");
	}

}
//Client Bill
function deleteBill(a,id){
    var sdata = {};
    sdata['mode'] = "deleteBill";
    sdata['id'] = id;
    log(sdata['mode']);
    $.ajax({
        url: 'ajax/manifest_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            $(a).parent('td').parent().remove();
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      }); // end ajax call
    
}

//Pay roll
function deletepay(a,id){
    var sdata = {};
    sdata['mode'] = "deletepayroll";
    sdata['id'] = id;
    log(sdata['mode']);
    $.ajax({
        url: 'ajax/manifest_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            $(a).parent('td').parent().remove();
        },
        error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call

}

//delete rate
function deleterate(a,id){
    var sdata = {};
    sdata['mode'] = "deleterate";
    sdata['id'] = id;
    log(sdata['mode']);
    $.ajax({
        url: 'ajax/manifest_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            $(a).parent('td').parent().remove();
        },
        error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call

}

//Pay roll
function deletepay_ra(a,id){
    var sdata = {};
    sdata['mode'] = "deletepayroll_ra";
    sdata['id'] = id;
    log(sdata['mode']);
    $.ajax({
        url: 'ajax/manifest_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            $(a).parent('td').parent().remove();
        },
        error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call

}

//Calendar

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },

            events: {
                url: 'ajax/calendar_ajax.php',
                type: 'POST', // Send post data
                error: function() {
                    alert('There was an error while fetching events.');
                }
            }

        });

function changestatus_ajax(oid,status){

		var sdata = {};
		sdata["mode"] = "changestatus";
		sdata["o_id"] = oid;
		sdata["status"] = status;
			    $.ajax({
		        url: 'ajax/change_order_ajax.php',
		        type: 'post',
		        data: {myData:sdata},
		        success: function(data) {
		            //log(data);
		            //location.reload();
		          
		        },
		        error: function(xhr, desc, err) {
		          console.log(xhr);
		          console.log("Details: " + desc + "\nError:" + err);
		        }
		      }); // end ajax call
}



 function  getdata(id,th){
     var clock=document.getElementById(id).value;
     var manifestdate =$("#manifestdate").datepicker("getDate");
     var dateformated=$("#manifestdate").val();
     var form = document.createElement("form");
     var element1 = document.createElement("input");
     var element2 = document.createElement("input");
     form.method = "POST";
     form.name="manifestform";
     form.action="manifest.php";

     element1.value=clock
     element1.name="clock";
     element2.value=dateformated
     element2.name="dateValue";
     element1.setAttribute("type","hidden");
     element2.setAttribute("type","hidden");
     form.appendChild(element1);
     form.appendChild(element2);
     document.body.appendChild(form);
     form.submit();

 }
 $("#manifestdate").change(function(){
            var manifestdate =$("#manifestdate").datepicker("getDate");
            var dateformated=$(this).val();
            log(dateformated);
            var form = document.createElement("form");
            var element1 = document.createElement("input");
            form.method = "POST";
            form.name="dmanifestform";
            form.action="manifest.php";

            element1.value=dateformated
            element1.name="dateValue";
            element1.setAttribute("type","hidden");
            form.appendChild(element1);
            document.body.appendChild(form);
            form.submit();


        });


            function changetext(id){
             var butntext= document.getElementById(id).innerHTML;
             if(butntext=="Pending") {
                 document.getElementById(id).innerHTML = "Cancelled";
                 document.getElementById(id).className="btn btn-danger";
             }
             else if(butntext=="Cancelled") {
                 document.getElementById(id).innerHTML = "Success";
                 document.getElementById(id).className="btn btn-success";
             }
             else {
                 document.getElementById(id).innerHTML = "Pending";
                 document.getElementById(id).className="btn btn-warning";
             }


            }

            $(document).ready(function () {

                $('#editbtn').click(function () {
                    var currentTD = $(this).parents('tr').find('td');
                    if ($(this).html() == 'Edit') {
                        currentTD = $(this).parents('tr').find('td');
                        $.each(currentTD, function () {
                            $(this).prop('contenteditable', true)
                        });
                    } else {
                        $.each(currentTD, function () {
                            $(this).prop('contenteditable', false)
                        });
                    }

                    $(this).html($(this).html() == 'Edit' ? 'Save' : 'Edit')

                });

            });



$(document).ready(function() {
            $('#newDriver').bootstrapValidator({
//        live: 'disabled',
                message: 'This value is not valid',

                fields: {
                    dssn: {
                        validators: {
                            regexp: {
                                regexp: /^[0-9]*$/,
                                message: 'Please Enter numbers'
                            },
                        }
                    }
//                    commision: {
//                        validators: {
//                            regexp: {
//                                regexp: /[+-]?([0-9]*[.])?[0-9]+/,
//                                message: 'Please Enter numbers'
//                            },
//                        }
//                    }


                }
            });
        })


        function validateForm() {
            if(document.forms["newDriver"]["dlnumber"].value==null || document.forms["newDriver"]["dlnumber"].value==""){
                document.forms["newDriver"]["dlnumber"].value=0;
            }

            return true;
        }


//schooldata.php

$( function() {
	
    $( "#accordion" ).accordion({
		active: false,
    collapsible: true  
	});
	
	
	
  } );

// var firstClicked = false;
//
// $('.schools').click(function(){
// 		if(!firstClicked){
// 			firstClicked = true;
// 			/******** Elementary School Data **********/
// 			var sdata = {};
// 			sdata['mode'] = "typedropdown";
// 			sdata['client_id'] = $('.schools').attr('value');
// 			sdata['school_type'] = "elementary";
//
// 			//ajax call to find schools data according to the client
// 			$.ajax({
// 				url: 'ajax/neworder_ajax.php',
// 				type: 'post',
// 				data: {myData:sdata},
// 				success: function(data) {
// 					var schoolObjForElementary = $.parseJSON(data);
//
// 					if(firstClicked){
// 						 $('#elementary .elemList li').remove();
// 						for (i = 0; i < schoolObjForElementary.length; i++){
// 							$('#elementary .elemList').append('<li class="list-group-item"><p class="elemItem">'+schoolObjForElementary[i].school_name+'<a href="addschool.html"><span class="size2 pull-right" style="color: #68ca1b;"><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
// 						}
// 					}
//
//
// 				},
// 				error: function(xhr, desc, err) {
// 				  console.log(xhr);
// 				  console.log("Details: " + desc + "\nError:" + err);
// 				}
// 			  }); // end ajax call
//
// 			  /******** Middle School Data **********/
// 			var sdata = {};
// 			sdata['mode'] = "typedropdown";
// 			sdata['client_id'] = $('.schools').attr('value');
// 			sdata['school_type'] = "middle";
// 			//ajax call to find schools data according to the client
// 			$.ajax({
// 				url: 'ajax/neworder_ajax.php',
// 				type: 'post',
// 				data: {myData:sdata},
// 				success: function(data) {
// 					//console.log(data);
// 					var schoolObjForMiddle = $.parseJSON(data);
// 					if(firstClicked){
// 						for (i = 0; i < schoolObjForMiddle.length; i++){
// 							$('#middle .middleList').append('<li class="list-group-item"><p class="middleItem">'+schoolObjForMiddle[i].school_name+'<a href="addschool.html"><span class="size2 pull-right" style="color: #68ca1b;"><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
// 						}
// 					}
// 					//console.log(schoolObjForElementary);
//
// 				},
// 				error: function(xhr, desc, err) {
// 				  console.log(xhr);
// 				  console.log("Details: " + desc + "\nError:" + err);
// 				}
// 			  }); // end ajax call
//
// 			   /******** High School Data **********/
// 			var sdata = {};
// 			sdata['mode'] = "typedropdown";
// 			sdata['client_id'] = $('.schools').attr('value');
// 			sdata['school_type'] = "high";
// 			//ajax call to find schools data according to the client
// 			$.ajax({
// 				url: 'ajax/neworder_ajax.php',
// 				type: 'post',
// 				data: {myData:sdata},
// 				success: function(data) {
// 					//console.log(data);
// 					var schoolObjForHigh = $.parseJSON(data);
// 					if(firstClicked){
// 						for (i = 0; i < schoolObjForHigh.length; i++){
// 							$('#high .highList').append('<li class="list-group-item"><p class="highItem">'+schoolObjForHigh[i].school_name+'<a href="addschool.html"><span class="size2 pull-right" style="color: #68ca1b;"><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
// 						}
// 					}
// 					//console.log(schoolObjForElementary);
//
// 				},
// 				error: function(xhr, desc, err) {
// 				  console.log(xhr);
// 				  console.log("Details: " + desc + "\nError:" + err);
// 				}
// 			  }); // end ajax call
//
// 			  /******** Other School Data **********/
// 			var sdata = {};
// 			sdata['mode'] = "typedropdown";
// 			sdata['client_id'] = $('.schools').attr('value');
// 			sdata['school_type'] = "other";
// 			//ajax call to find schools data according to the client
// 			$.ajax({
// 				url: 'ajax/neworder_ajax.php',
// 				type: 'post',
// 				data: {myData:sdata},
// 				success: function(data) {
// 					var schoolObjForOther = $.parseJSON(data);
// 					if(schoolObjForOther.length != 0){
// 						if(firstClicked){
// 							for (i = 0; i < schoolObjForOther.length; i++){
// 								$('#other .otherList').append('<li class="list-group-item"><p class="otherItem">'+schoolObjForOther[i].school_name+'<a href="addschool.html"><span class="size2 pull-right" style="color: #68ca1b;" ><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
// 							}
// 						}
// 					}else{
// 						$("#accordion").find('h3').filter(':contains(Other)').hide();
// 						$( ".other" ).hide();
// 					}
// 				},
// 				error: function(xhr, desc, err) {
// 				  console.log(xhr);
// 				  console.log("Details: " + desc + "\nError:" + err);
// 				}
// 			  }); // end ajax call
//
//
// 	}
//
// 	  $( ".elementary" ).button().on( "click", function() {
//
//
// 	  });
// });


// Billing and rates


function setRideAlongBill(){
    var trips = $('#ratotal_trips').val();
    var amount = $('#ra_amount').val();
    var deduct = +$('#ra_deductions').val();
    var payable=trips*amount-deduct;
    $('#ra_total').val(payable.toFixed(2));
    $('#savepl').removeClass("hidebox");
}


$('#d_contractorsPay').change(function(){
    var amount = $('#d_contractorsPay').val();
    var payable=  +$('#d_payable').text();
    var  total=   (amount*0.01)*payable;
    $('#d_pay').text(total.toFixed(2));
});

function setDriverBill(){
    var payable=  +$('#d_pay').text();
    var tips=  +$('#d_tip').text();
    var additions=  +$('#d_additions').val();
    var pay=payable+tips+additions;
    $('#d_contractorTotal').text(pay.toFixed(2));

    var fueladvance = +$('.fuel_advance').val();
    var tolls = +$('.d_tolls').val();
    var lease = +$('.d_lease').text();
    var cash_advance = +$('.cash_advance').val();
    var others = +$('.d_others').val();
    var totalSettlement=pay.toFixed(2)-(fueladvance+tolls+lease+cash_advance+others);
    $('#d_finalCheck').text(totalSettlement.toFixed(2));
    $('#savepl').removeClass("hidebox");

}


$('.d_leasepercentage').change(function(){
    var leaspercntage = +$('.d_leasepercentage').val();
    var bill=  +$('#d_payable').text();
    var  payamount=   (bill*0.01)*leaspercntage;
    $('.d_lease').text(payamount.toFixed(2));

});

function  printDriverBill(){
    //    var divToPrint = document.getElementById('divToPrint');
    var contractorName= $('#db_drivername').text();
    var db_from=$('#db_from').val();
    var db_to=$('#db_to').val();
    var fueladvance = +$('.fuel_advance').val();
    var tolls = +$('.d_tolls').val();
    var lease = +$('.d_lease').text();
    var cash_advance = +$('.cash_advance').val();
    var others = +$('.d_others').val();
    var totalDue= fueladvance+tolls+lease+cash_advance+others  ;


    var todayTime = new Date();
    var db_date= todayTime .getFullYear() + "-" + (todayTime .getMonth() + 1) + "-" + todayTime .getDate();

    $('.db_contractor').text(contractorName);
    $('.db_startdate').text(db_from);
    $('.db_enddate').text(db_to);
    $('.db_beforeDeductions').text(+$('#d_payable').text());
    $('.db_contractorRate').text(+$('#d_contractorsPay').val());
    $('.db_payable').text(+$('#d_pay').text());
    $('.db_additionalFee').text(+$('#d_additions').val());
    $('.db_tip').text(+$('#d_tip').text());
    $('.db_totalBeforeDeductions').text(+$('#d_contractorTotal').text());
    $('.db_fuelAdvance').text(+$('.fuel_advance').val());
    $('.db_tollAdvance').text(+$('.d_tolls').val());
    $('.db_leasePercentage').text(+$('.d_leasepercentage').val());
    $('.db_lease').text(+$('.d_lease').text());
    $('.db_commisionAdvance').text(+$('.cash_advance').val());
    $('.db_other').text(+$('.d_others').val());
    $('.db_totalDue').text(totalDue.toFixed(2));
    $('.db_totalPayable').text(+$('#d_finalCheck').text());
    $('.db_totalPrint').text(+$('#d_finalCheck').text());
    $('.db_issuedDate').text(db_date);
    $('.db_check').text($('.check_number').val());
    $('.total_erng').text($('#total_earnings').val());
    window.print();

}



function printClientBill(){
    // Date
    var todayTime = new Date();
    var cb_date= todayTime .getFullYear() + "-" + (todayTime .getMonth() + 1) + "-" + todayTime .getDate();
    $('.cb_date').text(cb_date);

    window.print();
}

function printCheck(){
    window.print();
}

function printAdvance() {
    window.print();
}

function printRABill(){
    var todayTime = new Date();
    var cb_date= todayTime .getFullYear() + "-" + (todayTime .getMonth() + 1) + "-" + todayTime .getDate();
    $('.ra_date').text(cb_date);
    $('#r_amount').text($('#ra_amount').val());
    $('#ra_pay').text($('#ra_total').val());
    $('#ra_Trips').text($('#ratotal_trips').val());
    window.print();
}

function printSheet(){
    window.print();
}

function printCheck(){
      window.print();
}


function   updateCashAdvance() {
    var cash_advance = +$('.cash_advance').val();
    var driverid = $('#cashad_driverid').val();
    var advance_initial = +$('#cashad_initial').val();
    if (advance_initial - cash_advance >= 0){
        updateCashAdvanceAjax(cash_advance, driverid);
        $('.advance_result').text("Data Updated Sucessfully");
    }else{
        alert('Please check the Cash Advance Amount');
    }


}

function  updateCashAdvanceAjax(cash_advance,driverid){
    var sdata = {};
    sdata["cashAdvance"] = cash_advance;
    sdata["driver_id"] = driverid;
    $.ajax({
        url: 'ajax/driver_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            //log(data);
            //location.reload();

        },
        error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call

}
//Save payroll for ridealongs

$('#savera_button').click(function(element){

    var id = element.target;
    var sdata = {};
    
    sdata['ra_id'] = $(id).siblings('input').data('ra_id');
    sdata['startdate'] = $(id).siblings('input').data('startdate');
    sdata['enddate'] = $(id).siblings('input').data('enddate');
    sdata['amount'] = $('#ra_total').val();
    sdata['deducts'] = $('#ra_deductions').val();
    sdata['amountpertrip'] = $('#ra_amount').val();
    sdata['totaltrips']=$('#ratotal_trips').val();


        sdata['mode'] = "savepayroll_ra";
        log(sdata);
        $.ajax({
            url: 'ajax/manifest_ajax.php',
            type: 'post',
            data: {myData:sdata},
            success: function(data) {
                log(data);
                $('#savera_button').text('Saved');
                $('#savera_button').attr('disabled', true);
              
            },
            error: function(xhr, desc, err) {
              console.log(xhr);
              console.log("Details: " + desc + "\nError:" + err);
            }
          }); // end ajax call



});

$('#savepl_button').click(function(element){

    var id = element.target;
    var sdata = {};
    
    sdata['driver_id'] = $(id).siblings('input').data('driver_id');
    sdata['startdate'] = $(id).siblings('input').data('startdate');
    sdata['enddate'] = $(id).siblings('input').data('enddate');
    sdata['amount'] = $('#d_finalCheck').text();
    sdata['totalBilling']=$('#d_payable').text();
    sdata['payToContractorsPerc']=$('#d_contractorsPay').val();
    sdata['payToContractors']=$('#d_pay').text();
    sdata['tips']=$('#d_tip').text();
    sdata['additions']=$('#d_additions').val()
    sdata['contractorsTotal']=$('#d_contractorTotal').text();
    sdata['fuelAdvance']=$('.fuel_advance').val()
    sdata['toll']=$('.d_tolls').val()
    sdata['leasePerc']=$('.d_leasepercentage').val();
    sdata['lease']=$('.d_lease').text();
    sdata['others']=$('.d_others').val();
    sdata['checkNumber']=$('.check_number').val();
    sdata['savedate']=$('#dbillsavedate').val();


        sdata['mode'] = "savepayroll";
        log(sdata);
        $.ajax({
            url: 'ajax/manifest_ajax.php',
            type: 'post',
            data: {myData:sdata},
            success: function(data) {
                log(data);
                $('#savepl_button').text('Saved');
                $('#savepl_button').attr('disabled', true);
              
            },
            error: function(xhr, desc, err) {
              console.log(xhr);
              console.log("Details: " + desc + "\nError:" + err);
            }
          }); // end ajax call



});

//Client Bill Saving

$('#savecb_button').click(function(element){
    var id = element.target;
    var sdata = {};

    sdata['c_id'] = $(id).siblings('input').data('clientid');
    sdata['startdate'] = $(id).siblings('input').data('startdate');
    sdata['enddate'] = $(id).siblings('input').data('enddate');
    sdata['totaltrips'] = $('#totaltrips').val();
    sdata['totalbillable']=$('#totalbillable').val();



    sdata['mode'] = "saveClientBill";
    log(sdata);
    $.ajax({
        url: 'ajax/manifest_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            log(data);
            $('#savecb_button').text('Saved');
            $('#savecb_button').attr('disabled', true);

        },
        error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call



});

//Client Bill Updating

$('#updateClientBill').click(function(element){
    var id = element.target;
    var sdata = {};
    sdata['c_id'] = $(id).siblings('input').data('clientid');
    sdata['startdate'] = $(id).siblings('input').data('startdate');
    sdata['enddate'] = $(id).siblings('input').data('enddate');
    sdata['invoiceId'] = $(id).siblings('input').data('invoicenumber');
    sdata['totaltrips'] = $('#totaltrips').val();
    sdata['totalbillable']=$('#totalbillable').val();



    sdata['mode'] = "UpdateClientBill";
    log(sdata);
    $.ajax({
        url: 'ajax/manifest_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            log(data);
            $('#updateClientBill').text('Saved');

        },
        error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call



});

// Driver Billing
$('#updateDriverBill').click(function(element){

    var id = element.target;
    var sdata = {};

    sdata['driver_id'] = $('#cashad_driverid').val();
    sdata['startdate'] =$('#db_from').val();
    sdata['enddate'] = $('#db_to').val();
    sdata['amount'] = $('#d_finalCheck').text();
    sdata['totalBilling']=$('#d_payable').text();
    sdata['payToContractorsPerc']=$('#d_contractorsPay').val();
    sdata['payToContractors']=$('#d_pay').text();
    sdata['tips']=$('#d_tip').text();
    sdata['additions']=$('#d_additions').val()
    sdata['contractorsTotal']=$('#d_contractorTotal').text();
    sdata['fuelAdvance']=$('.fuel_advance').val()
    sdata['toll']=$('.d_tolls').val()
    sdata['leasePerc']=$('.d_leasepercentage').val();
    sdata['lease']=$('.d_lease').text();
    sdata['others']=$('.d_others').val();
    sdata['checkNumber']=$('.check_number').val();
    sdata['pay_id']= $('#pay_id').val();
    sdata['savedate']=$('#dbillsavedate').val();
    sdata['mode'] = "updatepayroll";
    log(sdata);
    $.ajax({
        url: 'ajax/manifest_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            log(data);
        alert("Data Updated");
            window.location.href="driverbilling.php";

        },
        error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call



});

$('#updateraBill').click(function(element){

    var id = element.target;
    var sdata = {};
    
    sdata['plra_id'] = $(id).siblings('input').data('plra_id');
    sdata['startdate'] = $(id).siblings('input').data('startdate');
    sdata['enddate'] = $(id).siblings('input').data('enddate');
    sdata['amount'] = $('#ra_total').val();
    sdata['totaltrips']=$('#ratotal_trips').val();
    sdata['amountpertrip'] = $('#ra_amount').val();
    sdata['deducts'] = $('#ra_deductions').val();

        sdata['mode'] = "updatepayroll_ra";
        log(sdata);
        $.ajax({
            url: 'ajax/manifest_ajax.php',
            type: 'post',
            data: {myData:sdata},
            success: function(data) {
                log(data);
                alert("Data Updated");
            window.location.href="ralongbilling.php";
              
            },
            error: function(xhr, desc, err) {
              console.log(xhr);
              console.log("Details: " + desc + "\nError:" + err);
            }
          }); // end ajax call



});


function cash_modal(){
    alert('Data Updated Successfully');
}

$('#zone_save').on('click',function() {
    var zone = $('#zone_loc').val();

    if(zone==null ||  zone==""){
    	alert("Please enter the Zone");
    	return false;
	}

    var sdata = {};
    sdata["zoneloc"] = zone;
    toPerform=$('#zone_save').text();
    if (toPerform=='Save') {
      $.ajax({
        url: 'ajax/zone_ajax.php',
        type: 'post',
        data: {myData: sdata},
        success: function (data) {

            location.reload();

        },
        error: function (xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
       }); // end ajax call
    }

    if (toPerform=='Update') {
        var zoneid = $('#c_zoneId').val();
        sdata["zoneid"] = zoneid;
    	$.ajax({
            url: 'ajax/zone_ajax.php',
            type: 'post',
            data: {updateZone: sdata},
            success: function (data) {

                location.reload();

            },
            error: function (xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        }); // end ajax call
	}

 }
);


$('#add_zone').on('click',function(){
    $('#zone_save').text('Save');
    $('#zone_loc') .val("");
    $('#c_zoneId') .val("");
    $('#zone_toggle').toggle();

});

function editZone(event){
    ele = event.target;
    var id = $(ele).closest('td');
    var zone = $(id).siblings("[headers='zone']").children('span:first').text();
    var zoneid= $(id).parents('tr').find('.c_zoneId').val();
    $('#zone_loc') .val(zone);
    $('#c_zoneId').val(zoneid);
    $('#zone_save').text('Update');
    $('#zone_toggle').toggle();


}



function  add_rates(){
    var sdata = {};
    sdata["zone"] = $('#r_zones').val();
    sdata["item"]=$('#r_items').val();
    sdata["amount"] =$('#r_amount').val()
    $.ajax({
        url: 'ajax/rates_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            //log(data);

        },
        error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call
    location.reload();
}


$("[name='rate-checkbox']").bootstrapSwitch();
$("[name='rate-checkbox']").on('switchChange.bootstrapSwitch', function (event, state) {
    log(event.target+"with"+state);
    ele = event.target;
    var id = $(ele).closest('td');
    var sdata = {};

    if (state == false) {
        var rate = $(id).siblings("[headers='rate']").children('span:first').text();
        $(id).parents('tr').find('td').eq(2).replaceWith('<td class="col-xs-3" headers="rate"><input class="form-control rateValue" placeholder="'+rate+'" value="'+rate+'"></td>');
    }

    if (state == true){
    	var rate= $(id).siblings("[headers='rate']").find('input').val()
        $(id).parents('tr').find('td').eq(2).replaceWith('<td class="col-xs-3" headers="rate"><span>'+rate+'</span></td>');
        sdata['rateid'] = $(id).siblings('input').data('rateid');
        sdata['rate']=rate;
        $.ajax({
            url: 'ajax/rates_ajax.php',
            type: 'post',
            data: {r_ChangeData:sdata},
            success: function(data) {
            },
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
            });
    }

});

function dr_changestatus(th){
    var ele = th;
    var par_ele = $(ele).parent();
    var id = $(ele).closest('td');
    var did = $(id).siblings('input').data('driverid');
    if ($(ele).text() == "Active" ) {
        $(par_ele).children().remove();
        $(par_ele).append('<button type="button" class="btn btn-danger dstatus" onclick="dr_changestatus(this)">Inactive</button>');
        dr_changestatus_ajax(did,"inactive");
    }
    else{
        $(par_ele).children().remove();
        $(par_ele).append('<button type="button" class="btn btn-success dstatus" onclick="dr_changestatus(this)">Active</button>');
        dr_changestatus_ajax(did,"active");
    }
}

function dr_changestatus_ajax(did,status){

    var sdata = {};
    sdata["mode"] = "dr_changestatus";
    sdata["d_id"] = did;
    sdata["status"] = status;
    $.ajax({
        url: 'ajax/drChange_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            //log(data);
            //location.reload();

        },
        error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call
}

//School by asmita



$( function() {

    $( "#accordion" ).accordion({
        active: false,
        collapsible: true
    });



} );

$('#stypeSelect').change(function(){
    //log("inside click");
    var sdata = {};
    sdata['mode'] = "typedropdown";
    sdata['client_id'] = $('#ctypeSelect').val();
    sdata['school_type'] = $('#stypeSelect').val().toLowerCase();
    $.ajax({
        url: 'ajax/neworder_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            var schoolObj = $.parseJSON(data);
            append_schools(schoolObj);
            //log(schoolObj);

        },
        error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call

});


function show_schoolData(th) {
    var firstClicked = false;
    var ele = th;
    var id = $(ele).closest('td');
    var cid = $(id).siblings('input').data('clientid');
    if (!firstClicked) {
        firstClicked = true;
        /******** Elementary School Data **********/
        var sdata = {};
        sdata['mode'] = "typedropdown";
        sdata['client_id'] = cid;
        sdata['school_type'] = "elementary";
        //ajax call to find schools data according to the client
        $.ajax({
            url: 'ajax/neworder_ajax.php',
            type: 'post',
            data: {myData: sdata},
            success: function (data) {
                var schoolObjForElementary = $.parseJSON(data);
                if (firstClicked) {
                    $('#elementary .elemList li').remove();
                    for (i = 0; i < schoolObjForElementary.length; i++) {
                        $('#elementary .elemList').append('<li class="list-group-item"><p class="elemItem">' + schoolObjForElementary[i].school_name + '<a href="addschool.php?schoolid='+schoolObjForElementary[i].school_id+'"><span class="size2 pull-right" style="color: #68ca1b;"><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
                    }
                }


            },
            error: function (xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        }); // end ajax call

        /******** Middle School Data **********/
        var sdata = {};
        sdata['mode'] = "typedropdown";
        sdata['client_id'] =cid;
        sdata['school_type'] = "middle";
        //ajax call to find schools data according to the client
        $.ajax({
            url: 'ajax/neworder_ajax.php',
            type: 'post',
            data: {myData: sdata},
            success: function (data) {
                //console.log(data);
                var schoolObjForMiddle = $.parseJSON(data);
                if (firstClicked) {
                    $('#middle .middleList li').remove();
                    for (i = 0; i < schoolObjForMiddle.length; i++) {
                        $('#middle .middleList').append('<li class="list-group-item"><p class="middleItem">' + schoolObjForMiddle[i].school_name + '<a href="addschool.php?schoolid='+schoolObjForMiddle[i].school_id+'"><span class="size2 pull-right" style="color: #68ca1b;"><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
                    }
                }
                //console.log(schoolObjForElementary);

            },
            error: function (xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        }); // end ajax call

        /******** High School Data **********/
        var sdata = {};
        sdata['mode'] = "typedropdown";
        sdata['client_id'] =cid;
        sdata['school_type'] = "high";
        //ajax call to find schools data according to the client
        $.ajax({
            url: 'ajax/neworder_ajax.php',
            type: 'post',
            data: {myData: sdata},
            success: function (data) {
                //console.log(data);
                var schoolObjForHigh = $.parseJSON(data);
                if (firstClicked) {
                    $('#high .highList li').remove();
                    for (i = 0; i < schoolObjForHigh.length; i++) {
                        $('#high .highList').append('<li class="list-group-item"><p class="highItem">' + schoolObjForHigh[i].school_name + '<a href="addschool.php?schoolid='+schoolObjForHigh[i].school_id+'"><span class="size2 pull-right" style="color: #68ca1b;"><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
                    }
                }
                //console.log(schoolObjForElementary);

            },
            error: function (xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        }); // end ajax call

        /******** alternative School Data **********/
        var sdata = {};
        sdata['mode'] = "typedropdown";
        sdata['client_id'] =cid;
        sdata['school_type'] = "alternative";
        //ajax call to find schools data according to the client
        $.ajax({
            url: 'ajax/neworder_ajax.php',
            type: 'post',
            data: {myData: sdata},
            success: function (data) {
                //console.log(data);
                var schoolObjForHigh = $.parseJSON(data);
                if (firstClicked) {
                    $('#alternative .alternativeList li').remove();
                    for (i = 0; i < schoolObjForHigh.length; i++) {
                        $('#alternative .alternativeList').append('<li class="list-group-item"><p class="highItem">' + schoolObjForHigh[i].school_name + '<a href="addschool.php?schoolid='+schoolObjForHigh[i].school_id+'"><span class="size2 pull-right" style="color: #68ca1b;"><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
                    }
                }
                //console.log(schoolObjForElementary);

            },
            error: function (xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        }); // end ajax call

        /******** alternative School Data **********/

        /******** preschool School Data **********/
        var sdata = {};
        sdata['mode'] = "typedropdown";
        sdata['client_id'] =cid;
        sdata['school_type'] = "preschool";
        //ajax call to find schools data according to the client
        $.ajax({
            url: 'ajax/neworder_ajax.php',
            type: 'post',
            data: {myData: sdata},
            success: function (data) {
                //console.log(data);
                var schoolObjForHigh = $.parseJSON(data);
                if (firstClicked) {
                    $('#preschool .preschoolList li').remove();
                    for (i = 0; i < schoolObjForHigh.length; i++) {
                        $('#preschool .preschoolList').append('<li class="list-group-item"><p class="highItem">' + schoolObjForHigh[i].school_name + '<a href="addschool.php?schoolid='+schoolObjForHigh[i].school_id+'"><span class="size2 pull-right" style="color: #68ca1b;"><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
                    }
                }
                //console.log(schoolObjForElementary);

            },
            error: function (xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        }); // end ajax call

        /******** preschool School Data **********/

        /******** special School Data **********/
        var sdata = {};
        sdata['mode'] = "typedropdown";
        sdata['client_id'] =cid;
        sdata['school_type'] = "special";
        //ajax call to find schools data according to the client
        $.ajax({
            url: 'ajax/neworder_ajax.php',
            type: 'post',
            data: {myData: sdata},
            success: function (data) {
                //console.log(data);
                var schoolObjForHigh = $.parseJSON(data);
                if (firstClicked) {
                    $('#special .specialList li').remove();
                    for (i = 0; i < schoolObjForHigh.length; i++) {
                        $('#special .specialList').append('<li class="list-group-item"><p class="highItem">' + schoolObjForHigh[i].school_name + '<a href="addschool.php?schoolid='+schoolObjForHigh[i].school_id+'"><span class="size2 pull-right" style="color: #68ca1b;"><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
                    }
                }
                //console.log(schoolObjForElementary);

            },
            error: function (xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        }); // end ajax call

        /******** special School Data **********/


        /******** pilot School Data **********/
        var sdata = {};
        sdata['mode'] = "typedropdown";
        sdata['client_id'] =cid;
        sdata['school_type'] = "pilot";
        //ajax call to find schools data according to the client
        $.ajax({
            url: 'ajax/neworder_ajax.php',
            type: 'post',
            data: {myData: sdata},
            success: function (data) {
                //console.log(data);
                var schoolObjForHigh = $.parseJSON(data);
                if (firstClicked) {
                    $('#pilot .pilotList li').remove();
                    for (i = 0; i < schoolObjForHigh.length; i++) {
                        $('#pilot .pilotList').append('<li class="list-group-item"><p class="highItem">' + schoolObjForHigh[i].school_name + '<a href="addschool.php?schoolid='+schoolObjForHigh[i].school_id+'"><span class="size2 pull-right" style="color: #68ca1b;"><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
                    }
                }
                //console.log(schoolObjForElementary);

            },
            error: function (xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        }); // end ajax call

        /******** pilot School Data **********/

        // var sdata = {};
        // sdata['mode'] = "typedropdown";
        // sdata['client_id'] = cid;
        // sdata['school_type'] = "other";
        // //ajax call to find schools data according to the client
        // $.ajax({
        //     url: 'ajax/neworder_ajax.php',
        //     type: 'post',
        //     data: {myData: sdata},
        //     success: function (data) {
        //         var schoolObjForOther = $.parseJSON(data);
        //         if (schoolObjForOther.length != 0) {
        //             if (firstClicked) {
        //                 $('#other .otherList li').remove();
        //                 for (i = 0; i < schoolObjForOther.length; i++) {
        //                     $('#other .otherList').append('<li class="list-group-item"><p class="otherItem">' + schoolObjForOther[i].school_name + '<a href="addschool.html"><span class="size2 pull-right" style="color: #68ca1b;" ><i class="fa fa-pencil-square-o size2" aria-hidden="true"></i></span></a></p></li>')
        //                 }
        //             }
        //         } else {
        //             $("#accordion").find('h3').filter(':contains(Other)').hide();
        //             $(".other").hide();
        //             $(".other").hide();
        //         }
        //     },
        //     error: function (xhr, desc, err) {
        //         console.log(xhr);
        //         console.log("Details: " + desc + "\nError:" + err);
        //     }
        // }); // end ajax call
    }

    $(".elementary").button().on("click", function () {


    });
}

function ca_delete(th){
    var ele = th;
    var par_ele = $(ele).parent();
    var id = $(ele).closest('td');
    var tid = $(id).siblings('input').data('transid');
    ca_deleteadvance(tid);
}

function ca_deleteadvance(tid){

    var sdata = {};
    sdata["t_id"] = tid;
    $.ajax({
        url: 'ajax/ra_ajax.php',
        type: 'post',
        data: {myData:sdata},
        success: function(data) {
            location.reload();

        },
        error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call
}

$('#statsclient').change(function() {
var client= $('#statsclient').val();





});

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

function togglebtnstat(th) {


    var stat= $.trim($(th).text());
    if (confirm('Do you want to cancel all the trips?')) {
        if (stat == "Cancel All") {
            var sdata = {};
            sdata['mode'] = "cancelalltrips";
            sdata["date"] = $('#cancelbtnclick').data('date');
            sdata["clck"] = $('#cancelbtnclick').data('clck');

            $.ajax({
                url: 'ajax/manifest_ajax.php',
                type: 'post',
                data: {myData: sdata},
                success: function (data) {
                    location.reload();

                },
                error: function (xhr, desc, err) {
                    console.log(xhr);
                    console.log("Details: " + desc + "\nError:" + err);
                }
            }); // end ajax call$( ".btnstat" ).replaceWith( "<button type='button' class='btn btn-primary btnstat' data-cancelall='true'>Cancelled</button>" );
        }
    }

}