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
	$paypal_id='ydn.smile@gmail.com'; // Business email ID
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

<div class="content_data">
	<div class="product">            
	  
		<h2>Top-Up</h2>
		
		<div style="font-size:16px"> Please select the amount you wish to topup </div>
		<br/>
		<table>
			<tr>
				<td>
					<div class="topup-amount-div" data="0.01">
						<strong> $0.01(Test) </strong>
					</div>
				</td>
				<td>
					<div class="topup-amount-div" data="30">
						<strong> $30 </strong>
					</div>
				</td>
				<td>
					<div class="topup-amount-div" data="50">
						<strong> $50 </strong>
					</div>
				</td>
				<td>
					<div class="topup-amount-div" data="100">
						<strong> $100 </strong>
					</div>
				</td>
			</tr>
		</table>
		<br/>

		<form action="<?php echo $paypal_url; ?>" method="post" name="frmPayPal1">
			<input type="hidden" name="business" value="<?php echo $paypal_id; ?>">
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
			<input type="hidden" name="item_number" value="204">

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
			$("input[name='amount']").val(amount);
		});

		$($(".topup-amount-div")[0]).trigger('click');
	});

	

</script>