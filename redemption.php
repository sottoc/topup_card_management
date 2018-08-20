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
	if(isset($_POST['redemption_btn']))
	{
		//print_r($_POST);
		$std_id = $_POST['std_id'];
		$card_id = $_POST['hid_card_id'];
		$rdo_order_type = $_POST['rdo_order_type'];
		$hid_pre_order_id_list = $_POST['hid_pre_order_id_list'];
		$current_topupamt = $_POST['current_topupamt'];
		$hid_redempation_pay_amt = $_POST['hid_redempation_pay_amt'];
		$staff_id = $_SESSION['login_user_id'];
		if($current_topupamt>$hid_redempation_pay_amt)
		{
			//echo "$std_id,$card_id,$rdo_order_type,$hid_pre_order_id_list,$hid_redempation_pay_amt";
			//find pre_order data by using preorder id
			if($rdo_order_type=='pre_order')
			{
				$pre_order_id_arr = explode(',',$hid_pre_order_id_list);
				foreach($pre_order_id_arr as $key=>$value)
				{
					//save in redemption
					$pre_order_data_res = $transactionbol->get_preorder_data_byusing_preorderid($value);
					$pod_row = $pre_order_data_res->getNext();
					$item_price = $pod_row['item_price'];
					$qty = $pod_row['qty'];
					$multiple_res = $item_price * $qty;
					
					$redemptioninfo->set_redemption_amt($multiple_res);
					$redemptioninfo->set_user_id($staff_id);
					$redemptioninfo->set_pre_order_id($value);
					$redemption_id=$transactionbol->save_preorder_redemption($redemptioninfo);//saving in tbl_redemption
					if($redemption_id!=null)
					{
						//save in tbl_transaction
						$transactioninfo->set_trans_type('redemption');
						$transactioninfo->set_card_id($card_id);
						$transactioninfo->set_redempation_id($redemption_id);
						$transactioninfo->set_transaction_amt($multiple_res);
						$transactioninfo->set_participant_id($std_id);
						$transactioninfo->set_transaction_datetime($now_date_time);
						$trans_id=$transactionbol->save_transaction_preorder_redempation($transactioninfo);
						if($trans_id)
						{
							//to change meal_status in preorder table
							$change_meal_status_res = $transactionbol->update_meal_status($value);
							if($change_meal_status_res)
							{
								echo '<h3 id="h3_msg" style="margin-left:168px;padding-top: 13px;color:red;">Redemption is successfully for pre order food.</h3>';
								sleep(5);//sleep for 3 seconds
							}
						}
					}
					else
					{
						echo '<h3 id="h3_msg" style="margin-left:168px;padding-top: 13px;color:red;">Redemption fail.</h3>';
						sleep(5);//sleep for 3 seconds
					}
				}
			}
		}
		else
		{
			echo '<h3 id="h3_msg" style="margin-left:168px;padding-top: 13px;color:red;">Your usage amount is greater than your topup amount.Fill topup to do redemption.</h3>';
			sleep(5);//sleep for 3 seconds
		}
	}
?>
<style type="text/css">
	
