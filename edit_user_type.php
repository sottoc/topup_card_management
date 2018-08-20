<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$errors = array();
	$userbol = new userbol();
	$localizationbol= new localizationbol();
	$user_typeinfo=new user_typeinfo();	
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('user_type',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');
	
	if(isset($_GET['user_type_id']) && $_GET['user_type_id']!='')
	{
		$user_type_id=(int)$_GET['user_type_id'];
		$Rresult=$userbol->get_user_type_byid($user_type_id);
		while($aRow = $Rresult->getNext())
		{
			$user_type_name=htmlspecialchars($aRow['user_type_name']);
			$user_type_desc=htmlspecialchars($aRow['user_type_description']);
		}
		$old_user_type_value_str="user_type_id=>".$user_type_id.",user_type_name=>".$user_type_name.",user_type_desc=>".$user_type_desc;
	}
	
	if (isset($_POST['btnedit'])) 	
	{	
		if(trim($_POST['edt_txt_usertype_name']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_user_type_name'];
		}		
		
		if(count($errors) == 0)
		{	
			$edt_hid_usertype_values = $_POST['edt_hid_usertype_values'];
			$user_typeinfo->set_user_type_id($_POST['edt_hid_usertype_id']);	
			$user_typeinfo->set_user_type_name($_POST['edt_txt_usertype_name']);	
			$user_typeinfo->set_user_type_description($_POST['edt_txt_usertype_desc']);
			$user_typeinfo->set_user_type_modified_datetime($now_date_time);
			
			$check_dup_updating_result=$userbol->check_duplicate_usertype_updating($_POST['edt_txt_usertype_name'],$_POST['edt_hid_usertype_id']);
			$row = $check_dup_updating_result->getNext();
			$count_duplicate_updating = $row['count_duplicate_updating'];
			if($count_duplicate_updating==0)
			{
				$result=$userbol->update_user_type($user_typeinfo,$edt_hid_usertype_values);
				if($result)
					$_SESSION['user_type_msg'] = $localized_data['update_user_type_msg'];
				else
					$_SESSION['user_type_msg'] = $localized_home_data['update_fail_msg'];
			}
			else
				$_SESSION['user_type_msg'] = $localized_data['duplicate_save_user_type_msg'];
				
			header("location:user_type_list.php");
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
		jQuery("#edit_usertype").validate(
		{			
		'rules':
			{		
				'edt_txt_usertype_name':{'required':true}	
			},
		'messages':
			{
				'edt_txt_usertype_name':{'required':"*<?php echo $localized_data['check_req_field_user_type_name']; ?>"}	
			},				
			'errorPlacement': function(error, element) {
				$(element).after(error);
			}
		});
	}	
</script>

<div class="content_data">
	<form action="" method="POST" id="edit_usertype" name="edit_usertype">

		<h2><?php echo $localized_data['edt_new_user_type_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="edt_hid_usertype_values" name="edt_hid_usertype_values" value="<?php echo $old_user_type_value_str; ?>"/>
			<input type="hidden" id="edt_hid_usertype_id" name="edt_hid_usertype_id" value="<?php echo $user_type_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['user_type_name']; ?>:</div>
				<input type="text" name="edt_txt_usertype_name" id="edt_txt_usertype_name" value="<?php echo $user_type_name; ?>" />
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['user_type_desc']; ?>:</div>
				<input type="text" name="edt_txt_usertype_desc" id="edt_txt_usertype_desc" value="<?php echo $user_type_desc; ?>" />
			</div>
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnedit"  id="btnedit" value="<?php echo $localized_home_data['edit_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='user_type_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>	
			
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>