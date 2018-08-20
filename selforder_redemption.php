<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	require_once('header.php');	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	$errors = array();
	$transactionbol = new transactionbol();
	$redemptioninfo =  new redemptioninfo();
	$transactioninfo =  new transactioninfo();
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');
	
?>
<script>
var hid_items_list;
var hid_items_total_costs;
jQuery(document).ready(function(){
	hid_items_list='';
	hid_items_total_costs='';
	$("#tbl_canteen_item_add").on('click','.btnDelete',function(){
		var str='';
		var new_canteen_order_total_costs=0;
		var $row = $(this).closest("tr"),       // Finds the closest row <tr> 
		$tds = $row.find("td");             // Finds all children <td> elements
		$.each($tds, function() {               // Visits every single <td> element       
			if(str=='')
				str = $(this).text();// Prints out the text within the <td>
			else
				str = str+'$#$'+$(this).text();
		});
		console.log("td data="+str);
		//8$#$B-Item2$#$2$#$5.7$#$11.4$#$Delete
		
		var res_arr = str.split("$#$");
		var remove_str = res_arr[0]+'@#@'+res_arr[2];
		var remove_str_2 = res_arr[4];
		console.log("remove_str="+remove_str);
		
		var new_hid_items_list;
		var hid_items_list = $("#hid_items_list").val();
		console.log("hid_items_list="+hid_items_list);
		
		var new_hid_items_list = hid_items_list.replace(remove_str,"0");
		console.log("new_hid_items_list="+new_hid_items_list);
		
		$("#hid_items_list").val(new_hid_items_list);
		
		//update value in total textbox
		var hid_items_total_costs = $("#hid_items_total_costs").val();
		var new_hid_items_total_costs = hid_items_total_costs.replace(remove_str_2,"0");
		var new_integer_hid_items_total_costs = new_hid_items_total_costs;
		$("#hid_items_total_costs").val(new_hid_items_total_costs);
		
		new_hid_items_total_costs = String(new_hid_items_total_costs);
		var find_comma = new_hid_items_total_costs.indexOf(",");
		if(find_comma!='-1')
		{
			new_res_arr = new_hid_items_total_costs.split(",");
			for(var n=0;n<new_res_arr.length;n++)
				new_canteen_order_total_costs = new_canteen_order_total_costs + parseFloat(new_res_arr[n]);
			console.log("new_canteen_order_total_costs="+new_canteen_order_total_costs);
		}
		else
			new_canteen_order_total_costs = new_integer_hid_items_total_costs;
		new_canteen_order_total_costs = new_canteen_order_total_costs.toFixed(2);
		$("#canteen_order_total_costs").val(new_canteen_order_total_costs);
		
		$(this).closest('tr').remove();
	});
});
function sel_item_onChange()
{
	$("#lbl_item_description").html("");
	var sel_item = $("#sel_item").val();
	if(sel_item != '-1')
		$.post("get_item_descrption_byid.php",{"item_id":sel_item},function(data){
			$("#lbl_item_description").html(data);
		});
}
function add_item()
{
	$("#tbl_canteen_item_add").show();
	var item_id = $("#sel_item").val();
	var item_name = $( "#sel_item option:selected" ).text();
	var qty = $("#txt_qty").val();
	var row;
	if(qty=='' && qty==0)
		message_popup("Please fill up quantity amount",350);
	else
	{
		if(hid_items_list=='')
			hid_items_list= item_id+"@#@"+qty;
		else
			hid_items_list= hid_items_list+","+item_id+"@#@"+qty;
		console.log("hid_items_list="+hid_items_list);
		
		$("#hid_items_list").val(hid_items_list);
		
		//find item price
		var item_price;
		var sum=0;
		var res_arr='';
		var canteen_order_total_costs=0;
		$.post( 
			"get_item_by_student.php",
			{item_id:item_id},
			function(data) {
			console.log(data);
			var priceobj=JSON.parse(data);
			row = "<tr>";
			row = row + "<td>"+item_id+"</td>";
			row = row + "<td>"+item_name+"</td>";
			row = row + "<td>"+qty+"</td>";
			if(priceobj.status=='success')
			{
				row = row + "<td>"+priceobj.item_price+"</td>";
				sum= priceobj.item_price * qty;
				row = row + "<td>"+sum+"</td>";
				var hid_items_total_costs = $("#hid_items_total_costs").val();
				if(hid_items_total_costs=='')
					hid_items_total_costs= sum;
				else
					hid_items_total_costs= hid_items_total_costs+","+sum;
				console.log("hid_items_total_costs="+hid_items_total_costs);
				$("#hid_items_total_costs").val(hid_items_total_costs);
				
				hid_items_total_costs = String(hid_items_total_costs);
				var find_comma = hid_items_total_costs.indexOf(",");
				if(find_comma!='-1')
				{
					res_arr = hid_items_total_costs.split(",");
					for(var m=0;m<res_arr.length;m++)
						canteen_order_total_costs = canteen_order_total_costs + parseFloat(res_arr[m]);
					console.log("canteen_order_total_costs="+canteen_order_total_costs);
				}
				else
					canteen_order_total_costs = sum;
				canteen_order_total_costs = canteen_order_total_costs.toFixed(2);
				$("#canteen_order_total_costs").val(canteen_order_total_costs);
			}
			else
			{
				row = row + "<td>"+0+"</td>";
				row = row + "<td>"+0+"</td>";
			}
			row = row + "<td><button class='btnDelete'>Delete</button></td>";
			row = row + "</tr>";
			
			$('#tbl_canteen_item_add').append(row);
		});
	}
}
function canteen_order_redemption()
{
	var student_enroll_number = $("#redemption_studentid").val();
	var sel_meal_type = $("#sel_meal_type").val();
	var hid_items_list = $("#hid_items_list").val();
	var canteen_order_total_costs = $("#canteen_order_total_costs").val();
	if(student_enroll_number=='')
	{
		message_popup("Please insert student ID",350);
		return false;
	}
	if(sel_meal_type=='-1')
	{
		message_popup("Please select meal type",350);
		return false;
	}
	if(hid_items_list=='')
	{
		message_popup("Please click ADD button to add item",350);
		return false;
	}
	$.post( 
			"get_item_by_student.php",
			{student_enroll_number:student_enroll_number,sel_meal_type:sel_meal_type,hid_items_list:hid_items_list,canteen_order_total_costs:canteen_order_total_costs},
			function(data) {
				message_popup(data,350);
				location.reload();
			});
}
</script>
<div class="content_data">
	<form action="" method="POST" id="topup_manual" name="topup_manual" enctype="multipart/form-data">
		<h2>Self Order Redemption</h2>
		<div id="canteen_order_redemption_msg_show"></div>
		<div class="frm">
			<div class="frm_labelcat">Student ID:</div>
			<input type="text" name="redemption_studentid" id="redemption_studentid"/>
		</div>
		<div id="div_show_order">
			<div class="frm" id="div_canteen_order">
				<div class="form-control">
					<label class="frm_labelcat" for="sel_meal_type">Meal Type *</label>
					<div class="controls">
						<select name="sel_meal_type" id="sel_meal_type">
							<option value='-1'>Please Select Meal Type</option>
							<?php
								$setupbol = new setupbol();
								$mealtype_res = $setupbol->get_all_category_type();
								while($mealtype_row = $mealtype_res->getNext())
								{
									$category_type_id = $mealtype_row['category_type_id'];
									$category_type_name = $mealtype_row['category_type_name'];
									echo "<option value='".$category_type_id."'>" .htmlspecialchars($category_type_name) . "</option>";
								}
							?>
						</select>
					</div>
				</div>
				
				<div class="form-control">
					<label class="frm_labelcat" for="sel_item">Item Name *</label>
					<div class="controls">
						<select name="sel_item" id="sel_item" onChange="sel_item_onChange();">
							<option value='-1'>Please Select Item Name</option>
							<?php
								$setupbol=new setupbol();
								$item_res = $setupbol->get_all_item_list();
								while($item_row = $item_res->getNext())
								{
									$item_id = $item_row['item_id'];
									$item_name = $item_row['item_name'];
									echo "<option value='".$item_id."'>" .htmlspecialchars($item_name) . "</option>";
								}
							?>
						</select>
						<input type="button" class="btn" value="ADD" onClick="add_item();"/>
						<input type="hidden" id="hid_items_list" name="hid_items_list"/>
						<input type="hidden" id="hid_items_total_costs" name="hid_items_total_costs"/>
					</div>
				</div>
				<div class="form-control">
					<label class="frm_labelcat" for="txt_qty">Qty*</label>
					<div class="controls">
						<input type="number" name="txt_qty" id="txt_qty" class="text ui-widget-content ui-corner-all" min="0">
					</div>
				</div>
				<div class="form-control">
					<div class="controls" id="lbl_item_description" name="lbl_item_description"  style="margin-left: 161px;">
					</div>
					<br/>
					<table class="table" id='tbl_canteen_item_add' border='1' cellspacing='0' cellpadding='0' style="display:none;width:50%;margin-left:158px;">
						<tr style="background:lightblue;">
							<td>Item Id</td>
							<td>Item Name</td>
							<td>QTY</td>
							<td>Unit Price</td>
							<td>SUM</td>
							<td>Delete</td>
						</tr>
					</table>
					<br/>
					<div class="form-control">
						<label class="frm_labelcat" for="canteen_order_total_costs">Totally:</label>
						<input type="text" id="canteen_order_total_costs" name="canteen_order_total_costs"/>
					</div>
				</div>
			</div>
			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="button" name="canteenorder_redemption_btn"  id="canteenorder_redemption_btn" value="Redemption" onClick="canteen_order_redemption();" class="btn"style="margin-left: 20px;"/>
			</div>
		</div>
	</form>
</div>
<?php
	include('library/closedb.php');
	include("footer.php");
?>