</style>
<script>
var hid_items_list;
var hid_items_total_costs;
	jQuery(document).ready(function(){
		$("#h3_msg").hide();
		$('#tbl_item').hide();
		//$('#redemption_btn').show();
		hid_items_list='';
		hid_items_total_costs='';
		$("#canteen_order_redemption_msg_show").hide();
		
		$("#tbl_canteen_item_add").on('click','.btnDelete',function(){
			//alert("delete row");
			var str='';
			var $row = $(this).closest("tr"),       // Finds the closest row <tr> 
			$tds = $row.find("td");             // Finds all children <td> elements
			$.each($tds, function() {               // Visits every single <td> element       
				if(str=='')
					str = $(this).text();// Prints out the text within the <td>
				else
					str = str+'$#$'+$(this).text();
			});
			var res_arr = str.split("$#$");
			var remove_str = res_arr[0]+'@#@'+res_arr[2];
			
			var new_hid_items_list;
			var hid_items_list = $("#hid_items_list").val();
			console.log("hid_items_list="+hid_items_list);
			console.log("remove_str="+remove_str);
			var new_hid_items_list = hid_items_list.replace(remove_str,"0");
			console.log("new_hid_items_list="+new_hid_items_list);
			$("#hid_items_list").val(new_hid_items_list);
			
			$(this).closest('tr').remove();
		});
	});
	
	function clear()
	{
		//for preorder
		$("#std_id").val('');
		$("#allow_preorder").val('');
		$("#redem_category_type").val('-1');
		$('#tbl_item').html("<table id='tbl_item' border='1' cellspacing='0' cellpadding='0'><tr><td>Item Name</td><td>QTY</td><td>Unit Price</td><td>SUM</td></tr></table>");
		$('#tbl_item').hide();
		
		//for canteen order
		hid_items_list='';
		hid_items_total_costs='';
		$('#txt_qty').val('');
		$('#sel_item').val('-1');
		$('#sel_meal_type').val('-1');
		$("#lbl_item_description").html('');
		
	}
	function show_order()
	{
		clear();
		var redemption_studentid = $("#redemption_studentid").val();
		jQuery.getJSON('get_item_by_student.php?redemption_studentid='+redemption_studentid, callbackfun);
	}
	function callbackfun(data)
	{
		if(data.status=='success')
		{
			$("#div_show_order").show();
			$("#std_id").val(data.std_id);
			$("#allow_preorder").val(data.allow_preorder);
			$("#current_topupamt").val(data.current_amt);
			$("#hid_card_id").val(data.card_id);
			
			if(data.allow_preorder==1)
			{
				alert("This student is preorder student");
				//jQuery("input[value='pre_order']").attr('checked', true);
				jQuery("#rdo_pre_order").attr('checked', true);
				$("#div_canteen_order").hide();
				$("#div_pre_order").show();
				$("#redemption_btn").show();
				$("#canteenorder_redemption_btn").hide();
			}
			else if(data.allow_preorder==0)
			{
				alert("This student is not preorder student");
				//jQuery("input[value='canteen_order']").attr('checked', true);
				jQuery("#rdo_canteen_order").attr('checked', true);
				$("#div_canteen_order").show();
				$("#div_pre_order").hide();
				$("#redemption_btn").hide();
				$("#canteenorder_redemption_btn").show();
			}
		}
		else
		{
			alert("Invalid Student ID");
			location.reload();
			return false;
		}
	}
	function choose_order_type(order_type)
	{
		if(order_type=='canteen_order')
		{
			$("#div_canteen_order").show();
			$("#div_pre_order").hide();
			$("#div_tbl_item").hide();
			$("#redemption_btn").hide();
			$("#canteenorder_redemption_btn").show();
		}
		else
		{
			$("#div_canteen_order").hide();
			$("#div_pre_order").show();
			$("#div_tbl_item").show();
			$("#redemption_btn").show();
			$("#canteenorder_redemption_btn").hide();
		}
	};
	function search_item()
	{
		var sel_redem_category_type = $("#redem_category_type").val();
		if(sel_redem_category_type == '-1')
		{
			$('#tbl_item').hide();
			$('#div_tbl_item').hide();
		}
		var hid_std_id = $("#std_id").val();
		$('#tbl_item').html("<table id='tbl_item' border='1' cellspacing='0' cellpadding='0'><tr><td>Item Name</td><td>QTY</td><td>Unit Price</td><td>SUM</td></tr></table>");
		$.post( 
                  "get_item_by_student.php",
                  {sel_redem_category_type:sel_redem_category_type,hid_std_id:hid_std_id},
                  function(data) {
					$("#div_tbl_item").show();
                    $('#tbl_item').show();
					var obj=JSON.parse(data);
					if(obj.status=='success')
					{
						$('#hid_pre_order_id_list').val(obj.pre_order_id_list);
						$('#redemption_btn').show();
						$('#canteenorder_redemption_btn').hide();
						for (var i = 0; i < obj.tbl_info.length; i++) 
						{
							tr = '<tr>';
							tr = tr+"<td>" + obj.tbl_info[i].item_name + "</td>";
							tr = tr+"<td>" + obj.tbl_info[i].qty + "</td>";
							tr = tr+"<td>" + obj.tbl_info[i].price + "</td>";
							tr = tr+"<td>" + obj.tbl_info[i].mul_res + "</td>";
							tr = tr+"</tr>";
							console.log(tr);
							$('#tbl_item').append(tr);
						}
						var tr2 = '<tr>';
							tr2 = tr2+"<td>Totally:</td>";
							tr2 = tr2+"<td colspan='3' style='text-align:right;'>" + obj.total_amt+ "</td>";
							tr2 = tr2+"</tr>";
							console.log(tr2);
							$('#tbl_item').append(tr2);
							$('#hid_redempation_pay_amt').val(obj.total_amt);
					}
					else
					{
						$('#redemption_btn').hide();
						
						tr3 = '<tr>';
						tr3 = tr3+"<td colspan='4' style='text-align:center;'>Not having preorder for today of this studentID.</td>";
						tr3 = tr3+"</tr>";
						$('#tbl_item').append(tr3);
					}
						
               });
		
	}
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
			alert("Please fill up quantity amount");
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
		if(student_enroll_number=='')
		{
			alert("Please insert student ID");
			return false;
		}
		if(sel_meal_type=='-1')
		{
			alert("Please select meal type");
			return false;
		}
		if(hid_items_list=='')
		{
			alert("Please select item");
			return false;
		}
		$.post( 
                "get_item_by_student.php",
                {student_enroll_number:student_enroll_number,sel_meal_type:sel_meal_type,hid_items_list:hid_items_list},
                function(data) {
					alert(data);
					location.reload();
				});
	}
