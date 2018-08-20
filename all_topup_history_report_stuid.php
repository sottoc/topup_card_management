<?php
require_once('library/reference.php');
	require_once('autoload.php');	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
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
	
	require_once('header.php');
	$participantbol = new participantbol();
	
	
	if(isset($_GET['participant_id']))
	{
		$pid = $_GET['participant_id'];
		// $result = $participantbol->select_participant_byid($pid);
		// $row = $result->getNext();
		// $participant_name = $row['participant_name'];
		// echo "<h2>" . $participant_name . "'s TopUp History</h2>";
		// echo "Coming Soon!";
	}
?>

<script language="javascript">
	jQuery(document).ready(function()
	{
		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#topup_report_dtList').attr('width',sWidth);
		
		if(jQuery.cookie('topupReport[iDisplayStart]')==null)
			jQuery.cookie('topupReport[iDisplayStart]', 0);

		if(jQuery.cookie('topupReport[iDisplayLength]')==null)
			jQuery.cookie('topupReport[iDisplayLength]', 10);
		
		if(jQuery.cookie('topupReport[aaSorting]')==null)
		{
			jQuery.cookie('topupReport[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('topupReport[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('topupReport[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('topupReport[iDisplayStart]'));		
		sFilter=getFilter();	
		
		oTable=jQuery('#topup_report_dtList').dataTable({
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
				jQuery.cookie('topupReport[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('topupReport[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('topupReport[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_all_topup_history_report.php",
			
			"aoColumns": [						
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"} 
						]
		});	
		jQuery('.dataTables_filter').hide();
	});
	
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.participant_id = jQuery('#participant_id').val();
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
		
	}
</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_card' name='frm_card' method='POST'>
		<input type="hidden" id="participant_id" name="participant_id" value="<?php echo $pid;  ?>">
		<h2>TopUp History Report</h2>
		
		<!--datatable-->
		<div class="cleaner"></div>
		<table cellpadding="0"  border="1" class="display" id="topup_report_dtList" name="topup_report_dtList">
			<thead>
				<tr>
					<th>Student ID</th>
					<th>Student Name</th>
					<th>Card Number</th>					
					<th>Entry Date</th>					
					<th>Entry Time</th>					
					<th>TopUp Amount</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="6" class="dataTables_empty"><?php echo $localized_home_data['loading_msg_datatable']; ?></td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
	</form>
</div>

<?php	
	require_once('footer.php');	
?>