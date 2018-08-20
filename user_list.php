<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');		
	
	$userbol = new userbol();
	$localizationbol= new localizationbol();
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localizedresult=$localizationbol->get_localization_by_pagename('user',1);
	while($row=$localizedresult->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	$user_type_id = '0';
	if(isset($_POST['search_sel_usertype'])) //for drop down option selected
		$user_type_id=$_POST['search_sel_usertype'];
		
	require_once('header.php');
	
?>
<script language="javascript">
	jQuery(document).ready(function()
	{
		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#usr_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('userlist[iDisplayStart]')==null)
			jQuery.cookie('userlist[iDisplayStart]', 0);

		if(jQuery.cookie('userlist[iDisplayLength]')==null)
			jQuery.cookie('userlist[iDisplayLength]', 10);
		
		if(jQuery.cookie('userlist[aaSorting]')==null)
		{
			jQuery.cookie('userlist[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('userlist[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('userlist[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('userlist[iDisplayStart]'));		
		sFilter=getFilter();	
		
		oTable=jQuery('#usr_dtList').dataTable({
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
				jQuery.cookie('userlist[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('userlist[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('userlist[aaSorting]', aaSorting);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_user_list.php",
			
			"aoColumns": [						
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": true,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"150px"} 
						]
		});	
		jQuery('.dataTables_filter').hide();
		show_item_msg();
	});
	function loadpagestate()
	{
		updatecontrol('#search_txt_username', jQuery.cookie('userlist[search_txt_username]'));
		updatecontrol('#search_txt_useremail', jQuery.cookie('userlist[search_txt_useremail]'));
		updatecontrol('#search_sel_usertype', jQuery.cookie('userlist[search_sel_usertype]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.search_txt_username = jQuery.cookie('userlist[search_txt_username]');
		jsonfilter.search_txt_useremail = jQuery.cookie('userlist[search_txt_useremail]');
		jsonfilter.search_sel_usertype = jQuery.cookie('userlist[search_sel_usertype]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
		
	}
	function savepagestate()
	{
		jQuery.cookie('userlist[search_txt_username]', jQuery('#search_txt_username').val());
		jQuery.cookie('userlist[search_txt_useremail]', jQuery('#search_txt_useremail').val());
		jQuery.cookie('userlist[search_sel_usertype]', jQuery('#search_sel_usertype').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('userlist[search_txt_username]', null);		
		jQuery.cookie('userlist[search_txt_useremail]', null);		
		jQuery.cookie('userlist[search_sel_usertype]', null);		
		jQuery.cookie('userlist[iDisplayStart]', null);
		return true;
	}
	
	//active_inactive_user process
	function active_inactive_user(params)
	{
		var params_arr = params.split('@@#$#@@');
		var user_id = params_arr[0];
		var status_to_do = params_arr[1];
		var user_name = params_arr[2];
		var confirm_sentence1 = "<?php echo $localized_data['confirm_change_user_status_1']; ?>";
		var confirm_sentence2 = "<?php echo $localized_data['confirm_change_user_status_2']; ?>";
		$("#hid_user_id").val(user_id);
		$("#hid_status_to_do").val(status_to_do);
		confirm_popup(confirm_sentence1+' '+status_to_do+' '+confirm_sentence2+' '+user_name+' ?',350);	
		return false;
	}
	function continue_action()
	{
		var user_id = $("#hid_user_id").val();
		var status_to_do = $("#hid_status_to_do").val();
		jQuery.getJSON('active_inactive_user.php?user_id='+user_id+'&status_to_do='+status_to_do, active_inactive_user_callback);
	}
	function active_inactive_user_callback(data)
	{
		message_popup(data.msg,350);
		oTable.fnStandingRedraw();//datatable api to use instead of fndraw for standing current page number /need to add one.js file to jsfolder 
	}
	function show_item_msg()
	{
		<?php 
			if(isset($_SESSION['user_msg'])) 
			{
				$user_msg = $_SESSION['user_msg'];
		?>
			var msg = '<?php echo $user_msg; ?>';
			message_popup(msg,350);
		<?php
			}
			unset ( $_SESSION ['user_msg'] );
			session_write_close ();
		?>
	}
</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_usr' name='frm_usr' method='POST'>
	
		<h2><?php echo $localized_data['user_list']; ?></h2>
		<input type="hidden" id="hid_user_id" name="hid_user_id" value=""/>
		<input type="hidden" id="hid_status_to_do" name="hid_status_to_do" value=""/>
		
		<!--Searching criteria-->
		<div style="float: left; width: 50%;">
			<div class="frm">
				<div class="frm_label"><?php echo $localized_data['name']; ?> : </div>			
				<input type="text" id="search_txt_username" name="search_txt_username" >
			</div>
			<div class="frm">
				<div class="frm_label"><?php echo $localized_data['email']; ?> : </div>			
				<input type="text" id="search_txt_useremail" name="search_txt_useremail" >
			</div>
			<div class="frm">
				<div class="frm_label"><?php echo $localized_data['user_type']; ?> : </div>
				<select id="search_sel_usertype" name="search_sel_usertype">
				<option value="-1">--Select User Type--</option>
				<?php
					$userbol = new userbol();
					$sel_usertype_result = $userbol->get_all_usertype();
					while($sel_row=$sel_usertype_result->getNext())
					{
						echo '<option value="'.$sel_row['user_type_id'].'"';
						if($user_type_id==$sel_row['user_type_id'])
						echo 'selected';
						echo ">".$sel_row['user_type_name']."</option>";
					}
				?>
				</select>
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
		<table cellpadding="0"  border="1" class="display" id="usr_dtList" name="usr_dtList">
			<thead>
				<tr>							
					<th><?php echo $localized_data['name']; ?></th>
					<th><?php echo $localized_data['email']; ?></th>
					<th><?php echo $localized_data['user_type']; ?></th>					
					<th><?php echo $localized_data['address']; ?></th>					
					<th><?php echo $localized_data['phone']; ?></th>					
					<th><?php echo $localized_data['user_status']; ?></th>					
					<th><?php echo $localized_home_data['action']; ?></th>					
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="7" class="dataTables_empty"><?php echo $localized_home_data['loading_msg_datatable']; ?></td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
		
		<!--add new button is not having in user list.Admin user is default predefined in database.Parent user can register from register form.Staff can register from staff list-->
	</form>
</div>

<?php
	include("footer.php");
?>