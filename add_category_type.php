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
	$category_typeinfo=new category_typeinfo();	
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('category_type',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');

	if (isset($_POST['btnsave'])) 	
	{	
		if(trim($_POST['txt_categorytype_name'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_category_type_name'];
		}
		if(count($errors) == 0)
		{
			$category_typeinfo->set_category_type_name($_POST['txt_categorytype_name']);
			$category_typeinfo->set_category_type_created_datetime($now_date_time);
			$category_typeinfo->set_category_type_modified_datetime($now_date_time);
			
			$check_dup_saving_result=$setupbol->check_duplicate_categorytype_saving($_POST['txt_categorytype_name']);
			$row = $check_dup_saving_result->getNext();
			$count_duplicate_saving = $row['count_duplicate_saving'];
			if($count_duplicate_saving==0)
			{
				$result=$setupbol->save_categorytype($category_typeinfo);
				if($result)
					$_SESSION['category_type_msg'] = $localized_data['save_category_type_msg'];
				else
					$_SESSION['category_type_msg'] = $localized_home_data['save_fail_msg'];
			}
			else
				$_SESSION['category_type_msg'] = $localized_data['duplicate_category_type_msg'];
				
			header("location:category_type_list.php");
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
		jQuery("#addnew_categorytype").validate(
		{			
		'rules':
			{		
				'txt_categorytype_name':{'required':true}	
			},
		'messages':
			{
				'txt_categorytype_name':{'required':"*<?php echo $localized_data['check_req_field_category_type_name']; ?>"}	
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
	<form action="" method="POST" id="addnew_categorytype" name="addnew_categorytype" >
		<h2><?php echo $localized_data['add_new_category_type_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join( $errors)."</div>";
	  			}
			?>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['category_type_name']; ?>:</div>
				<input type="text" name="txt_categorytype_name" id="txt_categorytype_name" />
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