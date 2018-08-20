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
	$itemsinfo=new itemsinfo();	
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('items',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');

	if (isset($_POST['btnsave'])) 	
	{
		if(trim($_POST['txt_item_name'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_item_name'];
		}
		else if(trim($_POST['txt_item_desc'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_item_desc'];
		}
		else if(trim($_POST['txt_item_price'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_item_price'];
		}
		if(count($errors) == 0)
		{
			$itemsinfo->set_item_name($_POST['txt_item_name']);	
			$itemsinfo->set_item_description($_POST['txt_item_desc']);
			$itemsinfo->set_item_price($_POST['txt_item_price']);
			$itemsinfo->set_item_created_datetime($now_date_time);
			$itemsinfo->set_item_modified_datetime($now_date_time);
			
			$check_dup_saving_result=$setupbol->check_duplicate_item_saving($_POST['txt_item_name']);
			$row = $check_dup_saving_result->getNext();
			$count_duplicate_saving = $row['count_duplicate_saving'];
			if($count_duplicate_saving==0)
			{
				$result_id=$setupbol->save_item($itemsinfo);
				if($result_id)
				{
					if($_FILES['txt_item_image']['size']>0)
					{
						$imgtype = $_FILES['txt_item_image']['type'];
						$extension = explode('/',$imgtype);
						$ext_type = $extension[1];
						$img_name = $result_id.".".$ext_type;
						$res = upload_file($_FILES["txt_item_image"],"ItemImages",$img_name);
						if(count($res)>0)
							$_SESSION['item_msg'] = 'File Upload Fails. Try again.';
						else
						{
							//save image name in db
							$itemsinfo->set_item_image_name($img_name);
							$result=$setupbol->save_item_image_name($result_id,$itemsinfo);
							if($result)
								$_SESSION['item_msg'] = $localized_data['save_item_msg'];
						}
					}
				}
				else
					$_SESSION['item_msg'] = $localized_home_data['save_fail_msg'];
			}
			else
				$_SESSION['item_msg'] = $localized_data['duplicate_item_msg'];
				
			header("location:items_list.php");
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
		jQuery("#addnew_item").validate(
		{			
		'rules':
			{		
				'txt_item_name':{'required':true},	
				'txt_item_desc':{'required':true},	
				'txt_item_price':{'required':true},	
			},
		'messages':
			{
				'txt_item_name':{'required':"*<?php echo $localized_data['check_req_field_item_name']; ?>"},	
				'txt_item_desc':{'required':"*<?php echo $localized_data['check_req_field_item_desc']; ?>"},	
				'txt_item_price':{'required':"*<?php echo $localized_data['check_req_field_item_price']; ?>"},	
			},				
		'errorPlacement': function(error, element) {
			$(element).after(error);
		}
		});
	}
	
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            $("#image_show_div,#deletebtn").show();
            $("#txt_item_image").hide().after("<br/>");
            reader.onload = function (e) {
                $('#imgshow')
                    .attr('src', e.target.result)
                    .width(220)
                    .height(165);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function delete_uploaded_image () {
    	$("#image_show_div,#deletebtn").hide();
    	$("#txt_item_image").val('');
        $("#txt_item_image").show();
    }
</script>
<style type="text/css">
	.thumbnail {
		border: 1px solid #dfdfdf;
		padding: 15px 20px 20px;
		-webkit-border-radius: 3px;
		   -moz-border-radius: 3px;
				border-radius: 3px;
	}
	.valign-middle {
	  text-align: center;
	  vertical-align: middle;
	  display: table-cell;
	}
</style>

<div class="content_data">
	<form action="" method="POST" id="addnew_item" name="addnew_item"  enctype="multipart/form-data">
		<h2>Add New Food Item</h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join( $errors)."</div>";
	  			}
			?>
			<div class="frm">
				<div class="frm_labelcat">Food Item Name:</div>
				<input type="text" name="txt_item_name" id="txt_item_name" />
			</div>
			<div class="frm">
				<div class="frm_labelcat">Ingredient:</div>
				<textarea name="txt_item_desc" id="txt_item_desc"></textarea>
			</div>
			<div class="frm">
				<div class="frm_labelcat">Food Price:</div>
				<input type="text" name="txt_item_price" id="txt_item_price" />
			</div>
			<div class="frm">
				<div class="frm_labelcat">Food Image :</div>
				<div >
					<span class="thumbnail valign-middle" style="display:none;" id="image_show_div" >
						<img id="imgshow" />
					</span>
					<input type="file" name="txt_item_image" id="txt_item_image" style="display:block;" onchange="readURL(this);" accept="image/gif,image/jpeg,image/jpg,image/png" /> 
					<input type="button" value="Delete Image" id="deletebtn" onclick="delete_uploaded_image()" style="display:none;margin-left:200px;color:white;background-color:red;font-size:12px;text-align:center;width:150px;"/>
				</div>
			</div>
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnsave"  id="btnsave" value="<?php echo $localized_home_data['save_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='items_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>			
	</form>
</div><!-- /content_data -->
<?php
	include('library/closedb.php');
	include("footer.php");
?>