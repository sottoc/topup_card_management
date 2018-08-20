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
	$meal_statusinfo=new meal_statusinfo();	
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('meal_status',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');
	
	if(isset($_GET['meal_status_id']) && $_GET['meal_status_id']!='')
	{
		$meal_status_id=(int)$_GET['meal_status_id'];
		$Rresult=$setupbol->get_meal_status_byid($meal_status_id);
		while($aRow = $Rresult->getNext())
		{
			$meal_status_name=htmlspecialchars($aRow['meal_status_name']);
		}
		$old_meal_status_value_str="meal_status_id=>".$meal_status_id.",meal_status_name=>".$meal_status_name;
	}
	
	if (isset($_POST['btnedit'])) 	
	{	
		if(trim($_POST['edt_txt_mealstatus_name']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_meal_status_name'];
		}		
		
		if(count($errors) == 0)
		{	
			$edt_hid_mealstatus_values = $_POST['edt_hid_mealstatus_values'];
			$meal_statusinfo->set_meal_status_id($_POST['edt_hid_mealstatus_id']);	
			$meal_statusinfo->set_meal_status_name($_POST['edt_txt_mealstatus_name']);
			$meal_statusinfo->set_meal_status_modified_datetime($now_date_time);
			
			$check_dup_updating_result=$setupbol->check_duplicate_mealstatus_updating($_POST['edt_txt_mealstatus_name'],$_POST['edt_hid_mealstatus_id']);
			$row = $check_dup_updating_result->getNext();
			$count_duplicate_updating = $row['count_duplicate_updating'];
			if($count_duplicate_updating==0)
			{
				$result=$setupbol->update_meal_status($meal_statusinfo,$edt_hid_mealstatus_values);
				if($result)
					$_SESSION['meal_status_msg'] = $localized_data['update_meal_status_msg'];
				else
					$_SESSION['meal_status_msg'] = $localized_home_data['update_fail_msg'];
			}
			else
				$_SESSION['meal_status_msg'] = $localized_data['duplicate_save_meal_status_msg'];
				
			header("location:meal_status_list.php");
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
		jQuery("#edit_mealstatus").validate(
		{			
		'rules':
			{		
				'edt_txt_mealstatus_name':{'required':true}	
			},
		'messages':
			{
				'edt_txt_mealstatus_name':{'required':"*<?php echo $localized_data['check_req_field_meal_status_name']; ?>"}	
			},				
			'errorPlacement': function(error, element) {
				$(element).after(error);
			}
		});
	}	
</script>

<div class="content_data">
	<form action="" method="POST" id="edit_mealstatus" name="edit_mealstatus">

		<h2><?php echo $localized_data['edt_meal_status_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="edt_hid_mealstatus_values" name="edt_hid_mealstatus_values" value="<?php echo $old_meal_status_value_str; ?>"/>
			<input type="hidden" id="edt_hid_mealstatus_id" name="edt_hid_mealstatus_id" value="<?php echo $meal_status_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['meal_status_name']; ?>:</div>
				<input type="text" name="edt_txt_mealstatus_name" id="edt_txt_mealstatus_name" value="<?php echo $meal_status_name; ?>" />
			</div>
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnedit"  id="btnedit" value="<?php echo $localized_home_data['edit_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='meal_status_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>	
			
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>