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
        //---- set background for active menu -----
        if(localStorage.getItem("current_page") != undefined){
            for(var i=0; i < $("#nav ul li").length; i++){
                if($($("#nav ul li")[i]).html() == localStorage.getItem("current_page")){
                    $($("#nav ul li")[i]).css("background", '#05815f');
                }
            }
		}
		$($("#nav ul li")[0]).css("background", '#2c2c2c');
		$($("#nav ul li")[1]).css("background", '#2c2c2c');
		$($("#nav ul li")[2]).css("background", '#2c2c2c');
		$($("#nav ul li")[3]).css("background", '#05815f');
		$($("#nav ul li")[4]).css("background", '#2c2c2c');
        //---- End -----
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
			"lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
            "sDom": 'Rfrtlip',		
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

                var tds = $(".dataTables_wrapper td");
                for(var i=0;i<tds.length;i++){
                    if(i%7==6){
                        if($(tds[i]).html().indexOf("div") == -1){
                            $(tds[i]).html("<a class='edit-button' href='#refund_modal' rel='modal:open' style='margin-left:30%'> Refund </a>");
                        }
                    }
                }

				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_user.php",
			
			"aoColumns": [						
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"200px", 'sClass':'alignCenter'} 
						]
		});	
		jQuery('.dataTables_filter').hide();
		show_item_msg();

        $("#btnsearch_a").click(function(){
            $("#btnsearch").trigger('click');
        })
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
	function changeSearchKey(e){
		if($(e).val() == '-1'){
			console.log("email");
			$('#search_txt_useremail').removeAttr('disabled');
			$('#search_txt_useremail').focus();
		} else{
			$('#search_txt_useremail').val('');
			$('#search_txt_useremail').attr('disabled','disabled');
		}
	}


</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_usr' name='frm_usr' method='POST'>
        <h2>Users</h2>
        <table class="control-section">
            <tr> <span style="color:black; font-size:15px; font-weight:500;"> Search By </span> </tr>
            <tr>
                <td style="width:70%">
                    <div class="left-section">
                        <select id="search_sel_usertype" name="search_sel_usertype" class="select-custom" onChange="changeSearchKey(this)">
                            <option value="-1">Please choose</option>
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
							<option value='-1'> Email </option>
                        </select>
                        &nbsp;&nbsp;&nbsp;
						<input type="text" class="input-text-custom" id="search_txt_useremail" name="search_txt_useremail" style="width:25%;" placeholder="Email Address" disabled> </input>
                        <!-- <input type="submit" id="btnsearch" name="btnsearch" value="Search" onclick=" return savepagestate() " class="btn" /> -->
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="control-button" id="btnsearch_a"> <?php echo $localized_home_data['search_btn']; ?> </a>
                        <input type="submit" id="btnsearch" name="btnsearch" value="<?php echo $localized_home_data['search_btn']; ?>" onclick=" return savepagestate() " class="btn" style="display:none"/> &nbsp;
                    <div>
                </td>
            </tr>
        </table>

		<!--datatable-->
		<div class="cleaner"></div>
		<table cellpadding="0"  border="0" class="display" id="usr_dtList" name="usr_dtList">
			<thead>
				<tr>							
					<th><?php echo $localized_data['email']; ?></th>
                    <th><?php echo $localized_data['user_type']; ?></th>				
                    <th> Family Code </th>				
					<th> Card </th>					
					<th> Amount </th>					
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

<div id="refund_modal" class="modal" style="padding:50px 50px;padding-top:10px;">
    <h2 align="center" style="font-size:28px;"> Refund </h2>
    <div style="margin:10px 5px;"> 
        <div style="margin-bottom:10px"> 
            <span class="label-span"> Enter Refund Amount </span>
        </div>
        <div>
            <input type="text" class="input-text-custom" style="width:95%">
        </div>
    </div>

    <br>
    <div>
        <a class="control-button" href="#" rel="modal:close" style="float:left"> Cancel </a>
        <a class="control-button" style="float:right"> Confirm </a>
    </div>
</div>

<?php
	include("footer.php");
?>