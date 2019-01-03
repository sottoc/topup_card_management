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
	if(isset($_POST['search_filter_by'])) //for drop down option selected
		$user_type_id=$_POST['search_filter_by'];
		
	require_once('header.php');
	
?>
<script language="javascript">
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
		$($("#nav ul li")[0]).css("background", '#2c2c2c');
		$($("#nav ul li")[1]).css("background", '#2c2c2c');
		$($("#nav ul li")[2]).css("background", '#b12226');
		$($("#nav ul li")[3]).css("background", '#2c2c2c');
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
		ilength = -1;
		oTable=jQuery('#usr_dtList').dataTable({
			"iDisplayLength": ilength,
			"iDisplayStart": istart,
			"aaSorting": aasorting,
			"bProcessing": true,
			"bSortable": true,
			"asSorting": [ 'asc', 'desc' ],
			"lengthMenu": [ [-1], ["All"] ],
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
				var j=0;
                for(var i=0;i<tds.length;i++){
                    if(i%8==7){
                        if($(tds[i]).html().indexOf("div") == -1){
							var id_card = $(tds[i]).html();
							t = id_card.split('-');
							var id = t[0];
							var family_code = t[1];
							var last_name = $(tds[j*8+3]).html();
							var first_name = $(tds[j*8+2]).html();
							if(id !='' ){
								$(tds[i]).html("<div style='padding:0 10px'> <div class='edit-button user-edit' id='" + id + "' style='float:left;'> Edit </div> <a class='edit-button view-log' href='#view_log_modal' rel='modal:open' data-last-name='"+ last_name +"' data-first-name='"+ first_name +"' data-family-code='" + family_code + "' style='float:right;'> View Log </a> </div>");
							} else{
								$(tds[i]).html("<div style='padding:0 10px'> <div class='edit-button user-edit' id='" + id + "' style='float:left;'>  </div> <a class='edit-button view-log' href='#view_log_modal' rel='modal:open' data-last-name='"+ last_name +"' data-first-name='"+ first_name +"' data-family-code='" + family_code + "' style='float:right;'> View Log </a> </div>");
							}
                            
                        }
						j++;
					}
					if(i%8==5){
						var v=$(tds[i]).html();
						if(v!=""){
							$(tds[i]).html('$'+v);
						}
					}
                }
				
				$(".edit-button.user-edit").click(function(){
					var id = $(this).attr('id');
					window.location.replace("<?php echo $rootpath;?>/user_edit.php?id="+id);
				});

				
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
		updatecontrol('#search_txt', jQuery.cookie('userlist[search_txt]'));
		updatecontrol('#search_filter_by', jQuery.cookie('userlist[search_filter_by]'));
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
		jsonfilter.search_txt = jQuery.cookie('userlist[search_txt]');
		jsonfilter.search_filter_by = jQuery.cookie('userlist[search_filter_by]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
	}
	function savepagestate()
	{
		jQuery.cookie('userlist[search_txt_username]', jQuery('#search_txt_username').val());
		jQuery.cookie('userlist[search_txt]', jQuery('#search_txt').val());
		jQuery.cookie('userlist[search_filter_by]', jQuery('#search_filter_by').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('userlist[search_txt_username]', null);		
		jQuery.cookie('userlist[search_txt]', null);		
		jQuery.cookie('userlist[search_filter_by]', null);		
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
		console.log($(e).val());
		switch($(e).val()){
			case '-1':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "");
				return;
			case '0':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter email");
				$('#search_txt').focus();
				return;
			case '1':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter account type");
				$('#search_txt').focus();
				return;
			case '2':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter Last Name");
				$('#search_txt').focus();
				return;
			case '3':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter First Name");
				$('#search_txt').focus();
				return;
			case '4':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter Family Code");
				$('#search_txt').focus();
				return;
			case '5':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter Card Value");
				$('#search_txt').focus();
                return;
            case '6':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "0 or 1");
				$('#search_txt').focus();
				return;
			default:
				return;
		}
	}


</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frm_usr' name='frm_usr' method='POST'>
        <h2>Users Detail</h2>
        <table class="control-section">
            <tr> <span style="color:black; font-size:15px; font-weight:500;"> Search By </span> </tr>
            <tr>
                <td style="width:60%">
                    <div class="left-section">
                        <select id="search_filter_by" name="search_filter_by" class="select-custom" onChange="changeSearchKey(this)">
                            <option value="-1">Please choose</option>
							<option value='0'> Email </option>
                            <option value='1'> Account Type </option>
                            <option value='2'> Last Name </option>
							<option value='3'> First Name </option>
							<option value='4'> Family Code </option>
							<option value='5'> Card Value </option>
                            <option value='6'> Status </option>
                        </select>
                        &nbsp;&nbsp;&nbsp;
						<input type="text" class="input-text-custom" id="search_txt" name="search_txt" style="width:25%;" placeholder=""> </input>
                        <!-- <input type="submit" id="btnsearch" name="btnsearch" value="Search" onclick=" return savepagestate() " class="btn" /> -->
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="control-button" id="btnsearch_a"> Go </a>
                        <input type="submit" id="btnsearch" name="btnsearch" value="<?php echo $localized_home_data['search_btn']; ?>" onclick=" return savepagestate() " class="btn" style="display:none"/> &nbsp;
                    <div>
                </td>
				<td style="width:40%;">
                    <a href="<?php echo $rootpath; ?>/user_multiple_add.php" class="control-button"> Add Mulitple Users <a/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="<?php echo $rootpath; ?>/user_single_add.php" class="control-button"> Add Single Users <a/>
                </td>
            </tr>
        </table>

		<!--datatable-->
		<div class="cleaner"></div>
		<table cellpadding="0"  border="0" class="display" id="usr_dtList" name="usr_dtList">
			<thead>
				<tr>							
					<th><?php echo $localized_data['email']; ?></th>
                    <th> Account Type </th>
					<th> First Name </th>
					<th> Last Name </th>				
                    <th> Family Code </th>					
					<th> Card Value </th>					
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


<div id="view_log_modal" class="modal" style="padding:50px 35px;padding-top:30px;max-width:850px !important;">
	<span style='font-size:18px;'> Transaction Detail - <span id='log_first_name'> </span> <span id='log_last_name'> </span> (UserCode:<span id='log_user_code'></span>) </span>
    <br>
	<div style="max-height:400px;overflow-y:scroll;">
		<table style='margin-top:20px;'>
			
		</table>
	</div>
	<br>
	<br>
	<div style="width:30%; padding-left:35%;">
		<a href="#" rel="modal:close" class="control-button"> Close </a>
	</div>
</div>

<script>
	$(document).ready(function(){

		setTimeout(() => {
			$('.edit-button.view-log').click(function(e) {
				$("#log_first_name").html($(e.target).attr('data-first-name'));
				$("#log_last_name").html($(e.target).attr('data-last-name'));
				$("#log_user_code").html('');
				var family_code = $(e.target).attr('data-family-code');
				console.log(family_code);
				$.post("api/get_purchase_log.php", {family_code: family_code}, function(result){
					var info = JSON.parse(result);
					var all_data = info.response.data;
					console.log(all_data);
					var table = $("#view_log_modal table");
					var str = '';
					if(all_data.length == 0){
						all_data = [[],[]];
					}
					data = all_data[0];
					for(var i=0;i<data.length;i++){
						str+="<tr><td><span class='date-time'> " + data[i][2] + " - </span> Spend <strong class='spend-amount'>$" + data[i][1] + "</strong> at POS ID(" + data[i][0] + ") by Card Number(" + data[i][3] + ").</td></tr>";
					}
					data = all_data[1];
					for(var i=0;i<data.length;i++){
						if(data[i][4] == "Cash"){
							str+="<tr><td><span class='date-time'> " + data[i][2] + " - </span> Topup <strong class='topup-amount'>$" + data[i][1] + "</strong> at POS ID(" + data[i][0] + ") by Card Number(" + data[i][3] + ").</td></tr>";
						}
						if(data[i][4] == "Paypal"){
							str+="<tr><td><span class='date-time'> " + data[i][2] + " - </span> Topup <strong class='topup-amount'>$" + data[i][1] + "</strong> at Transaction ID(" + data[i][0] + ") by Paypal.</td></tr>";
						}
					}
					table.html(str);
				});
			});
		}, 500);
		
	});
</script>

<style>
    .control-section{
        width:100%;
	}
	#view_log_modal span{
		font-weight: 500 !important;
	}
	#view_log_modal table td {
		border: 1px solid black;
		padding:10px;
		font-size:16px;
	}
	#view_log_modal table {
		border-collapse: collapse;
		width: 100%;
	}
	.spend-amount{
		color:red;
	}
	.topup-amount{
		color:blue;
	}
</style>

<?php
	include("footer.php");
?>