<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$orderbol = new orderbol();
	$setupbol = new setupbol();
	$localizationbol= new localizationbol();
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('pre_order',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');
	
	
	$login_user_id = $_SESSION ['login_user_id'];
		
	require_once('header.php');
?>
<script type="text/javascript" src="javascript/moment.min.js" ></script>
<script type="text/javascript" src="javascript/fullcalendar.min.js" ></script>
<link rel="stylesheet" type="text/css" href="style/fullcalendar.min.css">
<link rel="stylesheet" href="style/jquery.ui.timepicker.css" type="text/css" />
<script type="text/javascript" src="javascript/jquery.ui.timepicker.js"></script>
<style type="text/css">
	#calendar {
		max-width: 99%;
		margin: 20px auto;
		font-size: 15px
	}
	.fc-title {
		/*color: rgb(58, 92, 155);*/
		padding: .4em;
	}
	.fc-time {
		color: #000000;
	};
</style>
<script>
	/*var hid_items_list;*/
    function updateTips( t ) {
      $( ".validateTips" ).text( t );
    }

	function saveEvent() {
		var valid = true;
		if($( "#sel_participant" ).val()=='-1'){
			valid = false;
			updateTips("Please select student name!");
			return false;
		}
		if($( "#sel_item" ).val()=='-1'){
			valid = false;
			updateTips("Please select item name!");
			return false;
		}
		if($( "#sel_meal_type" ).val()=='-1'){
			valid = false;
			updateTips("Please select meal type!");
			return false;
		}
		if($( "#txt_qty" ).val()=='' || $( "#txt_qty" ).val()==0){
			valid = false;
			updateTips("Please fill quantity!");
			return false;
		}
		
		if ( valid ) {
			var pre_order_id = $("#hiddeneventid").val();
			var selected_date = $("#txt_preorder_date").text();
			var p_id = $("#sel_participant").val();
			var item_id = $( "#sel_item" ).val();
			/*var hid_items_list = $( "#hid_items_list" ).val();*/
			var category_type_id = $( "#sel_meal_type" ).val();
			var category_type_name = $("#sel_meal_type option:selected").text();
			var qty = $( "#txt_qty" ).val();
			if(pre_order_id==0)
				$action_type = "add_new_preorder";
			else
				$action_type = "update_preorder";
			
	  		$.post('get_pre_order.php',{"action_type":$action_type,"pre_order_id":pre_order_id,"selected_date":selected_date,"p_id":p_id,"item_id":item_id,"category_type_id":category_type_id,"category_type_name":category_type_name,"qty":qty}, function(data){	
				if(data.status=='success'){
	  				updateTips(data.msg);
					message_popup(data.msg,350);
	  				$("#calendar").fullCalendar( 'refetchEvents' );
	  				setTimeout(function(){ 
	  					document.getElementsByName('myform')[0].reset();
	  					dialog.dialog( "close" );
	  				}, 500);
	  			}
				else if(data.status=='duplicate'){
	  				updateTips(data.msg);
					message_popup(data.msg,350);
	  				return false;
	  			}
	  			else{
	  				updateTips(data.msg);
					message_popup(data.msg,350);
	  				return false;
	  			}
	  		});
	  	}
	  return valid;
	}

	function add_new_preorder(){
		$('.ui-dialog-buttonpane').find('button:first').css('visibility','hidden');
	    $(".validateTips").html('');
		$("#lbl_item_description").html('');
    	$(".ui-dialog-title").html("Adding Pre Order");
		$("#hiddeneventid").val(0);
    	//$("#sel_item").ufd({log:true,infix:false});
    	//$("#sel_meal_type").ufd({log:true,infix:false});
    	//$("#sel_participant").ufd({log:true,infix:false});
    	$( "#dialog-form" ).dialog( "open" );
	}
		
	function delete_events()
	{
		confirm_popup('Are you sure you want to delete this preorder ?',350);
		return false;
	}
	function continue_action()
	{
		var pre_order_id = $( "#hiddeneventid" ).val();
		jQuery.get("get_pre_order.php?delete_id="+pre_order_id, function(data){
	  			if(data.status=='success'){
	  				updateTips(data.msg);
					message_popup(data.msg,350);
	  				$('#calendar').fullCalendar('removeEvents',event._id);
	  				$("#calendar").fullCalendar( 'refetchEvents' );
	  				setTimeout(function(){ 
	  					document.getElementsByName('myform')[0].reset();
	  					dialog.dialog( "close" );
	  				}, 500);
	  			}
	  			else{
	  				updateTips(data.msg);
					message_popup(data.msg,350);
	  				return false;
	  			}
	  	});
	}
	
	$(document).ready(function() {
		//hid_items_list='';
		$("#lbl_item_description").html('');
		//for add pre order
		dialog = $( "#dialog-form" ).dialog({
	      autoOpen: false,
	      height: 500,
	      width: 800,
	      modal: true,
	      buttons: {
			"Delete Pre Order": delete_events,
	        "Save Pre Order": saveEvent,
	        Cancel: function() {
	          dialog.dialog( "close" );
	        }
	      },
	      close: function() {
	        document.getElementsByName('myform')[0].reset();
	        $(".validateTips").html('');
	      }
	    });
		
		//for view pre order
		dialog2 = $( "#view-dialog-form" ).dialog({
	      autoOpen: false,
	      height: 500,
	      width: 800,
	      modal: true,
	      buttons: {
	        Cancel: function() {
	          dialog2.dialog( "close" );
	        }
	      },
	      close: function() {
	      }
	    });
		
		$('#calendar').fullCalendar({
         	viewRender:function(view,element){
         		$(".fc-ltr .fc-basic-view .fc-day-number").prepend("<span onclick='add_new_preorder()' ><img src='images/pre_order.gif' title='Add Pre Order' style='width:15px;height:15px;' id='add_order_sign' ></span>&nbsp;");
         		//$(".fc-ltr .fc-basic-view .fc-day-number").prepend("<span onclick='view_preorder_event()' ><img src='images/search_btn.gif' title='View Pre Order' style='width:13px;height:13px;' id='view_order_sign' ></span>&nbsp;");
         	},
			'refetchEvents':true, // refresh only events without page reload
			theme: true,
			header: {
				left: 'prev,next',
				center: 'title',
				right: 'today'
			},
			disableDragging: false,	
			editable: true,
			eventLimit: 4, 
			businessHours: true, // display business hours
			eventClick:  function(event, jsEvent, view) {
				$('.ui-dialog-buttonpane').find('button:first').css('visibility','visible');
		    	$(".ui-dialog-title").html("Update Pre Order");
		    	$(".validateTips").html('');
		    	$( "#hiddeneventid" ).val( event.id );
		    	$( "#txt_preorder_date" ).html( event.formatted_preorder_date );
	           	//$("#sel_participant").ufd({log:true,infix:false});
	           	$( "#sel_participant" ).val( event.participant_id ).attr('selected', 'selected').change();
				//$("#sel_item").ufd({log:true,infix:false});
				$( "#sel_item" ).val( event.item_id ).attr('selected', 'selected').change();
				//$("#sel_meal_type").ufd({log:true,infix:false});
				$( "#sel_meal_type" ).val( event.category_type_id ).attr('selected', 'selected').change();
	           	$( "#txt_qty" ).val( event.qty );
		    	$( "#dialog-form" ).dialog( "open" );
			},
		    dayClick: function (date, allDay, jsEvent, view) {
		    	//to add new event , open dialog 
		    	//$( "#txt_preorder_date" ).val( date.format() );
		    	$( "#txt_preorder_date" ).html( date.format() );
		    },
		    events: 'pre_order_list_by_participant.php',
			dayRender: function(day, element, view) {
			   // can modify 'element' and add a class to it
			},
		   eventRender: function(event, element) {
		   		var cur_item_name = event.item_name;
		   		element.find('span.fc-time').html(cur_item_name);
		   	},
		    loading: function(bool) {
				$('#loading').toggle(bool);
			}
		});
	});
	
	//for drop down change in add new pre-order dialog
	function sel_item_onChange()
	{
		$("#lbl_item_description").html("");
		var sel_item = $("#sel_item").val();
		if(sel_item != '-1')
			$.post("get_item_descrption_byid.php",{"item_id":sel_item},function(data){
				$("#lbl_item_description").html(data);
			});
	}
	
	//for drop down change in add new pre-order dialog
	function sel_participant_onChange()
	{
		$("#lbl_pname").html("");
		var sel_participant = $("#sel_participant").val();
		if(sel_participant != '-1')
			$.post("get_item_descrption_byid.php",{"p_id":sel_participant},function(data){
				$("#lbl_pname").html(data);
			});
	}
	
