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
	
	if(isset($_GET['user_id']) && $_GET['user_id']!='')
	{
		$user_id=(int)$_GET['user_id'];
		$Rresult=$userbol->get_staff_byid($user_id);
		while($aRow = $Rresult->getNext())
		{
			$user_id = $aRow['user_id'];
			$user_name=htmlspecialchars($aRow['user_name']);
			$user_email=htmlspecialchars($aRow['user_email']);
			$user_password=htmlspecialchars($aRow['user_password']);
		}
		$old_user_value_str="staff_id=>".$user_id.",staff_password=>".$user_password;
	}
	
	if (isset($_POST['btn_reset_pw'])) 	
	{	
		if(trim($_POST['edt_txt_new_pw']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_new_pw'];
		}
			
		if(count($errors) == 0)
		{	
			$hid_reset_pw_id = $_POST['hid_reset_pw_id'];
			$hid_old_user_value_str = $_POST['hid_old_user_value_str'];
			$userinfo->set_user_id($hid_reset_pw_id);
			$userinfo->set_user_password($_POST['edt_txt_new_pw']);
			$userinfo->set_user_modified_datetime($now_date_time);
			$result=$userbol->reset_change_pw($userinfo,$hid_old_user_value_str);
			if($result)
				$_SESSION['staff_msg'] = $localized_home_data['save_pw_msg'];
			else
				$_SESSION['staff_msg'] = $localized_home_data['save_fail_msg'];
				
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
		jQuery("#reset_pw").validate(
		{			
		'rules':
			{		
				'edt_txt_new_pw':{'required':true},	
			},
		'messages':
			{
				'edt_txt_new_pw':{'required':"*<?php echo $localized_data['check_req_field_new_pw']; ?>"},
			},				
			'errorPlacement': function(error, element) {
				$(element).after(error);
			}
		});
	}	
</script>

<div class="content_data">
	<form action="" method="POST" id="reset_pw" name="reset_pw">

		<h2><?php echo $localized_home_data['reset_pw_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="hid_old_user_value_str" name="hid_old_user_value_str" value="<?php echo $old_user_value_str; ?>"/>
			<input type="hidden" id="hid_reset_pw_id" name="hid_reset_pw_id" value="<?php echo $user_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_name']; ?>: </div>
				<input type="text" name="edt_txt_new_pw" id="edt_txt_new_pw" value="<?php echo $user_name; ?>" disabled />
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['staff_email']; ?>: </div>
				<input type="text" name="edt_txt_new_pw" id="edt_txt_new_pw" value="<?php echo $user_email; ?>" disabled />
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_home_data['new_pw']; ?>:</div>
				<input type="password" name="edt_txt_new_pw" id="edt_txt_new_pw"/>
			</div>
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btn_reset_pw"  id="btn_reset_pw" value="<?php echo $localized_home_data['save_pw_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='staff_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>	
			
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>