<?php
	session_start();
	session_destroy();
	setcookie ( "url", "", time () - 3600 );
	session_start();
	require_once("login_header.php");
?>

<h2 align="center" style="font-size: 18px;">You have been successfully logged out.</h2>
<div style="text-align: center;">
	<div class="btn_link">
		<a href="userlogin.php" class="control-button">Login Again</a>
	</div>
</div>

<?php
	
	require ("footer.php");
?>
