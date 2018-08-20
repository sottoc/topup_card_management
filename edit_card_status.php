<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$errors = array();
	$setupbol = new setupbol();
	$localizationbol= new localizationbol();
	$card_statusinfo=new card_statusinfo();	
	
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
	
	if(isset($_GET['card_status_id']) && $_GET['card_status_id']!='')
	{
		$card_status_id=(int)$_GET['card_status_id'];
		$Rresult=$setupbol->get_card_status_byid($card_status_id);
		while($aRow = $Rresult->getNext())
		{
			$card_status_name=htmlspecialchars($aRow['card_status_name']);
		}
		$old_card_status_value_str="card_status_id=>".$card_status_id.",card_status_name=>".$card_status_name;
	}
	
	if (isset($_POST['btnedit'])) 	
	{	
		if(trim($_POST['edt_txt_cardstatus_name']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_card_status_name'];
		}		
		
		if(count($errors) == 0)
		{	
			$edt_hid_cardstatus_values = $_POST['edt_hid_cardstatus_values'];
			$card_statusinfo->set_card_status_id($_POST['edt_hid_cardstatus_id']);	
			$card_statusinfo->set_card_status_name($_POST['edt_txt_cardstatus_name']);
			$card_statusinfo->set_card_status_modified_datetime($now_date_time);
			
			$check_dup_updating_result=$setupbol->check_duplicate_card_status_updating($_POST['edt_txt_cardstatus_name'],$_POST['edt_hid_cardstatus_id']);
			$row = $check_dup_updating_result->getNext();
			$count_duplicate_updating = $row['count_duplicate_updating'];
			if($count_duplicate_updating==0)
			{
				$result=$setupbol->update_card_status($card_statusinfo,$edt_hid_cardstatus_values);
				if($result)
					$_SESSION['card_status_msg'] = $localized_data['update_card_status_msg'];
				else
					$_SESSION['card_status_msg'] = $localized_home_data['update_fail_msg'];
			}
			else
				$_SESSION['card_status_msg'] = $localized_data['duplicate_card_status_msg'];
				
			header("location:card_status_list.php");
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
		jQuery("#edit_cardstatus").validate(
		{			
		'rules':
			{		
				'edt_txt_cardstatus_name':{'required':true}	
			},
		'messages':
			{
				'edt_txt_cardstatus_name':{'required':"*<?php echo $localized_data['check_req_field_card_status_name']; ?>"}	
			},				
			'errorPlacement': function(error, element) {
				$(element).after(error);
			}
		});
	}	
</script>

<div class="content_data">
	<form action="" method="POST" id="edit_cardstatus" name="edit_cardstatus">

		<h2><?php echo $localized_data['edt_card_status_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="edt_hid_cardstatus_values" name="edt_hid_cardstatus_values" value="<?php echo $old_card_status_value_str; ?>"/>
			<input type="hidden" id="edt_hid_cardstatus_id" name="edt_hid_cardstatus_id" value="<?php echo $card_status_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['card_status_name']; ?>:</div>
				<input type="text" name="edt_txt_cardstatus_name" id="edt_txt_cardstatus_name" value="<?php echo $card_status_name; ?>" />
			</div>
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnedit"  id="btnedit" value="<?php echo $localized_home_data['edit_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='card_status_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>	
			
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>