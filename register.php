<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	session_start();
	
	$errors = array();
	$userbol = new userbol();
	$localizationbol= new localizationbol();
	$userinfo=new userinfo();	
	$organizerinfo=new organizerinfo();
	$send_mail_result = false;
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('register',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_user_result=$localizationbol->get_localization_by_pagename('user',1);
	while($row=$localized_user_result->getNext())
	{
		$localized_user_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//Kuala Lumpur, Singapore
    $now_date_time = date('Y-m-d H:i:s');
	
	$host = $_SERVER['HTTP_HOST'];
	$request_uri = $_SERVER['REQUEST_URI'];
	$folder_path_arr = explode("/",$request_uri);
	$folder_path = $folder_path_arr[1];
	$host_full_url = $host."/".$folder_path;
	
	if (isset($_POST['register_btn'])) 	
	{	
		if(trim($_POST['txt_reg_email']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_register_email'];
		}
		else if(trim($_POST['txt_reg_name']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_register_name'];
		}
		else if(trim($_POST['txt_reg_pw']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_register_pw'];
		}
		else if(trim($_POST['txt_reg_address']) == '')
		{
			$errors[] = '*Please fill address.';
		}
		else if(trim($_POST['txt_reg_phone']) == '')
		{
			$errors[] = '*Please fill phone number.';
		}
		else if(trim($_POST['sel_reg_gender']) == '')
		{
			$errors[] = '*Please choose gender.';
		}
			
		if(count($errors) == 0)
		{
			$userinfo->set_user_type_id(2);//for parent
			$register_name = $_POST['txt_reg_name'];
			$register_email = $_POST['txt_reg_email'];
			$userinfo->set_user_name($_POST['txt_reg_name']);	
			$userinfo->set_user_email($_POST['txt_reg_email']);
			$userinfo->set_user_password($_POST['txt_reg_pw']);
			$userinfo->set_user_address($_POST['txt_reg_address']);
			$userinfo->set_user_phone($_POST['txt_reg_phone']);
			$userinfo->set_user_gender_id($_POST['sel_reg_gender']);
			$userinfo->set_user_created_datetime($now_date_time);
			$userinfo->set_user_modified_datetime($now_date_time);
			
			$check_dup_saving_result=$userbol->check_duplicate_staff_saving($_POST['txt_reg_email']);//this function is also for parent/organizer user.
			$row = $check_dup_saving_result->getNext();
			$count_duplicate_saving = $row['count_duplicate_saving'];
			if($count_duplicate_saving==0)
			{
				$user_id_saving_result=$userbol->save_staff($userinfo);//this function is also for parent/organizer user.
				if($user_id_saving_result!=false)
				{
					$organizerinfo->set_user_id($user_id_saving_result);
					//$organizerinfo->set_organizer_description($_POST['txt_reg_description']);
					$result=$userbol->save_organizer($organizerinfo);
					//send verification email
					$message = "     
								  Hello $register_name,
								  <br /><br />
								  Welcome to Topup Management System!<br/>
								  To complete your registration  please , just click following link<br/>
								  <br /><br />
								  <a href='".$host_full_url."/verify_account.php?email=".$register_email."'>Click HERE to Activate Your Account :)</a>
								  <br /><br />
								  Thanks,";
  
					$subject = "Confirm Registration";
					$email = $_POST['txt_reg_email'];	
					$send_mail_result = send_mail($email,$message,$subject); 
					if($send_mail_result==true)
					{
						$_SESSION['reg_msg'] = "Register Successfully.Please check your email to activate your account.";
						//$_SESSION ['login_user_type_id'] = 2;
						//$_SESSION ['login_user_id'] = $result;
						//$_SESSION ['login_user_name'] = $_POST['txt_reg_name'];
						//$_SESSION ['login_user_email'] = $_POST['txt_reg_email'];
						//header ( "location:index.php" );
						//exit();
					}
					else
					{
						$_SESSION['reg_msg'] = "Register Fail.";
					}
				}
				
			}
			else
			{
				$_SESSION['reg_msg'] = "Duplicate register email.Please retry with another email.";
			}
		}
	}
	
//-----------------------//
require_once('login_header.php');
	
?>
<script type="text/javascript">			
	jQuery(document).ready(function(){	
		AddValidation();
		show_item_msg();
	});	
	
	function AddValidation()
	{
		jQuery("#register_form").validate(
		{			
			'rules':
				{		
					'txt_reg_email':{'required':true,'email':true},	
					'txt_reg_name':{'required':true},	
					'txt_reg_pw':{'required':true, 'minlength': 6},
					'txt_reg_confirm_pw': {'required': true,'minlength': 6,'equalTo': "#txt_reg_pw"}
				},
			'messages':
				{
					'txt_reg_email':{'required':"*<?php echo $localized_data['check_req_field_register_email']; ?>",'email':"*<?php echo $localized_home_data['check_valid_email_adddress']; ?>"},
					'txt_reg_name':{'required':"*<?php echo $localized_data['check_req_field_register_name']; ?>"},
					'txt_reg_pw':{'required':"*<?php echo $localized_data['check_req_field_register_pw']; ?>"},
				},				
			'errorPlacement': function(error, element) {
				$(element).after(error);
			}
		});
	}
	
	function show_item_msg()
	{
		<?php 
			if(isset($_SESSION['reg_msg'])) 
			{
				$reg_msg = $_SESSION['reg_msg'];
		?>
			var msg = '<?php echo $reg_msg; ?>';
			message_popup(msg,350);
		<?php
			}
			unset ( $_SESSION ['reg_msg'] );
			session_write_close ();
		?>
	}	
</script>

<div class="content_data">
	<form action="" method="POST" id="register_form" name="register_form">

		<h2><?php echo $localized_home_data['register']; ?></h2>
			<!--showing msgs-->
			<p>&nbsp;</p>
			<?php
				echo "<div id='message'>";
				if(isset($_SESSION['reg_msg']))
				{
					$reg_msg = $_SESSION['reg_msg'];
					echo $reg_msg;
				}
				echo "</div><br/>";
				unset ( $_SESSION ['reg_msg'] );
				session_write_close ();
			?>
		<!--showing msgs-->
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['register_email']; ?>: </div>
				<input type="text" name="txt_reg_email" id="edt_txt_reg_email"/>
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['register_name']; ?>: </div>
				<input type="text" name="txt_reg_name" id="edt_txt_reg_name"/>
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['register_pw']; ?>:</div>
				<input type="password" name="txt_reg_pw" id="txt_reg_pw"/>
			</div>
			<div class="frm">
				<div class="frm_labelcat">Confirm Password:</div>
				<input type="password" name="txt_reg_confirm_pw" id="txt_reg_confirm_pw"/>
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_user_data['address']; ?>:</div>
				<input type="text" name="txt_reg_address" id="txt_reg_address"/>
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_user_data['phone']; ?>:</div>
				<input type="text" name="txt_reg_phone" id="txt_reg_phone"/>
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_home_data['gender']; ?>:</div>
				<select id="sel_reg_gender" name="sel_reg_gender">
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
			<!--div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['description']; ?>:</div>
				<textarea id="txt_reg_description" name="txt_reg_description"></textarea>
			</div-->
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="register_btn"  id="register_btn" value="<?php echo $localized_data['register_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='userlogin.php'" value="Login" class="btn" />
			</div>	
			
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>