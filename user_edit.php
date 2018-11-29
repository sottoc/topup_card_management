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

<?php
    require_once('api/api_common.php');
    if(isset($_GET['id'])){
        $user_id = $_GET['id'];
        $query = "SELECT `user_email`, `family_code`, `user_first_name`, `user_last_name`, `user_type_id`, `is_active` FROM `tbl_user` WHERE `user_id` = ".$user_id;
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $email = $row['user_email'];
                $first_name = $row['user_first_name'];
                $last_name = $row['user_last_name'];
                $family_code = $row['family_code'];
                //$amount = $row['user_amount'];
                $user_type_id = $row['user_type_id'];
                $is_active = $row['is_active'];
            }
        }
        $query = "SELECT `amount` FROM `tbl_family_code_amount` WHERE `family_code` = '".$family_code."'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $amount = $row['amount'];
            }
        } else{
            $amount = '';
        }
    }
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

        $('#assign_new_card_btn').click(function(){
            if($('#new_card_number').val() == ""){
                $('#new_card_number').focus();
                return;
            }
            if($('#last_name_for_new_card').val() == ""){
                $('#last_name_for_new_card').focus();
                return;
            }
            
            var card_id = $('#new_card_number').val();
			var match_last_name = $('#last_name_for_new_card').val();
			var obj = {
				card_id : card_id,
				last_name : match_last_name
			};
			var url = '<?php echo $rootpath;?>/api/register_check_if_match.php';
			var request = JSON.stringify(obj);
			$.ajax({
				url : url,
				type : 'POST',
				data :  request,   
				tryCount : 0,
				retryLimit : 3,
				success : function(info) {
					var info = JSON.parse(info);
					if(info.response.data.substring(0,8) =='Matched!'){
                        var str = '';
						str = '<div class="card-div">   <table style="width:100%">  <tr> <td style="width:70%;padding-left:20px;"> &nbsp;';
                        str += '<span>' + card_id + '</span> &nbsp;&nbsp;';
                        str += '<span>' + match_last_name + '</span> </td>';
                        str += '<td style="width:30%;text-align:right;padding-right:20px;">';
                        str += '<a class="edit-button remove_card_btn"> Remove </a> &nbsp; </td></tr> </table></div>';
                        $('#card_div_content').append($.parseHTML(str));
                        $('#close_modal_btn').trigger('click');
					} else{
						alert('Not matched!');
					}
				},
				error : function(xhr, textStatus, errorThrown ) {
					console.log(xhr);
				}
			});
            
        });

        $('.remove_card_btn').click(function(){
            var card_div = $(this).parent().parent().parent().parent().parent();
            card_div.remove();
            console.log($(this).parent().parent().parent().parent().parent()[0]);
        });

    });

    function updateUser(){
        var user_email = $('#user_email').val();
        var user_family_code = $('#user_family_code').val();
        var amount = $('#user_amount').val();
        var user_status = $('#user_status').val();
        var user_type = $('#user_type').val();
        var first_name = $('#user_first_name').val();
        var last_name = $('#user_last_name').val();
        var obj = {
            user_id : <?php echo $user_id; ?>,
            user_email : user_email,
            first_name : first_name,
            last_name : last_name,
            user_status : user_status,
            user_type : user_type,
            family_code : user_family_code,
            amount : amount
        }
        var url = '<?php echo $rootpath;?>/api/update_user.php';
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
                alert(info.response.data);
            },
            error : function(xhr, textStatus, errorThrown ) {
                console.log(xhr);
            }
        });
    }
</script>


