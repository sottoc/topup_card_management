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
	
	$localized_result=$localizationbol->get_localization_by_pagename('organization',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	if(isset($_GET['participant_id']))
		$participant_id=(int)$_GET['participant_id'];
	
	require_once('header.php');
?>
<script language="javascript">
	jQuery(document).ready(function()
	{
		//var participant_id = $.("#hid_participant_id").val();
		jQuery.cookie('cardList[hid_participant_id]', jQuery('#hid_participant_id').val());
		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#card_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('cardList[iDisplayStart]')==null)
			jQuery.cookie('cardList[iDisplayStart]', 0);

		if(jQuery.cookie('cardList[iDisplayLength]')==null)
			jQuery.cookie('cardList[iDisplayLength]', 10);
		
		if(jQuery.cookie('cardList[aaSorting]')==null)
		{
			jQuery.cookie('cardList[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('cardList[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('cardList[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('cardList[iDisplayStart]'));		
		sFilter=getFilter();	
		
		oTable=jQuery('#card_dtList').dataTable({
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
				jQuery.cookie('cardList[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('cardList[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('cardList[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_card_list.php",
			
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
	});
	function loadpagestate()
	{
		updatecontrol('#search_card_number', jQuery.cookie('cardList[search_card_number]'));
		updatecontrol('#hid_participant_id', jQuery.cookie('cardList[hid_participant_id]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.search_card_number = jQuery.cookie('cardList[search_card_number]');
		jsonfilter.hid_participant_id = jQuery.cookie('cardList[hid_participant_id]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
		
	}
	function savepagestate()
	{
		jQuery.cookie('cardList[search_card_number]', jQuery('#search_card_number').val());
		jQuery.cookie('cardList[hid_participant_id]', jQuery('#hid_participant_id').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('cardList[search_card_number]', null);		
		jQuery.cookie('cardList[iDisplayStart]', null);
		return true;
	}
	//delete item process
	function delete_card(del_params)
	{
		var del_params_arr = del_params.split('@@#$#@@');
		var confirm_sentence = "Are you sure you want to delete the card number ";
		if(confirm(confirm_sentence+' '+del_params_arr[1]+' ?'))
		{
			jQuery.getJSON('delete_card.php?card_id='+del_params_arr[0], card_delete_callback);
		}
		return false;
	}
	function card_delete_callback(data)
	{
		jQuery('#message').html(data.msg);
		oTable.fnStandingRedraw();//datatable api to use instead of fndraw for standing current page number /need to add one.js file to jsfolder 
	}
</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_card' name='frm_card' method='POST'>
	
		<h2>Card List</h2>
		
		<!--showing msgs-->
			<p>&nbsp;</p>
			<?php
				echo "<div id='message'>";
				if(isset($_SESSION['card_msg']))
				{
					$card_msg = $_SESSION['card_msg'];
					echo $card_msg;
				}
				echo "</div><br/>";
				unset ( $_SESSION ['card_msg'] );
				session_write_close ();
			?>
		<!--showing msgs-->
		
		<!--Searching criteria-->
		<div style="float: left; width: 50%;">
			<input type="hidden" id="hid_participant_id" value="<?php echo $participant_id; ?>"/>
			<div class="frm">
				<div class="frm_label">Card Number : </div>			
				<input type="text" id="search_card_number" name="search_card_number" >
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
		<table cellpadding="0"  border="1" class="display" id="card_dtList" name="card_dtList">
			<thead>
				<tr>
					<th>Card Number</th>
					<th>Student Name</th>
					<th>Card Description</th>					
					<th>Card Issued date</th>					
					<th>Card Expired date</th>					
					<th>Current Status</th>					
					<th>Action</th>					
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="8" class="dataTables_empty"><?php echo $localized_home_data['loading_msg_datatable']; ?></td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
		
		<!--add new button-->
		<?php
			$add_btn_name="Add New Card";
			echo"<div class='addbtn'><a href='add_card.php?participant_id=$participant_id' class='link'>$add_btn_name</a></div>";
		?>
		<!--add new button-->
		<?php
			$back_btn_name="Back to Student List";
			echo"<div class='addbtn'><a href='participant_list.php' class='link'>$back_btn_name</a></div>";
		?>
	</form>
</div>

<?php
	include("footer.php");
?>