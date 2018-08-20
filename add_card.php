<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$errors = array();
	$studentcardbol = new studentcardbol();
	$localizationbol= new localizationbol();
	$cardinfo=new cardinfo();	
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('card_status',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');

	$login_user_id = $_SESSION ['login_user_id'];
	if(isset($_GET['participant_id']))
		$participant_id=(int)$_GET['participant_id'];
	
	if (isset($_POST['btnsave'])) 	
	{	
		$hid_participant_id = $_POST['hid_participant_id'];
		$txt_card_number = $_POST['txt_card_number'];
		//$txt_card_description = $_POST['txt_card_description'];
		if(trim($_POST['txt_card_number'] == ''))
		{
			$errors[] = '*Please fill card number.';
		}
		/* if(trim($_POST['txt_card_description'] == ''))
		{
			$errors[] = '*Please fill card description.';
		} */
		if(count($errors) == 0)
		{
			$cardinfo->set_card_number($txt_card_number);
			$cardinfo->set_participant_id($hid_participant_id);
			//$cardinfo->set_card_description($txt_card_description);
			$cardinfo->set_current_card_amt(0);
			$cardinfo->set_card_status_id(1);
			$cardinfo->set_card_issued_datetime($now_date_time);
			$cardinfo->set_card_data_modified_datetime($now_date_time);
			$expired_date = date('Y-m-d', strtotime('+2 years'));
			$cardinfo->set_card_expired_datetime($expired_date);
			$check_dup_saving_result=$studentcardbol->check_duplicate_card_saving($txt_card_number);
			$row = $check_dup_saving_result->getNext();
			$count_duplicate_saving = $row['count_duplicate_saving'];
			if($count_duplicate_saving==0)
			{
				$result_id=$studentcardbol->save_card($cardinfo);
				if($result_id)
					$_SESSION['card_msg'] = 'Saving card successfully!.';
				else
					$_SESSION['card_msg'] = 'Saving Fail!.';
			}
			else
				$_SESSION['card_msg'] = 'Duplicate card Number';
			header("location:card_list.php?participant_id=$hid_participant_id");
			exit();
		}
	}
	
//-----------------------//	
require_once('header.php');
	
?>
<script type="text/javascript">			
	jQuery(document).ready(function(){		
		AddValidation();
	});	
	 
	function AddValidation()
	{
		jQuery("#addnew_participant").validate(
		{			
		'rules':
			{		
				'txt_card_number':{'required':true},	
				'sel_participant':{'required':true}	
			},
		'messages':
			{
				'txt_card_number':{'required':"*Please Fill Student Name"},	
				'sel_participant':{'required':"*Please Fill Student ID"}	
			},				
		'errorPlacement': function(error, element) {
			$(element).after(error);
		}
		});
	}	

</script>
<style type="text/css">
</style>

<div class="content_data">
	<form action="" method="POST" id="addnew_participant" name="addnew_participant" >
		<h2>Add New Card</h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join( $errors)."</div>";
	  			}
			?>
			<input type="hidden" name="hid_participant_id" id="hid_participant_id" value="<?php echo $participant_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat">Card Number:</div>
				<input type="text" name="txt_card_number" id="txt_card_number" />
			</div>
			<!--<div class="frm">
				<div class="frm_labelcat">Card Description:</div>
				<input type="text" name="txt_card_description" id="txt_card_description" />
			</div>-->
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnsave"  id="btnsave" value="<?php echo $localized_home_data['save_btn']; ?>" class="btn" /> &nbsp;
				<button name="btncancel" id="btncancel" class="btn" ><a href="card_list.php?participant_id=<?php echo $participant_id; ?>"><?php echo $localized_home_data['cancel_btn']; ?></a></button>
			</div>			
	</form>
</div><!-- /content_data -->
<?php
	include('library/closedb.php');
	include("footer.php");
?>