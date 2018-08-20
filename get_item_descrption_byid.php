<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	require_once('userauth.php');
	
	$setupbol = new setupbol();
	$orderbol = new orderbol();
	$localizationbol= new localizationbol();
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('pre_order',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
	$now_date_time = date('Y-m-d H:i:s');
	
	if(isset($_POST['item_id']))
	{
		$item_id=(int)$_POST['item_id'];
		$Rresult=$setupbol->get_item_byid($item_id);
		$aRow = $Rresult->getNext();
		$item_name=htmlspecialchars($aRow['item_name']);
		$item_desc=htmlspecialchars($aRow['item_description']);
		$item_price=htmlspecialchars($aRow['item_price']);
		$item_image_name=htmlspecialchars($aRow['item_image_name']);
		if($item_image_name==null && $item_image_name=='')
			$item_image_name='item_logo_default.jpg';
		$item_image_name='ItemImages/'.$item_image_name;
		
		echo "<span>Ingredient:</span><br/><br/>".$item_desc."<br/><br/>$".$item_price."(unit price)<br/><br/><img src='".$item_image_name."' width='100px;' height='100px;'/>";
	}
	if(isset($_POST['p_id']))
	{
		$p_id=(int)$_POST['p_id'];
		$Presult=$orderbol->get_pname_byp_id($p_id);
		$pRow = $Presult->getNext();
		$participant_name=htmlspecialchars($pRow['participant_name']);
		
		echo "<span>Name:</span>".$participant_name;
	}
?>