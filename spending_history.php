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
		//---- set background for active menu -----
        if(localStorage.getItem("current_page") != undefined){
            for(var i=0; i < $("#nav ul li").length; i++){
				if(i == 3){
					$($("#nav ul li")[i]).css("background", '#b12226');
				}
            }
        }
        //---- End -----

		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#transaction_report_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('spendingHistory[iDisplayStart]')==null)
			jQuery.cookie('spendingHistory[iDisplayStart]', 0);

		if(jQuery.cookie('spendingHistory[iDisplayLength]')==null)
			jQuery.cookie('spendingHistory[iDisplayLength]', 10);
		
		if(jQuery.cookie('spendingHistory[aaSorting]')==null)
		{
			jQuery.cookie('spendingHistory[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('spendingHistory[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('spendingHistory[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('spendingHistory[iDisplayStart]'));		
		sFilter=getFilter();	
		ilength = -1;
		oTable=jQuery('#spending_history_dtList').dataTable({
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
				jQuery.cookie('spendingHistory[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('spendingHistory[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('spendingHistory[aaSorting]', aaSorting);
				
				var tds = $(".dataTables_wrapper td");
                var j = 0;
                for(var i=0;i<tds.length;i++){
                    if(i%7==6){
                        if($(tds[i]).html().indexOf("div") == -1){
							var date = $(tds[j*7+0]).html();
							var time = $(tds[j*7+1]).html();
							var first_name = $(tds[j*7+3]).html();
							var last_name = $(tds[j*7+4]).html();
							var card_id = $(tds[j*7+2]).html();
							var item_price = $(tds[j*7+5]).html();
							var str = $(tds[j*7+6]).html();
							var bill_id = str.split(",")[0];
							var pos_id = str.split(",")[1];
							var full_name = first_name + ' ' + last_name;
                            $(tds[i]).html("<a class='edit-button view-modal' href='#spending_history_detail_modal' rel='modal:open' data-date-time='" + date+' '+time + "' data-card-id='" + card_id + "' data-pos-id='" + pos_id + "' data-item-price='" + item_price + "' data-bill-id='" + bill_id +"' data-full-name='" + full_name + "'> View Detail </a>");
						}
						j++;
                    }
                }
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_spending_history.php",
			
			"aoColumns": [						
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

		$("#sel_date_from").datepicker({
			onSelect: function(date){
				$("#sel_date_from").val(get_date(date));
			}
		});

		$("#sel_date_to").datepicker({
			onSelect: function(date){
				$("#sel_date_to").val(get_date(date));
			}
		});

		$('#generate_report_btn').click(function(){
			
		});
	});
	function loadpagestate()
	{
		updatecontrol('#sel_student_id', jQuery.cookie('spendingHistory[sel_student_id]'));
		updatecontrol('#sel_date_from', jQuery.cookie('spendingHistory[sel_date_from]'));
		updatecontrol('#sel_date_to', jQuery.cookie('spendingHistory[sel_date_to]'));
		$( "#sel_student_id" ).val( jQuery.cookie('spendingHistory[sel_student_id]') );
		updatecontrol('#search_txt', jQuery.cookie('spendingHistory[search_txt]'));
		updatecontrol('#search_filter_by', jQuery.cookie('spendingHistory[search_filter_by]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.sel_student_id = jQuery.cookie('spendingHistory[sel_student_id]');
		jsonfilter.sel_date_from = jQuery.cookie('spendingHistory[sel_date_from]');
		jsonfilter.sel_date_to = jQuery.cookie('spendingHistory[sel_date_to]');
		jsonfilter.search_txt = jQuery.cookie('spendingHistory[search_txt]');
		jsonfilter.search_filter_by = jQuery.cookie('spendingHistory[search_filter_by]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
	}
	function spending_savepagestate()
	{
		jQuery.cookie('spendingHistory[sel_student_id]', jQuery('#sel_student_id').val());
		jQuery.cookie('spendingHistory[sel_date_from]', jQuery('#sel_date_from').val());
		jQuery.cookie('spendingHistory[sel_date_to]', jQuery('#sel_date_to').val());
		jQuery.cookie('spendingHistory[search_txt]', jQuery('#search_txt').val());
		jQuery.cookie('spendingHistory[search_filter_by]', jQuery('#search_filter_by').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('spendingHistory[sel_student_id]', '-1');
		jQuery.cookie('spendingHistory[sel_date_from]', 'Choose date');
		jQuery.cookie('spendingHistory[sel_date_to]', 'Choose date');		
		jQuery.cookie('spendingHistory[iDisplayStart]', null);
		jQuery.cookie('spendingHistory[search_txt]', null);		
		jQuery.cookie('spendingHistory[search_filter_by]', null);
		return true;
	}

	function changeSearchKey(e){
		console.log($(e).val());
		switch($(e).val()){
			case '-1':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "");
				$("#search_txt").attr('disabled','disabled');
				return;
			case '0':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter Card ID");
				$("#search_txt").removeAttr('disabled');
				$('#search_txt').focus();
				return;
			case '1':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter First Name");
				$("#search_txt").removeAttr('disabled');
				$('#search_txt').focus();
				return;
			case '2':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter Last Name");
				$("#search_txt").removeAttr('disabled');
				$('#search_txt').focus();
				return;
			case '3':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Enter Amount");
				$("#search_txt").removeAttr('disabled');
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
	<form id='frm_card' name='frm_card' method='POST'>
	
		<h2>Spending History</h2>
		<table style="width:100%">
            <tr>
                <td style="width:85%">
                    <div class="left-section filter-div">
						<table>
							<tr>
								<td style="padding-right:35px;">
									<div style="font-size:16px;"> From </div>
									<input type="text" value="Choose date" name="sel_date_from" id="sel_date_from" class='input-text-custom'/>
								</td>

								<td style="padding-right:35px;">
									<div style="font-size:16px;"> To </div>
									<input type='text' value="Choose date" name='sel_date_to' id='sel_date_to' class='input-text-custom'/>
								</td>

								<td style="padding-right:20px;">
									<div style="font-size:16px;"> Filter by </div>
									<select id="search_filter_by" name="search_filter_by" class="select-custom" onChange="changeSearchKey(this)">
										<option value="-1">No Filter</option>
										<option value='0'> Card ID </option>
										<option value='1'> First Name </option>
										<option value='2'> Last Name </option>
										<option value='3'> Amount Spend </option>
									</select>
								</td>

								<td style="padding-right:35px;">
									<div style="font-size:16px;visibility:hidden"> Content </div>
									<input type="text" class="input-text-custom" id="search_txt" name="search_txt" placeholder="" disabled/>
								</td>

								<td style="padding-top:20px;">
									<input type="submit" id="spending_btnsearch" name="spending_btnsearch" class="control-button" onclick=" return spending_savepagestate() " value='Generate Report'/>
								</td>
							</tr>
						</table>
                    <div>
                </td>
                <td style="width:30%;text-align:right;padding-top:20px;">
                    
                </td>
            </tr>
		</table>
		
		<!--Searching criteria-->
		<!-- <div style="float: left; width: 50%;">
			<div class="frm">
				<div class="frm_label">Student ID : </div>			
				<select id="sel_student_id" name="sel_student_id">
				<option value='-1'>--Select Student ID--</option>
				<?php
					$login_user_type_id = $_SESSION ['login_user_type_id'];
					$login_user_id = $_SESSION ['login_user_id'];
					$rResult= $reportbol->get_student_by_loginusertype($login_user_type_id,$login_user_id);
					while($row=$rResult->getNext())
					{
						echo "<option value='".$row['participant_id']."'>".$row['participant_enroll_no']."</option>";
					}
					
				?>
				</select>
			</div>
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" id="spending_btnsearch" name="spending_btnsearch" value="<?php echo $localized_home_data['search_btn']; ?>" onclick=" return spending_btnsearch() " class="btn" /> &nbsp;
				<input type="submit" id="btnshowall" name="btnshowall" value="<?php echo $localized_home_data['show_all_btn']; ?>" onclick="clearpagestate()" class="btn" />		
			</div>
		</div> -->
		<!--Searching criteria-->
		
		<!--datatable-->
		<div class="cleaner"></div>
		<table cellpadding="0"  border="0" class="display" id="spending_history_dtList" name="spending_history_dtList">
			<thead>
				<tr>
					<th>Date</th>
					<th>Time</th>
					<th>Card ID</th>
					<th>First Name</th>					
					<th>Last Name</th>
					<th>Amount Spend</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="5" class="dataTables_empty"><?php echo $localized_home_data['loading_msg_datatable']; ?></td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
	</form>
</div>

<div id="spending_history_detail_modal" class="modal" style="padding:50px 50px;padding-top:30px;">
    <table>
		<tr> 
			<td> 
				<span class="title"> Date & Time: </span>
				<span class="detail" id='modal_date_time'> </span>
			</td>
		</tr>
		<tr>
			<td>
				<span class="title"> Spending By : </span>
				<span class="detail" id='modal_full_name'> </span>
			</td>
		</tr>
		<tr> 
			<td> 
				<span class="title"> Card ID : </span>
				<span class="detail" id='modal_card_id'> </span>
			</td>
		</tr>
		<tr>
			<td>
				<span class="title"> POS ID : </span>
				<span class="detail" id='modal_pos_id'> </span>
			</td>
		</tr>
		<tr> 
			<td> 
				<span class="title"> Amount Spend : </span>
				<span class="detail" id='modal_item_price'> </span>
			</td>
		</tr>
		<tr>
			<td>
				<span class="title"> Receipt Number:  </span>
				<span class="detail" id="bill_id"> </span>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<span class="title"> Order Item:  </span>
				<div id="order_items_detail"> 
					
				</div>
			</td>
		</tr>
	</table>
</div>

<style>
	#spending_history_detail_modal table td{
		padding:10px 20px;
	}
	#spending_history_detail_modal .title{
		font-weight:600 !important;
		font-size:17px;
	}
	#spending_history_detail_modal .detail{
		font-size:15px;
	}
	#spending_history_detail_modal span{
		font-weight:500 !important;
	}
	#order_items_detail{
		padding:10px;
		font-size: 15px;
	}
</style>

<script>
	$(document).ready(function(){
		setTimeout(() => {
			$('.edit-button.view-modal').click(function(e) {
				$("#modal_date_time").html($(e.target).attr('data-date-time'));
				$("#modal_full_name").html($(e.target).attr('data-full-name'));
				$("#modal_card_id").html($(e.target).attr('data-card-id'));
				$("#modal_pos_id").html($(e.target).attr('data-pos-id'));
				$("#bill_id").html($(e.target).attr('data-bill-id'));
				$("#modal_item_price").html($(e.target).attr('data-item-price'));
				var bill_id = $(e.target).attr('data-bill-id');
				var obj = {
					bill_id : bill_id
				}
				var url = '<?php echo $rootpath;?>/api/get_items_from_bill_id.php';
				var request = JSON.stringify(obj);
				$.ajax({
					url : url,
					type : 'POST',
					data :  request,  
					tryCount : 0,
					retryLimit : 3,
					success : function(info) {
						var info = JSON.parse(info);
						info = info.response.data;
						var str = "";
						for(var i=0;i<info.length;i++){
							str += "<span>" + info[i][0] + ":  $" + info[i][1] + " &times " + info[i][2] + "</span></br>";
						}
						$("#order_items_detail").html(str);
					},
					error : function(xhr, textStatus, errorThrown ) {
						console.log(xhr);
					}
				});
			});
		}, 1000);
		
	});
</script>

<?php
	include("footer.php");
?>