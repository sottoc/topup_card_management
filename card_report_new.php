<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');	
	
	$reportbol = new reportbol();

	require_once('header.php');	
?>
<script language="javascript">
	jQuery(document).ready(function()
	{
		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#card_report_dtList').attr('width',sWidth);
		
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
		
		oTable=jQuery('#card_report_dtList').dataTable({
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
			"sAjaxSource": "get_card_report_new_list.php",
			
			"aoColumns": [						
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
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
	});
	function loadpagestate()
	{
		updatecontrol('#search_txt_studentid', jQuery.cookie('cardList[search_txt_studentid]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.search_txt_studentid = jQuery.cookie('cardList[search_txt_studentid]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
		
	}
	function savepagestate()
	{
		jQuery.cookie('cardList[search_txt_studentid]', jQuery('#search_txt_studentid').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('cardList[search_txt_studentid]', null);				
		jQuery.cookie('cardList[iDisplayStart]', null);
		return true;
	}

</script>

<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frmcard' name='frmcard' method='POST'>
	
		<h2>Prepaid Card</h2>
		
		<!--showing msgs-->
			<p>&nbsp;</p>
			<?php
				echo "<div id='message'>";
				if(isset($_SESSION['msg']))
				{
					$msg = $_SESSION['msg'];
					echo $msg;
				}
				echo "</div><br/>";
				unset ( $_SESSION ['msg'] );
				session_write_close ();
			?>
		<!--showing msgs-->
		
		<!--Searching criteria-->
		<!-- <div style="float: left; width: 50%;">
			<div class="frm">
				<div class="frm_label">Student Id : </div>			
				<input type="text" id="search_txt_studentid" name="search_txt_studentid" >
			</div>

			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" id="btnsearch" name="btnsearch" value="Search" onclick=" return savepagestate() " class="btn" /> &nbsp;
				<input type="submit" id="btnshowall" name="btnshowall" value="Show All" onclick="clearpagestate()" class="btn" />		
			</div>
		</div> -->
		<!--Searching criteria-->
		
		<!--datatable-->
		<div class="cleaner"></div>
		<table cellpadding="0"  border="0" class="display" id="card_report_dtList" name="card_report_dtList">
			<thead>
				<tr>							
					<th>User code</th>
					<th>Family code</th>
					<th>Card Id</th>
					<th>Last Name</th>
					<th>First Name</th>
					<th>Level</th>
					<th>Card value</th>
					<th>Card status</th>
					<th>Username</th>
					<th>Password</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="9" class="dataTables_empty">Loading data from server</td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
		
	</form>
</div>

<?php
	include("footer.php");
?>