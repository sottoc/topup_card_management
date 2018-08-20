<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	require_once('header.php');	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	$errors = array();
	$transactionbol = new transactionbol();
	$localizationbol= new localizationbol();
	$topupinfo = new topupinfo();
	$transactioninfo = new transactioninfo();
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('items',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	// //current date time
	// date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    // $now_date_time = date('Y-m-d H:i:s');
	
	/* if (isset($_POST['btnsave'])) 	
	{
		$topup_msg='';
		if(trim($_POST['topup_manual_studentid'] == ''))
		{
			$errors[] = '* Please fill the student ID.';
		}
		else if(trim($_POST['topup_manual_amt'] == '') && $_POST['topup_manual_amt'] ==0 && $_POST['topup_manual_amt'] ==0.0)
		{
			$errors[] = '* Please fill the topup amount.';
		}
		else if(trim($_POST['topup_pos_receipt_no'] == ''))
		{
			$errors[] = '* Please fill the POS receipt number.';
		}
		if(count($errors) == 0)
		{
			$topup_manual_studentid = $_POST['topup_manual_studentid'];
			$topupinfo->set_topup_amt($_POST['topup_manual_amt']);
			$topupinfo->set_payment_type("Manual");
			$topupinfo->set_payment_status("Paid");
			$topupinfo->set_pos_slip_id($_POST['topup_pos_receipt_no']);
			//check student id is right or wrong
			$count_participant_res=$transactionbol->check_studentid($topup_manual_studentid);
			$aRow = $count_participant_res->getNext();
			$count_participant = $aRow['count_participant'];
			if($count_participant==0)
			{
				echo '<h3 id="h3_msg" style="margin-left:168px;padding-top: 13px;color:red;">Invalid Student ID.Try to topup again.</h3>';
				sleep(5);//sleep for 5 seconds
			}
			else if($count_participant>0)
			{
				$topup_id=$transactionbol->save_topup_manually($topupinfo);//saving in tbl_topup
				if($topup_id!=null)
				{
					//find card_id student_primary_id and card_id
					$participant_result = $transactionbol->get_participant_data_byusingID($topup_manual_studentid);
					if($participant_result)
					{
						$prow = $participant_result->getNext();
						$pid = $prow['pid'];
						$card_id = $prow['card_id'];
						//saving in tbl_transaction
						$transactioninfo->set_trans_type('topup');
						$transactioninfo->set_card_id($card_id);
						$transactioninfo->set_topup_id($topup_id);
						$transactioninfo->set_transaction_amt($_POST['topup_manual_amt']);
						$transactioninfo->set_participant_id($pid);
						$transactioninfo->set_transaction_datetime($now_date_time);
						$trans_id=$transactionbol->save_transaction($transactioninfo);
						if($trans_id)
						{
							echo '<h3 id="h3_msg" style="margin-left:168px;padding-top: 13px;color:red;">TopUp amount is saving successfully.</h3>';
							sleep(5);//sleep for 5 seconds
						}
					}
				}
				else
				{
					echo '<h3 id="h3_msg" style="margin-left:168px;padding-top: 13px;color:red;">TopUp amount is saving fail.</h3>';
					sleep(5);//sleep for 5 seconds
				}
			}
		}
	} */
?>
<script type="text/javascript">	
	jQuery(document).ready(function(){
		$("#h3_msg").hide();
	});
	function topup()
	{
		jQuery('#popupforauth').modal({overlayClose:true, position:"center", opacity:40, overlayCss: {backgroundColor:"#000"}, containerCss:{
		background:"#fff repeat-x bottom left",
		borderColor:"#333",
		color: "#000",
		borderWidth:"3px",
		borderStyle:"solid",		
		height:"auto",
		padding: 30,
		width:150}});
		return false;
	
		$("#message").show();
			
	}
	
	function test_password(loginpassword)
	{
		jQuery("#frm_popupforauth").validate({
		'rules':{		
		'txtpassword':{'required':true}
				
		},
		'messages':{
			'txtpassword':{'required':'*Please enter Password: !'}
			}
		});
		if (jQuery("#frm_popupforauth").validate().form() == false)
		{
		  	return false;
		}
		
		jQuery("#topup_manual").validate({
		'rules':{		
		'topup_manual_studentid':{'required':true},
		'topup_manual_amt':{'required':true},
		'topup_pos_receipt_no':{'required':true}
		},
		'messages':{
			'topup_manual_studentid':{'required':'*Please enter Student Id: !'},
			'topup_manual_amt':{'required':'*Please enter Cash Amount: !'},
			'topup_pos_receipt_no':{'required':'*Please enter Receipt No: !'}
			}
		});
		
		if (jQuery("#topup_manual").validate().form() == false)
		{
		  	return false;
		}

		jQuery.post("checkuseridandpassword.php", {'loginpassword':loginpassword}, function(data)
		{
			if(data.success==0)
			{
				//$("#divbcno").text(data.warning);
				$.modal.close();
				message_popup(data.warning,350);
				return false;
			}
			else
			{
				var topup_manual_studentid=jQuery("#topup_manual_studentid").val();	
				var topup_manual_amt = jQuery("#topup_manual_amt").val();
				var topup_pos_receipt_no = jQuery("#topup_pos_receipt_no").val();	
				
				jQuery.post("save_topup_manually.php", {'topup_manual_studentid':topup_manual_studentid,'topup_manual_amt':topup_manual_amt,'topup_pos_receipt_no':topup_pos_receipt_no}, function (data)
				{
					if(data.success==0)
					{
						//$("#divbcno").text(data.warning);
						message_popup(data.warning,350);						
						return false;
					}
					else{
						//$("#divbcno").text(data.warning);
						message_popup(data.warning,350);
						$.modal.close(); 
						jQuery("#topup_manual_studentid").val('');	
						jQuery("#topup_manual_amt").val('');
						jQuery("#topup_pos_receipt_no").val('');	
						return false;
					}
				}, 'json');		
			
			}
		}, 'json');	
	
	}
</script>
<div class="content_data">
	<form action="" method="POST" id="topup_manual" name="topup_manual" enctype="multipart/form-data">

		<h2>Top up(Cash)</h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<div class="frm">
				<div id="divbcno" class="error"></div>
				<br/>
				<div class="frm_labelcat">Student ID:</div>
				<input type="text" name="topup_manual_studentid" id="topup_manual_studentid"/>
			</div>
			<div class="frm">
				<div class="frm_labelcat">Topup Amount:</div>
				<input type="text" name="topup_manual_amt" id="topup_manual_amt"/>
			</div>
			<div class="frm">
				<div class="frm_labelcat">POS Receipt Number:</div>
				<input type="text" name="topup_pos_receipt_no" id="topup_pos_receipt_no"/>
			</div>
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="button" name="btnsave"  id="save_btn" value="<?php echo $localized_home_data['save_btn']; ?>" class="btn" onClick="topup();"/> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='items_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>	
			
	</form>
	
	<!-- add new item popup -->
	<div id='popupforauth' style="display:none">
		<form id="frm_popupforauth" name="frm_popupforauth">			
			<div>						
				<div class="frm_labelpass" >Password: </div>
				<input type="password" name="txtpassword" id="txtpassword">
			</div>	
			
			<div> 
				<input type="button" name="btnadd" id="btnadd" value="Add" class="btn" onclick="test_password(document.frm_popupforauth.txtpassword.value);" />
				<input type="button" name="btncancel" id="btncancel" value="Cancel" class="btn" onclick="$.modal.close();" />
			</div>
		</form>
	</div>
	
</div>
<?php
	include('library/closedb.php');
	include("footer.php");
?>