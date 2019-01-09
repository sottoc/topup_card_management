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
        $query = "SELECT * FROM `tbl_card1` c LEFT JOIN tbl_family_code_amount fa on c.Family_code=fa.family_code WHERE c.id = ".$id;
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $user_code = $row['User_code'];
                $family_code = $row['Family_code'];
                $card_id = $row['Card_ID'];
                $level = $row['Level'];
                $first_name = $row['First_name'];
                $last_name = $row['Last_name'];
                $card_value = $row['amount'];
                $image_name = $row['Image'];
                $status = $row['Card_status'];
            }
        }
    }

    // $query = "SELECT created_time FROM tbl_food_purchase_records_old WHERE id>0 GROUP BY created_time";
    // $result = $conn->query($query);
    // $n = 1;
    // if ($result->num_rows > 0) {
    //     while($row = $result->fetch_assoc()) {
    //         $n = $n + 1;
    //         $bill_id = 'lycee00000'.$n;
    //         $created_time = $row['created_time'];

    //         $query2 = "SELECT COUNT(*) FROM tbl_food_purchase_records_old WHERE created_time = '".$created_time."' GROUP BY item_name";
    //         $result2 = $conn->query($query2);
    //         $item_qty = [];
    //         if($result2->num_rows > 0){
    //             while($row2 = $result2->fetch_assoc()) {
    //                 array_push($item_qty, $row2['COUNT(*)']);
    //             }
    //         }
            
    //         $query1 = "SELECT * FROM tbl_food_purchase_records_old WHERE created_time = '".$created_time."' GROUP BY item_name";
    //         $result1 = $conn->query($query1);
    //         if($result1->num_rows > 0){
    //             $m = 0;
    //             $total_amount = 0;
    //             while($row1 = $result1->fetch_assoc()) {
    //                 $card_id = $row1['card_id'];
    //                 $pos_id = $row1['pos_id'];
    //                 $item_name = $row1['item_name'];
    //                 $item_price = $row1['item_price'];
    //                 $qty = $item_qty[$m];
    //                 $total_amount = $total_amount + floatval($item_price) * $qty;
    //                 $query3="INSERT INTO `tbl_food_purchase_records` (`bill_id`, `item_name`, `item_price`, `item_qty`, `created_time`) VALUES ('".$bill_id."','".$item_name."','".$item_price."','".$qty."','".$created_time."')";
    //                 $result3 = $conn->query($query3);
                    
    //                 $m++;
    //             }
    //         }
    //         $total_amount = round(intval($total_amount*100)/10)/10;
    //         $query4="INSERT INTO `tbl_food_bill_records` (`bill_id`, `card_id`, `pos_id`, `total_amount`, `created_time`) VALUES ('".$bill_id."','".$card_id."','".$pos_id."','".$total_amount."','".$created_time."')";
    //         $result4 = $conn->query($query4);
    //         echo $n;
    //         echo '-----------------------------------';

    //     }
    // }
    // exit;

    //-------------------------------- update family code amount ------------------------
    // $query = "SELECT Family_code, Card_value FROM tbl_card1_old";
    // $result = $conn->query($query);
    // if ($result->num_rows > 0) {
    //     while($row = $result->fetch_assoc()) {
    //         $family_code = $row['Family_code'];
    //         $card_value = $row['Card_value'];
    //         $query1 = "SELECT amount FROM tbl_family_code_amount WHERE family_code = '".$family_code."'";
    //         $result1 = $conn->query($query1);
    //         if ($result1->num_rows > 0) {
    //             while($row1 = $result1->fetch_assoc()) {
    //                 $amount = $row1['amount'];
    //                 $amount = floatval($card_value) + floatval($amount);
    //                 $query3 = "UPDATE tbl_family_code_amount SET amount = '".$amount."' WHERE family_code = '".$family_code."'";
    //                 $result4 = $conn->query($query3);
    //                 echo "-------->".$amount;
    //             }
    //         }
    //     }
    // }
    // exit;

    // $query = "SELECT Family_code, Card_value FROM tbl_card1_old";
    // $result = $conn->query($query);
    // date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    // $time = date("Y-m-d H:i:s");
    // $n = 0;
    // if ($result->num_rows > 0) {
    //     while($row = $result->fetch_assoc()) {
    //         $family_code = $row['Family_code'];
    //         $query1 = "SELECT * FROM tbl_family_code_amount WHERE family_code = '".$family_code."'";
    //         $result1 = $conn->query($query1);
    //         if($result1->num_rows > 0){
    //             $Ok = "Ok";
    //         } else{
    //             $query2 = "INSERT INTO `tbl_family_code_amount` (family_code, amount, date_created, date_updated) VALUES ('".$family_code."', '0', '".$time."', '".$time."')";
    //             $result2 = $conn->query($query2);
    //             $n++;
    //             echo "------>".$n;
    //         }
    //     }
    // }

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
                                <option value="0"> InActive </option>
                            <?php } else { ?>
                                <option value="1"> Active </option>
                                <option value="0" selected> InActive </option>
                            <?php } ?>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div> <span class="label-span"> User Code </span> </div>
                    <div> 
                        <input type="text" name='user_code' value="<?php echo $user_code;?>" class="input-text-custom"> </input>
                    </div>
                </td>
                <td width="25%">
                    <div> <span class="label-span"> Card Number </span> </div>
                    <div> 
                        <input type="text" name='card_id' value="<?php echo $card_id;?>" class="input-text-custom"> </input>
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
                        <input type="text" name='family_code' class="input-text-custom" value="<?php echo $family_code;?>"> </input>
                    </div>
                </td>
                <td>
                    <div> <span class="label-span"> First Name </span> </div>
                    <div> 
                        <input type="text" name='first_name' class="input-text-custom" value="<?php echo $first_name; ?>"> </input>
                    </div>
                </td>
                <td>
                    <div> <span class="label-span"> Last Name </span> </div>
                    <div> 
                        <input type="text" name='last_name' class="input-text-custom" value="<?php echo $last_name;?>">
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