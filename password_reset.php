<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
    require_once('login_header.php');

    $email = $_GET['email'];
?>
<script language="javascript">
	
$(document).ready(function()
{   
		$('#txtpassword').focus();						
		
		$("#btnlogin_a").click(function(){
			if($('#txtemail').val() == ""){
                $('#txtemail').focus();
                return;
            }
            if($('#txtpassword').val() == ""){
                $('#txtpassword').focus();
                return;
            }
            if($('#confirm_password').val() == ""){
                $('#confirm_password').focus();
                return;
            }
            if($('#txtpassword').val() != $('#confirm_password').val()){
                alert('No match confirm password');
                $('#confirm_password').focus();
            }
            var obj = {
				email : $('#txtemail').val(),
				password : $('#txtpassword').val()
			};
			var url = '<?php echo $rootpath;?>/api/reset_password.php';
			var request = JSON.stringify(obj);
            $.ajax({
				url : url,
				type : 'POST',
				data :  request,   
				tryCount : 0,
				retryLimit : 3,
				success : function(result) {
					console.log(result);
                    if(result == 'No user'){
                        alert("Sorry, no user with <?php echo $email;?>");
                    }
                    if(result == 'OK'){
                        alert("Successfully reset password");
                        window.location.replace("<?php echo $rootpath;?>/index.php");
                    }
				},
				error : function(xhr, textStatus, errorThrown ) {
					console.log(xhr);
				}
			});
		});
});
</script>

	<div class="login_box">	

			<div align="center">
				<h2 style="font-size:30px;margin-bottom:0px;margin-top:80px;"> Please enter your new password </h2>
                <table>
                    <tr style="display:none;">
                        <td colspan="2" align="center">
                            <div style="margin-top:30px;">
                                <span class="label-span"> Email: </span> 
                                <input type="text" class="input-text-custom" name="txtemail" id="txtemail" disabled="disabled" value="<?php echo $email; ?>" style="width:50%"> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <div style="margin-top:40px;float:right;">
                                <span class="label-span"> New Password: </span> 
                            </div>
                        </td>
                        <td>
                            <div style="margin-top:40px;">
                                <input type="password" name="txtpassword" id="txtpassword" class="input-text-custom"> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <div style="margin-top:40px;float:right;">
                                <span class="label-span"> Re-enter Password: </span> 
                            </div>
                        </td>
                        <td>
                            <div style="margin-top:40px;">
                                <input type="password" name="confirm_password" id="confirm_password" class="input-text-custom"> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <br>
                            <br>
                            <a class="control-button" style="padding:18px 29% !important;" id="btnlogin_a"> SUBMIT </a>
                        </td>
                    </tr>
                </table>
                <br>
                <br>
                <div style="margin-top:30px;font-size:16px;">	
                    <div> If you encounter any issues, Kindly send us an email. </div>
                    <br>
                    Email: <a href="mailto:Support@gmail.com" style=""> Support@gmail.com </a>
                </div>
			</div>

	</div>