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
		jQuery("#txtfromdate").datepicker();		
		jQuery("#txtfromdate").datepicker('option', {'dateFormat':'yy/mm/dd', 'changeYear': 'true', 'changeMonth': 'true', 'yearRange': '-150:+10',onClose: function() {$(this).valid();}});
		jQuery("#txttodate").datepicker();		
		jQuery("#txttodate").datepicker('option', {'dateFormat':'yy/mm/dd', 'changeYear': 'true', 'changeMonth': 'true', 'yearRange': '-150:+10',onClose: function() {$(this).valid();}});
	
		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#order_report_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('orderReport[iDisplayStart]')==null)
			jQuery.cookie('orderReport[iDisplayStart]', 0);

		if(jQuery.cookie('orderReport[iDisplayLength]')==null)
			jQuery.cookie('orderReport[iDisplayLength]', 10);
		
		if(jQuery.cookie('orderReport[aaSorting]')==null)
		{
			jQuery.cookie('orderReport[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('orderReport[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('orderReport[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('orderReport[iDisplayStart]'));		
		sFilter=getFilter();	
		
		oTable=jQuery('#order_report_dtList').dataTable({
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
				jQuery.cookie('orderReport[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('orderReport[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('orderReport[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_order_delivery_schedule.php",
			
			"aoColumns": [				
							{"bSortable": false,"sWidth":"15px"},	
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},																						
							{"bSortable": true,"sWidth":"auto"} 
						]
		});	
		jQuery('.dataTables_filter').hide();
	});
	
	function CheckAll()
	{				
		var allcheck=document.getElementById('checkall');		
		if(allcheck.checked==true)
		{				
			var a=document.getElementsByName('chkfinish[]');
			var len=a.length;			
			for(i=0;i<len;i++)
			{
				a[i].checked=true;
			}			
		}	
		else
		{
			var b=document.getElementsByName('chkfinish[]');
			var len=b.length;			
			for(i=0;i<len;i++)
			{
				b[i].checked=false;
			}
		}		
		return false;
	}
	
	function check_to_finish()  //582014
	{ 
		var chksub='';		
		var subchk=document.getElementsByName('chkfinish[]');
		
		for(var i=0;i<subchk.length;i++)
		{	
			if(subchk[i].checked==true)						
			{chksub=1;}
		}		
		if(chksub=='')
		{				
			nodata_message_popup('Please check that you want to finish.',350);
			return false;
		}	
		else{
			check_all_checked();
		}		
	}
	
	function check_all_checked()
	{	
		var subchk=document.getElementsByName('chkfinish[]');
		var msg = '';
		
		for(var i=0;i<subchk.length;i++)
		{	
			if(subchk[i].checked==true)						
				msg += subchk[i].value +", ";
		}
		pre_order_id_str = msg.substr(0,  msg.length-2);
		confirm_popup('Are you sure you want to finish this order(s)?',350);	
		return false;
	}
	
	function continue_action()
	{
		if ( pre_order_id_str != ""  ){ //for update all checked
			jQuery.post("check_mealstatus_finish.php", {"pre_order_id_str":pre_order_id_str}, update_all_callback, "json");
		}
			
	}
	
	function update_all_callback(obj)
	{
		message_popup(obj.message,350);
		if ( obj.success != 1)			
			return false;		
		else 
			oTable.fnStandingRedraw();
	}
	
	function loadpagestate()
	{
		updatecontrol('#txtfromdate', jQuery.cookie('orderReport[txtfromdate]'));
		updatecontrol('#txttodate', jQuery.cookie('orderReport[txttodate]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.txtfromdate= jQuery.cookie('orderReport[txtfromdate]');
		jsonfilter.txttodate= jQuery.cookie('orderReport[txttodate]');
		var cri_str = JSON.stringify(jsonfilter);
		//console.log("cri str = " + cri_str);
		return cri_str;
		
	}
	function savepagestate()
	{
		//var fromdate = $('#txtfromdate').val();
		//console.log("fromDate = " + fromdate);
		//return false;
		jQuery.cookie('orderReport[txtfromdate]',$('#txtfromdate').val());
		jQuery.cookie('orderReport[txttodate]',$('#txttodate').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('orderReport[txtfromdate]',null);
		jQuery.cookie('orderReport[txttodate]',null);		
		jQuery.cookie('orderReport[iDisplayStart]', null);
		return true;
	}
	function download_xls()
	{
		alert("download xls");
		var subchk=document.getElementsByName('chkfinish[]');
		var msg = '';
		console.log("subchk"+subchk);
		for(var i=0;i<subchk.length;i++)
		{	
			if(subchk[i].checked==true)						
				msg += subchk[i].value +", ";
		}
		pre_order_id_str = msg.substr(0,  msg.length-2);
		console.log("pre_order_id_str="+pre_order_id_str);
		if(pre_order_id_str=='')
		{
			message_popup("Please check that you want to download",350);
			return false;
		}
		else
		{
			$.get("export_xls_order_delivery.php?export=0&pre_order_id_str="+pre_order_id_str,no_export);		
			return false;
		}
	}
	function no_export(data)
	{
		return false;
		if(data=='norecord')
		{
			message_popup("No record to download.",250);
		}
		else
		{
			window.location='export_xls_order_delivery.php'+'?export='+1+'';
			return false;
		}
	}
	function download_pdf()
	{
		var subchk=document.getElementsByName('chkfinish[]');
		var msg = '';
		console.log("subchk"+subchk);
		for(var i=0;i<subchk.length;i++)
		{	
			if(subchk[i].checked==true)						
				msg += subchk[i].value +", ";
		}
		pre_order_id_str = msg.substr(0,  msg.length-2);
		console.log("pre_order_id_str="+pre_order_id_str);
		if(pre_order_id_str=='')
		{
			message_popup("Please check that you want to download",350);
			return false;
		}
		else
		{
			$.post("export_pdf_order_delivery.php",{"pre_order_id_str":pre_order_id_str});		
			return false;
		}
	}
	function showpdf()
	{
		var subchk=document.getElementsByName('chkfinish[]');
		var msg = '';
		console.log("subchk"+subchk);
		for(var i=0;i<subchk.length;i++)
		{	
			if(subchk[i].checked==true)						
				msg += subchk[i].value +", ";
		}
		pre_order_id_str = msg.substr(0,  msg.length-2);
		console.log("pre_order_id_str="+pre_order_id_str);
		if(pre_order_id_str=='')
		{
			message_popup("Please check that you want to download",350);
			return false;
		}
		else
		{
			var url ='showpdf.php?pre_order_id_str='+pre_order_id_str;
			window.open(url);
		}
	}
	function showexcel()
	{
		var subchk=document.getElementsByName('chkfinish[]');
		var msg = '';
		console.log("subchk"+subchk);
		for(var i=0;i<subchk.length;i++)
		{	
			if(subchk[i].checked==true)						
				msg += subchk[i].value +", ";
		}
		pre_order_id_str = msg.substr(0,  msg.length-2);
		console.log("pre_order_id_str="+pre_order_id_str);
		if(pre_order_id_str=='')
		{
			message_popup("Please check that you want to download",350);
			return false;
		}
		else
			$.get("showexcel.php?pre_order_id_str="+pre_order_id_str, check_export);
	}
	
	function check_export(data)
	{ 	
		//var hidquote = $('#hidquote').val();
		if(data=='No Record')
			alert("there_is_no_record");
		else{
			window.location = "showexcel.php?export="+1;
			return false;
		}
	}
</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_order' name='frm_order' method='POST'>
	
		<h2>Order Schedule Report</h2>
		
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
		<table cellpadding="0"  border="1" class="display" id="order_report_dtList" name="order_report_dtList">
			<thead>
				<tr>
					<th style="text-align: center;"><input type="checkbox" name="checkall" id="checkall"  onclick="CheckAll();" style="margin: 0;" /></th>
					<th>Student ID</th>
					<th>Student Name</th>
					<th>Preorder Date</th>					
					<th>Meal Type</th>					
					<th>Food Items Name</th>					
					
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="6" class="dataTables_empty"><?php echo $localized_home_data['loading_msg_datatable']; ?></td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
		<div class="frm" style="text-align: right;">
			<input type="button" id="btnCheck" name="btnCheck" value="Finish Delivery" onclick="check_to_finish();" class="btn" style="float: none;" /> 
			<!--input type="button" id="btnxls" name="btnxls" value="Excel Download" onclick="download_xls();" class="btn" style="float: none;" /> 
			<input type="button" id="btnpdf" name="btnpdf" value="PDF Download" onclick="download_pdf();" class="btn" style="float: none;" /--> 
			<!--button class="btn" style="float: none;" ><a href='export_pdf_order_delivery.php?pre_order_id_str=1' class='link'>Download PDF</a></button-->
			<input type="button" id="btnpdf" value="Download PDF" onclick="showpdf();"  class="btn" style="float: none;" />
			<input type="button" id="btnexcel" value="Download XLS" onclick="showexcel();"  class="btn" style="float: none;" />
		</div>
	</form>
</div>

<?php
	include("footer.php");
?>