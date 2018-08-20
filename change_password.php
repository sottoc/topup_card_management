<?php	
	require_once('autoload.php');
	require_once('library/reference.php');
	$error1=array();
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }		
	$userinfo=new userinfo();
	$changepasswordbol=new changepasswordbol();
	
	if(isset($_POST['btnchange']))
	{		
		
		$adminid=$_SESSION['login_user_id'];
		if($adminid != "0")
		{			
			$check_result=$changepasswordbol->checktochangepass($adminid,$_POST['txtoldpass']);
			$row = $check_result->getNext();
			$count_oldpassword = $row['count_record'];
			if($count_oldpassword==1)
			{
				$newpassword = $_POST['txtnewpass'];
				//print_r("newpassword " . $newpassword);exit();
				if ($changepasswordbol->changepassword($newpassword,$adminid))
				{							
					header("location:index.php");
					exit();
				}
			}
			else
			{
				$error1[]="<div class='error'>Your old password is incorrect !</div>";
			}				
		}	
	}
	require_once('header.php');	
?>
<script language="javascript">	
	jQuery(document).ready(validation);
	function validation()
	{
		jQuery("#frmchangepassword").validate({
			'rules':{			
				'txtnewpass':{'required':true},
				'txtconpass':{'required':true,'equalTo':'#txtnewpass'}
			},
			'messages': {				
				'txtnewpass':{'required':'*Please enter new password.'},
				'txtconpass':{'required':'*Please enter confirm password.','equalTo':'Confirm password must be equal to new password.'}
			
			}
		});			
	}	
</script>		
<form method="post" name="frmchangepassword" id="frmchangepassword">
	<h2>Change Password</h2>	
	
	<div class="add_frm">
		<div class="frm">
			<div class="frm_label">Old Password :</div>
			<input type="password" name="txtoldpass" id="txtoldpass" />
		</div>		
		<?php
			if(count($error1))
			{	
				echo join('<br />', $error1);
			}
		?>
		<div class="frm">
			<div class="frm_label">New Password :</div>
			<input type="password" name="txtnewpass" id="txtnewpass" />
		</div>
		<div class="frm">
			<div class="frm_label">Confirm Password :</div>
			<input type="password" name="txtconpass" id="txtconpass" />
		</div>
		<div class="frm">
			<div class="frm_label">&nbsp;</div>
			<input type="submit" name="btnchange" id="btnchange" value="Change" class="btn" /> &nbsp;
			<input type="button" name="btncancel" id="btncancel" value="Cancel" onclick="window.location='index.php';" class="btn" />
		</div>			
	</div>	
</form>
<?php
	include('library/closedb.php');
	include('footer.php');
?>