<div class="user_edit" style="width:80%; margin:0 auto;">
    <h2 style="padding-left:20px"> <strong style="color:#2d2d2d"> Users . Edit </strong> </h2>
    <table width="100%">
        <tr>
            <td width="40%" style="padding:0;">
                <table>
                    <tr>
                        <td>
                            <div> <span class="label-span"> Family Code </span> </div>
                            <div> 
                                <input type="text" value='<?php echo $family_code;?>' id='user_family_code' class="input-text-custom" disabled> </input>
                            </div>
                        </td>
                        <td>
                            <div> <span class="label-span"> Amount </span> </div>
                            <div> 
                                <input type="text" value='<?php echo $amount;?>' id='user_amount' class="input-text-custom" disabled> </input>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="40%">
                <div> <span class="label-span"> Status </span> </div>
                <div> 
                    <select class="select-custom" style="border-color:#797070;width:100% !important;" id='user_status'>
                        <?php if($is_active=="1"){ ?>
                            <option value="1" selected> Active </option>
                            <option value="0"> InActive </option>
                        <?php } else {?>
                            <option value="1"> Active </option>
                            <option value="0" selected> InActive </option>
                        <?php } ?>
                    </select>
                </div>
            </td>
            <td width="10%" rowspan="3">
                <div class="card-assign-div">
                    <div> Card Assign on Users </div>
                    <div id='card_div_content'>
                        <div class="card-div"> 
                            <table style="width:100%">
                                <tr>
                                    <td style="width:70%;padding-left:20px;"> 
                                        &nbsp;
                                        <span> D01928374 </span>
                                        &nbsp;&nbsp;
                                        <span> Bermong </span>
                                    </td>
                                    <td style="width:30%;text-align:right;padding-right:20px;"> 
                                        <a class="edit-button remove_card_btn"> Remove </a> &nbsp;
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="new-card-div">
                        <a class="edit-button" href="#assign_new_card" rel="modal:open"> Assign New Card </a>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div> <span class="label-span"> Email Address </span> </div>
                <div> 
                    <input type="text" value='<?php echo $email;?>' id='user_email' class="input-text-custom" disabled/>
                </div>
            </td>
            <td>
                <div> <span class="label-span"> Account Type </span> </div>
                <div> 
                    <select class="select-custom" style="border-color:#797070; width:100% !important;" id='user_type'>
                        <option value="1" <?php if($user_type_id=='1'){ echo 'selected'; } ?>> Admin </option>
                        <option value="2" <?php if($user_type_id=='2'){ echo 'selected'; } ?>> Parent </option>
                        <option value="3" <?php if($user_type_id=='3'){ echo 'selected'; } ?>> Staff </option>
                        <option value="4" <?php if($user_type_id=='4'){ echo 'selected'; } ?>> Student </option>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div> <span class="label-span"> First Name </span> </div>
                <div> 
                    <input type="text" value='<?php echo $first_name;?>' id='user_first_name' class="input-text-custom" value=''> </input>
                </div>
            </td>
            <td>
                <div> <span class="label-span"> Last Name </span> </div>
                <div> 
                    <input type="text" value='<?php echo $last_name;?>' id='user_last_name' class="input-text-custom"> </input>
                </div>
            </td>
        </tr>
    </table>

    <div style="margin-top:30px; text-align:center;">
        <a class="control-button" href="<?php echo $rootpath;?>/user.php"> Back </a> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="control-button" onclick='updateUser()'> Save </a>
    </div>
</div>

<div id="assign_new_card" class="modal" style="padding:50px 50px;padding-top:10px;">
    <h2 align="center" style="font-size:28px;"> Assign New Card </h2>
    <div style="margin:10px 5px;"> 
        <div style="margin-bottom:10px"> 
            <span class="label-span"> Enter Card Number </span>
        </div>
        <div>
            <input type="text" class="input-text-custom" id="new_card_number" style="width:95%">
        </div>
    </div>

    <div style="margin:10px 5px;"> 
        <div style="margin-bottom:10px"> 
            <span class="label-span"> Last Name </span>
        </div>
        <div>
            <input type="text" class="input-text-custom" id="last_name_for_new_card" style="width:95%">
        </div>
    </div>
    <br>
    <div>
        <a class="control-button" href="#" rel="modal:close" style="float:left" id='close_modal_btn'> Cancel </a>
        <a class="control-button" style="float:right" id="assign_new_card_btn"> Match & Save </a>
    </div>
</div>

<?php
	include("footer.php");
?>

<style type="text/css">
    .user_edit td{
        padding:20px;
    }
    .card-assign-div{
        font-size: 17px;
        margin-left: 10%;
        padding-left: 10%;
        display: none;
    }
    .card-assign-div td{
        padding:0px !important;
    }
    .card-assign-div span{
        width:100%;
        color: #2d2d2d !important;
        font-weight:normal !important;
    }
    .card-div{
       border:1px solid #797070;
       padding: 5px 0px;
       margin-top: 10px;
    }
    .new-card-div{
        margin-top:15px;
        border:1px solid #797070;
        text-align:center;
        padding:5px 0px;
    }
</style>