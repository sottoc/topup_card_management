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

<style type="text/css">
	.user_single_add_div table td{
        padding:25px;
    }
    .card-value-caution{
        padding-left:30px; 
        color:red; 
        font-size:17px;
        font-weight:600;
    }
</style>

<?php 
    require_once('api/api_common.php');
    $query = "SELECT family_code FROM tbl_family_code_amount";
    $result = $conn->query($query);
    $family_code = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            array_push($family_code, $row['family_code']);
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
		$($("#nav ul li")[0]).css("background", '#2c2c2c');
		$($("#nav ul li")[1]).css("background", '#2c2c2c');
		$($("#nav ul li")[2]).css("background", '#b12226');
		$($("#nav ul li")[3]).css("background", '#2c2c2c');
		$($("#nav ul li")[4]).css("background", '#2c2c2c');
        //---- End -----

       $("#family_code_select").keyup(function(e){
            $("#family_code_check_tip").hide();
            var obj = {
                family_code : e.target.value
            }
            var url = '<?php echo $rootpath;?>/api/check_if_family_code_exist.php';
            var request = JSON.stringify(obj);
            $.ajax({
                url : url,
                type : 'POST',
                data :  request,   
                tryCount : 0,
                retryLimit : 3,
                success : function(info) {
                    var info = JSON.parse(info);
                    var data = info.response.data;
                    console.log(data);
                    if(data[0] == "Already used!"){
                        $("#family_code_check_tip").show();
                    }
                    var amount = data[1];
                    $("input[name='card_value']").val(amount);
                },
                error : function(xhr, textStatus, errorThrown ) {
                    console.log(xhr);
                }
            });
            
       });

       $('#save_btn').click(function(){
           var status = $("#user_status").val();
           var email = $("input[name='user_email']").val();
           var first_name = $("input[name='first_name']").val();
           var last_name = $("input[name='last_name']").val();
           var account_type = $("#account_type").val();
           var family_code = $("#family_code_select").val();
           if(email == ''){
                $("input[name='user_email']").focus();
                return;
           }
           if(first_name == ''){
                $("input[name='first_name']").focus();
                return;
           }
           if(last_name == ''){
                $("input[name='last_name']").focus();
                return;
           }
           if(family_code == ''){
                $("#family_code_select").focus();
                return;
           }
           if(validateEmail(email) == false){
                alert('Please enter valid email');
                $("input[name='user_email']").focus();
                return;
           }
           if($("#email_check_tip").css('display') == 'block'){
                $("input[name='user_email']").focus();
                return;
           }
           var obj = {
                status : status,
                email : email,
                first_name : first_name,
                last_name : last_name,
                account_type : account_type,
                family_code : family_code
            }
            var url = '<?php echo $rootpath;?>/api/user_add_single_api.php';
            var request = JSON.stringify(obj);
            console.log(request);
            $.ajax({
                url : url,
                type : 'POST',
                data :  request,   
                tryCount : 0,
                retryLimit : 3,
                success : function(info) {
                    console.log(info);
                    var info = JSON.parse(info);
                    alert(info.response.data);
                    default_setting();
                },
                error : function(xhr, textStatus, errorThrown ) {
                    console.log(xhr);
                }
            });
       });

    });

    function default_setting(){
        $("#user_status").val('0');
        $("input[name='user_email']").val('');
        $("input[name='first_name']").val('');
        $("input[name='last_name']").val('');
        $("#account_type").val('2');
        $("#family_code_select").val('');
        $("input[name='card_value']").val('');
    }

    function validateEmail(email) {
        var re = /[^\s@]+@[^\s@]+\.[^\s@]+/;
        if(re.test( email )){
            return true;
        }else{
            return false;
        }
    }

     function checkUserExist(){
           var email = $("input[name='user_email']").val();
           if(email == ''){
               return;
           }
           var obj = {
                email : email
            }
            var url = '<?php echo $rootpath;?>/api/check_if_useremail_exist.php';
            var request = JSON.stringify(obj);
            $.ajax({
                url : url,
                type : 'POST',
                data :  request,   
                tryCount : 0,
                retryLimit : 3,
                success : function(info) {
                    var info = JSON.parse(info);
                    if(info.response.data == 'Exist!'){
                        $("#email_check_tip").show();
                        $("input[name='user_email']").focus();
                    }
                },
                error : function(xhr, textStatus, errorThrown ) {
                    console.log(xhr);
                }
            });
       }

       function emailChange(){
            var email = $("input[name='user_email']").val();
            console.log(email);
            $("#email_check_tip").hide();
            if(email == ''){
                $("#email_valid_tip").hide();
            }
            if(validateEmail(email) == false){
                $("#email_valid_tip").show();
            }
            if(validateEmail(email) == true){
                $("#email_valid_tip").hide();
                checkUserExist();
            }
       }
