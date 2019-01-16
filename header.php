<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	$localizationbol= new localizationbol();
	
	//localization
	$localizedresult=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localizedresult->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		
		<title>Butterfly Food Card System</title>
		
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<?php header('Content-type: text/html; charset=utf-8'); ?>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="style/dataTables.jqueryui.css" type="text/css">
		<link href="style/ufd-base.css" rel="stylesheet" type="text/css" />
		<link href="style/plain/plain.css" rel="stylesheet" type="text/css" />
		<link href="style/mystyle.css" media="all" rel="stylesheet" type="text/css" />	
		<link href="style/fixfooter.css" media="all" rel="stylesheet" type="text/css" /><!-- This is the stylesheet that makes the footer stick. -->
		<link href="style/jquery-ui.css" type="text/css" rel="stylesheet" />
		<link href="style/tablegrid_ui.css" rel="stylesheet" type="text/css">
		<link href="style/multiple-select.css" rel="stylesheet" type="text/css">
		<link href="style/jquery.datetimepicker.css" rel="stylesheet" type="text/css">
		
		<!-- This conditional is for IE8 and IE6 and earlier- 8 needs that display:table -->
		<!--[if !IE 7]>
			<style type="text/css">
				#wrap {
					display: table;
					height: 100%
				}
			</style>
		<![endif]-->

		<!--[if IE 6]>
		<link href="ie.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		
		<link rel="icon" href="images/logo.jpg"/>
		<script type="text/javascript" src="javascript/jquery.min.js"></script>
		<script type="text/javascript" src="javascript/jquery-ui.min.js"></script>
		<script type="text/javascript" src="javascript/animatedcollapse.js"></script>
		<script type="text/javascript" src="javascript/general.js?1"></script>
		<script type="text/javascript" src="javascript/jquery.cookie.js"></script>
		<script type="text/javascript" src="javascript/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="javascript/dataTables.fnStandingRedraw.js"></script>
		<script type="text/javascript" src="javascript/jquery.simplemodal.1.4.4.min.js"></script>
		<script type="text/javascript" src="javascript/jquery.dataTables.editable.js"></script>
		<script type="text/javascript" src="javascript/jquery.jeditable.js"></script>
		<script type="text/javascript" src="javascript/json2.js"></script>
		<script type="text/javascript" language="javascript" src="javascript/highlight.js"></script>
		<script type="text/javascript" language="javascript" src="javascript/superfish.js"></script>
		<script type="text/javascript" language="javascript" src="javascript/supersubs.js"></script>		
		<script type="text/javascript" language="javascript" src="javascript/jquery.multiple.select.js"></script>	
		<script type="text/javascript" src="javascript/jquery.ui.ufd.js"></script> 	
		<script type="text/javascript" src="javascript/jquery.validate.min.js"></script>

		<!-- jQuery Modal -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
		
		<script type="text/javascript">
		$(document).ready(function() {
			var example = $('#example').superfish({
				//add options here if required
			});
			
			$("#nav ul li").click(function(){
				localStorage.setItem("current_page", $(this).html());
			});
		});
		</script>
</head>
	
<body>

