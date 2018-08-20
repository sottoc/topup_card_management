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

	$login_user_id = $_SESSION ['login_user_id'];
	
	if (isset($_POST['btnsave'])) 	
	{	
		if(trim($_POST['txt_participant_name'] == ''))
		{
			$errors[] = '* Please Fill Student Name';
		}
		else if(trim($_POST['txt_participant_id'] == ''))
		{
			$errors[] = '* Please Fill Student ID';
		}
		else if($_POST['sel_participant_gender'] == '-1')
		{
			$errors[] = '* Please Select Student Gender';
		}
		if(count($errors) == 0)
		{
			$participant_name = $_POST['txt_participant_name'];
			$participant_enroll_no = $_POST['txt_participant_id'];
			$org_id = $_POST['sel_org'];
			$participant_gender_id = $_POST['sel_participant_gender'];
			$participant_description = $_POST['txt_participant_desc'];
			$organizer_id = $login_user_id;
			$participant_allergy_flag = $_POST['chk_allergy'];
			$others_allergy_food_description = $_POST['txt_other_food_allergy'];
			
			//check duplicate student by student id(student enroll no)
			$check_dup_saving_result=$participantbol->check_duplicate_participant_saving($participant_enroll_no);
			$row = $check_dup_saving_result->getNext();
			$count_duplicate_saving = $row['count_duplicate_saving'];
			if($count_duplicate_saving==0)
			{
				//save in tbl_participant
				$participantinfo->set_participant_name($participant_name);
				$participantinfo->set_participant_enroll_no($participant_enroll_no);
				$participantinfo->set_org_id($org_id);
				$participantinfo->set_participant_allergy_flag($participant_allergy_flag);
				$participantinfo->set_others_allergy_food_description($others_allergy_food_description);
				$participantinfo->set_participant_description($participant_description);
				$participantinfo->set_participant_gender_id($participant_gender_id);
				$participantinfo->set_organizer_id($organizer_id);
				$participantinfo->set_participant_created_datetime($now_date_time);
				$participantinfo->set_participant_modified_datetime($now_date_time);
				$participant_id = $participantbol->save_participant($participantinfo);
				
				if($participant_allergy_flag=='on')
				{
					//save in tbl_participant_food_allergy
					if(!empty($_POST['check_list'])) 
					{
						foreach($_POST['check_list'] as $check)
						{
							$participant_food_allergyinfo->set_participant_id($participant_id);
							$participant_food_allergyinfo->set_food_allergy_id($check);
							$result = $participantbol->save_participant_food_allergy($participant_food_allergyinfo);
						}
					}
				}
				if($participant_id)
					$_SESSION['participant_message'] = "Student saved successfully.";
				else
					$_SESSION['participant_message'] = "Student saved fail.";
			}
			else
				$_SESSION['participant_message'] = "Duplicate student ID.";
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
				'txt_participant_name':{'required':"*Please Fill Student Name"},	
				'txt_participant_id':{'required':"*Please Fill Student ID"},	
				'sel_participant_gender':{'required':"*Please Select Gender"}	
			},				
		'errorPlacement': function(error, element) {
			$(element).after(error);
		}
		});
	}	

</script>
<style type="text/css">
</style>

<div class="content_data">
	<form action="" method="POST" id="addnew_participant" name="addnew_participant" >
		<h2>Add New Student</h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join( $errors)."</div>";
	  			}
			?>
			<div class="frm">
				<div class="frm_labelcat">Student Name:</div>
				<input type="text" name="txt_participant_name" id="txt_participant_name" />
			</div>
			
			<div class="frm">
				<div class="frm_labelcat">Student ID:</div>
				<input type="text" name="txt_participant_id" id="txt_participant_id" />
			</div>
			
			<div class="frm">
				<div class="frm_labelcat">Student Gender:</div>
				<select id="sel_participant_gender" name="sel_participant_gender">
					<option value="-1">--Select Student Gender--</option>
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
				<div class="frm_labelcat">School:</div>
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
				<div class="frm_labelcat">Student Description:</div>
				<textarea id="txt_participant_desc" name="txt_participant_desc"></textarea>
			</div>
			
			<div class="frm">
				<div class="frm_labelcat">Student Allergy:</div>
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
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnsave"  id="btnsave" value="<?php echo $localized_home_data['save_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='participant_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>			
	</form>
</div><!-- /content_data -->
<?php
	include('library/closedb.php');
	include("footer.php");
?>