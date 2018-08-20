<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$errors = array();
	$participantbol = new participantbol();
	$localizationbol= new localizationbol();
	$participantinfo=new participantinfo();	
	$student_predefinebol = new student_predefinebol();
	$participant_food_allergyinfo=new participant_food_allergyinfo();	
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('card_status',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');

	$res = $participantbol->get_organizer_id($_SESSION['login_user_id']);//$_SESSION ['login_organizer_id'];
	$my_res = $res->getNext();
	$login_organizer_id = $my_res['organizer_id'];
	
	if (isset($_POST['btnsave'])) 	
	{	
		if(trim($_POST['txt_participant_name'] == ''))
		{
			$errors[] = '* Please Fill Child Name';
		}
		else if(trim($_POST['txt_participant_id'] == ''))
		{
			$errors[] = '* Please Fill Child ID';
		}
		else if($_POST['sel_participant_gender'] == '-1')
		{
			$errors[] = '* Please Select Child Gender';
		}
		
		if(count($errors) == 0)
		{
			$flag_click_match_btn = $_POST['flag_click_match_btn'];
			if($flag_click_match_btn==0)
			{
				$_SESSION['participant_message'] = "Please Click on Match Name and ID button before Save";
			}
			else
			{
				$participant_name = $_POST['txt_participant_name'];
				$participant_enroll_no = $_POST['txt_participant_id'];
				$org_id = $_POST['sel_org'];
				$participant_gender_id = $_POST['sel_participant_gender'];
				$organizer_id = $login_organizer_id;
				
				$participant_allergy_flag = 0;
				if(isset($_POST['chk_allergy']) && $_POST['chk_allergy']!='' ){
					$participant_allergy_flag = 1;
				}
				
				$others_allergy_food_description = '';
				if(isset($_POST['txt_other_food_allergy']) && $_POST['txt_other_food_allergy']!='' )
					$others_allergy_food_description = $_POST['txt_other_food_allergy'];

				$participant_description = '';
				if(isset($_POST['txt_participant_desc']) && $_POST['txt_participant_desc']!='' )
					$participant_description = $_POST['txt_participant_desc'];
					
				$participant_class_name = '';
				if(isset($_POST['txt_class_name']) && $_POST['txt_class_name']!='' )
					$participant_class_name = $_POST['txt_class_name'];

				$allow_canteen_order = 0;
				if(isset($_POST['chk_allow_canteen_order']) && $_POST['chk_allow_canteen_order']!='')
					$allow_canteen_order = 1;

				//check duplicate student by student id(student student ID)
				$check_dup_saving_result=$participantbol->check_duplicate_participant_saving($participant_enroll_no);
				$row = $check_dup_saving_result->getNext();
				$count_duplicate_saving = $row['count_duplicate_saving'];
				if($count_duplicate_saving==0)
				{
					//save in tbl_participant
					$predefine_participant_id = $_POST['hidden_predefine_participant_id'];
					$participantinfo->set_participant_name($participant_name);
					$participantinfo->set_participant_enroll_no($participant_enroll_no);
					$participantinfo->set_org_id($org_id);
					$participantinfo->set_student_class($participant_class_name);
					$participantinfo->set_participant_allergy_flag($participant_allergy_flag);
					$participantinfo->set_others_allergy_food_description($others_allergy_food_description);
					$participantinfo->set_participant_description($participant_description);
					$participantinfo->set_participant_gender_id($participant_gender_id);
					$participantinfo->set_organizer_id($organizer_id);
					$participantinfo->set_participant_created_datetime($now_date_time);
					$participantinfo->set_participant_modified_datetime($now_date_time);
					$participantinfo->set_allow_canteen_order($allow_canteen_order);
					$participantinfo->set_predefine_participant_id($predefine_participant_id);	
					$participantinfo->set_upload_file($_POST['hidden_upload_name']);
					$participant_id = $participantbol->save_participant($participantinfo);
					
					//save upload file 
					if($_FILES["upload_mc"]["size"] > 0){
						if(!is_dir("upload/".$predefine_participant_id)){
							mkdir("upload/".$predefine_participant_id);
						}
						$file_arr = explode(".", $_FILES["upload_mc"]["name"]);
						if(count($file_arr) >0 )
						{
							$file_name = $file_arr[0];
							$file_type = $file_arr[1];
						}
						$currentTime = currentTimeStamp();
						$uploaded_file = $predefine_participant_id.'/'.$file_name.'_'.$currentTime.'.'.$file_type;
						move_uploaded_file($_FILES["upload_mc"]["tmp_name"], "upload/".$uploaded_file);
						$participantinfo->set_upload_file($uploaded_file);
						$participantinfo->set_predefine_participant_id($predefine_participant_id);
						$participantbol->update_participant_uploadfile($participantinfo);
					}

					$student_predefinebol->delete_participant_food_allergy($predefine_participant_id);

					if($participant_allergy_flag==1)
					{
						//save in tbl_participant_food_allergy
						if(!empty($_POST['check_list'])) 
						{
							foreach($_POST['check_list'] as $check)
							{
								$participant_food_allergyinfo->set_participant_id($participant_id);
								$participant_food_allergyinfo->set_predefine_participant_id($predefine_participant_id);
								$participant_food_allergyinfo->set_food_allergy_id($check);
								$result = $participantbol->save_participant_food_allergy($participant_food_allergyinfo);
							}
						}
					}
					if($participant_id)
						$_SESSION['participant_message'] = "Child saved successfully.";
					else
						$_SESSION['participant_message'] = "Child saved fail.";
				}
				else
					$_SESSION['participant_message'] = "Duplicate student ID.";
			}
			header("location:participant_list.php");
			exit();
		}
	}
	
//-----------------------//	
require_once('header.php');
	
?>
<script type="text/javascript">			
	jQuery(document).ready(function(){
		AddValidation();
		$('#chk_allergy').change(function() {
			if($(this).is(":checked")) {
				$("#div_sel_food_allergy").show();
				$("#div_other_food_allergy").show();
			}
			else{
				$("#div_sel_food_allergy").hide();
				$("#div_other_food_allergy").hide();
			}
			
		});
	});	
	 
	function AddValidation()
	{
		jQuery("#addnew_participant").validate(
		{			
		'rules':
			{		
				'txt_participant_name':{'required':true},	
				'txt_participant_id':{'required':true},	
				'sel_participant_gender':{'required':true}	
			},
		'messages':
			{
				'txt_participant_name':{'required':"*Please Fill Child Name"},	
				'txt_participant_id':{'required':"*Please Fill Child ID"},	
				'sel_participant_gender':{'required':"*Please Select Gender"}	
			},				
		'errorPlacement': function(error, element) {
			$(element).after(error);
		}
		});
	}	

	function checkIDandName()
	{
		var name = $("#txt_participant_name").val();
		var id = $("#txt_participant_id").val();
		$("#flag_click_match_btn").val('1');
		$('input[type="submit"]').removeAttr('disabled');
		$('#txt_participant_name').prop('readonly', true);
		$('#txt_participant_id').prop('readonly', true);
		
		if(name == '' || id == ''){
			$("#my_check_error_show").css('display','block');
			$("#my_check_error_show").html("Please fill both Name and ID");
			$('input[type="submit"]').attr('disabled','disabled');
		}
		else{
			jQuery.getJSON('check_id_and_name.php?id='+id+"&&name=" + name, checkcallback);
		}
		return false;
	}

	function checkcallback(data)
	{
		if(data.mes == 'valid'){
			$('input[type="submit"]').removeAttr('disabled');
			$("#my_check_error_show").css('display','none');
			$("#my_check_error_show").html("");
			$("#txt_participant_name").val(data.predefine_participant_name);
			$("#txt_participant_id").val(data.predefine_participant_enroll_no);
			$("#txt_class_name").val(data.student_class);
			$("#txt_participant_desc").val(data.student_description);
			$("#chk_allergy").val(data.student_allergy_flag);
			$("#txt_other_food_allergy").val(data.others_allergy_food_description);
			$("#chk_allow_canteen_order").val(data.allow_canteen_order);
			$("#hidden_predefine_participant_id").val(data.predefine_participant_id);

			jQuery.each( data.allergy_food_array, function( key, value ) {
				$("input[type='checkbox'][value=" + value + "]").attr("checked", true);
			});

			
			if(data.allow_canteen_order == 1){
				$("#chk_allow_canteen_order").attr("value", data.allow_canteen_order).attr("checked", true);
			}
			

			if(data.student_allergy_flag == 1){
				$("#chk_allergy").attr("value", data.student_allergy_flag).attr("checked", true);
				$("#div_sel_food_allergy").show();
				$("#div_other_food_allergy").show();
			}

			var ufile = '';
			if(data.upload_file != ''){
				ufile = data.upload_file;
				var myfile = ufile.split("/");
				var upload_file_name = myfile[1];
			}
			$("#show_upload_file").html(upload_file_name);
			$("#hidden_upload_name").val(upload_file_name);

			$("#sel_org option").filter(function() {
			    return this.text == data.organization; 
			}).attr('selected', true);

			$("#sel_participant_gender option").filter(function() {
			    return this.text == data.gender; 
			}).attr('selected', true);

			
		}
		else{
			$("#my_check_error_show").css('display','block');
			$("#my_check_error_show").html("Name and ID are not match!");
			$('input[type="submit"]').attr('disabled','disabled');
		}
	}
</script>
<style type="text/css">
</style>

<div class="content_data">
	<form action="" method="POST" id="addnew_participant" name="addnew_participant" enctype="multipart/form-data">
		<h2>Add New Child</h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join( $errors)."</div>";
	  			}
			?>
			<input type="hidden" name="hidden_predefine_participant_id" id="hidden_predefine_participant_id" value="">
			<input type="text" name="flag_click_match_btn" id="flag_click_match_btn" value='0'>
			<div class="frm">
				<div class="frm_labelcat">Child Name:</div>
				<input type="text" name="txt_participant_name" id="txt_participant_name" />
				<!-- <input type="button" class="btn" id="btn_check_stdID" value="Check Child ID" onclick="show_enrolltbl()" /> -->
			</div>
			
			<span style="color:red; margin-left:12%; display: none" id="not_found_msg">This student name not found in predefined student list:</span>
			<div class="frm" id="show_enroll_table" style="display: none;">
				<span>Please choose your student ID:</span>
				<table cellspacing='0' border="1px" id="enroll_table" cellpadding='0' width='100%' style='border-color: #adbec9; border-collapse: collapse;'>
					<thead bgcolor="#f5f5f5">
						<th>Child's student ID</th>
						<th>Child Name</th>
						<th>Parent Name</th>
						<th>School</th>
					</thead>
					<tbody id="show_result">
						
					</tbody>
				</table>
			</div>
			<input type="hidden" name="login_user_name" id="login_user_name" value="<?php echo $_SESSION['login_user_name']; ?>">
			<div class="frm">
				<div class="frm_labelcat">Child's Student ID:</div>
				<input type="text" name="txt_participant_id" id="txt_participant_id" onkeyup="check_duplicate_studentID()" />
				<input class="btn" type="button" name="" value="Match Name and ID" onclick="checkIDandName()">
				<span style="color:red; margin-left:17%; display: none" id="my_check_error_show"></span>
			</div>
			
			<!-- <div class="frm">
				<div class="frm_labelcat">Parent Name:</div>
				<input type="text" name="txt_organizer_name" id="txt_organizer_name" value="<?php echo $_SESSION['login_user_name']; ?>" readonly />
			</div> -->

			<div class="frm">
				<div class="frm_labelcat">School:</div>
				<input type="hidden" id="pre_org">
				<select id="sel_org" name="sel_org">
					<option value="-1">--Select School-</option>
					<?php
					$setupbol = new setupbol();
					$sel_orgs_result = $setupbol->get_all_org();
					while($sel_orow=$sel_orgs_result->getNext())
					{
						echo '<option value="'.$sel_orow['org_id'].'"';
						echo ">".$sel_orow['org_name']."</option>";
					}
					?>
				</select>
			</div>

			<div class="frm">
				<div class="frm_labelcat">Child Gender:</div>
				<input type="hidden" id="pre_gender">
				<select id="sel_participant_gender" name="sel_participant_gender">
					<option value="-1">--Select Child Gender--</option>
					<?php
					$userbol = new userbol();
					$sel_genders_result = $userbol->get_all_genders();
					while($sel_row=$sel_genders_result->getNext())
					{
						echo '<option value="'.$sel_row['gender_id'].'"';
						echo ">".$sel_row['gender_name']."</option>";
					}
					?>
				</select>
			</div>
			
			<div class="frm">
				<div class="frm_labelcat">Child Class Name:</div>
				<input type="text" id="txt_class_name" name="txt_class_name"/>
			</div>
			
			<div class="frm">
				<div class="frm_labelcat">Child Description:</div>
				<textarea id="txt_participant_desc" name="txt_participant_desc"></textarea>
			</div>
			
			<div class="frm">
				<div class="frm_labelcat">Child Allergy:</div>
				<input type="checkbox" name="chk_allergy" id="chk_allergy" />
			</div>
			
			<div class="frm" style="display:none;" id="div_sel_food_allergy">
				<div class="frm_labelcat">Food Allergy List:</div>
				<?php
					$setupbol = new setupbol();
					$food_allergy_result = $setupbol->get_all_food_allergy();
					while($fa_row=$food_allergy_result->getNext())
					{
						$food_allergy_id=$fa_row['food_allergy_id'];
						$food_allergy_name=$fa_row['food_allergy_name'];
						echo $food_allergy_name."<input type='checkbox' name='check_list[]' id='chk_food_allergy_".$food_allergy_id."' value='".$food_allergy_id."'>";
					}
				?>
			</div>
			
			<div class="frm" style="display:none;" id="div_other_food_allergy">
				<div class="frm_labelcat">Other:</div>
				<input type="text" name="txt_other_food_allergy" id="txt_other_food_allergy" />
			</div>
			
			<div class="frm">
				<div class="frm_labelcat">Medical Approve Letter:</div>
				<input type="file" name="upload_mc" >
				<input type="hidden" name="hidden_upload_name" id="hidden_upload_name" value="">
				<b id="show_upload_file"></b>
			</div>

			<div class="frm">
				<div class="frm_labelcat">Allow Order:</div>
				<input type="checkbox" name="chk_allow_canteen_order" id="chk_allow_canteen_order" />
				<span>If allow child to order their own foods, please tick this check box.</span>
			</div>

			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnsave"  id="btnsave" value="<?php echo $localized_home_data['save_btn']; ?>" class="btn"/> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='participant_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>			
	</form>
