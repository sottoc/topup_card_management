<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');

	$errors = array();
	$student_predefinebol = new student_predefinebol();
	$student_predefineinfo=new student_predefineinfo();	
	$participant_food_allergyinfo = new participant_food_allergyinfo();
	$participantbol = new participantbol();


	if(isset($_GET['predefine_participant_id']) && $_GET['predefine_participant_id']!='')
	{
		$predefine_participant_id=(int)$_GET['predefine_participant_id'];
		$Rresult=$student_predefinebol->get_student_predefine_byid($predefine_participant_id);
		while($aRow = $Rresult->getNext())
		{
			$predefine_participant_name=htmlspecialchars($aRow['predefine_participant_name']);
			$predefine_participant_enroll_no=htmlspecialchars($aRow['predefine_participant_enroll_no']);
			$predefine_org_id=htmlspecialchars($aRow['predefine_org_id']);
			$finger_print_number=htmlspecialchars($aRow['finger_print_number']);
			$predefine_parent_name=htmlspecialchars($aRow['predefine_parent_name']);
			$student_class=htmlspecialchars($aRow['student_class']);
			$student_description=htmlspecialchars($aRow['student_description']);
			$student_gender_id=htmlspecialchars($aRow['student_gender_id']);
			$student_allergy_flag=htmlspecialchars($aRow['student_allergy_flag']);
			$allow_canteen_order=htmlspecialchars($aRow['allow_canteen_order']);
			$others_allergy_food_description = htmlspecialchars($aRow['others_allergy_food_description']);
			$upload_file=htmlspecialchars($aRow['upload_file']);
			$upload_file_name = '';
			if($upload_file!=''){
				$upload_files = explode("/", $upload_file);
				$upload_file_name = $upload_files[1];
			}
		}
		$old_student_value_str="predefine_participant_id=>".$predefine_participant_id.",predefine_participant_name=>".$predefine_participant_name.",predefine_org_id=>".$predefine_org_id.",finger_print_number=>".$finger_print_number.",predefine_parent_name=>".$predefine_parent_name.",student_class=>".$student_class.",student_description=>".$student_description.",student_gender_id=>".$student_gender_id.",student_allergy_flag=>".$student_allergy_flag.",allow_canteen_order=>".$allow_canteen_order.",others_allergy_food_description=>".$others_allergy_food_description.",upload_file=>".$upload_file;

		$food_allergy_array = array();
		$old_foodallery_value_str = ''; //separated by &&&
		$fa_result = $student_predefinebol->select_allergyfoods_byid($predefine_participant_id);
		if($fa_result->rowCount()!= 0){
			while($faRow  =  $fa_result->getNext())
			{
				$food_allergy_array[]= $faRow['food_allergy_name'];
				$old_foodallery_value_str .=  $faRow['food_allergy_name'] .'&&&';
			}
		}
		$old_foodallery_value_str = rtrim($old_foodallery_value_str,'&&&');
	}
	
	if (isset($_POST['btnedit'])) 	
	{
		if(trim($_POST['edt_txt_student_name'] == ''))
		{
			$errors[] = '* Check Student Name';
		}
		else if(trim($_POST['edt_txt_student_id'] == ''))
		{
			$errors[] = '* Check Student Id';
		}
		else if(trim($_POST['edt_txt_finger_number'] == ''))
		{
			$errors[] = '* Check Finger Print Number';
		}
		else if(trim($_POST['edt_txt_parent_name'] == ''))
		{
			$errors[] = '* Check Parent Name';
		}
		
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
			
			$uploaded_file = '';
			$studentID = $_POST['edt_txt_student_id'];
			$edt_hid_student_values = $_POST['edt_hid_student_values'];
			$student_predefineinfo->set_predefine_participant_id($_POST['edt_hid_participant_id']);
			$student_predefineinfo->set_predefine_participant_name($_POST['edt_txt_student_name']);	
			$student_predefineinfo->set_predefine_participant_enroll_no($_POST['edt_txt_student_id']);
			$student_predefineinfo->set_predefine_org_id($_POST['edt_sel_school_name']);
			$student_predefineinfo->set_finger_print_number($_POST['edt_txt_finger_number']);
			$student_predefineinfo->set_predefine_parent_name($_POST['edt_txt_parent_name']);
			$student_predefineinfo->set_student_allergy_flag($student_allergy_flag);
			$student_predefineinfo->set_others_allergy_food_description($others_allergy_food_description);
			$student_predefineinfo->set_student_description($student_description);
			$student_predefineinfo->set_student_gender_id($student_gender_id);
			$student_predefineinfo->set_student_class($_POST['txt_class_name']);
			$student_predefineinfo->set_modified($now_date_time);
			$student_predefineinfo->set_allow_canteen_order($allow_canteen_order);
			$student_predefineinfo->set_upload_file($uploaded_file);

			$check_dup_updating_result=$student_predefinebol->check_duplicate_stunameandparname_updating($_POST['edt_txt_student_name'],$_POST['edt_txt_parent_name'],$_POST['edt_hid_participant_id']);
			$row = $check_dup_updating_result->getNext();
			$count_duplicate_updating = $row['count_duplicate_updating'];
			
			if($count_duplicate_updating==0)
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

				$result=$student_predefinebol->update_student_predefine($student_predefineinfo,$edt_hid_student_values);
				if($result){
					$_SESSION['student_msg'] = $localized_data['save_staff_msg'];

					//delete allergy foods
					$result = $student_predefinebol->delete_participant_food_allergy($predefine_participant_id);

					//Save allergy foods withing this student
					if($student_allergy_flag==1)
					{
						//update in tbl_participant_food_allergy
						if(!empty($_POST['check_list'])) 
						{
							foreach($_POST['check_list'] as $check)
							{
								$participant_food_allergyinfo->set_predefine_participant_id($predefine_participant_id);
								$participant_food_allergyinfo->set_food_allergy_id($check);
								$result = $participantbol->save_participant_food_allergy($participant_food_allergyinfo);
							}
						}
					}

				}
				else{
					$_SESSION['student_msg'] = $localized_home_data['save_fail_msg'];
				}
			}
			else
				$_SESSION['student_msg'] = "Duplicate Student Name and Parent Name";
				
			header("location:predefine_participant_list.php");
			exit();
		}
	}
	
	require_once('header.php');
