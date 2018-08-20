<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');	
	
	$userbol = new userbol();
	$localizationbol= new localizationbol();
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('staff',1);
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
		jQuery('#staff_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('staffList[iDisplayStart]')==null)
			jQuery.cookie('staffList[iDisplayStart]', 0);

		if(jQuery.cookie('staffList[iDisplayLength]')==null)
			jQuery.cookie('staffList[iDisplayLength]', 10);
		
		if(jQuery.cookie('staffList[aaSorting]')==null)
		{
			jQuery.cookie('staffList[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('staffList[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('staffList[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('staffList[iDisplayStart]'));		
		sFilter=getFilter();	
		
		oTable=jQuery('#staff_dtList').dataTable({
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
				jQuery.cookie('staffList[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('staffList[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('staffList[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_staff_list.php",
			
			"aoColumns": [						
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"150px"} 
						]
		});	
		jQuery('.dataTables_filter').hide();
		show_item_msg();
	});
	function loadpagestate()
	{
		updatecontrol('#search_txt_staffname', jQuery.cookie('staffList[search_txt_staffname]'));
		updatecontrol('#search_txt_staffemail', jQuery.cookie('staffList[search_txt_staffemail]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.search_txt_staffname = jQuery.cookie('staffList[search_txt_staffname]');
		jsonfilter.search_txt_staffemail = jQuery.cookie('staffList[search_txt_staffemail]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
		
	}
	function savepagestate()
	{
		jQuery.cookie('staffList[search_txt_staffname]', jQuery('#search_txt_staffname').val());
		jQuery.cookie('staffList[search_txt_staffemail]', jQuery('#search_txt_staffemail').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('staffList[search_txt_staffname]', null);		
		jQuery.cookie('staffList[search_txt_staffemail]', null);		
		jQuery.cookie('staffList[iDisplayStart]', null);
		return true;
	}
	
	//active_inactive_staff process
	function active_inactive_staff(params)
	{
		var params_arr = params.split('@@#$#@@');
		var staff_id = params_arr[0];
		var status_to_do = params_arr[1];
		var staff_email = params_arr[2];
		var confirm_sentence1 = "<?php echo $localized_data['confirm_change_staff_status_1']; ?>";
		var confirm_sentence2 = "<?php echo $localized_data['confirm_change_staff_status_2']; ?>";
		$("#hid_staff_id").val(staff_id);
		$("#hid_status_to_do").val(status_to_do);
		confirm_popup(confirm_sentence1+' '+status_to_do+' '+confirm_sentence2+' '+staff_email+' ?',350);	
		return false;
	}
	function continue_action()
	{
		var staff_id = $("#hid_staff_id").val();
		var status_to_do = $("#hid_status_to_do").val();
		jQuery.getJSON('active_inactive_staff.php?staff_id='+staff_id+'&status_to_do='+status_to_do, active_inactive_staff_callback);
	}
	function active_inactive_staff_callback(data)
	{
		message_popup(data.msg,350);
		oTable.fnStandingRedraw();//datatable api to use instead of fndraw for standing current page number /need to add one.js file to jsfolder 
	}
	function show_item_msg()
	{
		<?php 
			if(isset($_SESSION['staff_msg'])) 
			{
				$staff_msg = $_SESSION['staff_msg'];
		?>
			var msg = '<?php echo $staff_msg; ?>';
			message_popup(msg,350);
		<?php
			}
			unset ( $_SESSION ['staff_msg'] );
			session_write_close ();
		?>
	}
</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_staff' name='frm_staff' method='POST'>
	
		<h2><?php echo $localized_data['staff_list']; ?></h2>
		<input type="hidden" id="hid_staff_id" name="hid_staff_id" value=""/>
		<input type="hidden" id="hid_status_to_do" name="hid_status_to_do" value=""/>
		
		<!--Searching criteria-->
		<div style="float: left; width: 50%;">
			<div class="frm">
				<div class="frm_label"><?php echo $localized_data['staff_name']; ?> : </div>			
				<input type="text" id="search_txt_staffname" name="search_txt_staffname" >
			</div>
			<div class="frm">
				<div class="frm_label"><?php echo $localized_data['staff_email']; ?> : </div>			
				<input type="text" id="search_txt_staffemail" name="search_txt_staffemail" >
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
		<table cellpadding="0"  border="1" class="display" id="staff_dtList" name="staff_dtList">
			<thead>
				<tr>							
					<th><?php echo $localized_data['staff_name']; ?></th>
					<th><?php echo $localized_data['staff_email']; ?></th>
					<th><?php echo $localized_data['staff_address']; ?></th>
					<th><?php echo $localized_data['staff_phone']; ?></th>
					<th><?php echo $localized_data['staff_gender']; ?></th>
					<th><?php echo $localized_data['staff_status']; ?></th>
					<th><?php echo $localized_home_data['action']; ?></th>					
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="7" class="dataTables_empty"><?php echo $localized_home_data['loading_msg_datatable']; ?></td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
		
		<!--add new button-->
		<?php
			$add_btn_name=$localized_data['add_new_staff_btn'];
			echo'<div class="addbtn"><a href="add_staff.php" class="link">'.$add_btn_name.'</a></div>';
		?>
		<!--add new button-->
	</form>
</div>

<?php
	include("footer.php");
?>