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
	
	$localized_result=$localizationbol->get_localization_by_pagename('card_status',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	require_once('header.php');	
?>

<script language="javascript">
		
	function delete_participant(delete_param)
	{
		var delete_param_arr = delete_param.split('@@#$#@@');
		var confirm_sentence = "Are you sure you want to delete this student name - ";
		if(confirm(confirm_sentence+' '+delete_param_arr[1]+' ?'))
		{
			jQuery.getJSON('delete_participant.php?participant_id='+delete_param_arr[0], participant_delete_callback);
		}
		return false;
	}
	
	function participant_delete_callback(data)
	{
		jQuery('#message').html(data.mes);
		oTable.fnStandingRedraw();//datatable api to use instead of fndraw for standing current page number /need to add one.js file to jsfolder 
	}

	function savepagestate()
	{		 
		jQuery.cookie('participantList[search_txt_participant]', jQuery('#search_txt_participant').val()); 
        jQuery.cookie('participantList[iDisplayStart]', null);
		return true;
	}
	
	function loadpagestate()
	{ 
		updatecontrol('#search_txt_participant', jQuery.cookie('participantList[search_txt_participant]'));    
	}
	
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).val(parvalue);		
	}
	
	function clearpagestate()
	{
		jQuery.cookie('participantList[search_txt_participant]', null);
		jQuery.cookie('participantList[iDisplayStart]', null);		
	    return true;		
	}
	
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.search_txt_participant = jQuery.cookie('participantList[search_txt_participant]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
	}	
	
	jQuery(document).ready(function()
	{
		loadpagestate();
		if(jQuery.cookie('participantList[iDisplayStart]')==null)
		jQuery.cookie('participantList[iDisplayStart]', 0);

		if(jQuery.cookie('participantList[iDisplayLength]')==null)
			jQuery.cookie('participantList[iDisplayLength]', 10);
			
		if(jQuery.cookie('participantList[aaSorting]')==null)	//added by zmn to save sorting state 
		{
			jQuery.cookie('participantList[aaSorting]', "[[1,'desc']]");  //need to set default sorting state
			aasorting = [[1,'desc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('participantList[aaSorting]') + ')'); 	//convert json string to json object
		}
			
		ilength = parseInt(jQuery.cookie('participantList[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('participantList[iDisplayStart]'));
		sFilter=getFilter();		
		
		oTable = jQuery('#participant_dtList').dataTable(
		{
			"iDisplayLength": ilength,
			"iDisplayStart": istart,
			"aaSorting": aasorting,
			"bProcessing": true,
			"bServerSide": true,
			"bLengthChange": true,
			"bJQueryUI":true,
			"sPaginationType": "full_numbers",
			"bFilter": true,
			"oSearch": {"sSearch": sFilter},
			"fnDrawCallback": function() 
			{				
			    var oSettings = oTable.fnSettings();
				var aaSorting = JSON.stringify(oSettings.aaSorting); 	//convert json object to json string because 
				
				if(oSettings._iRecordsTotal==0){
					$('.btn_download').css("display","none");
				}
				
			    //store paging state into cookie		    
				jQuery.cookie('participantList[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('participantList[iDisplayStart]', oSettings._iDisplayStart);	
				jQuery.cookie('participantList[aaSorting]', aaSorting);
			},
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_participants.php",
			"aoColumns": [
						{ "bSortable": true, "sWidth":"15%"},
						{ "bSortable": true, "sWidth":"10%"},
						{ "bSortable": false, "sWidth":"50%"},
						{ "bSortable": true, "sWidth":"10%"},
						{ "bSortable": false, "sWidth":"15%"}]			
		});	
		jQuery('.dataTables_filter').hide();	
	});
 
</script>
	
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>
<form id='saleheaderform' name='saleheaderform' method='POST'>
	<div class="content_data">
		<h2>Student List</h2>
		<?php
			echo "<div id='message'>";
			if(isset($_SESSION['participant_message']))
			{
				$participant_message = $_SESSION['participant_message'];
				echo $participant_message;
			}
			echo "</div><br/>";
			unset ( $_SESSION ['participant_message'] );
			session_write_close ();
			
			
		?>
		<div class="frm">
			<div class="frm_label">Participants Name : </div>			
			<input type="text" id="search_txt_participant" name="search_txt_participant" />
		</div>
		<div class="frm">
			<div class="frm_label">&nbsp;</div>
			<input type="submit" id="search" name="search" onclick=" return savepagestate()" value="Search" class="btn" /> &nbsp;
			<input type="submit" id="showall" name="showall" value="Show All" onclick="clearpagestate();" class="btn" />
		</div>
		<!-- School Name Filter-->

		<div class="cleaner"></div>
		
		<table cellpadding="0" cellspacing="0" border="1" class="display" name="participant_dtList" id="participant_dtList">
			<thead>
				<tr>
					<th>Student Name</th>
					<th>Student ID</th>
					<th>Allergy Foods</th>
					<th>Gender</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="5" style="text-align:center;">Loading data from server</td>
				</tr>
			</tbody>
		</table>
		
		<?php
			echo '<div class="addbtn"><a href="add_new_participant.php" class="link">Add New Student</a></div>';
		?>
	</div>
</form>

<?php
	include('library/closedb.php');
	include('footer.php');
?>