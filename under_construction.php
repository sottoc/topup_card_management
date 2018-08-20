<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	require_once('header.php');
?>
<script type="text/javascript">			

</script>
<style type="text/css">
</style>

<div class="content_data">
	<h2>Under maintaining Period</h2>
	<img src="images/Website-Under-Construction-Coming-Soon1.jpg">
</div><!-- /content_data -->
<?php
	include('library/closedb.php');
	include("footer.php");
?>