</div><!-- /content_data -->
<script type="text/javascript">
	$('#enroll_table').on('click', 'tr', function() {
	    var enrollNO = $(this).find('td:eq(0)').text();
	    var participantName = $(this).find('td:eq(1)').text();
	    var organizerName = $(this).find('td:eq(2)').text();
	    var organization = $(this).find('td:eq(3)').text();
	    $("#txt_participant_id").val(enrollNO);
	    $("#txt_participant_name").val(participantName);
	    $("#txt_organizer_name").val(organizerName);
	    $('#sel_org').val($("#sel_org option:contains('"+organization+"')").val());
	    $("#show_enroll_table").css("display",'none');
	});

	/*function show_enrolltbl() {
		if($("#txt_participant_name").val() == '' ){
			alert('Please fill student name.');
			$("#show_enroll_table").css("display",'none');
			$("#not_found_msg").css("display",'none');
			$("#txt_participant_id").val('');
			$("#txt_participant_name").val('');
		    $("#txt_organizer_name").val('');
		    $('#sel_org').val('');
		}
		else{
			var participant_name = $("#txt_participant_name").val();
			var organizer = $("#login_user_name").val();
			jQuery.get("showparticipantIds.php?participant_name=" + participant_name+"&&organizer_name=" + organizer, show_studentIDs);
		}
		return false;
	}

	function show_studentIDs(data)
	{
		if(data.studentIDs_result != ''){
			$("#not_found_msg").css('display','none');
			jQuery("#show_result").html(data.studentIDs_result);
			$("#show_enroll_table").css("display",'block');
		}
		else{
			if($("#txt_participant_name").val() != '' ){
				$("#not_found_msg").css('display','block');
			}
			else{
				$("#txt_participant_id").val('');
				$("#txt_participant_name").val('');
			    $("#txt_organizer_name").val('');
			    $('#sel_org').val('');
			}
			$("#show_enroll_table").css("display",'none');
		}
	}*/
	function check_duplicate_studentID() {
		//
	}

	function autofill_data(argument) {
		// body...
	}
</script>
<?php
	include('library/closedb.php');
	include("footer.php");
?>