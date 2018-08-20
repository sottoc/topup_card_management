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
<script language="Javascript">
	jQuery(document).ready(function(){
		
		jQuery("#txtfromdate").datepicker();		
		jQuery("#txtfromdate").datepicker('option', {'dateFormat':'yy/mm/dd', 'changeYear': 'true', 'changeMonth': 'true', 'yearRange': '-150:+10',onClose: function() {$(this).valid();}});
		jQuery("#txttodate").datepicker();		
		jQuery("#txttodate").datepicker('option', {'dateFormat':'yy/mm/dd', 'changeYear': 'true', 'changeMonth': 'true', 'yearRange': '-150:+10',onClose: function() {$(this).valid();}});
	});
	
	jQuery(document).ready(function()
	{		
		loadpagestate();
		if(jQuery.cookie('order[iDisplayStart]')==null)
		jQuery.cookie('order[iDisplayStart]', 0);
		if(jQuery.cookie('order[iDisplayLength]')==null)
		jQuery.cookie('order[iDisplayLength]', 10);		
		ilength= parseInt(jQuery.cookie('order[iDisplayLength]'));
		istart= parseInt(jQuery.cookie('order[iDisplayStart]'));		
		sFilter= getFilter();
		oTable= jQuery('#order_detail_dtList').dataTable(
		{
			"iDisplayLength":ilength,
			"iDisplayStart":istart,
			"bProcessing":true,
			"bServerSide":true,
			"bLengthChange":true,
			"bJQueryUI":true,
			"sPaginationType":"full_numbers",
			"bFilter":true,
			"oSearch":{"sSearch":sFilter},
			"sAjaxSource": "get_order_schedule_detail.php",			
			"fnDrawCallback": function() {			
			var oSettings = oTable.fnSettings();				
			jQuery.cookie('order[iDisplayLength]', oSettings._iDisplayLength);
			jQuery.cookie('order[iDisplayStart]', oSettings._iDisplayStart);			
									
		},
			"bAutoWidth": false,
			"aoColumns":[{"bSortable": false}]
		});
		jQuery('.dataTables_filter').hide();		
		
	});
	
	function changepic(objimg,objtrname)
	{		
		if(objimg.src.indexOf('minus') > 0)
		{			
			objimg.src= "images/plus__.png";
			objtrname.style.display='none';
		}
		else
		{			
			objimg.src= "images/minus.png";
			objtrname.style.display='block';
		}
	}
	
	function savepagestate()
	{	
		jQuery.cookie('order[txtfromdate]', $('#txtfromdate').val());			
		jQuery.cookie('order[txttodate]', $('#txttodate').val());							
		jQuery.cookie('order[iDisplayStart]',null);
		return true;		
	}	
	function loadpagestate()
	{		
		control('#txtfromdate',jQuery.cookie('order[txtfromdate]'));
		control('#txttodate',jQuery.cookie('order[txttodate]'));
	}
	function control(par1,par2)
	{		
		if(par2)
		{
			//jQuery(par1).attr('value',par2);
			jQuery(par1).val(par2);
		}
	}
	function clearpagestate()
	{		
		jQuery.cookie('order[txtfromdate]', null);		
		jQuery.cookie('order[txttodate]', null);		
		jQuery.cookie('order[iDisplayStart]',null);		
		return true;
	}	
	function getFilter()
	{
		var jsonfilter = {};		
		jsonfilter.txtfromdate = jQuery.cookie('order[txtfromdate]');			
		jsonfilter.txttodate = jQuery.cookie('order[txttodate]');					
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;	
	}	
	function export_detail_pdf()
	{
		var export_txtfromdate = $('#txtfromdate').val();
		export_txtfromdate = $('#txtfromdate').val().replace('-','/');
		var export_txttodate = $('#txttodate').val();
		export_txttodate = $('#txttodate').val().replace('-','/');
		if(export_txtfromdate=='')
			message_popup("Please select from date.",350);
		else if(export_txttodate=='')
			message_popup("Please select to date.",350);
		else if(export_txttodate < export_txtfromdate)
			message_popup("End date must be greather than start date.",350);
		else
		{
			var url ='export_detail_pdf.php?export_txtfromdate='+export_txtfromdate+"&export_txttodate="+export_txttodate;
			window.open(url);
		}
	}
	function export_detail_xls()
	{
		var export_txtfromdate = $('#txtfromdate').val();
		export_txtfromdate = $('#txtfromdate').val().replace('-','/');
		var export_txttodate = $('#txttodate').val();
		export_txttodate = $('#txttodate').val().replace('-','/');
		if(export_txtfromdate=='')
			message_popup("Please select from date.",350);
		else if(export_txttodate=='')
			message_popup("Please select to date.",350);
		else if(export_txttodate < export_txtfromdate)
			message_popup("End date must be greather than start date.",350);
		else
			$.get("export_detail_xls.php?export_txtfromdate="+export_txtfromdate+"&export_txttodate="+export_txttodate, check_export);
	}
	function check_export(data)
	{ 	
		if(data=='No Record')
			alert("there_is_no_record");
		else{
			window.location = "export_detail_xls.php?export="+1;
			return false;
		}
	}
</script>

<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_order' name='frm_order' method='POST'>
	
		<h2>Order Schedule Detail Report</h2>
		
		<!--Searching criteria-->
		<div style="float: left; width: 50%;">
			<div class="frm">
			<div class="frm_label">From-Date:</div>
			<input type="text" id="txtfromdate" name="txtfromdate" readonly />
			<div id="disapp"></div>		
		</div>
		
		<div class="frm">
			<div class="frm_label">To-Date:</div>
			<input type="text" id="txttodate" name="txttodate" readonly />
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
		<table cellpadding="0"  border="1" class="display" id="order_detail_dtList" name="order_detail_dtList">
			<thead style="display: none;">
			<tr><td>&nbsp;</td></tr>
			</thead>
			<tbody>
				<tr>
					<td class="dataTables_empty">Loading data from server</td>
				</tr>
			</tbody>	
		</table>
		<!--datatable-->
		<div class="frm" style="text-align: right;">
			<input type="button" id="btnpdf" value="Download PDF" onclick="export_detail_pdf();"  class="btn" style="float: none;" />
			<input type="button" id="btnexcel" value="Download XLS" onclick="export_detail_xls();"  class="btn" style="float: none;" />
		</div>
	</form>
</div>

<?php
	include("footer.php");
?>