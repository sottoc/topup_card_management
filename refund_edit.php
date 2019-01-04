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
        $id = $_GET['id'];
        $family_code = $_GET['family_code'];
        $query = "SELECT `amount` FROM `tbl_family_code_amount` WHERE `family_code` = '".$family_code."'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $current_amount = $row['amount'];
            }
        }

        $query = "SELECT `user_email`, `user_first_name`, `user_last_name`, `is_active` FROM `tbl_user` WHERE `family_code` = '".$family_code."'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $user_email = $row['user_email'];
                $first_name = $row['user_first_name'];
                $last_name = $row['user_last_name'];
                $status = $row['is_active'];
            }
        } else{
            $query1 = "SELECT `First_name`, `Last_name`, `Card_status` FROM `tbl_card1` WHERE `Family_code` = '".$family_code."'";
            $result1 = $conn->query($query1);
            if ($result1->num_rows > 0) {
                while($row1 = $result1->fetch_assoc()) {
                    $user_email = "";
                    $first_name = $row1['First_name'];
                    $last_name = $row1['Last_name'];
                    $status_str = $row1['Card_status'];
                    if($status_str == 'Active'){
                        $status = 1;
                    } else{
                        $status = 0;
                    }
                }
            }
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
		$($("#nav ul li")[2]).css("background", '#2c2c2c');
		$($("#nav ul li")[3]).css("background", '#b12226');
		$($("#nav ul li")[4]).css("background", '#2c2c2c');
        //---- End -----
        
        $('#refund_reason').val('');
        $("#refund_amount").keyup(function(e){
            $("#amount_tip").hide();
            var current_amount = $("#current_amount").html();
            var refund_amount = e.target.value;
            console.log(parseFloat(refund_amount));
            console.log(parseFloat(current_amount));
            if(current_amount == '0' || parseFloat(refund_amount) > parseFloat(current_amount)){
                $("#amount_tip").show();
            }
        });

        $("#refund_reason").keyup(function(e){
            if(e.target.value != ''){
                $("#refund_reason_tip").hide();
            } else{
                $("#refund_reason_tip").show();
            }
        });
    });

    function refund(){
        if($("#amount_tip").css('display') != 'none'){
            $('#refund_amount').focus();
            return;
        }
        var family_code = '<?php echo $family_code; ?>';
        var issues_person = "<?php echo $id; ?>";
        var refund_amount = $('#refund_amount').val();
        var refund_reason = $('#refund_reason').val();
        if(refund_amount == ''){
            $('#refund_amount').focus();
            return;
        }
        if(refund_reason == ''){
            $("#refund_reason_tip").show();
            $('#refund_reason').focus();
            return;
        }
        var obj = {
            family_code : family_code,
            refund_amount : refund_amount,
            refund_reason : refund_reason,
            issues_person : issues_person,
            status : $("#status").val()
        };
        var url = '<?php echo $rootpath;?>/api/record_refund.php';
        var request = JSON.stringify(obj);
        $.ajax({
            url : url,
            type : 'POST',
            data :  request,   
            tryCount : 0,
            retryLimit : 3,
            success : function(info) {
                var info = JSON.parse(info);
                alert("Successfully saved!");
                $('#current_amount').html(info.response.data);
                $('#refund_amount').val('');
                $('#refund_reason').val('');
                console.log(info);
            },
            error : function(xhr, textStatus, errorThrown ) {
                console.log(xhr);
            }
        });
    }
</script>


<div class="user_edit" style="width:80%; margin:0 auto;">
    <h2 style="padding-left:20px"> <strong style="color:#2d2d2d"> Refund <span style='color:red;'> <span id='user_full_name'> <?php echo $first_name.' '.$last_name; ?> </span> (<span><?php echo $user_email; ?></span>) </span>  </strong> </h2>
    <h2 style="padding-left:20px"> <strong style="color:#2d2d2d"> Current Amount Value: $<span id="current_amount"><?php echo $current_amount; ?></span> </strong> </h2>
    <table width="60%">
        <tr>
            <td>
                <div> <span class="label-span"> Refund Amount </span> </div>
                <div> 
                    <input type="email" id='refund_amount' class="input-text-custom" placeholder='Enter amount'/>
                </div>
                <div style='color:red;font-size:16px;font-weight:600;display:none' id='amount_tip'> Refund amount have to be less than current amount. </div> 
            </td>
            <td>
                <div> <span class="label-span"> Status </span> </div> 
                <select class="select-custom" id="status" style="width:100% !important;">
                    <?php if($status == '1'){ ?>
                        <option value="1" selected> Active </option>
                        <option value="0"> InActive </option>
                    <?php } ?>
                    <?php if($status == '0'){ ?>
                        <option value="1"> Active </option>
                        <option value="0" selected> InActive </option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <div> <span class="label-span"> Refund Reasons </span> </div>
                <div> 
                    <textarea id='refund_reason' class="input-text-custom" placeholder='Enter refund reason'> </textarea>
                </div>
                <div id="refund_reason_tip"> You are require to enter a refund reasons. </div>
            </td>
        </tr>
    </table>

    <div style="margin-top:30px; text-align:center;">
        <a class="control-button" href="<?php echo $rootpath;?>/refund.php"> Back </a> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="control-button" onclick="refund()"> Confirm Refund </a>
    </div>
</div>



<?php
	include("footer.php");
?>

<style type="text/css">
    table td{
        padding:25px;
    }
    table textarea{
        height:70px !important;
    }
    #refund_reason_tip{
        color: red;
        font-size: 17px;
        display: none;
    }
</style>