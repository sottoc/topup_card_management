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
		$($("#nav ul li")[2]).css("background", '#05815f');
		$($("#nav ul li")[3]).css("background", '#2c2c2c');
		$($("#nav ul li")[4]).css("background", '#2c2c2c');
        //---- End -----

       

    });
</script>


<div class="prepaid_card_edit_div" style="width:80%; margin:0 auto;">
    <h1> <strong style="color:#2d2d2d"> User Detail . Add Single Users </strong> </h1>

    <form action="" method="post" enctype="multipart/form-data">
        <table width="85%">
            
        </table>
        <div style="margin-top:30px; text-align:center;">
            <a class="control-button" href="<?php echo $rootpath;?>/user.php"> Cancel </a> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="control-button" id="save_btn"> Add </a>
        </div>
        
    </form>
</div>

<?php
	include("footer.php");
?>

<style type="text/css">
	
</style>

<script>
    $(document).ready(function(){
        
    });

    
   
</script>