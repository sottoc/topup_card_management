<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$errors = array();
	$userbol = new userbol();
	$localizationbol= new localizationbol();
	$userinfo=new userinfo();	
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('staff',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');

	if (isset($_POST['btnsave'])) 	
	{	
		if(trim($_POST['txt_staff_name'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_staff_name'];
		}
		else if(trim($_POST['txt_staff_email'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_staff_email'];
		}
		else if(trim($_POST['txt_staff_password'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_staff_password'];
		}
		if(count($errors) == 0)
		{
			$userinfo->set_user_type_id(3);//for staff
			$userinfo->set_user_name($_POST['txt_staff_name']);	
			$userinfo->set_user_email($_POST['txt_staff_email']);
			$userinfo->set_user_password($_POST['txt_staff_password']);
			$userinfo->set_user_address($_POST['txt_staff_address']);
			$userinfo->set_user_phone($_POST['txt_staff_phone']);
			$userinfo->set_user_gender_id($_POST['sel_staff_gender']);
			$userinfo->set_user_created_datetime($now_date_time);
			$userinfo->set_user_modified_datetime($now_date_time);
			
			$check_dup_saving_result=$userbol->check_duplicate_staff_saving($_POST['txt_staff_email']);
			$row = $check_dup_saving_result->getNext();
			$count_duplicate_saving = $row['count_duplicate_saving'];
			if($count_duplicate_saving==0)
			{
				$result=$userbol->save_staff($userinfo);
				if($result)
					$_SESSION['staff_msg'] = $localized_data['save_staff_msg'];
				else
					$_SESSION['staff_msg'] = $localized_home_data['save_fail_msg'];
			}
			else
				$_SESSION['staff_msg'] = $localized_data['duplicate_staff_msg'];
				
			header("location:staff_list.php");
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
		jQuery("#addnew_staff").validate(
		{			
		'rules':
			{		
				'txt_staff_name':{'required':true},	
				'txt_staff_email':{'required':true,'email':true},	
				'txt_staff_password':{'required':true},	
			},
		'messages':
			{
				'txt_staff_name':{'required':"*<?php echo $localized_data['check_req_field_staff_name']; ?>"},	
				'txt_staff_email':{'required':"*<?php echo $localized_data['check_req_field_staff_email']; ?>",'email':"*<?php echo $localized_home_data['check_valid_email_adddress']; ?>"},	
				'txt_staff_password':{'required':"*<?php echo $localized_data['check_req_field_staff_password']; ?>"},	
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
	<form action="" method="POST" id="addnew_staff" name="addnew_staff" >
		<h2><?php echo $localized_data['add_new_staff_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join( $errors)."</div>";
	  			}
			?>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_name']; ?>:</div>
				<input type="text" name="txt_staff_name" id="txt_staff_name" />
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_email']; ?>:</div>
				<input type="text" name="txt_staff_email" id="txt_staff_email" />
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_password']; ?>:</div>
				<input type="password" name="txt_staff_password" id="txt_staff_password" />
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_address']; ?>:</div>
				<textarea name="txt_staff_address" id="txt_staff_address"></textarea>
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_phone']; ?>:</div>
				<input type="text" name="txt_staff_phone" id="txt_staff_phone" />
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_gender']; ?>:</div>
				<select id="sel_staff_gender" name="sel_staff_gender">
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
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnsave"  id="btnsave" value="<?php echo $localized_home_data['save_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='staffs_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>			
	</form>
</div><!-- /content_data -->
<?php
	include('library/closedb.php');
	include("footer.php");
?>