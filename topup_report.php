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
		jQuery('#transaction_report_dtList').attr('width',sWidth);
		
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
				jQuery.cookie('topupReport[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('topupReport[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('topupReport[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_topup_report.php",
			
			"aoColumns": [						
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

		$('#generate_report_btn').click(function(){
			
		});
	});
	function loadpagestate()
	{
		updatecontrol('#sel_student_id', jQuery.cookie('topupReport[sel_student_id]'));
		updatecontrol('#sel_date_from', jQuery.cookie('topupReport[sel_date_from]'));
		updatecontrol('#sel_date_to', jQuery.cookie('topupReport[sel_date_to]'));
		$( "#sel_student_id" ).val( jQuery.cookie('topupReport[sel_student_id]') );
		updatecontrol('#search_txt', jQuery.cookie('topupReport[search_txt]'));
		updatecontrol('#search_filter_by', jQuery.cookie('topupReport[search_filter_by]'));
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
		jsonfilter.sel_date_from = jQuery.cookie('topupReport[sel_date_from]');
		jsonfilter.sel_date_to = jQuery.cookie('topupReport[sel_date_to]');
		jsonfilter.search_txt = jQuery.cookie('topupReport[search_txt]');
		jsonfilter.search_filter_by = jQuery.cookie('topupReport[search_filter_by]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
	}
	function savepagestate()
	{
		jQuery.cookie('topupReport[sel_student_id]', jQuery('#sel_student_id').val());
		jQuery.cookie('topupReport[sel_date_from]', jQuery('#sel_date_from').val());
		jQuery.cookie('topupReport[sel_date_to]', jQuery('#sel_date_to').val());
		jQuery.cookie('topupReport[search_txt]', jQuery('#search_txt').val());
		jQuery.cookie('topupReport[search_filter_by]', jQuery('#search_filter_by').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('topupReport[sel_student_id]', '-1');
		jQuery.cookie('topupReport[sel_date_from]', 'Choose date');
		jQuery.cookie('topupReport[sel_date_to]', 'Choose date');		
		jQuery.cookie('topupReport[iDisplayStart]', null);
		jQuery.cookie('topupReport[search_txt]', null);		
		jQuery.cookie('topupReport[search_filter_by]', null);
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
				$('#search_txt').attr("placeholder", "Enter Family Code");
				$("#search_txt").removeAttr('disabled');
				$('#search_txt').focus();
				return;
			case '1':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter Topup Amount");
				$("#search_txt").removeAttr('disabled');
				$('#search_txt').focus();
				return;
			case '2':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter Payment Type");
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
			report_type : "topup",
            sel_date_from : sel_date_from,
            sel_date_to : sel_date_to,
            filter_index : filter_index,
            filter_value : filter_value
        }
        var url = '<?php echo $rootpath;?>/api/get_total_amount_api.php';
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
                console.log(info);
				$("#total_amount").html(info.response.data[0]);
				$("#bonus_amount").html(info.response.data[1]);
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
	});
	
</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_card' name='frm_card' method='POST'>
	
		<h2>Topup Report</h2>
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

								<td style="padding-right:20px;">
									<div style="font-size:16px;"> Filter by </div>
									<select id="search_filter_by" name="search_filter_by" class="select-custom" onChange="changeSearchKey(this)">
										<option value="-1">Please choose</option>
										<option value='0'> Family Code </option>
										<option value='1'> Amount Topup </option>
										<option value='2'> Payment Type </option>
									</select>
								</td>

								<td style="padding-right:35px;">
									<div style="font-size:16px;visibility:hidden"> Content </div>
									<input type="text" class="input-text-custom" id="search_txt" name="search_txt" placeholder="" disabled/>
								</td>

								<td style="padding-top:20px;">
									<input type="submit" id="btnsearch" name="btnsearch" class="control-button" onclick=" return savepagestate() " value='Generate Report'/>
								</td>
							</tr>
						</table>
                    <div>
                </td>
			</tr>
			<tr>
				<td style='font-size:20px;'>
					<div> Total Topup amount : $ <span id="total_amount">  </span> </div>
					<div> Total Bonus amount : $ <span id="bonus_amount">  </span> </div>
				</td>
                <td style="width:30%;padding:10px 0px;text-align:right;"> 
					<a href="#file_type_modal" rel="modal:open" class="control-button"> Export </a>
				</td>	
            </tr>
		</table>
		
		<!--Searching criteria-->
		<!-- <div style="float: left; width: 50%;">
			<div class="frm">
				<div class="frm_label">Student ID : </div>			
				<select id="sel_student_id" name="sel_student_id">
				<option value='-1'>--Select Student ID--</option>
				
				</select>
			</div>
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" id="btnsearch" name="btnsearch" value="<?php echo $localized_home_data['search_btn']; ?>" onclick=" return savepagestate() " class="btn" /> &nbsp;
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
					<th>Family Code</th>			
					<th>Amount Topup</th>
					<th>Payment Type</th>
					<th>Topup Bonus</th>
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