<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');	
	require_once('header.php');
	
	$paypal_url='https://www.sandbox.paypal.com/cgi-bin/webscr'; // Test Paypal API URL
	//$paypal_id='ydn.smile-facilitator-1@gmail.com'; // Business email ID
	$paypal_id='ydn.smile@gmail.com'; // Business email ID
?>

<script language="javascript">
	jQuery(document).ready(function()
		{
			//---- set background for active menu -----
			if(localStorage.getItem("current_page") != undefined){
				for(var i=0; i < $("#nav ul li").length; i++){
					if($($("#nav ul li")[i]).html() == localStorage.getItem("current_page")){
						$($("#nav ul li")[i]).css("background", '#b12226');
					}
				}
			}
			//---- End -----
		}
	);
</script>

<div class="content_data">
	<div class="product">            
	  
		<h2>TopUp Paypal</h2>
		
		<div style="font-size:16px"> Please select the amount you wish to topup </div>
		<br/>
		<table>
			<tr>
				<td>
					<div class="topup-amount-div">
						<strong> S$50 </strong>
					</div>
				</td>
				<td>
					<div class="topup-amount-div">
						<strong> S$100 </strong>
					</div>
				</td>
				<td>
					<div class="topup-amount-div">
						<strong> S$200 </strong>
					</div>
				</td>
			</tr>
		</table>
		<br/>

		<form action="<?php echo $paypal_url; ?>" method="post" name="frmPayPal1">
		<input type="hidden" name="business" value="<?php echo $paypal_id; ?>">
		<input type="hidden" name="cmd" value="_xclick">
		
		<!-- <div class="frm">
			<div class="frm_label">Student Name :</div>
			<input type="text" name="item_name">
		</div>
		<div class="frm">
			<div class="frm_label">Student Id :</div>
			<input type="text" name="item_number">
		</div>
		<div class="frm">
			<div class="frm_label">Amount : </div>
			<input type="text" name="amount">
		</div> -->
		
		<!--div class="frm">
			<div class="frm_label">Invoice : </div>
			<input type="text" name="invoice" value="">
		</div-->
		
		<!--div class="frm">
			<div class="frm_label">Item name : </div>
			 <input type="text" name="item_name" value="PHPGang Payment">
		</div-->
	
		<input type="hidden" name="no_shipping" value="1">
  
		<input type="hidden" name="no_note" value="0" /> 
		<input type="hidden" name="rm" value="2">
		<input type="hidden" name="handling" value="0">
		<!--input type="hidden" name="cpp_header_image" value="http://localhost/topup_card_management_dec5/images/topup_logotext.gif"-->
		<input type="hidden" name="currency_code" value="SGD">
		<!--input type="hidden" name="handling" value="0"-->
		<input type="hidden" name="cancel_return" value="http://localhost/topup_card_management/cancel.php">
		<input type="hidden" name="return" value="http://localhost/topup_card_management/success.php">
		<div>
			<input type="submit" name="btnsubmit" value="Pay by Paypal" class="btn control-button" style="height:100% !important;">
		</div>
		
		<!--input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"-->
		<!--img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"-->
		</form> 
		
	</div>
</div>

<style>
	.topup-amount-div{
		width: 210px;
		background: #e9e9e9;
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
		font-size: 20px;
		font-weight:600;
	}
</style>

<script>
	$(".topup-amount-div").click(function(){
		$(".topup-amount-div").css("background", "#e9e9e9");
		$(this).css("background", "#f0c370");
	});

</script>