<div id="wrap">  <!-- This wrap div needs to encompass everything except the footer div at bottom -->

	<div id="header">
	
		<div id="header_container"> <!-- this extra div is just centering the fixed width area of the header content -->
			
			<div id="logo">
		
			</div>
			
			<div id="topmenu">
				<div style="text-align: left; position:absolute; top: 0px; color:blue;">
					<?php echo $_SESSION ['login_user_email']; ?>
				</div>
				<div style="margin-top: 20px;">
					 <a href="userlogout.php" ><?php echo $localized_home_data['logout']; ?></a>&nbsp; | &nbsp; <a href="change_password.php" ><?php echo $localized_home_data['change_pw']; ?></a>
				</div>
			</div>
		</div>			
		<?php
			if($_SESSION ['login_user_type_id']==1)
				$nav_class="style='background-color: #2c2c2c ');'";
			else if($_SESSION ['login_user_type_id']==2)//parent
				$nav_class="style='background: #011880;'";
			else if($_SESSION ['login_user_type_id']==3)
				$nav_class="style='background: #011880;'";
		?>
		<div id="nav" <?php echo $nav_class; ?> >
			<ul  class="sf-menu" id="example">			
				<li><a href="index.php" id="go_to_home"><?php echo $localized_home_data['home']; ?></a></li>
				<?php if($_SESSION ['login_user_type_id']==1){ ?><!--admin user type-->
					<li><a href="card_detail.php" >CARD DETAIL</a></li>
					<!-- <li><a href="#" >Data Entry Form</a>
						<ul style='display: none;' class='dropdown'>
							<li><a href="user_list.php" ><?php echo $localized_home_data['user']; ?></a></li>
							<li><a href="user_type_list.php" ><?php echo $localized_home_data['user_type']; ?></a></li>
							<li><a href="organization_list.php" ><?php echo $localized_home_data['organization']; ?></a></li>
							<li><a href="card_status_list.php" ><?php echo $localized_home_data['card_status']; ?></a></li>
							<li><a href="food_allergy_list.php" ><?php echo $localized_home_data['food_allergy']; ?></a></li>
							<li><a href="category_type_list.php" ><?php echo $localized_home_data['category_type']; ?></a></li>
							<li><a href="meal_status_list.php" ><?php echo $localized_home_data['meal_status']; ?></a></li>
							<li><a href="items_list.php" >Food Items</a></li>
						</ul>
					</li> -->
					<li><a href="user.php" ><?php echo "Users"; ?></a></li>
					<li><a href="refund.php" ><?php echo "Refund"; ?></a></li>

					<!-- <li><a href="staff_list.php" ><?php echo $localized_home_data['staff']; ?></a></li> -->
				<?php } ?>
				
				<?php if($_SESSION ['login_user_type_id']==2 || $_SESSION ['login_user_type_id']==3){ ?><!--admin user type or parent user type-->
					<li><a href="participant_list.php" >Card</a></li>
				<?php } ?>
				
				<?php if($_SESSION ['login_user_type_id']==1){ ?><!--admin user type or organizer user type-->
					<!-- <li><a href="predefine_participant_list.php">Student(Predefine)</a></li> -->
				<?php } ?>
				
				<?php if($_SESSION ['login_user_type_id']==2 || $_SESSION ['login_user_type_id']==3){ ?><!--for parentuser type-->
					<!-- <li><a href="pre_order.php" ><?php echo $localized_home_data['pre_order']; ?></a></li> -->
					<li><a href="topup_paypal.php" >Topup</a></li>
				<?php } ?>
				
				<?php if($_SESSION ['login_user_type_id']==1 || $_SESSION ['login_user_type_id']==3){ ?>
					<!-- <li><a href="#" >Schedule</a>
					<ul style='display: none;' class='dropdown'>
						<li><a href="order_delivery_schedule.php">Delivery Schedule</a></li>
						<li><a href="order_schedule_summary.php">Order Schedule Summary</a></li>
						<li><a href="order_schedule_detail.php">Order Schedule Detail</a></li>
					</ul>
					</li> -->
				<?php } ?>
				
				<?php //if($_SESSION ['login_user_type_id']==3){ ?><!--for staff user type-->
					<!-- <li><a href="topup_manual.php" >Topup(Cash)</a></li>
					<li><a href="selforder_redemption.php" ><?php echo $localized_home_data['redumption']; ?></a></li> -->
				<?php // } ?>
				
				<li><a href="#" ><?php echo $localized_home_data['reports']; ?></a>
					<ul style='display: none;' class='dropdown'>
						<?php if($_SESSION ['login_user_type_id']==1){ ?><!--admin user type-->
							<!-- <li><a href="card_report.php" ><?php echo $localized_home_data['card_report_title']; ?></a></li>
							<li><a href="card_report_new.php" >New Card Report</a></li> -->
						<?php } ?>
						
						<!-- <li><a href="pre_order_report.php" ><?php echo $localized_home_data['pre_order_report_title']; ?></a></li> -->
						
						<?php if($_SESSION ['login_user_type_id']==1){ ?><!--admin user type or parent/organizer user type-->
							<li><a href="transaction_report.php" ><?php echo "Spending Report"; ?></a></li>
							<li><a href="topup_report.php" ><?php echo "Topup Report"; ?></a></li>
							<li><a href="users_report.php" ><?php echo "Users Report"; ?></a></li>
							<li><a href="refund_report.php" ><?php echo "Refund Report"; ?></a></li>
							<li><a href="summary_report.php" ><?php echo "Summary Report"; ?></a></li>
							<!-- <li><a href="redemption_report.php" ><?php echo $localized_home_data['redemption_report_title']; ?></a></li> -->
						<?php } ?>

						<?php if($_SESSION ['login_user_type_id']==2 || $_SESSION ['login_user_type_id']==3){ ?><!--admin user type or parent/organizer user type-->
							<li><a href="spending_history.php" ><?php echo "Spending History"; ?></a></li>
							<li><a href="topup_history.php" ><?php echo "Topup History"; ?></a></li>
							<!-- <li><a href="redemption_report.php" ><?php echo $localized_home_data['redemption_report_title']; ?></a></li> -->
						<?php } ?>
					</ul>
				</li>
				<?php if($_SESSION ['login_user_type_id']==1){ ?><!--admin user type -->
				<li><a href="#" ><?php echo "SETTINGS"; ?></a>
					<ul style='display: none;' class='dropdown'>
						<li><a href="topup_online_setting.php" ><?php echo "Online Topup Settings"; ?></a></li>
					</ul>
				</li>
				<?php } ?>
			</ul>
		</div>
		
		
	</div><!--Close the header div here-->
	
	<!--Start the content area div here-->
	<div id="main"> <!-- Inside this main div we are floating the content to the left and the sidebar to the right  -->

		<div id="content"> 

<?php if($_SESSION ['login_user_type_id']==1){ ?>
	<style>
		#nav a:hover {
			color: white;
			/* background: url('../images/nav_hover.gif') bottom center no-repeat; */
			background: #b12226;
		}
		#nav a:visited {
			color: white;
			/* background: url('../images/nav_hover.gif') bottom center no-repeat; */
			background: #b12226;
		}
	</style>
<?php } ?>

<?php if($_SESSION ['login_user_type_id']==2 || $_SESSION ['login_user_type_id']==3){ ?>
	<style>
		#nav a:hover {
			color: white;
			/* background: url('../images/nav_hover.gif') bottom center no-repeat; */
			background: #b12226;
		}
		#nav a:visited {
			color: white;
			/* background: url('../images/nav_hover.gif') bottom center no-repeat; */
			background: #b12226;
		}
	</style>
<?php } ?>

<script>
	function get_date(date){
		d = date;
		d = d.split("/");
		return d[2]+'-'+d[0]+'-'+d[1];
	}
	$(document).ready(function(){
		$("#go_to_home").html("Home");
	});
</script>