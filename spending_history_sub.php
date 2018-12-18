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
		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#transaction_report_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('spendingSubHistory[iDisplayStart]')==null)
			jQuery.cookie('spendingSubHistory[iDisplayStart]', 0);

		if(jQuery.cookie('spendingSubHistory[iDisplayLength]')==null)
			jQuery.cookie('spendingSubHistory[iDisplayLength]', 10);
		
		if(jQuery.cookie('spendingSubHistory[aaSorting]')==null)
		{
			jQuery.cookie('spendingSubHistory[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('spendingSubHistory[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('spendingSubHistory[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('spendingSubHistory[iDisplayStart]'));		
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
				jQuery.cookie('spendingSubHistory[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('spendingSubHistory[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('spendingSubHistory[aaSorting]', aaSorting);
				
				var tds = $("#spending_history_dtList_wrapper td");
				var j = 0;
                for(var i=0;i<tds.length;i++){
                    if(i%7==6){
                        if($(tds[i]).html().indexOf("div") == -1){
							var date = $(tds[j*7+0]).html();
							var time = $(tds[j*7+1]).html();
							var first_name = $(tds[j*7+2]).html();
							var card_id = $(tds[j*7+3]).html();
							var pos_id = $(tds[j*7+4]).html();
							var item_price = $(tds[j*7+5]).html();
							var str = $(tds[j*7+6]).html();
							var bill_id = str.split(",")[0];
							var last_name = str.split(",")[1];
							var full_name = first_name + ' ' + last_name;
                            $(tds[i]).html("<a class='edit-button view-modal' href='#spending_history_detail_modal' rel='modal:open' data-date-time='" + date+' '+time + "' data-card-id='" + card_id + "' data-pos-id='" + pos_id + "' data-item-price='" + item_price + "' data-bill-id='" + bill_id +"' data-full-name='" + full_name + "'> View Detail </a>");
						}
						j++;
                    }
                }
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_spending_history_sub.php",
			
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

		$("#spending_sub_date_from").datepicker({
			onSelect: function(date){
				$("#spending_sub_date_from").val(get_date(date));
			}
		});

		$("#spending_sub_date_to").datepicker({
			onSelect: function(date){
				$("#spending_sub_date_to").val(get_date(date));
			}
		});
		
	});
	function loadpagestate()
	{
		updatecontrol('#sel_student_id', jQuery.cookie('spendingSubHistory[sel_student_id]'));
		updatecontrol('#spending_sub_date_from', jQuery.cookie('spendingSubHistory[spending_sub_date_from]'));
		updatecontrol('#spending_sub_date_to', jQuery.cookie('spendingSubHistory[spending_sub_date_to]'));
		$( "#sel_student_id" ).val( jQuery.cookie('spendingSubHistory[sel_student_id]') );
		//$( "#spending_sub_date_from" ).val( jQuery.cookie('spendingSubHistory[spending_sub_date_from]') );
		//$( "#spending_sub_date_to" ).val( jQuery.cookie('spendingSubHistory[spending_sub_date_to]') );
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.sel_student_id = jQuery.cookie('spendingSubHistory[sel_student_id]');
		jsonfilter.spending_sub_date_from = jQuery.cookie('spendingSubHistory[spending_sub_date_from]');
		jsonfilter.spending_sub_date_to = jQuery.cookie('spendingSubHistory[spending_sub_date_to]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
	}
	function spending_sub_savepagestate()
	{
		jQuery.cookie('spendingSubHistory[sel_student_id]', jQuery('#sel_student_id').val());
		jQuery.cookie('spendingSubHistory[spending_sub_date_from]', jQuery('#spending_sub_date_from').val());
		jQuery.cookie('spendingSubHistory[spending_sub_date_to]', jQuery('#spending_sub_date_to').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('spendingSubHistory[sel_student_id]', '-1');
		jQuery.cookie('spendingSubHistory[spending_sub_date_from]', 'Choose date');
		jQuery.cookie('spendingSubHistory[spending_sub_date_to]', 'Choose date');		
		jQuery.cookie('spendingSubHistory[iDisplayStart]', null);
		return true;
	}
	
</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data" style="margin:0px 0px !important">
	<form id='frm_card' name='frm_card' method='POST'>
	
		<h2>Spending History <span style="font-weight:500;"> for last 7 days </span> </h2>
		<table style="width:100%; display:none;">
            <tr>
                <td style="width:70%">
                    <div class="left-section">
						<table>
							<tr>
								<td style="padding-right:35px;">
									<div style="font-size:16px;"> From </div>
									<input type="text" value="Choose date" name="spending_sub_date_from" id="spending_sub_date_from" class='input-text-custom'/>
								</td>

								<td style="padding-right:35px;">
									<div style="font-size:16px;"> To </div>
									<input type='text' value="Choose date" name='spending_sub_date_to' id='spending_sub_date_to' class='input-text-custom'/>
								</td>

								<td style="padding-top:20px;">
									<input type="submit" id="spending_sub_btnsearch" name="spending_sub_btnsearch" class="control-button" onclick=" return spending_sub_savepagestate() " value='Go'/>
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
				<input type="submit" id="spending_sub_btnsearch" name="spending_sub_btnsearch" value="<?php echo $localized_home_data['search_btn']; ?>" onclick=" return spending_sub_savepagestate() " class="btn" /> &nbsp;
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
					<th>First Name</th>					
					<th>Card ID</th>					
					<th>POS ID</th>
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

<div id="spending_history_detail_modal" class="modal" style="padding:50px 50px;padding-top:10px;">
    <table>
		<tr> 
			<td> 
				<span class="title"> Date & Time:  </span>
				<span class="detail" id='modal_date_time'>  </span>
			</td>
		</tr>
		<tr>
			<td>
				<span class="title"> Spending By:  </span>
				<span class="detail" id='modal_full_name'>  </span>
			</td>
		</tr>
		<tr> 
			<td> 
				<span class="title"> Card ID:  </span>
				<span class="detail" id='modal_card_id'>  </span>
			</td>
		</tr>
		<tr>
			<td>
				<span class="title"> POS ID:  </span>
				<span class="detail" id='modal_pos_id'>  </span>
			</td>
		</tr>
		<tr> 
			<td> 
				<span class="title"> Amount Spend:  </span>
				<span class="detail" id='modal_item_price'>  </span>
			</td>
		</tr>
		<tr>
			<td>
				<span class="title"> Receipt Number:  </span>
				<span class="detail" id="bill_id">  </span>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<span class="title"> Order Item: </span>
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
		font-weight:600;
		font-size:17px;
	}
	#spending_history_detail_modal .detail{
		font-size:15px;
	}
	#spending_history_detail_modal span{
		font-weight:500;
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
				console.log(bill_id);
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