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
	
	if(isset($_GET['edit_card_id']) && $_GET['edit_card_id']!='')
	{
		$edit_card_id =(int)$_GET['edit_card_id'];
		$participant_id =(int)$_GET['participant_id'];
		$Rresult=$studentcardbol->get_card_byid($edit_card_id);
		while($aRow = $Rresult->getNext())
		{
			$card_number=htmlspecialchars($aRow['card_number']);
			//$card_description=htmlspecialchars($aRow['card_description']);
		}
		$old_card_value_str="card_id=>".$edit_card_id.",card_number=>".$card_number;
	}
	
	if (isset($_POST['btnedit'])) 	
	{	
		if(trim($_POST['edt_txt_card_number']) == '')
		{
			$errors[] = '*Please fill card number.';
		}
		/* if(trim($_POST['edt_txt_card_description']) == '')
		{
			$errors[] = '*Please fill card description.';
		} */
		
		if(count($errors) == 0)
		{	
			$hid_participant_id = $_POST['hid_participant_id'];
			$edt_hid_card_values = $_POST['edt_hid_card_values'];
			$cardinfo->set_card_id($_POST['edt_hid_card_id']);	
			$cardinfo->set_card_number($_POST['edt_txt_card_number']);
			//$cardinfo->set_card_description($_POST['edt_txt_card_description']);
			$cardinfo->set_card_data_modified_datetime($now_date_time);
			
			$check_dup_updating_result=$studentcardbol->check_duplicate_card_updating($_POST['edt_txt_card_number'],$_POST['edt_hid_card_id']);
			$row = $check_dup_updating_result->getNext();
			$count_duplicate_updating = $row['count_duplicate_updating'];
			if($count_duplicate_updating==0)
			{
				$result=$studentcardbol->update_card($cardinfo,$edt_hid_card_values);
				if($result)
					$_SESSION['card_msg'] = 'Updated Card Successfully';
				else
					$_SESSION['card_msg'] = 'Updated fail';
			}
			else
				$_SESSION['card_msg'] = 'Duplicate card number';
				
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
		jQuery("#edit_card").validate(
		{			
		'rules':
			{		
				'edt_txt_card_number':{'required':true}
				//'edt_txt_card_description':{'required':true}	
			},
		'messages':
			{
				'edt_txt_card_number':{'required':"*Please fill card number."}
				//'edt_txt_card_description':{'required':"*Please fill card description."}	
			},				
			'errorPlacement': function(error, element) {
				$(element).after(error);
			}
		});
	}	
</script>

<div class="content_data">
	<form action="" method="POST" id="edit_card" name="edit_card">

		<h2>Edit Card</h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="edt_hid_card_values" name="edt_hid_card_values" value="<?php echo $old_card_value_str; ?>"/>
			<input type="hidden" id="edt_hid_card_id" name="edt_hid_card_id" value="<?php echo $edit_card_id; ?>"/>
			<input type="hidden" name="hid_participant_id" id="hid_participant_id" value="<?php echo $participant_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat">Card Number:</div>
				<input type="text" name="edt_txt_card_number" id="edt_txt_card_number" value="<?php echo $card_number; ?>" />
			</div>
			<!--<div class="frm">
				<div class="frm_labelcat">Card Description:</div>
				<input type="text" name="edt_txt_card_description" id="edt_txt_card_description" value="<?php echo $card_description; ?>" />
			</div>-->
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnedit"  id="btnedit" value="<?php echo $localized_home_data['edit_btn']; ?>" class="btn" /> &nbsp;
				<button name="btncancel" id="btncancel" class="btn" ><a href="card_list.php?participant_id=<?php echo $participant_id; ?>"><?php echo $localized_home_data['cancel_btn']; ?></a></button>
			</div>		
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>