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
	
	if(isset($_GET['category_type_id']) && $_GET['category_type_id']!='')
	{
		$category_type_id=(int)$_GET['category_type_id'];
		$Rresult=$setupbol->get_category_type_byid($category_type_id);
		while($aRow = $Rresult->getNext())
		{
			$category_type_name=htmlspecialchars($aRow['category_type_name']);
		}
		$old_category_type_value_str="category_type_id=>".$category_type_id.",category_type_name=>".$category_type_name;
	}
	
	if (isset($_POST['btnedit'])) 	
	{	
		if(trim($_POST['edt_txt_categorytype_name']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_category_type_name'];
		}		
		
		if(count($errors) == 0)
		{	
			$edt_hid_categorytype_values = $_POST['edt_hid_categorytype_values'];
			$category_typeinfo->set_category_type_id($_POST['edt_hid_categorytype_id']);	
			$category_typeinfo->set_category_type_name($_POST['edt_txt_categorytype_name']);
			$category_typeinfo->set_category_type_modified_datetime($now_date_time);
			
			$check_dup_updating_result=$setupbol->check_duplicate_category_type_updating($_POST['edt_txt_categorytype_name'],$_POST['edt_hid_categorytype_id']);
			$row = $check_dup_updating_result->getNext();
			$count_duplicate_updating = $row['count_duplicate_updating'];
			if($count_duplicate_updating==0)
			{
				$result=$setupbol->update_category_type($category_typeinfo,$edt_hid_categorytype_values);
				if($result)
					$_SESSION['category_type_msg'] = $localized_data['update_category_type_msg'];
				else
					$_SESSION['category_type_msg'] = $localized_home_data['update_fail_msg'];
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
		jQuery("#edit_categorytype").validate(
		{			
		'rules':
			{		
				'edt_txt_categorytype_name':{'required':true}	
			},
		'messages':
			{
				'edt_txt_categorytype_name':{'required':"*<?php echo $localized_data['check_req_field_category_type_name']; ?>"}	
			},				
			'errorPlacement': function(error, element) {
				$(element).after(error);
			}
		});
	}	
</script>

<div class="content_data">
	<form action="" method="POST" id="edit_categorytype" name="edit_categorytype">

		<h2><?php echo $localized_data['edt_category_type_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="edt_hid_categorytype_values" name="edt_hid_categorytype_values" value="<?php echo $old_category_type_value_str; ?>"/>
			<input type="hidden" id="edt_hid_categorytype_id" name="edt_hid_categorytype_id" value="<?php echo $category_type_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['category_type_name']; ?>:</div>
				<input type="text" name="edt_txt_categorytype_name" id="edt_txt_categorytype_name" value="<?php echo $category_type_name; ?>" />
			</div>
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnedit"  id="btnedit" value="<?php echo $localized_home_data['edit_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='category_type_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>	
			
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>