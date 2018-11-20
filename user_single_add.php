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

       $("input[name='user_email']").blur(function(e){
           var email = e.target.value;
           console.log(email);
           if(email == ''){
               return;
           }
           if(validateEmail(email) == false){
               alert('Please enter valid email');
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
                        alert("The email user already exist!");
                        $("input[name='user_email']").val('');
                        $("input[name='user_email']").focus();
                    }
                },
                error : function(xhr, textStatus, errorThrown ) {
                    console.log(xhr);
                }
            });
       });

       $("input[name='family_code']").blur(function(e){
            var obj = {
                family_code : e.target.value
            }
            var url = '<?php echo $rootpath;?>/api/get_amount_by_family_code.php';
            var request = JSON.stringify(obj);
            $.ajax({
                url : url,
                type : 'POST',
                data :  request,   
                tryCount : 0,
                retryLimit : 3,
                success : function(info) {
                    var info = JSON.parse(info);
                    $("input[name='card_value']").val(info.response.data);
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
           var family_code = $("input[name='family_code']").val();
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
                $("input[name='family_code']").focus();
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
            $.ajax({
                url : url,
                type : 'POST',
                data :  request,   
                tryCount : 0,
                retryLimit : 3,
                success : function(info) {
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
        $("input[name='family_code']").val('');
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
                        <option value="0" selected> DeActive </option>
                    </select>
                </td>
                <td colspan='2'> 
                    <div> <span class="label-span"> Login Email </span> </div>
                    <div> 
                        <input type="email" name='user_email' value='' class="input-text-custom" placeholder='Enter Email'/>
                    </div>
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
                            $userbol = new userbol();
                            $sel_usertype_result = $userbol->get_all_usertype();
                            $user_type_id = 2;
                            while($sel_row=$sel_usertype_result->getNext())
                            {
                                echo '<option value="'.$sel_row['user_type_id'].'"';
                                if($user_type_id==$sel_row['user_type_id'])
                                echo 'selected';
                                echo ">".$sel_row['user_type_name']."</option>";
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
                        <input type="text" name='family_code' value='' class="input-text-custom" placeholder="Family Code"/>
                    </div>
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
        <div class='card-value-caution'> Caution! Card value will be change when Family Code is changed </div> 
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

<script>
    $(document).ready(function(){
        
    });
</script>