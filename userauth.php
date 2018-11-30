<?php 
	if(isset($_SESSION)==false)
		session_start();
	$errmsg_arr = array();
	$autherrflag =false;
	$currentpage=basename($_SERVER['PHP_SELF']);
	if((isset($_SESSION['login_user_id'])) && $_SESSION['login_user_id']!='')
	{	
		$autherrflag = true;
	}
	
	if ($autherrflag==false)
	{		
		if($currentpage=="userlogin.php")
		{
			$errmsg_arr [] = '';
		}
		else
		{
			$errmsg_arr [] = 'You have not login or session expired. Please login again.';
		}
		$_SESSION ['USER_ERRMSG_ARR'] = $errmsg_arr;
		session_write_close ();		
		
		header ( "location:userlogin.php" );
		exit ();	
	}
?>