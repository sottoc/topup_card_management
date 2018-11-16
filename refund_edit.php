<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');	
	
	$reportbol = new reportbol();

	require_once('header.php');	
?>

<script language="javascript">
	$(document).ready(function(){
        //---- set background for active menu -----
        if(localStorage.getItem("current_page") != undefined){
            for(var i=0; i < $("#nav ul li").length; i++){
                if($($("#nav ul li")[i]).html() == localStorage.getItem("current_page")){
                    $($("#nav ul li")[i]).css("background", '#05815f');
                }
            }
		}
		$($("#nav ul li")[0]).css("background", '#2c2c2c');
		$($("#nav ul li")[1]).css("background", '#2c2c2c');
		$($("#nav ul li")[2]).css("background", '#2c2c2c');
		$($("#nav ul li")[3]).css("background", '#05815f');
		$($("#nav ul li")[4]).css("background", '#2c2c2c');
        //---- End -----
        

    });

    function refund(){
        console.log("okay");
    }
       
</script>


<div class="user_edit" style="width:80%; margin:0 auto;">
    <h2 style="padding-left:20px"> <strong style="color:#2d2d2d"> Refund <span style='color:red;'> Michael BIRTO </span> </strong> </h2>
    <table width="100%">
        
    </table>

    <div style="margin-top:30px; text-align:center;">
        <a class="control-button" href="<?php echo $rootpath;?>/refund.php"> Back </a> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="control-button" onclick="refund()"> Confirm Refund </a>
    </div>
</div>



<?php
	include("footer.php");
?>

<style type="text/css">
    
</style>