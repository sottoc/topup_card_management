<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
    require_once('login_header.php');
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
                        alert("Sorry, no user with the email address");
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
				<h2 style="font-size:30px;"> Reset Password </h2>
                <table>
                    <tr>
                        <td colspan="2" align="center">
                            <div style="margin-top:30px;">
                                <span class="label-span"> Email: </span> 
                                <input type="text" class="input-text-custom" name="txtemail" id="txtemail" disabled="disabled" value="<?php echo $_GET['email'] ?>" style="width:50%"> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <div style="margin-top:40px;">
                                <span class="label-span"> Password : </span> 
                                <input type="password" name="txtpassword" id="txtpassword" class="input-text-custom" style="width:50%"> 
                            </div>
                        </td>
                        <td> 
                            <div style="margin-top:40px;">
                                <span class="label-span"> Confirm Password : </span> 
                                <input type="password" name="confirm_password" id="confirm_password" class="input-text-custom" style="width:50%"> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <br>
                            <br>
                            <br>
                            <a class="control-button" style="padding:18px 29% !important;" id="btnlogin_a"> OK </a>
                        </td>
                    </tr>
                </table>
			</div>

	</div>