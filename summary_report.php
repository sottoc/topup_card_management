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
				if(i == 4){
					$($("#nav ul li")[i]).css("background", '#b12226');
				}
            }
        }
        //---- End -----

		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#summary_report_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('summaryReport[iDisplayStart]')==null)
			jQuery.cookie('summaryReport[iDisplayStart]', 0);

		if(jQuery.cookie('summaryReport[iDisplayLength]')==null)
			jQuery.cookie('summaryReport[iDisplayLength]', 10);
		
		if(jQuery.cookie('summaryReport[aaSorting]')==null)
		{
			jQuery.cookie('summaryReport[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('summaryReport[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('summaryReport[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('summaryReport[iDisplayStart]'));		
		sFilter=getFilter();	
		
		oTable=jQuery('#summary_report_dtList').dataTable({
			"iDisplayLength": ilength,
			"iDisplayStart": istart,
			"aaSorting": aasorting,
			"bProcessing": true,
			"bSortable": true,
			"asSorting": [ 'asc', 'desc' ],
			"lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ]	,		
			"bLengthChange" : false,
			"sDom": 'Rfrtlip',
			"bServerSide": true,
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"bFilter": true,			
			"oSearch": {"sSearch": sFilter},			 
			"fnDrawCallback": function() {	
				var oSettings = oTable.fnSettings();
				var aaSorting = JSON.stringify(oSettings.aaSorting); //convert json object to json string because cookie only allow to save string
				jQuery.cookie('summaryReport[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('summaryReport[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('summaryReport[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_summary_report.php",
			
			"aoColumns": [						
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
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

	});
	function loadpagestate()
	{
		updatecontrol('#sel_student_id', jQuery.cookie('summaryReport[sel_student_id]'));
		updatecontrol('#sel_date_from', jQuery.cookie('summaryReport[sel_date_from]'));
		updatecontrol('#sel_date_to', jQuery.cookie('summaryReport[sel_date_to]'));
		$( "#sel_student_id" ).val( jQuery.cookie('summaryReport[sel_student_id]') );
		updatecontrol('#search_txt', jQuery.cookie('summaryReport[search_txt]'));
		updatecontrol('#search_filter_by', jQuery.cookie('summaryReport[search_filter_by]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.sel_student_id = jQuery.cookie('summaryReport[sel_student_id]');
		jsonfilter.sel_date_from = jQuery.cookie('summaryReport[sel_date_from]');
		jsonfilter.sel_date_to = jQuery.cookie('summaryReport[sel_date_to]');
		jsonfilter.search_txt = jQuery.cookie('summaryReport[search_txt]');
		jsonfilter.search_filter_by = jQuery.cookie('summaryReport[search_filter_by]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
	}
	function savepagestate()
	{
		jQuery.cookie('summaryReport[sel_student_id]', jQuery('#sel_student_id').val());
		jQuery.cookie('summaryReport[sel_date_from]', jQuery('#sel_date_from').val());
		jQuery.cookie('summaryReport[sel_date_to]', jQuery('#sel_date_to').val());
		jQuery.cookie('summaryReport[search_txt]', jQuery('#search_txt').val());
		jQuery.cookie('summaryReport[search_filter_by]', jQuery('#search_filter_by').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('summaryReport[sel_student_id]', '-1');
		jQuery.cookie('summaryReport[sel_date_from]', 'Choose date');
		jQuery.cookie('summaryReport[sel_date_to]', 'Choose date');		
		jQuery.cookie('summaryReport[iDisplayStart]', null);
		jQuery.cookie('summaryReport[search_txt]', null);		
		jQuery.cookie('summaryReport[search_filter_by]', null);
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
				$('#search_txt').attr("placeholder", "Enter User Email");
				$("#search_txt").removeAttr('disabled');
				$('#search_txt').focus();
				return;
			case '1':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter Family Code");
				$("#search_txt").removeAttr('disabled');
				$('#search_txt').focus();
				return;
			default:
				return;
		}
	}

	function get_total_amount(){
        var sel_date_from = $('#sel_date_from').val();
        var sel_date_to = $('#sel_date_to').val();
        var filter_index = '-1';
        var filter_value = $('#search_txt').val();
		if(filter_value != ''){
			filter_index = $('#search_filter_by').attr('value');
		}
        var obj = {
			report_type : "spend",
            sel_date_from : sel_date_from,
            sel_date_to : sel_date_to,
            filter_index : filter_index,
            filter_value : filter_value
        }
        var url = '<?php echo $rootpath;?>/api/get_summary_total_amount_api.php';
        var request = JSON.stringify(obj);
		console.log(request);
        $.ajax({
            url : url,
            type : 'POST',
            data :  request,   
            tryCount : 0,
            retryLimit : 3,
            success : function(info) {
                var info = JSON.parse(info);
				var summary_data = info.response.data;
				console.log(summary_data);
				$("#summary_total_opening_balance").html(summary_data[0]);
				$("#summary_total_spending").html(summary_data[1]);
				$("#summary_total_topup_cash").html(summary_data[2]);
				$("#summary_total_topup_online").html(summary_data[3]);
				$("#summary_total_topup_bonus").html(summary_data[4]);
				$("#summary_total_refund").html(summary_data[5]);
				$("#summary_total_balance").html(summary_data[6]);
            },
            error : function(xhr, textStatus, errorThrown ) {
                console.log(xhr);
            }
        });
	}

	function update_summary_date(){
		$("div#divLoading").addClass('show');
        var sel_date_from = $('#sel_date_from').val();
        var sel_date_to = $('#sel_date_to').val();
        var obj = {
            sel_date_from : sel_date_from,
            sel_date_to : sel_date_to
        }
        var url = '<?php echo $rootpath;?>/api/update_summary_data.php';
        var request = JSON.stringify(obj);
        $.ajax({
            url : url,
            type : 'POST',
            data :  request,   
            tryCount : 0,
            retryLimit : 3,
            success : function(info) {
                var info = JSON.parse(info);
				info = info.response.data;
				$("div#divLoading").remove('show');
				$("#btnsearch").trigger('click');
				get_total_amount();
            },
            error : function(xhr, textStatus, errorThrown ) {
                console.log(xhr);
            }
        });
	}

	$(document).ready(function(){
		setTimeout(() => {
			get_total_amount();
		}, 1000);
		
		$("#btnsearch_button").click(function(){
			update_summary_date();
		});
	});
</script>

<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_card' name='frm_card' method='POST'>
	
		<h2>Summary Report</h2>
		<table style="width:100%">
            <tr>
                <td colspan='2' style="width:85%">
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

								<td style="padding-right:20px;display:none;">
									<div style="font-size:16px;"> Filter by </div>
									<select id="search_filter_by" name="search_filter_by" class="select-custom" onChange="changeSearchKey(this)">
										<option value="-1">Please choose</option>
										<option value='0'> Email </option>
										<option value='1'> Family Code </option>
									</select>
								</td>

								<td style="padding-right:35px;display:none;">
									<div style="font-size:16px;visibility:hidden"> Content </div>
									<input type="text" class="input-text-custom" id="search_txt" name="search_txt" placeholder="" disabled/>
								</td>

								<td style="padding-top:20px;">
									<input type="button" id="btnsearch_button" name="btnsearch_button" class="control-button" value='Generate Report'/>
								</td>

								<td style="display:none">
									<input type="submit" id="btnsearch" name="btnsearch" class="control-button" onclick=" return savepagestate() " value='Generate Report'/>
								</td>
								
							</tr>
						</table>
                    <div>
                </td>
			</tr>
			<tr>
				<td style='font-size:17px;'>
					<div> Total Opening Balance : $ <span id="summary_total_opening_balance"> </span> </div>
					<div> Total Spending : $ <span id="summary_total_spending">  </span> </div>
					<div> Total Topup (Cash) : $ <span id="summary_total_topup_cash"> </span> </div>
					<div> Total Topup (Online) : $ <span id="summary_total_topup_online"> </span> </div>
					<div> Total Bonus : $ <span id="summary_total_topup_bonus"> </span> </div>
					<div> Total Refund : $ <span id="summary_total_refund"> </span> </div>
					<div> Total Balance : $ <span id="summary_total_balance"> </span> </div>
				</td>
                <td style="width:30%;padding:10px 0px;text-align:right;display:none"> 
					<a href="#file_type_modal" rel="modal:open" class="control-button"> Export </a>
				</td>	
            </tr>
		</table>
		<br/>
		<a href="#file_type_modal" rel="modal:open" class="control-button"> Export </a>
		<!--datatable-->
		<div class="cleaner"></div>
		<table cellpadding="0"  border="0" class="display" id="summary_report_dtList" style="display:none">
			<tr>
				<td> From Date </td>
				<td id="export_from_date"> </td>
			</tr>
			<tr> 
				<td> To Date </td>
				<td id="export_to_date"> </td>
			</tr>
			<tr>
				<td>Total Opening Balance</td>
				<td id="export_total_opening_balance"> </td>	
			</tr>		
			<tr>		
				<th>Total Spending</th>	
				<td id="export_total_spending"> </td>
			</tr>		
			<tr>
				<th>Total Topup(Cash)</th>
				<td id="export_total_topup_cash"> </td>
			</tr>		
			<tr>
				<th>Total Topup(Online)</th>
				<td id="export_total_topup_online"> </td>
			</tr>		
			<tr>
				<th>Total Bonus</th>
				<td id="export_total_bonus"> </td>
			</tr>		
			<tr>
				<th>Total Refund</th>
				<td id="export_total_refund"> </td>
			</tr>		
			<tr>
				<th>Total Balance</th>
				<td id="export_total_balance"> </td>
			</tr>
		</table>
		<!--datatable-->
	</form>
</div>
<table cellpadding="0"  border="0" class="display" id="summary_report_dtList_export" style="display:none">
	<tr>
		<td> From Date </td>
		<td id="export_from_date"> </td>
	</tr>
	<tr> 
		<td> To Date </td>
		<td id="export_to_date"> </td>
	</tr>
	<tr>
		<td>Total Opening Balance</td>
		<td id="export_total_opening_balance"> </td>	
	</tr>		
	<tr>		
		<td>Total Spending</td>	
		<td id="export_total_spending"> </td>
	</tr>		
	<tr>
		<td>Total Topup(Cash)</td>
		<td id="export_total_topup_cash"> </td>
	</tr>		
	<tr>
		<td>Total Topup(Online)</td>
		<td id="export_total_topup_online"> </td>
	</tr>		
	<tr>
		<td>Total Bonus</td>
		<td id="export_total_bonus"> </td>
	</tr>		
	<tr>
		<td>Total Refund</td>
		<td id="export_total_refund"> </td>
	</tr>		
	<tr>
		<td>Balance</td>
		<td id="export_total_balance"> </td>
	</tr>
</table>

<div id="divLoading"> </div>
<div id="file_type_modal" class="modal">
	<div align="center">
		<select class='select-custom' id='sel_file_type' style='height:38px !important; transform: translateY(2px);'>
			<option value='excel' selected> Excel </option>
			<option value='csv'> CSV </option>
		</select>
		<input type='button' class='control-button' value='Export' onclick='export_table()'/>
	</div>
</div>
<script type="text/javascript" src="javascript/table2excel.js"></script>
<script type="text/javascript" src="javascript/table2csv.js"></script>
<script>
	function export_table2excel(){
		$("#summary_report_dtList_export").table2excel({
			filename: "summary_report.xls"
		});
	}
	
	function export_table2csv(){
		exportTableToCSV('summary_report.csv')
	}

	function export_table(){
		var file_type = $('#sel_file_type').val();
		$("#export_from_date").html($('#sel_date_from').val());
		$("#export_to_date").html($('#sel_date_to').val());
		$("#export_total_opening_balance").html('$' + $("#summary_total_opening_balance").html());
		$("#export_total_spending").html('$' + $("#summary_total_spending").html());
		$("#export_total_topup_cash").html('$' + $("#summary_total_topup_cash").html());
		$("#export_total_topup_online").html('$' + $("#summary_total_topup_online").html());
		$("#export_total_bonus").html('$' + $("#summary_total_topup_bonus").html());
		$("#export_total_refund").html('$' + $("#summary_total_refund").html());
		$("#export_total_balance").html('$' + $("#summary_total_balance").html());
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
#divLoading
{
	display : none;
}
#divLoading.show
{
	display : block;
	position : fixed;
	z-index: 100;
	background-image : url('http://loadinggif.com/images/image-selection/3.gif');
	background-color:#666;
	opacity : 0.6;
	background-repeat : no-repeat;
	background-position : center;
	left : 0;
	bottom : 0;
	right : 0;
	top : 0;
}
</style>