<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	session_start();
	
	//require_once('userauth.php');
	
	$errors = array();
	$setupbol = new setupbol();
	$localizationbol= new localizationbol();
	$food_allergyinfo=new food_allergyinfo();

	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('food_allergy',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');
	
	if(isset($_GET['food_allergy_id']) && $_GET['food_allergy_id']!='')
	{
		$food_allergy_id=(int)$_GET['food_allergy_id'];
		$Rresult=$setupbol->get_food_allergy_byid($food_allergy_id);
		while($aRow = $Rresult->getNext())
		{
			$food_allergy_name=htmlspecialchars($aRow['food_allergy_name']);
		}
		$old_food_allergy_value_str="food_allergy_id=>".$food_allergy_id.",food_allergy_name=>".$food_allergy_name;
	}
	
	if (isset($_POST['btnedit'])) 	
	{	
		if(trim($_POST['edt_txt_foodallergy_name']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_food_allergy_name'];
		}		
		
		if(count($errors) == 0)
		{	
			$edt_hid_foodallergy_values = $_POST['edt_hid_foodallergy_values'];
			$food_allergyinfo->set_food_allergy_id($_POST['edt_hid_foodallergy_id']);	
			$food_allergyinfo->set_food_allergy_name($_POST['edt_txt_foodallergy_name']);
			$food_allergyinfo->set_food_allergy_modified_datetime($now_date_time);
			
			$check_dup_updating_result=$setupbol->check_duplicate_food_allergy_updating($_POST['edt_txt_foodallergy_name'],$_POST['edt_hid_foodallergy_id']);
			$row = $check_dup_updating_result->getNext();
			$count_duplicate_updating = $row['count_duplicate_updating'];
			if($count_duplicate_updating==0)
			{
				$result=$setupbol->update_food_allergy($food_allergyinfo,$edt_hid_foodallergy_values);
				if($result)
					$_SESSION['food_allergy_msg'] = $localized_data['update_food_allergy_msg'];
				else
					$_SESSION['food_allergy_msg'] = $localized_home_data['update_fail_msg'];
			}
			else
				$_SESSION['food_allergy_msg'] = $localized_data['duplicate_food_allergy_msg'];
				
			header("location:food_allergy_list.php");
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
		jQuery("#edit_foodallergy").validate(
		{			
		'rules':
			{		
				'edt_txt_foodallergy_name':{'required':true}	
			},
		'messages':
			{
				'edt_txt_foodallergy_name':{'required':"*<?php echo $localized_data['check_req_field_food_allergy_name']; ?>"}	
			},				
			'errorPlacement': function(error, element) {
				$(element).after(error);
			}
		});
	}	
</script>

<div class="content_data">
	<form action="" method="POST" id="edit_foodallergy" name="edit_foodallergy">

		<h2><?php echo $localized_data['edt_food_allergy_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="edt_hid_foodallergy_values" name="edt_hid_foodallergy_values" value="<?php echo $old_food_allergy_value_str; ?>"/>
			<input type="hidden" id="edt_hid_foodallergy_id" name="edt_hid_foodallergy_id" value="<?php echo $food_allergy_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['food_allergy_name']; ?>:</div>
				<input type="text" name="edt_txt_foodallergy_name" id="edt_txt_foodallergy_name" value="<?php echo $food_allergy_name; ?>" />
			</div>
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnedit"  id="btnedit" value="<?php echo $localized_home_data['edit_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='food_allergy_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>	
			
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>