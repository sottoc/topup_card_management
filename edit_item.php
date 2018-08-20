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
	
	if(isset($_GET['item_id']) && $_GET['item_id']!='')
	{
		$item_id=(int)$_GET['item_id'];
		$Rresult=$setupbol->get_item_byid($item_id);
		while($aRow = $Rresult->getNext())
		{
			$item_name=htmlspecialchars($aRow['item_name']);
			$item_desc=htmlspecialchars($aRow['item_description']);
			$item_price=htmlspecialchars($aRow['item_price']);
			$item_image_name=htmlspecialchars($aRow['item_image_name']);
			if($item_image_name==null)
				$item_image_name='item_logo_default.jpg';
		}
		$old_item_value_str="item_id=>".$item_id.",item_name=>".$item_name.",item_desc=>".$item_desc.",item_price=>".$item_price.",item_image_name=>".$item_image_name;
	}
	
	if (isset($_POST['btnedit'])) 	
	{	
		if(trim($_POST['edt_txt_item_name']) == '')
		{
			$errors[] = '*'.$localized_data['check_req_field_item_name'];
		}
		else if(trim($_POST['edt_txt_item_desc'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_item_desc'];
		}
		else if(trim($_POST['edt_txt_item_price'] == ''))
		{
			$errors[] = '*'.$localized_data['check_req_field_item_price'];
		}		
		
		if(count($errors) == 0)
		{	
			$edt_hid_item_values = $_POST['edt_hid_item_values'];
			$hid_item_image_name = $_POST['hid_item_image_name'];
			$upload_image_name = $_FILES['txtproductimage']['name'];
			
			$itemsinfo->set_item_id($_POST['edt_hid_item_id']);	
			$itemsinfo->set_item_name($_POST['edt_txt_item_name']);	
			$itemsinfo->set_item_description($_POST['edt_txt_item_desc']);
			$itemsinfo->set_item_price($_POST['edt_txt_item_price']);
			$itemsinfo->set_item_modified_datetime($now_date_time);
			
			$check_dup_updating_result=$setupbol->check_duplicate_item_updating($_POST['edt_txt_item_name'],$_POST['edt_hid_item_id']);
			$row = $check_dup_updating_result->getNext();
			$count_duplicate_updating = $row['count_duplicate_updating'];
			if($count_duplicate_updating==0)
			{
				$result=$setupbol->update_item($itemsinfo,$edt_hid_item_values);
				if($result)
				{
					if($upload_image_name!='')
					{
						if($hid_item_image_name!=$upload_image_name)
						{
							if($_FILES['txtproductimage']['size']>0)
							{
								$imgtype = $_FILES['txtproductimage']['type'];
								$extension = explode('/',$imgtype);
								$ext_type = $extension[1];
								$img_name = $_POST['edt_hid_item_id'].".".$ext_type;
								$jpg_ext = 'ItemImages/'.$_POST['edt_hid_item_id'].".jpg";
								$png_ext = 'ItemImages/'.$_POST['edt_hid_item_id'].".png";
								$jpeg_ext = 'ItemImages/'.$_POST['edt_hid_item_id'].".jpeg";
								$gif_ext = 'ItemImages/'.$_POST['edt_hid_item_id'].".gif";
								if(file_exists($jpg_ext))
									unlink($jpg_ext);
								if(file_exists($png_ext))
									unlink($png_ext);
								if(file_exists($jpeg_ext))
									unlink($jpeg_ext);
								if(file_exists($gif_ext))
									unlink($gif_ext);
								$res = upload_file($_FILES["txtproductimage"],"ItemImages",$img_name);
								if(count($res)>0)
									$_SESSION['item_msg'] = 'File Upload Fails. Try again.';
								else
								{
									//save image name in db
									$itemsinfo->set_item_image_name($img_name);
									$result=$setupbol->save_item_image_name($_POST['edt_hid_item_id'],$itemsinfo);
									if($result)
										$_SESSION['item_msg'] = $localized_data['save_item_msg'];
								}
							}
						}
					}
					$_SESSION['item_msg'] = $localized_data['update_item_msg'];
				}
				else
					$_SESSION['item_msg'] = $localized_home_data['update_fail_msg'];
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
		if($("#hiddenimagestatus").val()=='')
		{
			$(".loadingimg").hide();
			$("#txtproductimage").show();
		}
		else{
			$(".loadingimg").hide();
			$("#image_show_div").show();
			$("#imgshow").attr('src',$("#hiddenimagestatus").val());
			$("#deletebtn").show();
		}
	});	
	
	function AddValidation()
	{
		jQuery("#edit_item").validate(
		{			
		'rules':
			{		
				'edt_txt_item_name':{'required':true},	
				'edt_txt_item_desc':{'required':true},	
				'edt_txt_item_price':{'required':true}
			},
		'messages':
			{
				'edt_txt_item_name':{'required':"*<?php echo $localized_data['check_req_field_item_name']; ?>"},	
				'edt_txt_item_desc':{'required':"*<?php echo $localized_data['check_req_field_item_desc']; ?>"},	
				'edt_txt_item_price':{'required':"*<?php echo $localized_data['check_req_field_item_price']; ?>"}	
			},				
			'errorPlacement': function(error, element) {
				$(element).after(error);
			}
		});
	}
	function delete_uploaded_image () {
		$("#hiddenimagestatus").val('');
    	$("#image_show_div,#deletebtn").hide();
    	$("#txtproductimage").val('');
        $("#txtproductimage").show();
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            $("#image_show_div,#deletebtn").show();
            $("#txtproductimage").hide().after("<br/>");
            reader.onload = function (e) {
                $('#imgshow')
                    .attr('src', e.target.result)
                    //.width(220)
                    .height(165);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<div class="content_data">
	<form action="" method="POST" id="edit_item" name="edit_item" enctype="multipart/form-data">

		<h2>Edit Food Item</h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<input type="hidden" id="edt_hid_item_values" name="edt_hid_item_values" value="<?php echo $old_item_value_str; ?>"/>
			<input type="hidden" id="edt_hid_item_id" name="edt_hid_item_id" value="<?php echo $item_id; ?>"/>
			<div class="frm">
				<div class="frm_labelcat">Food Item Name:</div>
				<input type="text" name="edt_txt_item_name" id="edt_txt_item_name" value="<?php echo $item_name; ?>" />
			</div>
			<div class="frm">
				<div class="frm_labelcat">Ingredient:</div>
				<input type="text" name="edt_txt_item_desc" id="edt_txt_item_desc" value="<?php echo $item_desc; ?>" />
			</div>
			<div class="frm">
				<div class="frm_labelcat">Food Price:</div>
				<input type="text" name="edt_txt_item_price" id="edt_txt_item_price" value="<?php echo $item_price; ?>" />
			</div>
			
			<div class="frm">
				<div class="frm_label">Food Image : </div>
				<div>
					<img src="images/loading.gif" class="loadingimg">
					<?php
						global $g_upload_path;
						$img_url = 'ItemImages/'.$item_image_name;
					?>
					<input type="hidden" value="<?php echo $item_image_name; ?>" id="hid_item_image_name" name="hid_item_image_name"/>
					<input type="hidden" value="<?php echo $img_url; ?>" id="hiddenimagestatus" name="hiddenimagestatus"/>
					<span class="thumbnail valign-middle" id="image_show_div" style="display:none">
						<img id="imgshow" src="" style="height:165px;"/>
					</span>
					<input type="button" value="Delete Image" id="deletebtn" onclick="delete_uploaded_image()" style="display:none;margin-left:200px;color:white;background-color:red;font-size:12px;text-align:center;width:150px;"/>
					<input type="file" name="txtproductimage" id="txtproductimage" style="display:none;" onchange="readURL(this);" accept="image/gif,image/jpeg,image/jpg,image/png" /> 
				</div>
			</div>
			
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" name="btnedit"  id="btnedit" value="<?php echo $localized_home_data['edit_btn']; ?>" class="btn" /> &nbsp;
				<input type="button" name="btncancel" id="btncancel" onclick="window.location='items_list.php'" value="<?php echo $localized_home_data['cancel_btn']; ?>" class="btn" />
			</div>	
			
	</form>
</div>

<?php
	include('library/closedb.php');
	include("footer.php");
?>