</script>
<div class="content_data">
	<form action="" method="POST" id="topup_manual" name="topup_manual" enctype="multipart/form-data">
		
		<h2>Redemption</h2>
			<?php
				
	  	  		if(count($errors))
		  		{
					echo "<div class='errors'>".join('<br />', $errors)."</div><br/>";
	  			}
			?>
			<div id="canteen_order_redemption_msg_show"></div>
			<div class="frm">
				<div class="frm_labelcat">Student ID:</div>
				<input type="text" name="redemption_studentid" id="redemption_studentid"/>
				<input type="button" class="btn"  name="btn_show_order" id="btn_show_order" value="Show Order" onClick="show_order()";/>
			</div>
			<div class="frm">
				<div class="frm_labelcat">Current Amount:<span>(readonly)</span></div>
				<input type="text" name="current_topupamt" id="current_topupamt" readonly />
			</div>
			<!--hidden fields-->
			<input type="hidden" id="std_id" name="std_id"/>
			<input type="hidden" id="hid_card_id" name="hid_card_id"/>
			<input type="hidden" id="allow_preorder" name="allow_preorder"/>
			<input type="hidden" id="hid_pre_order_id_list" name="hid_pre_order_id_list"/>
			<input type="hidden" id="hid_redempation_pay_amt" name="hid_redempation_pay_amt"/>
			<!--hidden fields-->
			<div id="div_show_order" style="display:none;">
				<div class="frm">
					<div class="frm_labelcat">Order Type:</div>
					<input type="radio" name="rdo_order_type" id="rdo_pre_order" value="pre_order" onClick="choose_order_type('pre_order');"/>Pre Order
					<input type="radio" name="rdo_order_type" id="rdo_canteen_order" value="canteen_order" onClick="choose_order_type('canteen_order');"/>Canteen Order
				</div>
				<div class="frm" id="div_pre_order" style="display:none;">
					<br/><hr/><br/><h4 style="font-size:18px;color:orange;">Your pre order list are as like as follows.</h4><br/><br/>
					<div class="frm_labelcat">Category Type:</div>
					<select id="redem_category_type" name="redem_category_type" onChange='search_item();'>
						<option value="-1">Select time for menu</option>
						<?php
							$res_category_type=$transactionbol->get_all_category_type();
							while($ctype_row=$res_category_type->getNext())
							{
								echo "<option value=".$ctype_row['category_type_id'].">".$ctype_row['category_type_name']."</option>";
							}
						?>
					</select>
				</div>
				<div id="div_tbl_item" class="frm" style="margin-left: 311px;display:none;">
					<table id='tbl_item' border='1' cellspacing='0' cellpadding='0' style="width:50%;display:none;">
						<tr>
							<td>Item Name</td>
							<td>QTY</td>
							<td>Unit Price</td>
							<td>SUM</td>
						</tr>
					</table>
				</div>
				<div class="frm" id="div_canteen_order" style="display:none;">
					<br/><hr/><br/><h4 style="font-size:18px;color:violet;">You can order yourself.</h4><br/><br/>
					
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
							<input type="text" id="hid_items_total_costs" name="hid_items_total_costs"/>
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
						<table id='tbl_canteen_item_add' border='1' cellspacing='0' cellpadding='0' style="display:none;width:50%;margin-left:158px;">
							<tr>
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
					<input type="submit" name="redemption_btn"  id="redemption_btn" value="Redemption" class="btn"style="margin-left: 20px;"/>
				</div>
				<br/>
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