?>

<script type="text/javascript">			
	jQuery(document).ready(function(){	
		AddValidation();
		if($("#chk_allergy").is(':checked') == true){
			$("#div_sel_food_allergy").show();
			$("#div_other_food_allergy").show();
		}

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
		jQuery("#edit_student").validate(
		{			
		'rules':
			{		
				'edt_txt_student_name':{'required':true},	
				'edt_txt_student_id':{'required':true},	
				'edt_txt_finger_number':{'required':true},
				'edt_txt_parent_name':{'required':true}				
			},
		'messages':
			{
				'edt_txt_student_name':{'required':"*Required Student Name"},	
				'edt_txt_student_id':{'required':"*Required Student Id"},	
				'edt_txt_finger_number':{'required':"*Required Finger Print Number"},
				'edt_txt_parent_name':{'required':"*Required Parent Name"}					
			},				
		'errorPlacement': function(error, element) {
			$(element).after(error);
		}
		});
	}	
</script>

<div class="content_data">
	<form action="" method="POST" id="edit_student" name="edit_student" enctype="multipart/form-data">

		<h2>Edit Student</h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="edt_hid_student_values" name="edt_hid_student_values" value="<?php echo $old_student_value_str; ?>"/>
			<input type="hidden" name="edt_hid_foodallery_values" value="<?php echo $old_foodallery_value_str; ?>">
			<input type="hidden" id="edt_hid_participant_id" name="edt_hid_participant_id" value="<?php echo $predefine_participant_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat">Student Name :</div>
				<input type="text" name="edt_txt_student_name" id="edt_txt_student_name" value="<?php echo $predefine_participant_name; ?>" />
			</div>

			<div class="frm">
				<div class="frm_labelcat">Student Id :</div>
				<input type="text" name="edt_txt_student_id" id="edt_txt_student_id" value="<?php echo $predefine_participant_enroll_no; ?>" />
			</div>

			<div class="frm">
				<div class="frm_labelcat">Finger Print Number :</div>
				<input type="text" name="edt_txt_finger_number" id="edt_txt_finger_number" value="<?php echo $finger_print_number; ?>" />
			</div>

			<div class="frm">
				<div class="frm_labelcat">Parent Name:</div>
				<input type="text" name="edt_txt_parent_name" id="edt_txt_parent_name" value="<?php echo $predefine_parent_name; ?>" />
			</div>

			<div class="frm">
				<div class="frm_labelcat">School Name :</div>
				<select id="edt_sel_school_name" name="edt_sel_school_name">
				<option value="-1">--Select School--</option>
				<?php
					$setupbol = new setupbol();
					$sel_setup_result = $setupbol->get_all_org();
					while($sel_row=$sel_setup_result->getNext())
					{
						echo '<option value="'.$sel_row['org_id'].'"';
						if($predefine_org_id==$sel_row['org_id'])
						echo 'selected';
						echo ">".$sel_row['org_name']."</option>";
					}
				?>
				</select>
			</div>

			<div class="frm">
				<div class="frm_labelcat">Class:</div>
				<input type="text" name="txt_class_name" id="txt_class_name" value="<?php echo $student_class; ?>" />
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
						$sel = '';
						if($student_gender_id==$sel_row['gender_id']) $sel = 'selected';
						echo '<option value="'.$sel_row['gender_id'].'" ';
						echo $sel.">".$sel_row['gender_name']."</option>";
					}
					?>
				</select>
			</div>
			
			<div class="frm">
				<div class="frm_labelcat">Description:</div>
				<textarea id="txt_student_desc" name="txt_student_desc" ><?php echo $student_description; ?></textarea>
			</div>
			
			<div class="frm">
				<div class="frm_labelcat">Food Allergy:</div>
				<input type="checkbox" name="chk_allergy" id="chk_allergy" <?php if($student_allergy_flag==1) echo 'checked'; ?> />
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
						$chk = '';
						if(in_array($food_allergy_name, $food_allergy_array))
							$chk = 'checked';
						echo $food_allergy_name."<input ".$chk." type='checkbox' name='check_list[]' id='chk_food_allergy_".$food_allergy_id."' value='".$food_allergy_id."'>";
					}
				?>
			</div>
			
			<div class="frm" style="display:none;" id="div_other_food_allergy">
				<div class="frm_labelcat">Other:</div>
				<input type="text" name="txt_other_food_allergy" id="txt_other_food_allergy" value="<?php echo $others_allergy_food_description; ?>" />
			</div>
			
			<div class="frm">
				<div class="frm_labelcat">Medical Approve Letter:</div>
				<input type="file" name="upload_mc">
				<b><?php echo $upload_file_name; ?></b>
			</div>

			<div class="frm">
				<div class="frm_labelcat">Allow Order:</div>
				<input type="checkbox" name="chk_allow_canteen_order" id="chk_allow_canteen_order" <?php if($allow_canteen_order==1) echo 'checked'; ?> />
				<span>If allow student to order their own foods, please tick this check box.</span>
			</div>
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnedit"  id="btnedit" value="Update" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='predefine_participant_list.php'" value="Cancel" class="btn" />
			</div>	
			
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>