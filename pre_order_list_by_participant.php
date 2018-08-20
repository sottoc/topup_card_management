<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');

	$login_user_id = $_SESSION ['login_user_id']; //organizer or parent_id
	$orderbol = new orderbol();

	$array = array();
	$pid_str='';
	$presult = $orderbol->get_participant_by_organizerid($login_user_id);
	while($prows = $presult->getNext())
	{
		if($pid_str=='')
			$pid_str = $prows['participant_id'];
		else
			$pid_str .= ','.$prows['participant_id'];
	}
	$preorder_result = $orderbol->get_preorder_by_pid($pid_str);
	$show_record_count=0;
	while($po_row = $preorder_result->getNext())
	{	
		$id = $po_row['pre_order_id'];
		
		$participant_id = $po_row['pid'];
		$participant_name = $po_row['participant_name'];		
		if(strlen($participant_name)>15)
			$participant_name = mb_substr($participant_name, 0, 15, 'UTF-8')."...";
			
		$participant_enroll_no = $po_row['participant_enroll_no'];		
		if(strlen($participant_enroll_no)>15)
			$participant_enroll_no = mb_substr($participant_enroll_no, 0, 15, 'UTF-8')."...";
			
		$preorder_date = $po_row['preorder_date'];
		if (strpos($preorder_date,' ') !== false) {
			$date = explode(" ", $preorder_date);
			$formatted_preorder_date = $date[0];
		}
		
		$item_id = $po_row['item_id'];
		$item_name = $po_row['item_name'];
		if(strlen($item_name)>15)
			$item_name = mb_substr($item_name, 0, 15, 'UTF-8')."...";
		
		$category_type_id = $po_row['category_type_id'];
		$category_type_name = $po_row['category_type_name'];
		if(strlen($category_type_name)>15)
			$category_type_name = mb_substr($category_type_name, 0, 15, 'UTF-8')."...";
		
		$meal_status_id = $po_row['meal_status_id'];
		$meal_status_name = $po_row['meal_status_name'];
		if($meal_status_id==1)
			$short_term_meal_status_name='F';
		else
			$short_term_meal_status_name='NF';
		
		$qty = $po_row['qty'];
		
		//$title = $participant_name.'-'.$item_name.'('.$category_type_name.'-'.$short_term_meal_status_name.')';
		$title = $participant_enroll_no.'('.$category_type_name.'-'.$item_name.')';
		
		$array[]=	array("id"=>"$id","title" => "$title", "start" => "$formatted_preorder_date","formatted_preorder_date"=>"$formatted_preorder_date","item_id"=>"$item_id","participant_id"=>"$participant_id",'item_name' => "$item_name","category_type_id"=>"$category_type_id","category_type_name"=>"$category_type_name","meal_status_id"=>"$meal_status_id","meal_status_name"=>"$meal_status_name","short_term_meal_status_name"=>"$short_term_meal_status_name","qty"=>"$qty");
	}
	echo json_encode($array);
	
?>