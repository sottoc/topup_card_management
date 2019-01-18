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
		//---- set background for active menu -----
        if(localStorage.getItem("current_page") != undefined){
            for(var i=0; i < $("#nav ul li").length; i++){
				if(i == 3){
					$($("#nav ul li")[i]).css("background", '#b12226');
				}
            }
        }
        //---- End -----

		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#transaction_report_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('topupHistory[iDisplayStart]')==null)
			jQuery.cookie('topupHistory[iDisplayStart]', 0);

		if(jQuery.cookie('topupHistory[iDisplayLength]')==null)
			jQuery.cookie('topupHistory[iDisplayLength]', 10);
		
		if(jQuery.cookie('topupHistory[aaSorting]')==null)
		{
			jQuery.cookie('topupHistory[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('topupHistory[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('topupHistory[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('topupHistory[iDisplayStart]'));		
		sFilter=getFilter();	
		
		oTable=jQuery('#transaction_report_dtList').dataTable({
			"iDisplayLength": ilength,
			"iDisplayStart": istart,
			"aaSorting": aasorting,
			"bProcessing": true,
			"bSortable": true,
			"asSorting": [ 'asc', 'desc' ],
			"lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ]	,		
			"sDom": 'Rfrtlip',
			"bServerSide": true,
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"bFilter": true,			
			"oSearch": {"sSearch": sFilter},			 
			"fnDrawCallback": function() {	
				var oSettings = oTable.fnSettings();
				var aaSorting = JSON.stringify(oSettings.aaSorting); //convert json object to json string because cookie only allow to save string
				jQuery.cookie('topupHistory[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('topupHistory[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('topupHistory[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_topup_history.php",
			
			"aoColumns": [						
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},
							{"bSortable": false,"sWidth":"auto"}
						]
		});	
		jQuery('.dataTables_filter').hide();

		$("#sel_date_from").datepicker({
			onSelect: function(date){
				$("#sel_date_from").val(get_date(date));
			}
		});

		$("#sel_date_to").datepicker({
			onSelect: function(date){
				$("#sel_date_to").val(get_date(date));
			}
		});

		$('#generate_report_btn').click(function(){
			
		});
	});
	function loadpagestate()
	{
		updatecontrol('#sel_student_id', jQuery.cookie('topupHistory[sel_student_id]'));
		updatecontrol('#sel_date_from', jQuery.cookie('topupHistory[sel_date_from]'));
		updatecontrol('#sel_date_to', jQuery.cookie('topupHistory[sel_date_to]'));
		$( "#sel_student_id" ).val( jQuery.cookie('topupHistory[sel_student_id]') );
		updatecontrol('#search_txt', jQuery.cookie('topupHistory[search_txt]'));
		updatecontrol('#search_filter_by', jQuery.cookie('topupHistory[search_filter_by]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.sel_student_id = jQuery.cookie('topupHistory[sel_student_id]');
		jsonfilter.sel_date_from = jQuery.cookie('topupHistory[sel_date_from]');
		jsonfilter.sel_date_to = jQuery.cookie('topupHistory[sel_date_to]');
		jsonfilter.search_txt = jQuery.cookie('topupHistory[search_txt]');
		jsonfilter.search_filter_by = jQuery.cookie('topupHistory[search_filter_by]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
	}
	function topup_savepagestate()
	{
		jQuery.cookie('topupHistory[sel_student_id]', jQuery('#sel_student_id').val());
		jQuery.cookie('topupHistory[sel_date_from]', jQuery('#sel_date_from').val());
		jQuery.cookie('topupHistory[sel_date_to]', jQuery('#sel_date_to').val());
		jQuery.cookie('topupHistory[search_txt]', jQuery('#search_txt').val());
		jQuery.cookie('topupHistory[search_filter_by]', jQuery('#search_filter_by').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('topupHistory[sel_student_id]', '-1');
		jQuery.cookie('topupHistory[sel_date_from]', 'Choose date');
		jQuery.cookie('topupHistory[sel_date_to]', 'Choose date');		
		jQuery.cookie('topupHistory[iDisplayStart]', null);
		jQuery.cookie('topupHistory[search_txt]', null);		
		jQuery.cookie('topupHistory[search_filter_by]', null);
		return true;
	}

	function changeSearchKey(e){
		console.log($(e).val());
		switch($(e).val()){
			case '-1':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "");
				$("#search_txt").attr('disabled','disabled');
				return;
			case '0':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "1(Cash) or 0(paypal)");
				$("#search_txt").removeAttr('disabled');
				$('#search_txt').focus();
				return;
			case '1':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter topup amount");
				$("#search_txt").removeAttr('disabled');
				$('#search_txt').focus();
				return;
			default:
				return;
		}
	}
	
</script>

<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_card' name='frm_card' method='POST'>
	
		<h2>Topup History</h2>
		<table style="width:100%">
            <tr>
                <td style="width:85%">
                    <div class="left-section filter-div">
						<table>
							<tr>
								<td style="padding-right:35px;">
									<div style="font-size:16px;"> From </div>
									<input type="text" value="Choose date" name="sel_date_from" id="sel_date_from" class='input-text-custom'/>
								</td>

								<td style="padding-right:35px;">
									<div style="font-size:16px;"> To </div>
									<input type='text' value="Choose date" name='sel_date_to' id='sel_date_to' class='input-text-custom'/>
								</td>

								<td style="padding-right:20px;">
									<div style="font-size:16px;"> Filter by </div>
									<select id="search_filter_by" name="search_filter_by" class="select-custom" onChange="changeSearchKey(this)">
										<option value="-1">No Filter</option>
										<option value='0'> Payment Type </option>
										<option value='1'> Topup Amount </option>
									</select>
								</td>

								<td style="padding-right:35px;">
									<div style="font-size:16px;visibility:hidden"> Content </div>
									<input type="text" class="input-text-custom" id="search_txt" name="search_txt" placeholder="" disabled/>
								</td>

								<td style="padding-top:20px;">
									<input type="submit" id="topup_btnsearch" name="topup_btnsearch" class="control-button" onclick=" return topup_savepagestate() " value='Generate Report'/>
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
				<input type="submit" id="topup_btnsearch" name="topup_btnsearch" value="<?php echo $localized_home_data['search_btn']; ?>" onclick=" return topup_savepagestate() " class="btn" /> &nbsp;
				<input type="submit" id="btnshowall" name="btnshowall" value="<?php echo $localized_home_data['show_all_btn']; ?>" onclick="clearpagestate()" class="btn" />		
			</div>
		</div> -->
		<!--Searching criteria-->
		
		<!--datatable-->
		<div class="cleaner"></div>
		<table cellpadding="0"  border="0" class="display" id="transaction_report_dtList" name="transaction_report_dtList">
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

<?php
	include("footer.php");
?>

<style>
	@media screen and (max-width: 1450px) {
		.select-custom{
			width:90px !important;
		}
		#topup_btnsearch{
			width:165px !important;
		}
	}
</style>