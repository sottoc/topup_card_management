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
	
	if(isset($_GET['staff_id']) && $_GET['staff_id']!='')
	{
		$staff_id=(int)$_GET['staff_id'];
		$Rresult=$userbol->get_staff_byid($staff_id);
		while($aRow = $Rresult->getNext())
		{
			$staff_name=htmlspecialchars($aRow['user_name']);
			$staff_email=htmlspecialchars($aRow['user_email']);
			//$staff_password=htmlspecialchars($aRow['user_password']);
			$staff_address=htmlspecialchars($aRow['user_address']);
			$staff_phone=htmlspecialchars($aRow['user_phone']);
			$staff_gender_id=htmlspecialchars($aRow['user_gender_id']);
		}
		$old_staff_value_str="staff_id=>".$staff_id.",staff_name=>".$staff_name.",staff_email=>".$staff_email.",staff_address=>".$staff_address.",staff_phone=>".$staff_phone.",staff_gender_id=>".$staff_gender_id;
	}
	
	if (isset($_POST['btnedit'])) 	
	{
		if(trim($_POST['edt_txt_staff_name']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_staff_name'];
		}
		else if(trim($_POST['edt_txt_staff_email'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_staff_email'];
		}
		/*else if(trim($_POST['edt_txt_staff_password'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_staff_password'];
		}*/		
		
		if(count($errors) == 0)
		{
			$edt_hid_staff_values = $_POST['edt_hid_staff_values'];
			$userinfo->set_user_id($_POST['edt_hid_staff_id']);
			$userinfo->set_user_name($_POST['edt_txt_staff_name']);	
			$userinfo->set_user_email($_POST['edt_txt_staff_email']);
			//$userinfo->set_user_password($_POST['edt_txt_staff_password']);
			$userinfo->set_user_address($_POST['edt_txt_staff_address']);
			$userinfo->set_user_phone($_POST['edt_txt_staff_phone']);
			$userinfo->set_user_gender_id($_POST['edt_sel_staff_gender']);
			$userinfo->set_user_modified_datetime($now_date_time);
			
			$check_dup_updating_result=$userbol->check_duplicate_staff_updating($_POST['edt_txt_staff_name'],$_POST['edt_hid_staff_id']);
			$row = $check_dup_updating_result->getNext();
			$count_duplicate_updating = $row['count_duplicate_updating'];
			if($count_duplicate_updating==0)
			{
				$result=$userbol->update_staff($userinfo,$edt_hid_staff_values);
				if($result)
					$_SESSION['staff_msg'] = $localized_data['update_staff_msg'];
				else
					$_SESSION['staff_msg'] = $localized_home_data['update_fail_msg'];
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
		jQuery("#edit_staff").validate(
		{			
		'rules':
			{		
				'edt_txt_staff_name':{'required':true},	
				'edt_txt_staff_email':{'required':true,'email':true},	
				//'edt_txt_staff_password':{'required':true}	
			},
		'messages':
			{
				'edt_txt_staff_name':{'required':"*<?php echo $localized_data['check_req_field_staff_name']; ?>"},	
				'edt_txt_staff_email':{'required':"*<?php echo $localized_data['check_req_field_staff_email']; ?>",'email':"*<?php echo $localized_home_data['check_valid_email_adddress']; ?>"},	
				//'edt_txt_staff_password':{'required':"*<?php echo $localized_data['check_req_field_staff_password']; ?>"}	
			},				
			'errorPlacement': function(error, element) {
				$(element).after(error);
			}
		});
	}	
</script>

<div class="content_data">
	<form action="" method="POST" id="edit_staff" name="edit_staff">

		<h2><?php echo $localized_data['edt_staff_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="edt_hid_staff_values" name="edt_hid_staff_values" value="<?php echo $old_staff_value_str; ?>"/>
			<input type="hidden" id="edt_hid_staff_id" name="edt_hid_staff_id" value="<?php echo $staff_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_name']; ?>:</div>
				<input type="text" name="edt_txt_staff_name" id="edt_txt_staff_name" value="<?php echo $staff_name; ?>" />
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_email']; ?>:</div>
				<input type="text" name="edt_txt_staff_email" id="edt_txt_staff_email" value="<?php echo $staff_email; ?>" />
			</div>
			<!--div class="frm">
				<div class="frm_labelcat"><?php //echo $localized_data['staff_password']; ?>:</div>
				<input type="password" name="edt_txt_staff_password" id="edt_txt_staff_password" value="<?php echo $staff_password; ?>" />
			</div-->
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_address']; ?>:</div>
				<textarea name="edt_txt_staff_address" id="edt_txt_staff_address"><?php echo $staff_address; ?></textarea>
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_phone']; ?>:</div>
				<input type="text" name="edt_txt_staff_phone" id="edt_txt_staff_phone" value="<?php echo $staff_phone; ?>"/>
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_gender']; ?>:</div>
				<select id="edt_sel_staff_gender" name="edt_sel_staff_gender">
				<option value="-1">--Select Gender--</option>
				<?php
					$userbol = new userbol();
					$sel_genders_result = $userbol->get_all_genders();
					while($sel_row=$sel_genders_result->getNext())
					{
						echo '<option value="'.$sel_row['gender_id'].'"';
						if($staff_gender_id==$sel_row['gender_id'])
						echo 'selected';
						echo ">".$sel_row['gender_name']."</option>";
					}
				?>
				</select>
			</div>
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnedit"  id="btnedit" value="<?php echo $localized_home_data['edit_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='staff_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>	
			
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>