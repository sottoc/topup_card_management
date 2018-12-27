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
		jQuery('#transaction_report_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('topupSubHistory[iDisplayStart]')==null)
			jQuery.cookie('topupSubHistory[iDisplayStart]', 0);

		if(jQuery.cookie('topupSubHistory[iDisplayLength]')==null)
			jQuery.cookie('topupSubHistory[iDisplayLength]', 10);
		
		if(jQuery.cookie('topupSubHistory[aaSorting]')==null)
		{
			jQuery.cookie('topupSubHistory[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('topupSubHistory[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('topupSubHistory[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('topupSubHistory[iDisplayStart]'));		
		sFilter=getFilter();	
		ilength = -1;
		oTable=jQuery('#topup_history_dtList').dataTable({
			"iDisplayLength": ilength,
			"iDisplayStart": istart,
			"aaSorting": aasorting,
			"bProcessing": true,
			"bSortable": true,
			"asSorting": [ 'asc', 'desc' ],
			"lengthMenu": [ [-1], ["All"] ]	,		
			"sDom": 'Rfrtlip',
			"bServerSide": true,
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"bFilter": true,			
			"oSearch": {"sSearch": sFilter},			 
			"fnDrawCallback": function() {	
				var oSettings = oTable.fnSettings();
				var aaSorting = JSON.stringify(oSettings.aaSorting); //convert json object to json string because cookie only allow to save string
				jQuery.cookie('topupSubHistory[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('topupSubHistory[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('topupSubHistory[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_topup_history_sub.php",
			
			"aoColumns": [						
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},
							{"bSortable": false,"sWidth":"auto"}
						]
		});	
		jQuery('.dataTables_filter').hide();

		$("#topup_sub_sel_date_from").datepicker({
			onSelect: function(date){
				$("#topup_sub_sel_date_from").val(get_date(date));
			}
		});

		$("#topup_sub_sel_date_to").datepicker({
			onSelect: function(date){
				$("#topup_sub_sel_date_to").val(get_date(date));
			}
		});

		$('#generate_report_btn').click(function(){
			
		});
	});
	function loadpagestate()
	{
		updatecontrol('#sel_student_id', jQuery.cookie('topupSubHistory[sel_student_id]'));
		updatecontrol('#topup_sub_sel_date_from', jQuery.cookie('topupSubHistory[topup_sub_sel_date_from]'));
		updatecontrol('#topup_sub_sel_date_to', jQuery.cookie('topupSubHistory[topup_sub_sel_date_to]'));
		$( "#sel_student_id" ).val( jQuery.cookie('topupSubHistory[sel_student_id]') );
		//$( "#topup_sub_sel_date_from" ).val( jQuery.cookie('topupSubHistory[topup_sub_sel_date_from]') );
		//$( "#topup_sub_sel_date_to" ).val( jQuery.cookie('topupSubHistory[topup_sub_sel_date_to]') );
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.sel_student_id = jQuery.cookie('topupSubHistory[sel_student_id]');
		jsonfilter.topup_sub_sel_date_from = jQuery.cookie('topupSubHistory[topup_sub_sel_date_from]');
		jsonfilter.topup_sub_sel_date_to = jQuery.cookie('topupSubHistory[topup_sub_sel_date_to]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
	}
	function savepagestate()
	{
		jQuery.cookie('topupSubHistory[sel_student_id]', jQuery('#sel_student_id').val());
		jQuery.cookie('topupSubHistory[topup_sub_sel_date_from]', jQuery('#topup_sub_sel_date_from').val());
		jQuery.cookie('topupSubHistory[topup_sub_sel_date_to]', jQuery('#topup_sub_sel_date_to').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('topupSubHistory[sel_student_id]', '-1');
		jQuery.cookie('topupSubHistory[topup_sub_sel_date_from]', 'Choose date');
		jQuery.cookie('topupSubHistory[topup_sub_sel_date_to]', 'Choose date');		
		jQuery.cookie('topupSubHistory[iDisplayStart]', null);
		return true;
	}
	
</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data" style="margin:0 0 !important;">
	<form id='frm_card' name='frm_card' method='POST'>
	
		<h2>Topup History <span style="font-weight:500;"> for last 7 days </span> </h2>
		<table style="width:100%; display:none;">
            <tr>
                <td style="width:70%">
                    <div class="left-section">
						<table>
							<tr>
								<td style="padding-right:35px;">
									<div style="font-size:16px;"> From </div>
									<input type="text" value="Choose date" name="topup_sub_sel_date_from" id="topup_sub_sel_date_from" class='input-text-custom'/>
								</td>

								<td style="padding-right:35px;">
									<div style="font-size:16px;"> To </div>
									<input type='text' value="Choose date" name='topup_sub_sel_date_to' id='topup_sub_sel_date_to' class='input-text-custom'/>
								</td>

								<td style="padding-top:20px;">
									<input type="submit" id="topup_sub_btnsearch" name="topup_sub_btnsearch" class="control-button" onclick=" return savepagestate() " value='Go'/>
								</td>
							</tr>
						</table>
                    <div>
                </td>
                <td style="width:30%;text-align:right;padding-top:20px;">
                    
                </td>
            </tr>
		</table>
		
		<!--Searching criteria-->
		<!-- <div style="float: left; width: 50%;">
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
				<input type="submit" id="topup_sub_btnsearch" name="topup_sub_btnsearch" value="<?php echo $localized_home_data['search_btn']; ?>" onclick=" return savepagestate() " class="btn" /> &nbsp;
				<input type="submit" id="btnshowall" name="btnshowall" value="<?php echo $localized_home_data['show_all_btn']; ?>" onclick="clearpagestate()" class="btn" />		
			</div>
		</div> -->
		<!--Searching criteria-->
		
		<!--datatable-->
		<div class="cleaner"></div>
		<table cellpadding="0"  border="0" class="display" id="topup_history_dtList" name="topup_history_dtList">
			<thead>
				<tr>
					<th>Date</th>
					<th>Time</th>
					<th>Payment Type</th>
					<th>Topup Amount</th>
					<th>Bonus Amount</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="5" class="dataTables_empty"><?php echo $localized_home_data['loading_msg_datatable']; ?></td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
	</form>
</div>

<div id="file_type_modal" class="modal">
	<div align="center">
		<select class='select-custom' id='sel_file_type' style='height:38px !important; transform: translateY(2px);'>
			<option value='excel'> Excel </option>
			<option value='csv' selected> CSV </option>
		</select>
		<input type='button' class='control-button' value='Export' onclick='export_table()'/>
	</div>
</div>
<script type="text/javascript" src="javascript/table2excel.js"></script>
<script type="text/javascript" src="javascript/table2csv.js"></script>
<script>
	function export_table2excel(){
		$("#transaction_report_dtList").table2excel({
			filename: "topup_report.xls"
		});
	}
	
	function export_table2csv(){
		exportTableToCSV('topup_report.csv')
	}

	function export_table(){
		var file_type = $('#sel_file_type').val();
		if(file_type == 'csv'){
			export_table2csv();
		}
		if(file_type == 'excel'){
			export_table2excel();
		}
	}
</script>

<style>
#topup_history_dtList_wrapper{
	width: 70% !important;
}
</style>