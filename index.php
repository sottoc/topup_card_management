<?php
	require_once('library/reference.php');
	require_once('autoload.php');	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	require_once('header.php');
	$localizationbol= new localizationbol();
	
	//localization
	$localizedresult=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localizedresult->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
?>

<style type="text/css">

</style>

<script type="text/javascript">
$(document).ready(function(){
	find_current_card_amount();
	localStorage.setItem("current_page", '<a href="index.php">Home</a>');
	//---- set background for active menu -----
	if(localStorage.getItem("current_page") != undefined){
		for(var i=0; i < $("#nav ul li").length; i++){
			if($($("#nav ul li")[i]).html() == localStorage.getItem("current_page")){
				$($("#nav ul li")[i]).css("background", '#05815f');
			}
		}
	}
	//---- End -----
});
function find_current_card_amount()
{
	var sel_student_id = $("#sel_student_id").val();
	$.post("get_current_card_amount_byId.php",{"sel_student_id":sel_student_id},function(data){
			$("#current_card_amount").html(data);
		});
		
	$.post("get_topup_history_report.php",{"sel_student_id":sel_student_id},function(data){
		$("#topup").html(data);
	});
	
	$.post("get_purchase_history_report.php",{"sel_student_id":sel_student_id},function(data){
		$("#purchase").html(data);
	});
}

</script>
<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>
		
<br/>
	<div style="margin:8px 100px;">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  style="font-size:15px;">
			<tbody>
				<tr>
					<td>
						<?php
							if($_SESSION ['login_user_type_id']==1) {
							?>
								<div style='font-size:19px;'>
									<h2> &nbsp;&nbsp;&nbsp;&nbsp; Record at <span style="color:#e63d02"> 22 August 2018 </span> </h2>
									<table width="100%" style="text-align:center;">
										<tr> 
											<td style="padding:20px;">
												<div class="home-block">
													<div> Total Transaction Today </div>
													<div class="price"> $ 1028.20 </div>
													<div> 
														<a class="edit-button"> View Record </a>
													</div>
												</div>
											</td>

											<td style="padding:20px;">
												<div class="home-block">
													<div> Total Topup Today </div>
													<div class="price"> $ 628.20 </div>
													<div> 
														<a class="edit-button"> View Record </a>
													</div>
												</div>
											</td>

											<td style="padding:20px;">
												<div class="home-block">
													<div> Total Account Register </div>
													<div class="price"> 45 </div>
												</div>
											</td>
										</tr>
									</table>
								</div>
							<?php
							}else if($_SESSION ['login_user_type_id']==2)
							{
								$login_user_type_id = $_SESSION ['login_user_type_id'];
								$login_user_id = $_SESSION ['login_user_id'];
								$reportbol = new reportbol();
								$rResult= $reportbol->get_student_by_loginusertype($login_user_type_id,$login_user_id);
								$rCount = $rResult->rowCount();
								if($rCount > 0)
								{
									echo "<div class='frm'>			
										<select id='sel_student_id' name='sel_student_id' onChange='find_current_card_amount();'>";
									while($row=$rResult->getNext())
									{
										echo "<option value='".$row['participant_id']."'>".$row['participant_enroll_no']."</option>";
									}
									echo "</select></div>";
									
									echo "<div class='frm' style='border:1px solid lightgray;padding-left: 10px;'>";
									echo "<label>Family Value</label><br/>";
									echo "<label id='current_card_amount' name='current_card_amount' style='font-size: 35px;font-weight: bold;'></label>";
									echo "</div>";
									
									echo "<div class='frm' style='background:black;padding-left: 10px;'><a href='under_construction.php'><label style='color:white;font-size: 22px;'>TOP UP NOW</label><br/><label style='color:yellow;font-size: 13px;'>Accept Visa & Master Card</label></a>";
									echo "</div>";
									
									echo "<div class='frm' style='border:1px solid lightgray;padding-left: 10px;'>";
									echo "<label><a href='pre_order_report.php' style='color:blue;'>Pre Order Now for your kids</a></label>";
									echo "</div>";
																	
									//Table for Purchase History Report
									echo '<div class="cleaner"></div>';
									echo "<div id='purchase' style='padding-left: 150px;'></div>";
									//End of Table for Purchase History Report
									
									//Table for TopUp Report
									echo '<div class="cleaner"></div>';
									echo "<div id='topup' style='padding-left: 150px;'></div>";
									//End of Table for TopUp Report
								}
								else
								{
									echo "<div class='frm' style='border:1px solid lightgray;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;'>";
									echo "<h5>You do not have account yet.</h5><label style='font-size: 13px;'>You can <a href='participant_list.php' style='color: blue;'><u>Register</u></a>.</label>";
									echo "</div>";
								}
								echo "<div class='frm' style='border:1px solid lightgray;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;'>";
								echo "<h5>Contact Us</h5><label style='font-size: 13px;'>For any difficulties or question you might have regarding our services,please drop us a email or contact us with the number below:</label>
								<br/><br/><h5>Email</h5><label style='font-size: 13px;'>helpdesk@compasspoint.sg</label>
								<br/><br/><h5>Compass Point Canteen</h5><label style='font-size: 13px;'>+6554546789</label>";
								echo "</div>";
							}
							else if($_SESSION ['login_user_type_id']==3)
								echo "<div style='font-size:19px;'>Welcome to Food Card Portal Staff Control Page </br></br><img src='images/staff_Home_img_1.jpg' alt='Admin_Home_img_1'></div>";
						?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	
<?php
	include('library/closedb.php');
	include("footer.php");
?>

<style>
	.home-block{
		background:#eee;
		padding:40px;
		height:120px;
	}
	.price{
		font-size:36px;
		font-weight:600;
		margin-top:10px;
		margin-bottom:10px;
	}
</style>