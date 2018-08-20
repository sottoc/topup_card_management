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

	if (isset($_POST['btnsave'])) 	
	{	
		if(trim($_POST['txt_org_name'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_org_name'];
		}
		if(trim($_POST['txt_org_address'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_org_address'];
		}
		if(count($errors) == 0)
		{
			$orginfo->set_org_name($_POST['txt_org_name']);	
			$orginfo->set_org_address($_POST['txt_org_address']);
			$orginfo->set_org_description($_POST['txt_org_desc']);
			$orginfo->set_org_created_datetime($now_date_time);
			$orginfo->set_org_modified_datetime($now_date_time);
			
			$check_dup_saving_result=$setupbol->check_duplicate_org_saving($_POST['txt_org_name']);
			$row = $check_dup_saving_result->getNext();
			$count_duplicate_saving = $row['count_duplicate_saving'];
			if($count_duplicate_saving==0)
			{
				$result=$setupbol->save_org($orginfo);
				if($result)
					$_SESSION['org_msg'] = $localized_data['success_org_msg'];
				else
					$_SESSION['org_msg'] = $localized_home_data['save_fail_msg'];
			}
			else
				$_SESSION['org_msg'] = $localized_data['duplicate_org_msg'];;
				
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
		jQuery("#addnew_org").validate(
		{			
		'rules':
			{		
				'txt_org_name':{'required':true},	
				'txt_org_address':{'required':true}	
			},
		'messages':
			{
				'txt_org_name':{'required':"*<?php echo $localized_data['check_req_field_org_name']; ?>"},	
				'txt_org_address':{'required':"*<?php echo $localized_data['check_req_field_org_address']; ?>"}	
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
	<form action="" method="POST" id="addnew_org" name="addnew_org" >
		<h2><?php echo $localized_data['add_new_org_title']; ?></h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join( $errors)."</div>";
	  			}
			?>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['org_name']; ?>:</div>
				<input type="text" name="txt_org_name" id="txt_org_name" />
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['org_address']; ?>:</div>
				<textarea name="txt_org_address" id="txt_org_address" ></textarea>
			</div>
			<div class="frm">
				<div class="frm_labelcat"><?php echo $localized_data['org_desc']; ?>:</div>
				<input type="text" name="txt_org_desc" id="txt_org_desc" />
			</div>
		
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnsave"  id="btnsave" value="<?php echo $localized_home_data['save_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='organization_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>			
	</form>
</div><!-- /content_data -->
<?php
	include('library/closedb.php');
	include("footer.php");
?>