<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');	
	
	$setupbol = new setupbol();
	$localizationbol= new localizationbol();
	
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
	
	require_once('header.php');	
?>
<script language="javascript">
	jQuery(document).ready(function()
	{
		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#category_type_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('categoryTypeList[iDisplayStart]')==null)
			jQuery.cookie('categoryTypeList[iDisplayStart]', 0);

		if(jQuery.cookie('categoryTypeList[iDisplayLength]')==null)
			jQuery.cookie('categoryTypeList[iDisplayLength]', 10);
		
		if(jQuery.cookie('categoryTypeList[aaSorting]')==null)
		{
			jQuery.cookie('categoryTypeList[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('categoryTypeList[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('categoryTypeList[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('categoryTypeList[iDisplayStart]'));		
		sFilter=getFilter();	
		
		oTable=jQuery('#category_type_dtList').dataTable({
			"iDisplayLength": ilength,
			"iDisplayStart": istart,
			"aaSorting": aasorting,
			"bProcessing": true,
			"bSortable": true,
			"asSorting": [ 'asc', 'desc' ],
			"lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ]	,		
			"bServerSide": true,
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"bFilter": true,			
			"oSearch": {"sSearch": sFilter},			 
			"fnDrawCallback": function() {	
				var oSettings = oTable.fnSettings();
				var aaSorting = JSON.stringify(oSettings.aaSorting); //convert json object to json string because cookie only allow to save string
				jQuery.cookie('categoryTypeList[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('categoryTypeList[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('categoryTypeList[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_category_type_list.php",
			
			"aoColumns": [						
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"150px"} 
						]
		});	
		jQuery('.dataTables_filter').hide();
		show_item_msg();
	});
	function loadpagestate()
	{
		updatecontrol('#search_category_type_name', jQuery.cookie('categoryTypeList[search_category_type_name]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.search_category_type_name = jQuery.cookie('categoryTypeList[search_category_type_name]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
		
	}
	function savepagestate()
	{
		jQuery.cookie('categoryTypeList[search_category_type_name]', jQuery('#search_category_type_name').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('categoryTypeList[search_category_type_name]', null);		
		jQuery.cookie('categoryTypeList[iDisplayStart]', null);
		return true;
	}
	
	//delete food_allergy process
	function delete_category_type(category_type_del_params)
	{
		var category_type_del_params_arr = category_type_del_params.split('@@#$#@@');
		var confirm_sentence = "<?php echo $localized_data['confirm_del_category_type_msg']; ?>";
		$("#hid_del_id").val(category_type_del_params_arr[0]);
		confirm_popup(confirm_sentence+' '+category_type_del_params_arr[1]+' ?',350);	
		return false;
	}
	function continue_action()
	{
		var del_id = $("#hid_del_id").val();
		jQuery.getJSON('delete_category_type.php?category_type_id='+del_id, category_type_delete_callback);
	}
	function category_type_delete_callback(data)
	{
		message_popup(data.msg,350);
		oTable.fnStandingRedraw();//datatable api to use instead of fndraw for standing current page number /need to add one.js file to jsfolder 
	}
	function show_item_msg()
	{
		<?php 
			if(isset($_SESSION['category_type_msg'])) 
			{
				$category_type_msg = $_SESSION['category_type_msg'];
		?>
			var msg = '<?php echo $category_type_msg; ?>';
			message_popup(msg,350);
		<?php
			}
			unset ( $_SESSION ['category_type_msg'] );
			session_write_close ();
		?>
	}
</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_category_type' name='frm_category_type' method='POST'>
	
		<h2><?php echo $localized_data['category_type_list']; ?></h2>
		<input type="hidden" id="hid_del_id" name="hid_del_id" value=""/>
		
		<!--Searching criteria-->
		<div style="float: left; width: 50%;">
			<div class="frm">
				<div class="frm_label"><?php echo $localized_data['category_type_name']; ?> : </div>			
				<input type="text" id="search_category_type_name" name="search_category_type_name" >
			</div>
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" id="btnsearch" name="btnsearch" value="<?php echo $localized_home_data['search_btn']; ?>" onclick=" return savepagestate() " class="btn" /> &nbsp;
				<input type="submit" id="btnshowall" name="btnshowall" value="<?php echo $localized_home_data['show_all_btn']; ?>" onclick="clearpagestate()" class="btn" />		
			</div>
		</div>
		<!--Searching criteria-->
		
		<!--datatable-->
		<div class="cleaner"></div>
		<table cellpadding="0"  border="1" class="display" id="category_type_dtList" name="category_type_dtList">
			<thead>
				<tr>							
					<th><?php echo $localized_data['category_type_name']; ?></th>
					<th><?php echo $localized_home_data['action']; ?></th>					
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="2" class="dataTables_empty"><?php echo $localized_home_data['loading_msg_datatable']; ?></td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
		
		<!--add new button-->
		<?php
			$add_btn_name=$localized_data['add_new_category_type_btn'];
			echo'<div class="addbtn"><a href="add_category_type.php" class="link">'.$add_btn_name.'</a></div>';
		?>
		<!--add new button-->
	</form>
</div>

<?php
	include("footer.php");
?>