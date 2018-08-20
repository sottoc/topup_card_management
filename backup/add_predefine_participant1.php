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
	
	if (isset($_POST['btnsave'])) 	
	{	
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
		if(count($errors) == 0)
		{
			$student_predefineinfo->set_predefine_participant_name($_POST['txt_student_name']);	
			$student_predefineinfo->set_predefine_participant_enroll_no($_POST['txt_student_id']);
			$student_predefineinfo->set_predefine_org_id($_POST['sel_school_name']);
			$student_predefineinfo->set_finger_print_number($_POST['txt_finger_number']);
			$student_predefineinfo->set_predefine_parent_name($_POST['txt_parent_name']);
			
			$check_dup_saving_result=$student_predefinebol->check_duplicate_stunameandparname_saving($_POST['txt_student_name'],$_POST['txt_parent_name']);
			$row = $check_dup_saving_result->getNext();
			$count_duplicate_saving = $row['count_duplicate_saving'];
			if($count_duplicate_saving==0)
			{
				$result=$student_predefinebol->save_student_predefine($student_predefineinfo);
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
		jQuery("#addnew_student").validate(
		{			
		'rules':
			{		
				'txt_student_name':{'required':true},	
				'txt_student_id':{'required':true,'email':true},	
				'txt_finger_number':{'required':true},
				'txt_parent_name':{'required':true}				
			},
		'messages':
			{
				'txt_student_name':{'required':"*Required Student Name"},	
				'txt_student_id':{'required':"*Required Student Id},	
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
	<form action="" method="POST" id="addnew_student" name="addnew_student" >
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