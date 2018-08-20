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
	$student_predefineinfo=new student_predefineinfo();	
	
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
		}
		$old_student_value_str="predefine_participant_id=>".$predefine_participant_id.",predefine_participant_name=>".$predefine_participant_name.",predefine_org_id=>".$predefine_org_id.",finger_print_number=>".$finger_print_number.",predefine_parent_name=>".$predefine_parent_name;
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
			$edt_hid_student_values = $_POST['edt_hid_student_values'];
			$student_predefineinfo->set_predefine_participant_id($_POST['edt_hid_participant_id']);
			$student_predefineinfo->set_predefine_participant_name($_POST['edt_txt_student_name']);	
			$student_predefineinfo->set_predefine_participant_enroll_no($_POST['edt_txt_student_id']);
			$student_predefineinfo->set_predefine_org_id($_POST['edt_sel_school_name']);
			$student_predefineinfo->set_finger_print_number($_POST['edt_txt_finger_number']);
			$student_predefineinfo->set_predefine_parent_name($_POST['edt_txt_parent_name']);
			
			$check_dup_updating_result=$student_predefinebol->check_duplicate_stunameandparname_updating($_POST['edt_txt_student_name'],$_POST['edt_txt_parent_name'],$_POST['edt_hid_participant_id']);
			$row = $check_dup_updating_result->getNext();
			$count_duplicate_updating = $row['count_duplicate_updating'];
			if($count_duplicate_updating==0)
			{
				//print_r("Hi Hi no duplicate");exit();
				$result=$student_predefinebol->update_student_predefine($student_predefineinfo,$edt_hid_student_values);
				if($result)
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
	});	
	
	function AddValidation()
	{
		jQuery("#edit_student").validate(
		{			
		'rules':
			{		
				'edt_txt_student_name':{'required':true},	
				'edt_txt_student_id':{'required':true,'email':true},	
				'edt_txt_finger_number':{'required':true},
				'edt_txt_parent_name':{'required':true}				
			},
		'messages':
			{
				'edt_txt_student_name':{'required':"*Required Student Name"},	
				'edt_txt_student_id':{'required':"*Required Student Id},	
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
	<form action="" method="POST" id="edit_student" name="edit_student">

		<h2>Edit Student</h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="edt_hid_student_values" name="edt_hid_student_values" value="<?php echo $old_student_value_str; ?>"/>
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