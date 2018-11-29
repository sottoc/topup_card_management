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
                    $($("#nav ul li")[i]).css("background", '#b12226');
                }
            }
        }
        $($("#nav ul li")[0]).css("background", '#2c2c2c');
		$($("#nav ul li")[1]).css("background", '#b12226');
		$($("#nav ul li")[2]).css("background", '#2c2c2c');
		$($("#nav ul li")[3]).css("background", '#2c2c2c');
		$($("#nav ul li")[4]).css("background", '#2c2c2c');
        //------------------ End -----------------

        $('#save_btn').click(function(){
            $('#upload_btn').trigger('click');
        });

    });
</script>


<div class="create-multiple-card-div" style="width:80%; margin:0 auto;">
    <h1> <strong style="color:#2d2d2d"> Create Multiple Card </strong> </h1>

    
</div>

<?php
	include("footer.php");
?>
