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
		else if(trim($_POST['txt_reg_first_name']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_register_name'];
		}
		else if(trim($_POST['txt_reg_last_name']) == '')
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
			$register_name = $_POST['txt_reg_first_name'].' '.$_POST['txt_reg_last_name'];
			$register_email = $_POST['txt_reg_email'];
			$userinfo->set_user_name($_POST['txt_reg_first_name'].' '.$_POST['txt_reg_last_name']);	
			$userinfo->set_user_email($_POST['txt_reg_email']);
			$userinfo->set_user_password($_POST['txt_reg_pw']);
			$userinfo->set_user_first_name($_POST['txt_reg_first_name']);
			$userinfo->set_user_last_name($_POST['txt_reg_last_name']);
			$userinfo->set_user_card_id($_POST['txt_reg_card_id']);
			$userinfo->set_user_address($_POST['txt_reg_address']);
			$userinfo->set_user_phone($_POST['txt_reg_phone']);
			$userinfo->set_user_gender_id($_POST['sel_reg_gender']);
			$userinfo->set_user_created_datetime($now_date_time);
			$userinfo->set_user_modified_datetime($now_date_time);
			$userinfo->set_is_active($_POST['txt_user_status']);
			
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
					$_SESSION['reg_msg'] = "Register Successfully!";
					// $send_mail_result = send_mail($email,$message,$subject); 
					// if($send_mail_result==true)
					// {
					// 	$_SESSION['reg_msg'] = "Register Successfully.Please check your email to activate your account.";
					// 	//$_SESSION ['login_user_type_id'] = 2;
					// 	//$_SESSION ['login_user_id'] = $result;
					// 	//$_SESSION ['login_user_name'] = $_POST['txt_reg_first_name'];
					// 	//$_SESSION ['login_user_email'] = $_POST['txt_reg_email'];
					// 	//header ( "location:index.php" );
					// 	//exit();
					// }
					// else
					// {
					// 	$_SESSION['reg_msg'] = "Register Fail.";
					// }
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
					'txt_reg_first_name':{'required':true},
					'txt_reg_last_name':{'required':true},		
					'txt_reg_pw':{'required':true, 'minlength': 6},
					'txt_reg_confirm_pw': {'required': true,'minlength': 6,'equalTo': "#txt_reg_pw"}
				},
			'messages':
				{
					'txt_reg_email':{'required':"*<?php echo $localized_data['check_req_field_register_email']; ?>",'email':"*<?php echo $localized_home_data['check_valid_email_adddress']; ?>"},
					'txt_reg_first_name':{'required':"*<?php echo $localized_data['check_req_field_register_name']; ?>"},
					'txt_reg_last_name':{'required':"*<?php echo $localized_data['check_req_field_register_name']; ?>"},
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
	  <div style="width:50%;margin:0 auto;">
		<h1 style="margin:50px 30px; margin-bottom:15px;text-align:center"> Create an account </h1>
			<!--showing msgs-->
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
				  <table style='width:100%'>
				  	<tr> 
					  <td style="padding-right:20px">
					  	<span class="label-span"> First Name </span>
						<div style="margin-top: 5px;">
							<input type="text" name="txt_reg_first_name" id="edt_txt_reg_first_name" class="input-text-custom"/>
						</div>
					  </td>
					  <td style="padding-left:20px">
					  	<span class="label-span"> Last Name </span>
						<div style="margin-top: 5px;">
							<input type="text" name="txt_reg_last_name" id="edt_txt_reg_last_name" class="input-text-custom"/>
						</div>
					  </td>
					</tr>
				  </table>
			</div>
			<div class="frm">
				<span class="label-span"> <?php echo "Email Address"; ?> </span>
				<div style="margin-top: 5px;">
					<input type="text" name="txt_reg_email" id="edt_txt_reg_email" class="input-text-custom"/>
				</div>
			</div>
			<div class="frm">
				<span class="label-span"><?php echo $localized_data['register_pw']; ?></span>
				<div style="margin-top: 5px;">
					<input type="password" name="txt_reg_pw" id="txt_reg_pw" class="input-text-custom"/>
				</div>
			</div>
			<div class="frm">
				<span class="label-span">Re-enter Password</span>
				<div style="margin-top: 5px;">
					<input type="password" name="txt_reg_confirm_pw" id="txt_reg_confirm_pw" class="input-text-custom"/>
				</div>
			</div>
			<div class="frm" style="display:none">
				<span class="label-span"><?php echo $localized_user_data['address']; ?></span>
				<div style="margin-top: 5px;">
					<input type="text" name="txt_reg_address" id="txt_reg_address" value="address" class="input-text-custom"/>
				</div>
			</div>
			<div class="frm" style="display:none">
				<span class="label-span"><?php echo $localized_user_data['phone']; ?></span>
				<div style="margin-top: 5px;">
					<input type="text" name="txt_reg_phone" id="txt_reg_phone" value="123456789" class="input-text-custom"/>
				</div>
			</div>
			<div class="frm" style="display:none">
				<span class="label-span"><?php echo $localized_home_data['gender']; ?>&nbsp;&nbsp;</span>
				
				<select id="sel_reg_gender" name="sel_reg_gender" class="select-custom" style="border-color:#797070">
				<option value="-1">Select Gender</option>
				<option value='1' selected> Mail </option>
				<!-- <?php
					$userbol = new userbol();
					$sel_genders_result = $userbol->get_all_genders();
					while($sel_row=$sel_genders_result->getNext())
					{
						echo '<option value="'.$sel_row['gender_id'].'"';
						echo ">".$sel_row['gender_name']."</option>";
					}
				?> -->
				</select>
				
			</div>
			<h1 style="margin:50px 30px; margin-bottom:15px;text-align:center"> Link your card to your account </h1>
			<div class="frm">
				<span class="label-span"> Account Type </span>
				<div>
					<select class="select-custom only-family">
						<option value='1'> Family </option>
					</select>
				</div>
			</div>
					
			<div class="frm">
				<span class="label-span"> Enter Card Number </span>
				</br>
				<span style='color:#2d2d2d;'> Need at least one card assign to your account </span>
				<div style="margin-top: 5px;">
					<input type="text" name="txt_reg_card_id" id="txt_reg_card_id" class="input-text-custom"/>
				</div>
			</div>

			<div class="frm">
				<span class="label-span"> Last Name </span>
				<div style="margin-top: 5px;">
					<input type="text" id="match_last_name" class="input-text-custom"/>
				</div>
				<span id="not-match-info" style='display:none;color:red; font-size:17px; font-weight:500'> Card Number and last name are not match. Please try again. </span>
			</div>
			
			<input type="hidden" name="txt_user_status" id="txt_user_status" value="0"/>
			<!--div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['description']; ?>:</div>
				<textarea id="txt_reg_description" name="txt_reg_description"></textarea>
			</div-->
			
			<div style="text-align:center; margin-top:30px;">
				<!-- <div class="frm_label">&nbsp;</div> -->
				<input type="submit" name="register_btn"  id="register_btn" value="<?php echo $localized_data['register_btn']; ?>" class="btn control-button" style="height:45px; display:none" />
				&nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='userlogin.php'" value="Login" class="btn control-button" style="height:45px; display:none" />
				
				<a href='userlogin.php' class="btn control-button"> Login </a> 
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a id="match_confirm_btn" class="btn control-button" style="height:45px;"> Match & confirm </a>
			</div>	
		</div>
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>

<script>
	$(document).ready(function(){
		$('#match_confirm_btn').click(function(){
			if($('#txt_reg_card_id').val() == ""){
				$('#txt_reg_card_id').focus();
				return;
			} else if($('#match_last_name').val() == ""){
				$('#match_last_name').focus();
				return;
			}
			var card_id = $('#txt_reg_card_id').val();
			var match_last_name = $('#match_last_name').val();
			var obj = {
				card_id : card_id,
				last_name : match_last_name
			};
			var url = '<?php echo $rootpath;?>/api/register_check_if_match.php';
			var request = JSON.stringify(obj);
			$.ajax({
				url : url,
				type : 'POST',
				data :  request,   
				tryCount : 0,
				retryLimit : 3,
				success : function(info) {
					var info = JSON.parse(info);
					if(info.response.data.substring(0,8) =='Matched!'){
						$("#not-match-info").hide();
						var classification = info.response.data.split("-")[1];
						console.log(classification);
						if(classification == 'Student'){
							$("#txt_user_status").val('0');
						} else{
							$("#txt_user_status").val('1');
						}
						$('#register_btn').trigger('click');
					} else{
						$("#not-match-info").show();
					}
				},
				error : function(xhr, textStatus, errorThrown ) {
					console.log(xhr);
				}
			});
		});
	});
</script>

<style>
	.select-custom.only-family{
		padding-bottom: 0px !important;
		padding-top: 0px !important;
		border-color: #797070;
		width: 300px;
		width: 100% !important;
	}
</style>