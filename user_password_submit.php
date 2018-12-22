<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }	
	$errmsg_arr = array();
	$refill_login_email='';
	$eventlogbol = new eventlogbol();
	$localizationbol= new localizationbol();
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('login',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	if(isset($_POST['btnlogin']))
	{
		if($_POST['txtemail'] == '')
		{
			$errmsg_arr [] = $localized_data['check_req_field_login_email'];
		}
		
		if($_POST['txtpassword'] == '')
		{
			$errmsg_arr [] = $localized_data['check_valid_email_adddress'];
		}
		$_SESSION ['USER_ERRMSG_ARR'] = $errmsg_arr;
		
		if(count($errmsg_arr) == 0)
		{
		
			$loginemail = $_POST['txtemail'];
			$loginpassword = $_POST['txtpassword'];
			$userbol = new userbol();
			$result = $userbol->check_user_login($loginemail,$loginpassword);

			/* for event log */
			$action_type = 'Login';
			$action_table = 'tbl_user';
			$description = '';
			$old_values='';
			/* ------- */

			if($result->rowCount() == 0)
			{
				$description = 'Unsuccessfully logined.';
				$new_values_arr = array();
				$new_values_arr['loginemail'] = $loginemail;
				$new_values_arr['loginpassword'] = $loginpassword;
				$new_values_arr['description'] = $description;
				$eventlogbol->save_eventlog($action_type,$action_table,$new_values_arr,$old_values);
				$errmsg_arr [] = $localized_data['login_fail_msg'];
				$_SESSION ['USER_ERRMSG_ARR'] = $errmsg_arr;
				$refill_login_email=$loginemail;
			}
			else
			{	
				$row = $result->getNext ();
				$_SESSION ['login_user_type_id'] = $row['user_type_id'];
				$_SESSION ['login_user_id'] = $row ['user_id'];
				$_SESSION ['login_user_name'] = $row ['user_name'];
				$_SESSION ['login_user_email'] = $row ['user_email'];
				$new_values_arr = array();
				$new_values_arr['loginemail'] = $loginemail;
				$new_values_arr['loginpassword'] = $loginpassword;
				$new_values_arr['description'] = $description;
				$description = 'Successfully logined.';
				$eventlogbol->save_eventlog($action_type,$action_table,$new_values_arr,$old_values);
				session_write_close ();
				
				if (isset ( $_COOKIE ['url'] ))
				{
					header ( "location:" . $_COOKIE ['url'] );
				}
				else 
				{
					header ( "location:index.php" );
					exit ();
				}
			}
		}
		
	}
require_once('login_header.php');
?>
<script language="javascript">
function AddValidation()
	{
		jQuery("#frmuserlogin").validate({
			'rules':{
				'txtemail':{'required':true, 'email':true},
				'txtpassword':{'required':true}
			},
			'messages': {
				'txtemail':{'required':"<br />*<?php echo $localized_data['check_req_field_login_email']; ?>",'email':"<br /><?php echo $localized_home_data['check_valid_email_adddress']; ?>"},
				'txtpassword':{'required':"<br />*<?php echo $localized_data['check_req_field_login_email']; ?>"}
			}
		});
	}
	
jQuery(document).ready(function()
{   
		jQuery('#txtemail').focus();						
		AddValidation();
		
		$("#btnlogin_a").click(function(){
			$("#btnlogin").trigger('click');
		});
});
</script>

<style type="text/css">
	.login_box {
		margin: 0 auto;
		margin-top: 50px;
		width: 50%;
		/* padding: 30px; */
		/* background: #f5f5f5;
		border: 1px dotted #ccc; */
	}
	.frm_label {
		width: 180px;
		font-weight: bold;
	}
</style>

<p>&nbsp;</p>
	<div class="login_box">	
		<form method="post" id="frmuserlogin" name="frmuserlogin">

			<div align="center">
				<h2 style="font-size:30px;"> Find your password </h2>
				<div style="font-size:16px"> Please enter your email address, we will send you a reset password link via email </div>
				<div style="margin-top:30px;">
					<span class="label-span"> Email: </span> 
					<input type="text" class="input-text-custom" name="send_email" id="send_email_address" style="width:50%"> 
				</div>
				<br>
				<br>
                <br>
				<div>	
					<a href="javascript:send_mail()" class="control-button" style="padding:16px 29% !important;" id="submit_email_btn"> SUBMIT </a>
				</div>
				
				<div style="margin-top:30px;font-size:16px;">	
					<div> If you encounter any issues, Kindly send us an email. </div>
					<br>
					Email: <a href="mailto:Support@gmail.com" style=""> Support@gmail.com </a>
				</div>
			</div>

			
			
			<!-- <h2><?php echo $localized_data['login']; ?></h2>
			
			<div class="frm">
				<div class="frm_label"><?php echo $localized_data['login_email']; ?><span>*</span> :</div>
				<input type="text" name="txtemail" id="txtemail"  value="<?php echo $refill_login_email;?>"/>
			</div>
			<div class="frm">
				<div class="frm_label"><?php echo $localized_data['login_pw']; ?><span>(case sensitive)*</span> :</div>
				<input type="password" name="txtpassword" id="txtpassword" />
			</div> -->
			<div class="frm" style="display:none">
				<input type="submit" name="btnlogin" id="btnlogin" value="Login" class="btn" /> &nbsp;
				<input type="reset" name="btnlogin" id="btnlogin" value="Cancel" class="btn" />
			</div>
			
			<?php
				if (isset ( $_SESSION ['USER_ERRMSG_ARR'] ) && is_array ( $_SESSION ['USER_ERRMSG_ARR'] )) 
				{					
					foreach ( $_SESSION ['USER_ERRMSG_ARR'] as $msg ) 
					{
						echo "<p class='error'><b>";
						echo $msg;
						echo "</b></p>";
					}
					unset ( $_SESSION ['USER_ERRMSG_ARR'] );
					session_write_close ();
				}
			?>
		</form>
	</div>
<?php
	require ("library/closedb.php");
	require ("footer.php");
?>

<script>
	function send_mail(){
		var email = $("#send_email_address").val();
		if(email == ""){
			$("#send_email_address").focus();
			return;
		}

		$.post("library/send_mail.php", {email: email}, function(result){
			window.location.replace("<?php echo $rootpath;?>/user_password_check.php");
		});
		
	}
</script>