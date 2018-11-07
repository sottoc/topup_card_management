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
        //---- End -----

        $('#save_btn').click(function(){
            $('#upload_btn').trigger('click');
        });

    });
</script>
<?php
    require_once('api/api_common.php');
    $uploadPath = $rootpath.'/upload/profile';
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $query = "SELECT `User_code`, `Family_code`, `Card_ID`, `Last_name`, `First_name`, `Level`, `Card_value`, `Card_status`, `Image`,  `Username`, `Password` FROM `tbl_card1` WHERE `id` = ".$id;
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $user_code = $row['User_code'];
                $family_code = $row['Family_code'];
                $card_id = $row['Card_ID'];
                $level = $row['Level'];
                $first_name = $row['First_name'];
                $last_name = $row['Last_name'];
                $card_value = $row['Card_value'];
                $image_name = $row['Image'];
                $status = $row['Card_status'];
            }
        }
    }
?>

<div class="prepaid_card_edit_div" style="width:80%; margin:0 auto;">
    <h1> <strong style="color:#2d2d2d"> Card Detail . Edit </strong> </h1>

    <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
        <table width="85%">
            <tr>
                <td width="30%">
                    <div> 
                        <span class="label-span"> Card Status </span>
                        <select class="select-custom" name="status">
                            <?php if($status == 'Active') { ?>
                                <option value="1" selected> Active </option>
                                <option value="0"> DeActive </option>
                            <?php } else { ?>
                                <option value="1"> Active </option>
                                <option value="0" selected> DeActive </option>
                            <?php } ?>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div> <span class="label-span"> User Code </span> </div>
                    <div> 
                        <input type="text" name='user_code' value='<?php echo $user_code;?>' class="input-text-custom"> </input>
                    </div>
                </td>
                <td width="25%">
                    <div> <span class="label-span"> Card Number </span> </div>
                    <div> 
                        <input type="text" name='card_id' value='<?php echo $card_id;?>' class="input-text-custom"> </input>
                    </div>
                </td>
                <td width="25%">
                    <div> <span class="label-span"> Level </span> </div>
                    <div> 
                        <input type="text" name='level' value="<?php echo $level;?>" class="input-text-custom"> </input>
                    </div>
                </td>
                <td width="30%" rowspan="2">
                    <div class = "label-span" style="text-align:center;margin-bottom:10px;"> Profile Image </div>
                    
                        <div id="image_preview">
                            <img id="previewing" width='200px' height='200px' src="<?php echo $uploadPath.'/'.$image_name;?>" alt="Select Image file here" />
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
                        <input type="text" name='family_code' class="input-text-custom" value='<?php echo $family_code;?>'> </input>
                    </div>
                </td>
                <td>
                    <div> <span class="label-span"> First Name </span> </div>
                    <div> 
                        <input type="text" name='first_name' class="input-text-custom" value='<?php echo $first_name; ?>'> </input>
                    </div>
                </td>
                <td>
                    <div> <span class="label-span"> Last Name </span> </div>
                    <div> 
                        <input type="text" name='last_name' class="input-text-custom" value='<?php echo $last_name;?>'> </input>
                    </div>
                </td>
            </tr>
        </table>
        <div style="margin-top:30px; text-align:center;">
            <a class="control-button" href="<?php echo $rootpath;?>/card_detail.php"> Back </a> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="control-button" id="save_btn"> Save </a>
        </div>
        <input type='hidden' name='id' value='<?php echo $id;?>'/>
        <input type='hidden' name='image_name' value='<?php echo $image_name;?>'/>
        <input type="submit" value="Upload" id='upload_btn' class="submit" style="display:none"/>
    </form>
</div>

<?php
	include("footer.php");
?>

<style type="text/css">
	.prepaid_card_edit_div td{
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
                    url: "<?php echo $rootpath; ?>/api/update_prepaid_card.php", // Url to which the request is send
                    type: "POST",             // Type of request to be send, called as method
                    data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    processData:false,        // To send DOMDocument or non processed data file it is set to false
                    success: function(msg)   // A function to be called if request succeeds
                    {
                        alert(msg);
                        console.log(msg);
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