</script>
</head>
<body>
	<style type="text/css">
	    .form-control {
	    	margin-bottom:12px;
	    }
	    .control-label { float: left; padding: 7px 0; width: 100px; }
	    .controls { margin-left: 110px; }
	    
	    select {width: 80%; }
	   
	    fieldset { padding:0; border:0; margin-top:25px; }
	    h1 { font-size: 1.2em; margin: .6em 0; }
	    
	   .ui-state-error { padding: .3em; }
	    .validateTips { border: 1px ; padding: 0.3em; color: red;}
	    #loading {
			display: none;
			position: absolute;
			top: 10px;
			right: 10px;
		}
		.pull-left {
			float: left;
		}
		.col {
			width: 45%;
			margin: 0 2.5%;
		}

	</style>
	<div id='loading'>loading...</div>
	<div id='calendar'></div>
	<!--div id="view-dialog-form" title="View Pre Order">
		view pre order dialog
	</div-->
	<div id="dialog-form" title="Adding Pre Order">
	
	 	<p class="validateTips"></p>
		
	 	<form id="myform" name="myform">
	    	<div class="pull-left col">
				<input type="hidden" name="hiddeneventid" id="hiddeneventid" value="" />
	    		<div class="form-control">
			    	<label class="control-label" for="txt_preorder_date">Date</label>
			    	<div class="controls">
			    		<!--input type="text" name="txt_preorder_date" id="txt_preorder_date" value="" readonly class="text ui-widget-content ui-corner-all"-->
			    		<label name="txt_preorder_date" id="txt_preorder_date"></label>
			    	</div>
	    		</div>
				
				<div class="form-control">
					<label class="control-label" for="sel_participant">Student ID*</label>
					<div class="controls">
						<select id="sel_participant" onChange="sel_participant_onChange();">
						<option value="-1">--Select--</option>
						<?php
							$presult=$orderbol->get_participant_by_organizerid($login_user_id);
							while($prow=$presult->getNext())
							{
								$participant_id = $prow['participant_id'];
								$participant_enroll_no = $prow['participant_enroll_no'];
								echo "<option value='".$participant_id."'>".$participant_enroll_no."</option>";
							}
						?>
						</select>
					</div>
					<div class="controls" id="lbl_pname" name="lbl_pname">
					</div>
				</div>

				<div class="form-control">
		    		<label class="control-label" for="sel_meal_type">Meal Type *</label>
		    		<div class="controls">
			    		<select name="sel_meal_type" id="sel_meal_type" >
							<option value='-1'>--Select--</option>
							<?php
								$mealtype_res = $setupbol->get_all_category_type();
								while($mealtype_row = $mealtype_res->getNext())
								{
									$category_type_id = $mealtype_row['category_type_id'];
									$category_type_name = $mealtype_row['category_type_name'];
									echo "<option value='".$category_type_id."'>" .htmlspecialchars($category_type_name) . "</option>";
								}
							?>
						</select>
					</div>
	    		</div>
				
	    		<div class="form-control">
		    		<label class="control-label" for="sel_item">Food Item Name *</label>
		    		<div class="controls">
			    		<select name="sel_item" id="sel_item" onChange="sel_item_onChange();">
							<option value='-1'>--Select--</option>
							<?php
								$item_res = $setupbol->get_all_item_list();
								while($item_row = $item_res->getNext())
								{
									$item_id = $item_row['item_id'];
									$item_name = $item_row['item_name'];
									echo "<option value='".$item_id."'>" .htmlspecialchars($item_name) . "</option>";
								}
							?>
						</select>
						
					</div>
	    		</div>
				
				<div class="form-control">
			    	<label class="control-label" for="txt_qty">Qty *</label>
			    	<div class="controls">
			    		<input type="number" name="txt_qty" id="txt_qty" class="text ui-widget-content ui-corner-all" min="0">
			    	</div>
	    		</div>
				
				<div class="form-control">
					<div class="controls" id="lbl_item_description" name="lbl_item_description">
					</div>
				</div>
				
			</div>
			
			<div class="cleaner"></div>
			
	    	<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
  		</form>
	</div>
</body>
</html>
<?php
	include 'footer.php';
?>