</script>

<div class="user_single_add_div" style="width:80%; margin:0 auto;">
    <h1> <strong style="color:#2d2d2d"> User Detail . Add Single Users </strong> </h1>

    <form action="" method="post" enctype="multipart/form-data">
        <table width="85%">
            <tr> 
                <td> 
                    <div> <span class="label-span"> User Status </span> </div> 
                    <select class="select-custom" name="status" id="user_status" style="width:100% !important;">
                        <option value="1"> Active </option>
                        <option value="0" selected> InActive </option>
                    </select>
                </td>
                <td colspan='2'> 
                    <div> <span class="label-span"> Login Email </span> </div>
                    <div> 
                        <input type="email" name='user_email' value='' onkeyup="emailChange()" class="input-text-custom" placeholder='Enter Email'/>
                    </div>
                    <div style='color:red;font-size:16px;font-weight:600;display:none' id='email_valid_tip'> Enter valid email. </div> 
                    <div style='color:red;font-size:16px;font-weight:600;display:none' id='email_check_tip'> This email address has already been used. </div> 
                </td>
            </tr>
            <tr> 
                <td> 
                    <div> <span class="label-span"> First Name </span> </div>
                    <div> 
                        <input type="text" name='first_name' value='' class="input-text-custom" placeholder='First Name'/>
                    </div>
                </td>
                <td> 
                    <div> <span class="label-span"> Last Name </span> </div>
                    <div> 
                        <input type="text" name='last_name' value='' class="input-text-custom" placeholder='Last Name'/>
                    </div>
                </td>
                <td> 
                    <div> <span class="label-span"> Account Type </span> </div>
                    <div> 
                        <select class="select-custom" name="account_type" id="account_type" style="width:100% !important;">
                        <?php
                            $user_type_id = 2;
                            $query = "select user_type_id, user_type_name from tbl_user_type";
                            $result = $conn->query($query);
                            if ($result->num_rows > 0) {
                                while($sel_row = $result->fetch_assoc()) {
                                    echo '<option value="'.$sel_row['user_type_id'].'"';
                                    if($user_type_id==$sel_row['user_type_id'])
                                    echo 'selected';
                                    echo ">".$sel_row['user_type_name']."</option>";
                                }
                            }
                        ?>
                        </select>
                    </div>
                </td>
            </tr>
            <tr> 
                <td> 
                    <div> <span class="label-span"> Family Code </span> </div>
                    <div> 
                        <input type='text' class="input-text-custom" id="family_code_select" name="family_code" placeholder="Family Code" style="width:100% !important;"/>
                    </div>
                    <div style='color:red;font-size:16px;font-weight:600;display:none' id='family_code_check_tip'> Family Code already used. </div> 
                </td>
                <td> 
                    <div> <span class="label-span"> Card Value </span> </div>
                    <div> 
                        <input type="text" name='card_value' value='' class="input-text-custom" disabled/>
                    </div>
                </td>
                <td> 
                </td>
            </tr>
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

