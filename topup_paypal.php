<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');	
	require_once('header.php');
	
	//$paypal_url='https://www.sandbox.paypal.com/cgi-bin/webscr'; // Test Paypal API URL
	$paypal_url='https://www.paypal.com/cgi-bin/webscr'; // Test Paypal API URL

	//$paypal_id='ydn.smile-facilitator-1@gmail.com'; // Business email ID
	//$paypal_id='ydn.smile@gmail.com'; // Business email ID

	$paypal_id = 'peihan@compass-group.sg';
?>

<script language="javascript">
	jQuery(document).ready(function()
		{
			//---- set background for active menu -----
			// if(localStorage.getItem("current_page") != undefined){
			// 	for(var i=0; i < $("#nav ul li").length; i++){
			// 		if($($("#nav ul li")[i]).html() == localStorage.getItem("current_page")){
			// 			$($("#nav ul li")[i]).css("background", '#b12226');
			// 		}
			// 	}
			// }
			//---- End -----
			$($("#nav ul li")[2]).css("background", '#b12226');
		}
	);
</script>

<?php
    require_once('api/api_common.php');
	$query1 = "SELECT * FROM `tbl_user` WHERE user_email = '".$_SESSION ['login_user_email']."'";
	$result1 = $conn->query($query1);
	if ($result1->num_rows > 0) {
        while($row1 = $result1->fetch_assoc()) {
			$family_code = $row1['family_code'];
		}
	}

	$query = "SELECT * FROM `tbl_topup_box` ORDER BY amount";
    $result = $conn->query($query);
    $all_box = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $box = array();
            $box[] = $row['box_id'];
            $box[] = number_format((float)$row['amount'], 2, '.', '');
            $box[] = $row['description'];
            $box[] = number_format((float)$row['bonus_value'], 2, '.', '');
            $box[] = $row['limit_times'];
            $box[] = $row['datetime_from'];
			$box[] = $row['datetime_to'];
			$box[] = $row['box_status'];
			if($row['bonus_value'] != "0" && $row['box_status'] =="1"){
				$query1 = "SELECT * FROM tbl_topup_limit_record WHERE family_code = '".$family_code."' AND group_id = '".$row['group_id']."'";
				$result1 = $conn->query($query1);
				if ($result1->num_rows > 0) {
					while($row1 = $result1->fetch_assoc()) {
						$limit_used = $row1['limit_used'];
						$box[] = (int)$row['limit_times'] - (int)$limit_used;
					}
				} else{
					$box[] = $row['limit_times'];
				}
			} else{
				$box[] = '0';
			}
            array_push($all_box, $box);
        }
	}

?>

<div class="content_data">
	<div class="product">            
	  
		<h2>Top-Up</h2>
		
		<div style="font-size:16px"> Please select the amount you wish to topup </div>
		<br/>
		<table>
			<tr>
				<?php foreach ($all_box as $box) { if($box[7] == '1'){ 
					$effective_date = "Effective";
					date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
					if($box[3] == '0') { $time_to = "2030-01-01 00:00:00"; } else { $time_to = date($box[6]); }
					$time_now = date("Y-m-d h:i:s");
					if($time_now >= $time_to) { $effective_date = "Not Effective"; }
				?>
					<td>
						<div class="topup-amount-div" data="<?php echo $box[1];?>" box-id="<?php echo $box[0];?>">
							<strong> $<?php echo $box[1];?> </strong>
							<?php if($box[3] != "0") { ?>
								<?php if($effective_date == "Effective") { ?>
									<?php if($box[8] == '0') { ?>
									<div style="visibility:hidden;">
										<div style="font-size:16px;font-weight:600;"> Extra $<?php echo $box[3]; ?> </div>
									</div>
									<?php } else{?>
									<div>
										<div style="font-size:16px;font-weight:600;"> Extra $<?php echo $box[3]; ?> </div>
									</div>
									<?php } ?>
							    <?php } else { ?>
									<div style="visibility:hidden;">
										<div style="font-size:16px;font-weight:600;"> Extra $<?php echo $box[3]; ?> </div>
									</div>
								<?php } ?>
							<?php } else {?>
								<div style="font-size:16px;font-weight:600;visibility:hidden;"> Extra $<?php echo $box[3]; ?> </div>
							<?php }?>
						</div>
					</td>
				<?php } } ?>
			</tr>
		</table>
		<br/>

		<form action="<?php echo $paypal_url; ?>" method="post" name="frmPayPal1">
			<input type="hidden" name="business" value="<?php echo $paypal_id; ?>">
			<input type="hidden" name="bn" value="butterflysg_SP_Compass">
			<input type="hidden" name="cmd" value="_xclick">
			
			<input type="hidden" name="item_name" value="Chartwells Food Card Topup">

			<input type="hidden" name="add" add="1">
			<input type="hidden" name="display" display="1">
			
			<!-- <div class="frm">
				<div class="frm_label">Student Name :</div>
				<input type="text" name="item_name">
			</div>
			<div class="frm">
				<div class="frm_label">Student Id :</div>
				<input type="text" name="item_number">
			</div> -->
			<input type="hidden" name="item_number" value="0">

			<div class="frm" style="display:none;">
				<div class="frm_label">Amount : </div>
				<input type="text" name="amount">
			</div>
			
			<input type="hidden" name="no_shipping" value="1">
	
			<input type="hidden" name="no_note" value="0" /> 
			<input type="hidden" name="rm" value="2">
			<input type="hidden" name="handling" value="0">
			<!--input type="hidden" name="cpp_header_image" value="http://localhost/topup_card_management_dec5/images/topup_logotext.gif"-->
			<input type="hidden" name="currency_code" value="SGD">
			<!--input type="hidden" name="handling" value="0"-->
			<input type="hidden" name="cancel_return" value="<?php echo $rootpath; ?>/cancel.php">
			<input type="hidden" name="return" value="<?php echo $rootpath; ?>/success.php">
			<div>
				<input type="submit" name="btnsubmit" value="Top-up Now" class="btn control-button" style="height:100% !important;">
			</div>
			
			<!--input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"-->
			<!--img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"-->
		</form> 
		
	</div>
</div>

<style>
	.topup-amount-div{
		width: 180px;
		background: rgb(191, 189, 189);
		border-radius: 13px;
		padding: 52px 30px;
		text-align:center;
		cursor: pointer;
		margin-left: 10px;
	}
	.topup-amount-div:hover, .topup-amount-div:visited{
		background-color: #f0c370;
	}
	.topup-amount-div strong{
		font-size: 30px;
		font-weight:600;
	}
</style>

<script>

	$(document).ready(function(){
		$(".topup-amount-div").click(function(){
			$(".topup-amount-div").css("background", "rgb(191, 189, 189)");
			$(this).css("background", "#f0c370");
			var amount = $(this).attr('data');
			var box_id = $(this).attr('box-id');
			console.log(box_id);
			$("input[name='amount']").val(amount);
			$("input[name='item_number']").val(box_id);
		});

		$($(".topup-amount-div")[0]).trigger('click');
		
	});

</script>