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
	$orginfo=new orginfo();
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('organization',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');
	
	if(isset($_GET['org_id']) && $_GET['org_id']!='')
	{
		$org_id=(int)$_GET['org_id'];
		$Rresult=$setupbol->get_org_byid($org_id);
		while($aRow = $Rresult->getNext())
		{
			$org_name=htmlspecialchars($aRow['org_name']);
			$org_address=htmlspecialchars($aRow['org_address']);
			$org_description=htmlspecialchars($aRow['org_description']);
		}
		$old_org_value_str="org_name=>".$org_name.",org_address=>".$org_address.",org_description=>".$org_description;
	}
	
	if (isset($_POST['btnedit'])) 	
	{	
		if(trim($_POST['edt_txt_org_name']) == '')
		{
			$errors[] = '*Please enter school name.';
		}
		if(trim($_POST['edt_txt_org_address']) == '')
		{
			$errors[] = '*Please enter school address.';
		}		
		
		if(count($errors) == 0)
		{	
			$edt_hid_org_values = $_POST['edt_hid_org_values'];
			$orginfo->set_org_id($_POST['edt_hid_org_id']);
			$orginfo->set_org_name($_POST['edt_txt_org_name']);	
			$orginfo->set_org_address($_POST['edt_txt_org_address']);	
			$orginfo->set_org_description($_POST['edt_txt_org_desc']);
			$orginfo->set_org_modified_datetime($now_date_time);
			
			$check_dup_updating_result=$setupbol->check_duplicate_org_updating($_POST['edt_txt_org_name'],$_POST['edt_hid_org_id']);
			$row = $check_dup_updating_result->getNext();
			$count_duplicate_updating = $row['count_duplicate_updating'];
			if($count_duplicate_updating==0)
			{
				$result=$setupbol->update_org($orginfo,$edt_hid_org_values);
				if($result)
					$_SESSION['org_msg'] = $localized_data['update_org_msg'];
				else
					$_SESSION['org_msg'] = $localized_home_data['update_fail_msg'];
			}
			else
				$_SESSION['org_msg'] = $localized_data['duplicate_org_msg'];
				
			header("location:organization_list.php");
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
		jQuery("#edit_org").validate(
		{			
		'rules':
			{		
				'edt_txt_org_name':{'required':true},
				'edt_txt_org_address':{'required':true}	
			},
		'messages':
			{
				'edt_txt_org_name':{'required':"*<?php echo $localized_data['check_req_field_org_name']; ?>"},
				'edt_txt_org_address':{'required':"*<?php echo $localized_data['check_req_field_org_address']; ?>"}
			},				
			'errorPlacement': function(error, element) {
				$(element).after(error);
			}
		});
	}	
</script>

<div class="content_data">
	<form action="" method="POST" id="edit_org" name="edit_org">

		<h2><?php echo $localized_data['edt_org_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="edt_hid_org_values" name="edt_hid_org_values" value="<?php echo $old_org_value_str; ?>"/>
			<input type="hidden" id="edt_hid_org_id" name="edt_hid_org_id" value="<?php echo $org_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['org_name']; ?>:</div>
				<input type="text" name="edt_txt_org_name" id="edt_txt_org_name" value="<?php echo $org_name; ?>" />
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['org_address']; ?>:</div>
				<textarea name="edt_txt_org_address" id="edt_txt_org_address" ><?php echo $org_address; ?></textarea>
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['org_desc']; ?>:</div>
				<input type="text" name="edt_txt_org_desc" id="edt_txt_org_desc" value="<?php echo $org_description; ?>" />
			</div>
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnedit"  id="btnedit" value="<?php echo $localized_home_data['edit_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='organization_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>	
			
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>