<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$errors = array();
	$student_predefinebol = new student_predefinebol();
	$participantbol = new participantbol();
	$student_predefineinfo=new student_predefineinfo();	
	$participant_food_allergyinfo = new participant_food_allergyinfo();
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');

	if (isset($_POST['btnsave'])) 	
	{	
		$uploaded_file = '';
		if(trim($_POST['txt_student_name'] == ''))
		{
			$errors[] = '* Check Student Name';
		}
		else if(trim($_POST['txt_student_id'] == ''))
		{
			$errors[] = '* Check Student Id';
		}
		else if(trim($_POST['txt_finger_number'] == ''))
		{
			$errors[] = '* Check Finger Print Number';
		}
		else if(trim($_POST['txt_parent_name'] == ''))
		{
			$errors[] = '* Check Parent Name';
		}
		/*if ($_FILES["upload_mc"]["error"] > 0)
	    {
	        $errors[] = "Return Code: " . $_FILES["upload_mc"]["error"] . "<br>";
	    }*/
		if(count($errors) == 0)
		{
			
			$student_gender_id = $_POST['sel_student_gender'];
			
			$student_allergy_flag = 0;
			if(isset($_POST['chk_allergy']) && $_POST['chk_allergy']!='' ){
				$student_allergy_flag = 1;
			}

			$others_allergy_food_description = '';
			if(isset($_POST['txt_other_food_allergy']) && $_POST['txt_other_food_allergy']!='' )
				$others_allergy_food_description = $_POST['txt_other_food_allergy'];

			$student_description = '';
			if(isset($_POST['txt_student_desc']) && $_POST['txt_student_desc']!='' )
				$student_description = $_POST['txt_student_desc'];

			$allow_canteen_order = 0;
			if(isset($_POST['chk_allow_canteen_order']) && $_POST['chk_allow_canteen_order']!='')
				$allow_canteen_order = 1;

			$studentID = $_POST['txt_student_id'];

			$student_predefineinfo->set_predefine_participant_name($_POST['txt_student_name']);	
			$student_predefineinfo->set_predefine_participant_enroll_no($studentID);
			$student_predefineinfo->set_predefine_org_id($_POST['sel_school_name']);
			$student_predefineinfo->set_student_class($_POST['txt_class_name']);
			$student_predefineinfo->set_finger_print_number($_POST['txt_finger_number']);
			$student_predefineinfo->set_predefine_parent_name($_POST['txt_parent_name']);
			$student_predefineinfo->set_student_allergy_flag($student_allergy_flag);
			$student_predefineinfo->set_others_allergy_food_description($others_allergy_food_description);
			$student_predefineinfo->set_student_description($student_description);
			$student_predefineinfo->set_student_gender_id($student_gender_id);
			$student_predefineinfo->set_created($now_date_time);
			$student_predefineinfo->set_modified($now_date_time);
			$student_predefineinfo->set_allow_canteen_order($allow_canteen_order);
			$student_predefineinfo->set_upload_file($uploaded_file);

			$check_dup_saving_result=$student_predefinebol->check_duplicate_stunameandparname_saving($_POST['txt_student_name'],$_POST['txt_parent_name']);
			$row = $check_dup_saving_result->getNext();
			$count_duplicate_saving = $row['count_duplicate_saving'];
			if($count_duplicate_saving==0)
			{
				//save upload file 
				if($_FILES["upload_mc"]["size"] > 0){
					if(!is_dir("upload/".$studentID)){
						mkdir("upload/".$studentID);
					}
					$file_arr = explode(".", $_FILES["upload_mc"]["name"]);
					if(count($file_arr) >0 )
					{
						$file_name = $file_arr[0];
						$file_type = $file_arr[1];
					}
					$currentTime = currentTimeStamp();
					$uploaded_file = $studentID.'/'.$file_name.'_'.$currentTime.'.'.$file_type;
					move_uploaded_file($_FILES["upload_mc"]["tmp_name"], "upload/".$uploaded_file);
					$student_predefineinfo->set_upload_file($uploaded_file);
				}

				$saved_predefined_student_id = $student_predefinebol->save_student_predefine($student_predefineinfo);

				if($student_allergy_flag==1)
				{
					//save in tbl_participant_food_allergy
					if(!empty($_POST['check_list'])) 
					{
						foreach($_POST['check_list'] as $check)
						{
							//$participant_food_allergyinfo->set_participant_id(NULL);
							$participant_food_allergyinfo->set_predefine_participant_id($saved_predefined_student_id);
							$participant_food_allergyinfo->set_food_allergy_id($check);
							$result = $participantbol->save_participant_food_allergy($participant_food_allergyinfo);
						}
					}
				}

				if($saved_predefined_student_id)
					$_SESSION['student_msg'] = $localized_data['save_staff_msg'];
				else
					$_SESSION['student_msg'] = $localized_home_data['save_fail_msg'];
			}
			else
				$_SESSION['student_msg'] = "Duplicate Student Name and Parent Name";
				
			header("location:predefine_participant_list.php");
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
		jQuery("#addnew_student").validate(
		{			
		'rules':
			{		
				'txt_student_name':{'required':true},	
				'txt_student_id':{'required':true},	
				'txt_finger_number':{'required':true},
				'txt_parent_name':{'required':true}				
			},
		'messages':
			{
				'txt_student_name':{'required':"*Required Student Name"},	
				'txt_student_id':{'required':"*Required Student Id"},	
				'txt_finger_number':{'required':"*Required Finger Print Number"},
				'txt_parent_name':{'required':"*Required Parent Name"}					
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
	<form action="" method="POST" id="addnew_student" name="addnew_student" enctype="multipart/form-data">
		<h2>Create Student </h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join( $errors)."</div>";
	  			}
			?>
			<div class="frm">
				<div class="frm_labelcat">Student Name:</div>
				<input type="text" name="txt_student_name" id="txt_student_name" />
			</div>
			<div class="frm">
				<div class="frm_labelcat">Student Id:</div>
				<input type="text" name="txt_student_id" id="txt_student_id" />
			</div>
			<div class="frm">
				<div class="frm_labelcat">Finger Print Number:</div>
				<input type="text" name="txt_finger_number" id="txt_finger_number" />
			</div>
			<div class="frm">
				<div class="frm_labelcat">Parent Name:</div>
				<input type="text" name="txt_parent_name" id="txt_parent_name" />
			</div>
			<div class="frm">
				<div class="frm_labelcat">School Name:</div>
				<select id="sel_school_name" name="sel_school_name">
				<option value="-1">--Select School--</option>
				<?php
					$setupbol = new setupbol();
					$sel_setup_result = $setupbol->get_all_org();
					while($sel_row=$sel_setup_result->getNext())
					{
						echo '<option value="'.$sel_row['org_id'].'"';
						echo ">".$sel_row['org_name']."</option>";
					}
				?>
				</select>
			</div>
			<div class="frm">
				<div class="frm_labelcat">Class:</div>
				<input type="text" name="txt_class_name" id="txt_class_name" />
			</div>
			<div class="frm">
				<div class="frm_labelcat">Gender:</div>
				<select id="sel_student_gender" name="sel_student_gender">
					<option value="-1">--Select Gender--</option>
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
				<div class="frm_labelcat">Description:</div>
				<textarea id="txt_student_desc" name="txt_student_desc"></textarea>
			</div>
			
			<div class="frm">
				<div class="frm_labelcat">Food Allergy:</div>
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
			</div>

			<div class="frm">
				<div class="frm_labelcat">Allow Order:</div>
				<input type="checkbox" name="chk_allow_canteen_order" id="chk_allow_canteen_order" />
				<span>If allow student to order their own foods, please tick this check box.</span>
			</div>
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnsave"  id="btnsave" value="Save" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='predefine_participant_list.php'" value="Cancel" class="btn" />
			</div>			
	</form>
</div><!-- /content_data -->
<?php
	include('library/closedb.php');
	include("footer.php");
?>