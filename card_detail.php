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
        //---- set background for active menu -----
        if(localStorage.getItem("current_page") != undefined){
            for(var i=0; i < $("#nav ul li").length; i++){
                if($($("#nav ul li")[i]).html() == localStorage.getItem("current_page")){
                    $($("#nav ul li")[i]).css("background", '#b12226');
                }
            }
        }
        $($("#nav ul li")[0]).css("background", '#2c2c2c');
		$($("#nav ul li")[1]).css("background", '#b12226');
		$($("#nav ul li")[2]).css("background", '#2c2c2c');
		$($("#nav ul li")[3]).css("background", '#2c2c2c');
		$($("#nav ul li")[4]).css("background", '#2c2c2c');
        //------------------ End -----------------

		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#prepaid_card_dtList').attr('width',sWidth);
		
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
		ilength = -1;
		oTable=jQuery('#prepaid_card_dtList').dataTable({
			"iDisplayLength": ilength,
			"iDisplayStart": istart,
			"aaSorting": aasorting,
			"bProcessing": true,
			"bSortable": true,
			"asSorting": [ 'asc', 'desc' ],
			"lengthMenu": [ [-1], ["All"] ]	,	
            "sDom": 'Rfrtlip',	
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

				var tds = $(".dataTables_wrapper td");
				var j=0;
                for(var i=0;i<tds.length;i++){
                    if(i%8==7){
                        if($(tds[i]).html().indexOf("div") == -1){
							var id = $(tds[i]).html();
							var user_code = $(tds[j*8+0]).html();
							var card_id = $(tds[j*8+1]).html();
							var first_name = $(tds[j*8+3]).html();
							var last_name = $(tds[j*8+2]).html();
							var family_code = $(tds[j*8+4]).html();
                            $(tds[i]).html("<div style='padding:0 10%'> <div class='edit-button edit-card' id='edit_btn_" + id + "' style='float:left;'> Edit </div> <a class='edit-button view-log' href='#view_log_modal' rel='modal:open' data-user-code='"+ user_code +"' data-card-id='" + card_id + "' data-first-name='"+ first_name +"' data-last-name='"+ last_name +"' style='float:right;'> View Log </a> </div>");
						}
						j++;
                    }
					if(i%8==6){
						if($(tds[i]).html() == 'Active'){
							//$(tds[i]).css('color', 'red');
						}
                        // if($(tds[i]).html().indexOf("div") == -1){
                        //     var id = $(tds[i]).html();
                        //     $(tds[i]).html("<div class='edit-button' id='edit_btn_" + id + "'> Edit </div>");
                        // }
					}
                }

                //-------- when click Edit button -----
                setTimeout(() => {
                    $(".edit-button.edit-card").click(function(){
                        console.log($(this).attr('id'));
                        var x = $(this).attr('id');
                        var id = x.split('_')[2];
                        window.location.replace('<?php echo $rootpath;?>/prepaid_card_edit.php?id=' + id);
                    });
                }, 1000);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_card_detail.php",
			
			"aoColumns": [						
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
		
		$('#btnsearch_a').click(function(){
			if($('#search_filter_by').val() == '-1'){
				$('#search_filter_by').focus();
				return;
			}
			$('#btnsearch').trigger('click');
		});
	});

	function loadpagestate()
	{
		updatecontrol('#search_txt_studentid', jQuery.cookie('cardList[search_txt_studentid]'));
		updatecontrol('#search_filter_by', jQuery.cookie('cardList[search_filter_by]'));
		updatecontrol('#search_txt', jQuery.cookie('cardList[search_txt]'));
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
		jsonfilter.search_filter_by = jQuery.cookie('cardList[search_filter_by]');
		jsonfilter.search_txt = jQuery.cookie('cardList[search_txt]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
		
	}
	function savepagestate()
	{
		jQuery.cookie('cardList[search_txt_studentid]', jQuery('#search_txt_studentid').val());
		jQuery.cookie('cardList[search_filter_by]', jQuery('#search_filter_by').val());
		jQuery.cookie('cardList[search_txt]', jQuery('#search_txt').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('cardList[search_txt_studentid]', null);				
		jQuery.cookie('cardList[iDisplayStart]', null);
		return true;
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
				$('#search_txt').attr("placeholder", "User code");
				$('#search_txt').focus();
				return;
			case '1':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Card Number");
				$('#search_txt').focus();
				return;
			case '2':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Last Name");
				$('#search_txt').focus();
				return;
			case '3':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "First Name");
				$('#search_txt').focus();
				return;
			case '4':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Family Code");
				$('#search_txt').focus();
                return;
            case '5':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Active or InActive");
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
	<form id='frmcard' name='frmcard' method='POST'>
	
        <h2>Card Detail</h2>
        <table class="control-section">
            <tr> <span style="color:black; font-size:15px; font-weight:500;"> Search By </span> </tr>
            <tr>
                <td style="width:50%">
                    <div class="left-section">
                        <select class="select-custom" id="search_filter_by" name="search_filter_by" onChange="changeSearchKey(this)"> 
                            <option value='-1'> Please choose </option>
                            <option value='0'> User code </option>
                            <option value='1'> Card Number </option>
                            <option value='2'> Last Name </option>
							<option value='3'> First Name </option>
							<option value='4'> Family Code </option>
                            <option value='5'> Status </option>
						</select>
                        &nbsp;&nbsp;&nbsp;
						<input type="text" class="input-text-custom" id="search_txt" name="search_txt" style="width:25%;"> </input>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a id="btnsearch_a" class="control-button"> Go </a>
						<input type="submit" id="btnsearch" name="btnsearch" value="Search" onclick=" return savepagestate() " class="btn" style="display:none"/>
                    <div>
                </td>
				<td style="width:40%;">
                    <a href="<?php echo $rootpath; ?>/card_create_multiple.php" class="control-button"> Create Mulitple Card <a/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="<?php echo $rootpath; ?>/card_create_new.php" class="control-button"> Create New Card <a/>
                </td>
            </tr>
        </table>
		<!--showing msgs-->
			<!-- <p>&nbsp;</p> -->
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
		<table cellpadding="0"  border="0" class="display" id="prepaid_card_dtList" name="prepaid_card_dtList">
			<thead>
				<tr>							
					<th>User code</th>
					<th>Card Number</th>
					<th>Last Name</th>
					<th>First Name</th>
					<th>Family code</th>
					<th>Card Value</th>
					<th>Status</th>
					<th>Action</th>
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

<div id="view_log_modal" class="modal" style="padding:50px 35px;padding-top:30px;max-width:850px !important;">
	<span style='font-size:18px;'> Transaction Detail - <span id='log_first_name'>  </span> <span id='log_last_name'>  </span> (UserCode:<span id='log_user_code'></span>) </span>
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
				$("#log_user_code").html($(e.target).attr('data-user-code'));
				var card_id = $(e.target).attr('data-card-id');
				$.post("api/get_purchase_log.php", {card_id: card_id}, function(result){
					var info = JSON.parse(result);
					console.log(info);
					var all_data = info.response.data;
					var table = $("#view_log_modal table");
					var str = '';
					data = all_data[0];
					for(var i=0;i<data.length;i++){
						str+="<tr><td><span class='date-time'> " + data[i][2] + " - </span> Spend <strong class='spend-amount'>$" + data[i][1] + "</strong> at POS ID(" + data[i][0] + ") by Card Number(" + card_id + ").</td></tr>";
					}
					data = all_data[1];
					for(var i=0;i<data.length;i++){
						str+="<tr><td><span class='date-time'> " + data[i][2] + " - </span> Topup <strong class='topup-amount'>$" + data[i][1] + "</strong> at POS ID(" + data[i][0] + ") by Card Number(" + card_id + ").</td></tr>";
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

