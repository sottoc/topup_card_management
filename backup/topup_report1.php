<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');		
	
	$reportbol = new reportbol();
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
?>
<script language="javascript">
	jQuery(document).ready(function()
	{
		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#topup_report_dtList').attr('width',sWidth);
		
		loadpagestate();
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
			"sAjaxSource": "get_topup_report.php",
			
			"aoColumns": [						
							{"bSortable": true,"sWidth":"auto"},											
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
	function loadpagestate()
	{
		updatecontrol('#sel_student_id', jQuery.cookie('topupReport[sel_student_id]'));
		$( "#sel_student_id" ).val( jQuery.cookie('topupReport[sel_student_id]') );
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.sel_student_id = jQuery.cookie('topupReport[sel_student_id]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
		
	}
	function savepagestate()
	{
		jQuery.cookie('topupReport[sel_student_id]', jQuery('#sel_student_id').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('topupReport[sel_student_id]', '-1');		
		jQuery.cookie('topupReport[iDisplayStart]', null);
		return true;
	}
</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_card' name='frm_card' method='POST'>
	
		<h2>TopUp Report</h2>
		
		<!--Searching criteria-->
		<div style="float: left; width: 50%;">
			<div class="frm">
				<div class="frm_label">Student ID : </div>			
				<select id="sel_student_id" name="sel_student_id">
				<option value='-1'>--Select Student ID--</option>
				<?php
					$login_user_type_id = $_SESSION ['login_user_type_id'];
					$login_user_id = $_SESSION ['login_user_id'];
					$rResult= $reportbol->get_student_by_loginusertype($login_user_type_id,$login_user_id);
					while($row=$rResult->getNext())
					{
						echo "<option value='".$row['participant_id']."'>".$row['participant_enroll_no']."</option>";
					}
					
				?>
				</select>
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
		<table cellpadding="0"  border="1" class="display" id="topup_report_dtList" name="topup_report_dtList">
			<thead>
				<tr>
					<th>Student ID</th>
					<th>Student Name</th>
					<th>Card Number</th>					
					<th>TopUp Amount</th>					
					<th>Payment Type</th>					
					<th>POS PaySlip Number</th>					
					<th>Transaction Date</th>					
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="7" class="dataTables_empty"><?php echo $localized_home_data['loading_msg_datatable']; ?></td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
	</form>
</div>

<?php
	include("footer.php");
?>