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
    });

    function usercodeChange(){
        var user_code = $("input[name='user_code']").val();
        console.log(user_code);
        $("#user_code_check_tip").hide();
        checkUsercodeExist();
    }
    function checkUsercodeExist(){
           var user_code = $("input[name='user_code']").val();
           var obj = {
                user_code : user_code
            }
            var url = '<?php echo $rootpath;?>/api/check_if_usercode_exist.php';
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
                        $("#user_code_check_tip").show();
                    }
                },
                error : function(xhr, textStatus, errorThrown ) {
                    console.log(xhr);
                }
            });
       }

</script>


<div class="create-new-card-div" style="width:80%; margin:0 auto;">
    <h1> <strong style="color:#2d2d2d"> Create Card New </strong> </h1>

    <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
        <table width="85%">
            <tr>
                <td width="30%">
                    <div> 
                        <span class="label-span"> Card Status </span>
                        <select class="select-custom" name="status">
                            <option value="1" selected> Active </option>
                            <option value="0"> InActive </option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div> <span class="label-span"> User Code </span> </div>
                    <div> 
                        <input type="text" name='user_code' id="user_code" onkeyup="usercodeChange()" value='' class="input-text-custom"/>
                    </div>
                    <div style='color:red;font-size:16px;font-weight:600;display:none' id='user_code_check_tip'> This user code has already been used. </div> 
                </td>
                <td width="25%">
                    <div> <span class="label-span"> Card Number </span> </div>
                    <div> 
                        <input type="text" name='card_id' id="card_id" value='' class="input-text-custom"/>
                    </div>
                </td>
                <td width="25%">
                    <div> <span class="label-span"> Level </span> </div>
                    <div> 
                        <input type="text" name='level' value="0" class="input-text-custom"/>
                    </div>
                </td>
                <td width="30%" rowspan="2">
                    <div class = "label-span" style="text-align:center;margin-bottom:10px;"> Profile Image </div>
                    
                        <div id="image_preview">
                            <img id="previewing" width='200px' height='200px' src="" alt="Select Image file here" />
                        </div>
                        <div id="selectImage">
                        
                        <input type="file" name="file" id="file" style="display:none"/>
                        
                        </div>
                    
                    <div id="image_info"></div>
                
                </td>
            </tr>
            <tr>
                <td>
                    <div> <span class="label-span"> Family Code </span> </div>
                    <div> 
                        <input type="text" name='family_code' id="family_code" class="input-text-custom" value=''/>
                    </div>
                </td>
                <td>
                    <div> <span class="label-span"> First Name </span> </div>
                    <div> 
                        <input type="text" name='first_name' id="first_name" class="input-text-custom" value=''/>
                    </div>
                </td>
                <td>
                    <div> <span class="label-span"> Last Name </span> </div>
                    <div> 
                        <input type="text" name='last_name' id="last_name" class="input-text-custom" value=''/>
                    </div>
                </td>
            </tr>
        </table>
        <div style="margin-top:30px; text-align:center;">
            <a class="control-button" href="<?php echo $rootpath;?>/card_detail.php"> Back </a> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="control-button" id="save_btn"> Save </a>
        </div>
        <input type='hidden' name='image_name' value=''/>
        <input type="submit" value="Upload" id='upload_btn' class="submit" style="display:none"/>
    </form>
</div>

<?php
	include("footer.php");
?>

<style type="text/css">
	.create-new-card-div td{
        padding:20px;
    }

    #image_preview{
        font-size: 17px;
        width: 200px;
        height: 200px;
        text-align: center;
        font-weight: bold;
        color: #C0C0C0;
        background-color: #FFFFFF;
        border: 1px dashed black;
        margin:0 auto;
    }

    #image_info{
        margin-top:10px;
        color:#ff3333 !important;
        font-size:15px;
        text-align:center !important;
    }
    #image_info #error_message {
        color:#ff3333 !important;
    }
</style>

<script>
    $(document).ready(function(){
        $('#save_btn').click(function(){
            //-- check if black field --
            if($("#user_code").val() == ""){
                $("#user_code").focus();
                return;
            }
            if($("#card_id").val() == ""){
                $("#card_id").focus();
                return;
            }
            if($("#family_code").val() == ""){
                $("#family_code").focus();
                return;
            }
            if($("#first_name").val() == ""){
                $("#first_name").focus();
                return;
            }
            if($("#last_name").val() == ""){
                $("#last_name").focus();
                return;
            }
            if($("#user_code_check_tip").css('display') == 'block'){
                $("#user_code").focus();
                return;
            }
            //--- upload ----
            $('#upload_btn').trigger('click');
        });

        $("#image_preview").click(function(){
            $("#file").trigger('click');
        });
        // Function to preview image after validation
        $("#file").change(function() {
            $("#image_info").empty(); // To remove the previous error message
            var file = this.files[0];
            var imagefile = file.type;
            var match= ["image/jpeg","image/png","image/jpg"];
            if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
            {
                $('#previewing').attr('alt','Select valid Image');
                $("#image_info").html("<span id='error_message'>Note : Please Select a valid Image File </span>");
                return false;
            }
            else
            {
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
            }
        });
        //save data and image
        $("#uploadimage").on('submit',(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "<?php echo $rootpath; ?>/api/card_add_new_api.php", // Url to which the request is send
                    type: "POST",             // Type of request to be send, called as method
                    data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    processData:false,        // To send DOMDocument or non processed data file it is set to false
                    success: function(msg)   // A function to be called if request succeeds
                    {
                        alert(msg);
                        console.log(msg);
                        $("#user_code").val("");
                        $("#card_id").val("");
                        $("#family_code").val("");
                        $("#first_name").val("");
                        $("#last_name").val("");
                        $('#image_info').html("");
                    }
                });
            })
        );
    });

    function imageIsLoaded(e) {
        $("#file").css("color","green");
        $('#image_preview').css("display", "block");
        $('#previewing').attr('src', e.target.result);
        $('#previewing').attr('width', '100%');
        $('#previewing').attr('height', '100%');
    }
   
</script>