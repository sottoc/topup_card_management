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
				'txtpassword':{'required':"<br />*<?php echo $localized_data['check_req_field_login_pw']; ?>"}
			}
		});
	}
	
jQuery(document).ready(function()
{   
		console.log("start");
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
				<h2 style="font-size:30px;"> Welcome to Compass Food Card Portal </h2>
				<div style="font-size:16px"> Delivering great food and support services in around 50 countries at more than 55,000 client locations </div>
				<div style="margin-top:30px;">
					<span class="label-span"> Login Email: </span> 
					<input type="text" class="input-text-custom" name="txtemail" id="txtemail"  value="<?php echo $refill_login_email;?>" style="width:50%"> 
				</div>
				<div style="margin-top:40px;">
					<span class="label-span"> Password : </span> 
					<input type="password" name="txtpassword" id="txtpassword" class="input-text-custom" value="" style="width:50%"> 
				</div>
				<br>
				<br>
				<div>	
					<a class="control-button" style="padding:18px 29% !important;" id="btnlogin_a"> LOGIN </a>
				</div>
				<div style="margin-top:50px;font-size:16px;">	
					<div style="display:none;"> Don't have an account? <a class="edit-button" href="register.php"> Register here. </a> </div>
					<div> <a class="edit-button" href="user_password_submit.php"> Forgot Password </a> </div>
				</div>
				<div style="margin-top:30px;font-size:16px;">	
					<div> If you encounter any issues, Kindly send us an email. </div>
					<br>
					Email: <a href="mailto:Support@gmail.com" style=""> Support@gmail.com </a>
				</div>
				<br/>
				<br/>
				<br/>
				<span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=By2iIIj040vuizYbNUtfSc0L4o1upGr5OKHfLaxAvP3y2ewD9QeRgailkYpa"></script></span>
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
				<!-- <input type="reset" name="btnlogin" id="btnlogin" value="Cancel" class="btn" /> -->
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