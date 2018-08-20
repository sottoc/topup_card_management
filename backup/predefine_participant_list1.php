<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');	
	
	$student_predefinebol = new student_predefinebol();

	require_once('header.php');	
?>
<script language="javascript">
	jQuery(document).ready(function()
	{
		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#student_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('studentList[iDisplayStart]')==null)
			jQuery.cookie('studentList[iDisplayStart]', 0);

		if(jQuery.cookie('studentList[iDisplayLength]')==null)
			jQuery.cookie('studentList[iDisplayLength]', 10);
		
		if(jQuery.cookie('studentList[aaSorting]')==null)
		{
			jQuery.cookie('studentList[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('studentList[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('studentList[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('studentList[iDisplayStart]'));		
		sFilter=getFilter();	
		
		oTable=jQuery('#student_dtList').dataTable({
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
				jQuery.cookie('studentList[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('studentList[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('studentList[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_predefine_participant_list.php",
			
			"aoColumns": [						
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},																					
							{"bSortable": false,"sWidth":"150px"} 
						]
		});	
		jQuery('.dataTables_filter').hide();
	});
	function loadpagestate()
	{
		updatecontrol('#search_txt_studentname', jQuery.cookie('studentList[search_txt_studentname]'));
		updatecontrol('#search_txt_finger_print_number', jQuery.cookie('studentList[search_txt_finger_print_number]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.search_txt_studentname = jQuery.cookie('studentList[search_txt_studentname]');
		jsonfilter.search_txt_finger_print_number = jQuery.cookie('studentList[search_txt_finger_print_number]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
		
	}
	function savepagestate()
	{
		jQuery.cookie('studentList[search_txt_studentname]', jQuery('#search_txt_studentname').val());
		jQuery.cookie('studentList[search_txt_finger_print_number]', jQuery('#search_txt_finger_print_number').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('studentList[search_txt_studentname]', null);		
		jQuery.cookie('studentList[search_txt_finger_print_number]', null);		
		jQuery.cookie('studentList[iDisplayStart]', null);
		return true;
	}
	function delete_predefine_participant(student_del_params)
	{
		var del_params_arr = student_del_params.split('@@#$#@@');
		var confirm_sentence = "Are you sure want to delete";
		if(confirm(confirm_sentence+' '+del_params_arr[1]+' ?'))
		{
			jQuery.getJSON('delete_predefine_participant.php?predefine_participant_id='+del_params_arr[0], delete_callback);
		}
		return false;
	}
	function delete_callback(data)
	{
		jQuery('#message').html(data.msg);
		oTable.fnStandingRedraw();//datatable api to use instead of fndraw for standing current page number /need to add one.js file to jsfolder 
	}
	
</script>

<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_predefine' name='frm_predefine' method='POST'>
	
		<h2>Student List</h2>
		
		<!--showing msgs-->
			<p>&nbsp;</p>
			<?php
				echo "<div id='message'>";
				if(isset($_SESSION['student_msg']))
				{
					$student_msg = $_SESSION['student_msg'];
					echo $student_msg;
				}
				echo "</div><br/>";
				unset ( $_SESSION ['student_msg'] );
				session_write_close ();
			?>
		<!--showing msgs-->
		
		<!--Searching criteria-->
		<div style="float: left; width: 50%;">
			<div class="frm">
				<div class="frm_label">Student Name : </div>			
				<input type="text" id="search_txt_studentname" name="search_txt_studentname" >
			</div>
			<div class="frm">
				<div class="frm_label">Finger Print Number: </div>			
				<input type="text" id="search_txt_finger_print_number" name="search_txt_finger_print_number" >
			</div>
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" id="btnsearch" name="btnsearch" value="Search" onclick=" return savepagestate() " class="btn" /> &nbsp;
				<input type="submit" id="btnshowall" name="btnshowall" value="Show All" onclick="clearpagestate()" class="btn" />		
			</div>
		</div>
		<!--Searching criteria-->
		
		<!--datatable-->
		<div class="cleaner"></div>
		<table cellpadding="0"  border="1" class="display" id="student_dtList" name="student_dtList">
			<thead>
				<tr>							
					<th>Student Name</th>
					<th>Student Id</th>
					<th>School Name</th>
					<th>Finger Print Number</th>
					<th>Parent Name</th>
					<th>Action</th>					
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="6" class="dataTables_empty">Loading data from server</td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
		
		<!--add new button-->
		<div class="addbtn"><a href="add_predefine_participant.php" class="link">Add Student</a></div>
	
		<!--add new button-->
	</form>
</div>

<?php
	include("footer.php");
?>