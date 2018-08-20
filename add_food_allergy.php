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
	$food_allergyinfo=new food_allergyinfo();	
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');
	
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

	if (isset($_POST['btnsave'])) 	
	{	
		if(trim($_POST['txt_foodallergy_name'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_food_allergy_name'];
		}
		if(count($errors) == 0)
		{
			$food_allergyinfo->set_food_allergy_name($_POST['txt_foodallergy_name']);
			$food_allergyinfo->set_food_allergy_created_datetime($now_date_time);
			$food_allergyinfo->set_food_allergy_modified_datetime($now_date_time);
			
			$check_dup_saving_result=$setupbol->check_duplicate_foodallergy_saving($_POST['txt_foodallergy_name']);
			$row = $check_dup_saving_result->getNext();
			$count_duplicate_saving = $row['count_duplicate_saving'];
			if($count_duplicate_saving==0)
			{
				$result=$setupbol->save_foodallergy($food_allergyinfo);
				if($result)
					$_SESSION['food_allergy_msg'] = $localized_data['save_food_allergy_msg'];
				else
					$_SESSION['food_allergy_msg'] = $localized_home_data['save_fail_msg'];
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
		jQuery("#addnew_foodallergy").validate(
		{			
		'rules':
			{		
				'txt_foodallergy_name':{'required':true}	
			},
		'messages':
			{
				'txt_foodallergy_name':{"*<?php echo $localized_data['check_req_field_food_allergy_name']; ?>"}	
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
	<form action="" method="POST" id="addnew_foodallergy" name="addnew_foodallergy" >
		<h2><?php echo $localized_data['add_new_food_allergy_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join( $errors)."</div>";
	  			}
			?>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['food_allergy_name']; ?>:</div>
				<input type="text" name="txt_foodallergy_name" id="txt_foodallergy_name" />
			</div>
			
		
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnsave"  id="btnsave" value="<?php echo $localized_home_data['save_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='food_allergy_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>			
	</form>
</div><!-- /content_data -->
<?php
	include('library/closedb.php');
	include("footer.php");
?>