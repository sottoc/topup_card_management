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
		$("#hid_del_id").val(delete_param_arr[0]);
		confirm_popup(confirm_sentence+' '+delete_param_arr[1]+' ?',350);	
		return false;
	}
	function continue_action()
	{
		var del_id = $("#hid_del_id").val();
		jQuery.getJSON('delete_participant.php?participant_id='+del_id, participant_delete_callback);
	}
	function participant_delete_callback(data)
	{
		message_popup(data.mes,350);
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
		//---- set background for active menu -----
        if(localStorage.getItem("current_page") != undefined){
            for(var i=0; i < $("#nav ul li").length; i++){
                if($($("#nav ul li")[i]).html() == localStorage.getItem("current_page")){
                    $($("#nav ul li")[i]).css("background", '#b12226');
                }
            }
        }
		$($("#nav ul li")[0]).css("background", '#011880');
		$($("#nav ul li")[1]).css("background", '#b12226');
		$($("#nav ul li")[2]).css("background", '#011880');
		$($("#nav ul li")[3]).css("background", '#011880');
        //---- End -----

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
			"bSortable": true,
			"asSorting": [ 'asc', 'desc' ],
			"lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ]	,		
			"sDom": 'Rfrtlip',
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
			"sAjaxSource": "get_children_list.php",
			"aoColumns": [
						{ "bSortable": true,"sWidth":"auto"},
						{ "bSortable": true,"sWidth":"auto"},
						{ "bSortable": false,"sWidth":"auto"},
						{ "bSortable": true,"sWidth":"auto"},
						{ "bSortable": true,"sWidth":"auto"}]			
		});	
		jQuery('.dataTables_filter').hide();
		show_item_msg();		

		$("#sel_date_from").datepicker({
			onSelect: function(date){
				$("#sel_date_from").val(get_date(date));
			}
		});

	});
function show_item_msg()
{
	<?php 
		if(isset($_SESSION['participant_message'])) 
		{
			$participant_message = $_SESSION['participant_message'];
	?>
		var msg = '<?php echo $participant_message; ?>';
		message_popup(msg,350);
	<?php
		}
		unset ( $_SESSION ['participant_message'] );
		session_write_close ();
	?>
}
 
</script>
	
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>
<form id='saleheaderform' name='saleheaderform' method='POST'>
	<div class="content_data">
		<h2>Card List</h2>
		
		<!-- <input type="hidden" id="hid_del_id" name="hid_del_id" value=""/>
		
		<div class="frm">
			<div class="frm_label">Participants Name : </div>			
			<input type="text" id="search_txt_participant" name="search_txt_participant" />
		</div>
		<div class="frm">
			<div class="frm_label">&nbsp;</div>
			<input type="submit" id="search" name="search" onclick=" return savepagestate()" value="Search" class="btn" /> &nbsp;
			<input type="submit" id="showall" name="showall" value="Show All" onclick="clearpagestate();" class="btn" />
		</div> -->
		<!-- School Name Filter-->

		<div class="cleaner"></div>
		
		<table cellpadding="0" cellspacing="0" border="1" class="display" name="participant_dtList" id="participant_dtList">
			<thead>
				<tr>
					<th>User Code</th>
					<th>Card Number</th>
					<th>Family Code</th>
					<th>First Name</th>
					<th>Last Name</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="6" style="text-align:center;">Loading data from server</td>
				</tr>
			</tbody>
		</table>
		
		<!-- <?php
			echo '<div class="addbtn"><a href="add_new_participant.php" class="link">Add New Student</a></div>';
		?> -->
	</div>
</form>

<?php
	include('library/closedb.php');
	include('footer.php');
?>