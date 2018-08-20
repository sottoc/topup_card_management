<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	session_start();
	
	$userbol = new userbol();
	
	if(isset($_GET['email']))
	{
		$register_email = $_GET['email'];
		$result = $userbol->get_user_by_email($register_email);
		$row=$result->getNext();
		$is_active = $row['is_active'];
		$user_id = $row['user_id'];
		if($is_active==0)
		{
			//change to activate user
			$old_values = 'user_id=>'.$user_id.',user_email=>'.$register_email.',is_active=>0';
			$userbol->update_user_isactive($user_id,'1',$old_values);
			$_SESSION['reg_verify_msg'] = "<strong>WoW ! </strong>  Your Account is Now Activated : <a href='userlogin.php'>Login here</a>";
		}
		else
			$_SESSION['reg_verify_msg'] = "<strong>sorry ! </strong>Your Account is allready Activated : <a href='userlogin.php'>Login here</a>";
		
	}
	
	require_once('login_header.php');
?>
<div class="content_data">
	<form>
	<h2>Verification Account</h2>
	<!--showing msgs-->
	<p>&nbsp;</p>
	<?php
		echo "<div id='message'>";
		if(isset($_SESSION['reg_verify_msg']))
		{
			$reg_verify_msg = $_SESSION['reg_verify_msg'];
			echo $reg_verify_msg;
		}
		echo "</div><br/>";
		unset ( $_SESSION ['reg_verify_msg'] );
		session_write_close ();
	?>
	</form>
</div>
<?php
include('library/closedb.php');
include("footer.php");
?>