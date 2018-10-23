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
		if(jQuery.cookie('transactionReport[iDisplayStart]')==null)
			jQuery.cookie('transactionReport[iDisplayStart]', 0);

		if(jQuery.cookie('transactionReport[iDisplayLength]')==null)
			jQuery.cookie('transactionReport[iDisplayLength]', 10);
		
		if(jQuery.cookie('transactionReport[aaSorting]')==null)
		{
			jQuery.cookie('transactionReport[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('transactionReport[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('transactionReport[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('transactionReport[iDisplayStart]'));		
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
				jQuery.cookie('transactionReport[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('transactionReport[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('transactionReport[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_topup_history.php",
			
			"aoColumns": [						
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"}
						]
		});	
		jQuery('.dataTables_filter').hide();

		$("#sel_date_from").datepicker({
			onSelect: function(date){
				console.log(date);
				var d = new Date(date);
				$("#sel_date_from").val(d.toISOString().substring(0, 10));
			}
		});

		$("#sel_date_to").datepicker({
			onSelect: function(date){
				console.log(date);
				var d = new Date(date);
				$("#sel_date_to").val(d.toISOString().substring(0, 10));
			}
		});

		$('#generate_report_btn').click(function(){
			
		});
	});
	function loadpagestate()
	{
		updatecontrol('#sel_student_id', jQuery.cookie('transactionReport[sel_student_id]'));
		updatecontrol('#sel_date_from', jQuery.cookie('transactionReport[sel_date_from]'));
		updatecontrol('#sel_date_to', jQuery.cookie('transactionReport[sel_date_to]'));
		$( "#sel_student_id" ).val( jQuery.cookie('transactionReport[sel_student_id]') );
		//$( "#sel_date_from" ).val( jQuery.cookie('transactionReport[sel_date_from]') );
		//$( "#sel_date_to" ).val( jQuery.cookie('transactionReport[sel_date_to]') );
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.sel_student_id = jQuery.cookie('transactionReport[sel_student_id]');
		jsonfilter.sel_date_from = jQuery.cookie('transactionReport[sel_date_from]');
		jsonfilter.sel_date_to = jQuery.cookie('transactionReport[sel_date_to]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
	}
	function savepagestate()
	{
		jQuery.cookie('transactionReport[sel_student_id]', jQuery('#sel_student_id').val());
		jQuery.cookie('transactionReport[sel_date_from]', jQuery('#sel_date_from').val());
		jQuery.cookie('transactionReport[sel_date_to]', jQuery('#sel_date_to').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('transactionReport[sel_student_id]', '-1');
		jQuery.cookie('transactionReport[sel_date_from]', 'Choose date');
		jQuery.cookie('transactionReport[sel_date_to]', 'Choose date');		
		jQuery.cookie('transactionReport[iDisplayStart]', null);
		return true;
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
                <td style="width:70%">
                    <div class="left-section">
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

								<td style="padding-top:20px;">
									<input type="submit" id="btnsearch" name="btnsearch" class="control-button" onclick=" return savepagestate() " value='Go'/>
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
					<th>Payment Type</th>
					<th>Amount</th>
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