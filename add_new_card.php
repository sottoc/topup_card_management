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
        //---- End -----

        // $('#save_btn').click(function(){
        //     $('#upload_btn').trigger('click');
        // });

    });
</script>
<?php
    // require_once('api/api_common.php');
    // $uploadPath = $rootpath.'/upload/profile';
    // if(isset($_GET['id'])){
    //     $id = $_GET['id'];
    //     $query = "SELECT `User_code`, `Family_code`, `Card_ID`, `Last_name`, `First_name`, `Level`, `Card_value`, `Card_status`, `Image`,  `Username`, `Password` FROM `tbl_card1` WHERE `id` = ".$id;
    //     $result = $conn->query($query);
    //     if ($result->num_rows > 0) {
    //         while($row = $result->fetch_assoc()) {
    //             $user_code = $row['User_code'];
    //             $family_code = $row['Family_code'];
    //             $card_id = $row['Card_ID'];
    //             $level = $row['Level'];
    //             $first_name = $row['First_name'];
    //             $last_name = $row['Last_name'];
    //             $card_value = $row['Card_value'];
    //             $image_name = $row['Image'];
    //         }
    //     }
    // }
?>

<div class="prepaid_card_edit_div" style="width:80%; margin:0 auto;">
    <h1> <strong style="color:#2d2d2d"> Add New Card </strong> </h1>

    <form id="add_new_card" action="" method="post" enctype="multipart/form-data">
        <table width="100%">
            <tr>
                <td width="50%">
                    <div> <span class="label-span"> User Code </span> </div>
                    <div> 
                        <input type="text" name='user_code' id="new_user_code" value='' class="input-text-custom" placeholder="Enter User Code"> </input>
                    </div>
                </td>
                <td width="50%">
                    <div> <span class="label-span"> Card Number </span> </div>
                    <div> 
                        <input type="text" name='card_id' id="new_card_id" value='' class="input-text-custom" placeholder='Enter Card ID'> </input>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div> <span class="label-span"> Level </span> </div>
                    <select name='level' class="select-custom" id="new_level" style="width:100% !important;">
                        <option value='-1'> Choose Level </option>
                        <option vlaue='0'> 0 </option>
                        <option vlaue='1'> 1 </option>
                        <option vlaue='2'> 2 </option>
                        <option vlaue='3'> 3 </option> 
                    </select>
                </td>
                <td>
                    <div> <span class="label-span"> Family Code </span> </div>
                    <div> 
                        <input type="text" name='family_code' class="input-text-custom" id="new_family_code" value='' placeholder="Enter Family Code"> </input>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div> <span class="label-span"> Classification </span> </div>
                    <select name='classification' class="select-custom" id="new_classification" style="width:100% !important;">
                        <option value='-1'> Choose Classification </option>
                        <option vlaue='0'> Parent </option>
                        <option vlaue='1'> Student </option>
                        <option vlaue='2'> Staff </option>
                        <option vlaue='3'> Parent </option> 
                    </select>
                </td>
                <td>
                    <div> <span class="label-span"> Class </span> </div>
                    <div> 
                        <input type="text" name='class' id='new_class' class="input-text-custom" value='' placeholder="Enter Class"> </input>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div> <span class="label-span"> First Name </span> </div>
                    <div> 
                        <input type="text" name='first_name' id='new_first_name' class="input-text-custom" value='' placeholder='Enter First Name'> </input>
                    </div>
                </td>
                <td>
                    <div> <span class="label-span"> Last Name </span> </div>
                    <div> 
                        <input type="text" name='last_name' id='new_last_name' class="input-text-custom" value='' placeholder='Enter Last Name'> </input>
                    </div>
                </td>
            </tr>
        </table>
        <div style="margin-top:30px; text-align:center;">
            <a class="control-button" href="<?php echo $rootpath;?>/card_detail.php"> Back </a> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="control-button" id="save_btn"> Save </a>
        </div>
        
    </form>
</div>

<?php
	include("footer.php");
?>

<style type="text/css">
	#add_new_card table td{
        padding:10px 20px;
    }
</style>

<script>
    $(document).ready(function(){
        $('#save_btn').click(function(){
            if($('#new_user_code').val()==""){
                $('#new_user_code').focus();
                return;
            }
            if($('#new_card_id').val()==""){
                $('#new_card_id').focus();
                return;
            }
            if($('#new_level').val()=="-1"){
                $('#new_level').focus();
                return;
            }
            if($('#new_family_code').val()==""){
                $('#new_family_code').focus();
                return;
            }
            if($('#new_classification').val()=="-1"){
                $('#new_classification').focus();
                return;
            }
            if($('#new_class').val()==""){
                $('#new_class').focus();
                return;
            }
            if($('#new_first_name').val()==""){
                $('#new_first_name').focus();
                return;
            }
            if($('#new_last_name').val()==""){
                $('#new_last_name').focus();
                return;
            }
            var obj = {
                User_code : $("#new_user_code").val(),
                Family_code : $("#new_family_code").val(),
                Card_ID : $("#new_card_id").val(),
                Last_name : $("#new_last_name").val(),
                First_name : $("#new_first_name").val(),
                Level : $("#new_level").val(),
                Class : $("#new_class").val(),
                Classification : $("#new_classification").val()
            };
            var url = '<?php echo $rootpath;?>/api/add_new_card.php';
			var request = JSON.stringify(obj);
            $.ajax({
				url : url,
				type : 'POST',
				data :  request,   
				tryCount : 0,
				retryLimit : 3,
				success : function(info) {
					var info = JSON.parse(info);
					console.log(info);
                    if(info.response.data=='Saved!'){
                        alert("Successfully Saved!");
                    }
				},
				error : function(xhr, textStatus, errorThrown ) {
					console.log(xhr);
				}
			});
        });
    });
   
</script>