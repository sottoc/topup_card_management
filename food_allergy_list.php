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
	
	$localized_result=$localizationbol->get_localization_by_pagename('food_allergy',1);
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
		jQuery('#food_allergy_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('fAllergylist[iDisplayStart]')==null)
			jQuery.cookie('fAllergylist[iDisplayStart]', 0);

		if(jQuery.cookie('fAllergylist[iDisplayLength]')==null)
			jQuery.cookie('fAllergylist[iDisplayLength]', 10);
		
		if(jQuery.cookie('fAllergylist[aaSorting]')==null)
		{
			jQuery.cookie('fAllergylist[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('fAllergylist[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('fAllergylist[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('fAllergylist[iDisplayStart]'));		
		sFilter=getFilter();	
		
		oTable=jQuery('#food_allergy_dtList').dataTable({
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
				jQuery.cookie('fAllergylist[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('fAllergylist[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('fAllergylist[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_food_allergy_list.php",
			
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
		updatecontrol('#search_food_allergy_name', jQuery.cookie('fAllergylist[search_food_allergy_name]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.search_food_allergy_name = jQuery.cookie('fAllergylist[search_food_allergy_name]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
		
	}
	function savepagestate()
	{
		jQuery.cookie('fAllergylist[search_food_allergy_name]', jQuery('#search_food_allergy_name').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('fAllergylist[search_food_allergy_name]', null);		
		jQuery.cookie('fAllergylist[iDisplayStart]', null);
		return true;
	}
	
	//delete food_allergy process
	function delete_food_allergy(food_allergy_del_params)
	{
		var food_allergy_del_params_arr = food_allergy_del_params.split('@@#$#@@');
		var confirm_sentence = "<?php echo $localized_data['confirm_del_food_allergy_msg']; ?>";
		$("#hid_del_id").val(food_allergy_del_params_arr[0]);
		confirm_popup(confirm_sentence+' '+food_allergy_del_params_arr[1]+' ?',350);	
		return false;
	}
	function continue_action()
	{
		var del_id = $("#hid_del_id").val();
		jQuery.getJSON('delete_food_allergy.php?food_allergy_id='+del_id, food_allergy_delete_callback);
	}
	function food_allergy_delete_callback(data)
	{
		message_popup(data.msg,350);
		oTable.fnStandingRedraw();//datatable api to use instead of fndraw for standing current page number /need to add one.js file to jsfolder 
	}
	function show_item_msg()
	{
		<?php 
			if(isset($_SESSION['food_allergy_msg'])) 
			{
				$food_allergy_msg = $_SESSION['food_allergy_msg'];
		?>
			var msg = '<?php echo $food_allergy_msg; ?>';
			message_popup(msg,350);
		<?php
			}
			unset ( $_SESSION ['food_allergy_msg'] );
			session_write_close ();
		?>
	}
</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_food_allergy' name='frm_food_allergy' method='POST'>
	
		<h2><?php echo $localized_data['food_allergy_list']; ?></h2>
		<input type="hidden" id="hid_del_id" name="hid_del_id" value=""/>
		
		<!--Searching criteria-->
		<div style="float: left; width: 50%;">
			<div class="frm">
				<div class="frm_label"><?php echo $localized_data['food_allergy_name']; ?> : </div>			
				<input type="text" id="search_food_allergy_name" name="search_food_allergy_name" >
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
		<table cellpadding="0"  border="1" class="display" id="food_allergy_dtList" name="food_allergy_dtList">
			<thead>
				<tr>							
					<th><?php echo $localized_data['food_allergy_name']; ?></th>
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
			$add_btn_name=$localized_data['add_new_food_allergy_btn'];
			echo'<div class="addbtn"><a href="add_food_allergy.php" class="link">'.$add_btn_name.'</a></div>';
		?>
		<!--add new button-->
	</form>
</div>

<?php
	include("footer.php");
?>