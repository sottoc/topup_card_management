<?php
class reportdal{
	function get_pre_order_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$id_str='';
		if($_SESSION ['login_user_type_id']==2)
		{
			$login_user_id = $_SESSION ['login_user_id'];
			$login_user_type_id = $_SESSION ['login_user_type_id'];
			$qry = "SELECT * FROM tbl_participant p
			left join tbl_organizer o on p.organizer_id=o.organizer_id
			left join tbl_user u on p.organizer_id=u.user_id 
			where o.user_id=:login_user_id";
			$res = execute_query($qry,array(':login_user_id'=>$login_user_id)) or die ("query fail.");
			$res2 = new readonlyresultset($res);
			while($row = $res2->getNext())
			{
				if($id_str=='')
					$id_str="'".$row['participant_id']."'";
				else
					$id_str=$id_str.",'".$row['participant_id']."'";
			}
		}
		//echo "id_str=".$id_str;
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		
		$query = "SELECT SQL_CALC_FOUND_ROWS p.participant_enroll_no,p.participant_name,porders.preorder_date,i.item_name,porders.qty,i.item_price as 
				item_unit_price,ctype.category_type_name,mstatus.meal_status_name,c.card_number
				from tbl_pre_orders  porders 
				left join tbl_participant p on porders.participant_id=p.participant_id
				left join tbl_card  c on p.participant_id=c.participant_id
				left join tbl_items i on porders.item_id=i.item_id
				left join tbl_category_type ctype on porders.category_type_id=ctype.category_type_id
				left join tbl_meal_status mstatus on porders.meal_status_id=mstatus.meal_status_id";
		$query .= $cri_str;
		if(empty($param) && $id_str!='')
			$query .= " AND p.participant_id in ($id_str)";
		$query .= " group by porders.preorder_date,p.participant_id,ctype.category_type_id,i.item_id";
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		$result = execute_query($query,$param) or die ("get_pre_order_report query fail.");
			
		return new readonlyresultset($result);
	}
	function get_student_by_loginusertype($login_user_type_id,$login_user_id)
	{
		//echo "login_user_type_id=".$login_user_type_id;
		if($login_user_type_id==1 || $login_user_type_id==3)
		{
			$query = "SELECT * FROM tbl_participant";
			$result = execute_query($query) or die('get_student_by_loginusertype query fails');
		}
		else
		{
			$query = "SELECT * FROM tbl_participant p
			left join tbl_organizer o on p.organizer_id=o.organizer_id
			left join tbl_user u on p.organizer_id=u.user_id 
			where o.user_id=:login_user_id";
			$result = execute_query($query,array(':login_user_id'=>$login_user_id)) or die('get_student_by_loginusertype query fails');
		}
		return new readonlyresultset($result);
	}
	function get_redemption_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$id_str='';
		if($_SESSION ['login_user_type_id']==2)
		{
			$login_user_id = $_SESSION ['login_user_id'];
			$login_user_type_id = $_SESSION ['login_user_type_id'];
			$qry = "SELECT * FROM tbl_participant p
			left join tbl_organizer o on p.organizer_id=o.organizer_id
			left join tbl_user u on p.organizer_id=u.user_id 
			where o.user_id=:login_user_id";
			$res = execute_query($qry,array(':login_user_id'=>$login_user_id)) or die ("query fail.");
			$res2 = new readonlyresultset($res);
			while($row = $res2->getNext())
			{
				if($id_str=='')
					$id_str="'".$row['participant_id']."'";
				else
					$id_str=$id_str.",'".$row['participant_id']."'";
			}
		}
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		
		$query = "SELECT SQL_CALC_FOUND_ROWS *
				from tbl_redemption  r 
				left join tbl_transaction t on r.redemption_id=t.redempation_id
				left join tbl_participant p on p.participant_id=t.participant_id";
		$query .= $cri_str;
		if(empty($param) && $id_str!='')
			$query .= " AND t.participant_id in ($id_str)";
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		$result = execute_query($query,$param) or die ("get_redemption_report query fail.");	
		return new readonlyresultset($result);
	}
	function get_transaction_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$id_str='';
		if($_SESSION ['login_user_type_id']==2)
		{
			$login_user_id = $_SESSION ['login_user_id'];
			$login_user_type_id = $_SESSION ['login_user_type_id'];
			$qry = "SELECT * FROM tbl_participant p
			left join tbl_organizer o on p.organizer_id=o.organizer_id
			left join tbl_user u on p.organizer_id=u.user_id 
			where o.user_id=:login_user_id";
			$res = execute_query($qry,array(':login_user_id'=>$login_user_id)) or die ("query fail.");
			$res2 = new readonlyresultset($res);
			while($row = $res2->getNext())
			{
				if($id_str=='')
					$id_str="'".$row['participant_id']."'";
				else
					$id_str=$id_str.",'".$row['participant_id']."'";
			}
		}
		
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		
		// $query = "SELECT SQL_CALC_FOUND_ROWS *
		// 		FROM `tbl_transaction` t
		// 		left join tbl_topup top on t.topup_id=top.topup_id
		// 		left join tbl_redemption r on t.redempation_id=r.redemption_id
		// 		left join tbl_participant p on p.participant_id=t.participant_id";
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM `tbl_food_bill_records` t left join tbl_card1 top on t.card_id=top.Card_ID";
		$query .= $cri_str;
		
		if(empty($param) && $id_str!='')
			$query .= " AND t.participant_id in ($id_str)";
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		
		$result = execute_query($query,$param) or die ("get_redemption_report query fail.");	
		return new readonlyresultset($result);
	}
	function get_summary_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$id_str='';
		if($_SESSION ['login_user_type_id']==2)
		{
			$login_user_id = $_SESSION ['login_user_id'];
			$login_user_type_id = $_SESSION ['login_user_type_id'];
			$qry = "SELECT * FROM tbl_participant p
			left join tbl_organizer o on p.organizer_id=o.organizer_id
			left join tbl_user u on p.organizer_id=u.user_id 
			where o.user_id=:login_user_id";
			$res = execute_query($qry,array(':login_user_id'=>$login_user_id)) or die ("query fail.");
			$res2 = new readonlyresultset($res);
			while($row = $res2->getNext())
			{
				if($id_str=='')
					$id_str="'".$row['participant_id']."'";
				else
					$id_str=$id_str.",'".$row['participant_id']."'";
			}
		}
		
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		
		// $query = "SELECT SQL_CALC_FOUND_ROWS *
		// 		FROM `tbl_transaction` t
		// 		left join tbl_topup top on t.topup_id=top.topup_id
		// 		left join tbl_redemption r on t.redempation_id=r.redemption_id
		// 		left join tbl_participant p on p.participant_id=t.participant_id";
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM `tbl_summary_record`";
		$query .= $cri_str;
		
		if(empty($param) && $id_str!='')
			$query .= " AND t.participant_id in ($id_str)";
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		
		$result = execute_query($query,$param) or die ("get_redemption_report query fail.");	
		return new readonlyresultset($result);
	}
	function get_spending_history($offset, $rpage ,$sorting,$cri_arr)
	{
		$id_str='';
		
		//--- get family code of user ----
		$login_user_id = $_SESSION ['login_user_id'];
		$query = "SELECT `family_code` FROM `tbl_user`";
		$param = " WHERE user_id='".$login_user_id."'";
		require_once("api/api_common.php");
		$result = $conn->query($query.$param);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$family_code = $row['family_code'];
			}
		}
		//---------------- end --------------
		
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		
		// $query = "SELECT SQL_CALC_FOUND_ROWS *
		// 		FROM `tbl_transaction` t
		// 		left join tbl_topup top on t.topup_id=top.topup_id
		// 		left join tbl_redemption r on t.redempation_id=r.redemption_id
		// 		left join tbl_participant p on p.participant_id=t.participant_id";
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM `tbl_food_bill_records` t left join tbl_card1 top on t.card_id=top.Card_ID WHERE top.Family_code='".$family_code."'";
		$query .= $cri_str;
		
		if(empty($param) && $id_str!='')
			$query .= " AND t.participant_id in ($id_str)";
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		
		$result = execute_query($query,$param) or die ("get_redemption_report query fail.");	
		return new readonlyresultset($result);
	}
	function get_topup_history($offset, $rpage ,$sorting,$cri_arr)
	{
		$id_str='';
		// if($_SESSION ['login_user_type_id']==2)
		// {
		// 	$login_user_id = $_SESSION ['login_user_id'];
		// 	$login_user_type_id = $_SESSION ['login_user_type_id'];
		// 	$qry = "SELECT * FROM tbl_participant p
		// 	left join tbl_organizer o on p.organizer_id=o.organizer_id
		// 	left join tbl_user u on p.organizer_id=u.user_id 
		// 	where o.user_id=:login_user_id";
		// 	$res = execute_query($qry,array(':login_user_id'=>$login_user_id)) or die ("query fail.");
		// 	$res2 = new readonlyresultset($res);
		// 	while($row = $res2->getNext())
		// 	{
		// 		if($id_str=='')
		// 			$id_str="'".$row['participant_id']."'";
		// 		else
		// 			$id_str=$id_str.",'".$row['participant_id']."'";
		// 	}
		// }
		
		//--- get family code of user ----
		$login_user_id = $_SESSION ['login_user_id'];
		$query = "SELECT `family_code` FROM `tbl_user`";
		$param = " WHERE user_id='".$login_user_id."'";
		require_once("api/api_common.php");
		$result = $conn->query($query.$param);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$family_code = $row['family_code'];
			}
		}
		//---------------- end --------------
		
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		
		// $query = "SELECT SQL_CALC_FOUND_ROWS *
		// 		FROM `tbl_transaction` t
		// 		left join tbl_topup top on t.topup_id=top.topup_id
		// 		left join tbl_redemption r on t.redempation_id=r.redemption_id
		// 		left join tbl_participant p on p.participant_id=t.participant_id";
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM `tbl_food_topup_records` WHERE family_code='".$family_code."'";
		$query .= $cri_str;
		
		if(empty($param) && $id_str!='')
			$query .= " AND t.participant_id in ($id_str)";
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		
		$result = execute_query($query,$param) or die ("get_redemption_report query fail.");	
		return new readonlyresultset($result);
	}
	function get_user_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$id_str='';
		if($_SESSION ['login_user_type_id']==2)
		{
			$login_user_id = $_SESSION ['login_user_id'];
			$login_user_type_id = $_SESSION ['login_user_type_id'];
			$qry = "SELECT * FROM tbl_participant p
			left join tbl_organizer o on p.organizer_id=o.organizer_id
			left join tbl_user u on p.organizer_id=u.user_id 
			where o.user_id=:login_user_id";
			$res = execute_query($qry,array(':login_user_id'=>$login_user_id)) or die ("query fail.");
			$res2 = new readonlyresultset($res);
			while($row = $res2->getNext())
			{
				if($id_str=='')
					$id_str="'".$row['participant_id']."'";
				else
					$id_str=$id_str.",'".$row['participant_id']."'";
			}
		}
		
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		
		// $query = "SELECT SQL_CALC_FOUND_ROWS *
		// 		FROM `tbl_transaction` t
		// 		left join tbl_topup top on t.topup_id=top.topup_id
		// 		left join tbl_redemption r on t.redempation_id=r.redemption_id
		// 		left join tbl_participant p on p.participant_id=t.participant_id";
		
		//$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_user u LEFT JOIN tbl_family_code_amount fa on u.family_code=fa.family_code";
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_family_code_amount";
		$query .= $cri_str;
		
		if(empty($param) && $id_str!='')
			$query .= " AND t.participant_id in ($id_str)";
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		
		$result = execute_query($query,$param) or die ("get_redemption_report query fail.");	
		return new readonlyresultset($result);
	}
	function get_refund_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$id_str='';
		if($_SESSION ['login_user_type_id']==2)
		{
			$login_user_id = $_SESSION ['login_user_id'];
			$login_user_type_id = $_SESSION ['login_user_type_id'];
			$qry = "SELECT * FROM tbl_participant p
			left join tbl_organizer o on p.organizer_id=o.organizer_id
			left join tbl_user u on p.organizer_id=u.user_id 
			where o.user_id=:login_user_id";
			$res = execute_query($qry,array(':login_user_id'=>$login_user_id)) or die ("query fail.");
			$res2 = new readonlyresultset($res);
			while($row = $res2->getNext())
			{
				if($id_str=='')
					$id_str="'".$row['participant_id']."'";
				else
					$id_str=$id_str.",'".$row['participant_id']."'";
			}
		}
		
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		
		// $query = "SELECT SQL_CALC_FOUND_ROWS *
		// 		FROM `tbl_transaction` t
		// 		left join tbl_topup top on t.topup_id=top.topup_id
		// 		left join tbl_redemption r on t.redempation_id=r.redemption_id
		// 		left join tbl_participant p on p.participant_id=t.participant_id";
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_refund_record r LEFT JOIN tbl_user u on r.issues_person=u.user_id";
		$query .= $cri_str;
		
		if(empty($param) && $id_str!='')
			$query .= " AND t.participant_id in ($id_str)";
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		
		$result = execute_query($query,$param) or die ("get_redemption_report query fail.");	
		return new readonlyresultset($result);
	}
	function get_card_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		$query = "SELECT SQL_CALC_FOUND_ROWS c.card_number,c.card_description,cs.card_status_name,c.card_issued_datetime,c.card_expired_datetime,p.participant_name,p.participant_enroll_no,
				u.user_name as parent_name,org.org_name FROM tbl_card c left join tbl_participant p on c.participant_id = p.participant_id
				left join tbl_organizer o on o.organizer_id = p.organizer_id
				left join tbl_user u on o.user_id = u.user_id
				left join tbl_organization org on org.org_id = p.org_id
				left join tbl_card_status cs on c.card_status_id = cs.card_status_id";		
		$query .= $cri_str;
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		//echo $query;exit();
		$result = execute_query($query,$param) or die ("get_card_report query fail.");
			
		return new readonlyresultset($result);	
	}
	function get_card_report_new($offset, $rpage ,$sorting,$cri_arr)
	{
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		$query = "SELECT `User_code`, `Family_code`, `Card_ID`, `Last_name`, `First_name`, `Level`, `Card_value`, `Card_status`, `Username`, `Password` FROM `tbl_card1`";		
		$query .= $cri_str;
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		$result = execute_query($query,$param) or die ("get_card_report query fail.");
			
		return new readonlyresultset($result);	
	}
	function get_prepaid_card($offset, $rpage ,$sorting,$cri_arr)
	{
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		$query = "SELECT * FROM `tbl_card1` c LEFT JOIN tbl_family_code_amount fa on c.Family_code=fa.family_code";		
		$query .= $cri_str;
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		$result = execute_query($query,$param) or die ("get_card_report query fail.");
			
		return new readonlyresultset($result);	
	}
	function get_children_list($offset, $rpage ,$sorting,$cri_arr)
	{
		//--- get family code of user ----
		$login_user_id = $_SESSION ['login_user_id'];
		$query = "SELECT `family_code` FROM `tbl_user`";
		$param = " WHERE user_id='".$login_user_id."'";
		require_once("api/api_common.php");
		$result = $conn->query($query.$param);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$family_code = $row['family_code'];
			}
		}
		//---------------- end --------------

		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		$query = "SELECT * FROM `tbl_card1` c LEFT JOIN tbl_family_code_amount fa on c.Family_code=fa.family_code WHERE c.Family_code='".$family_code."'";		
		$query .= $cri_str;
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		$result = execute_query($query,$param) or die ("get_card_report query fail.");
			
		return new readonlyresultset($result);	
	}
	function get_topup_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$id_str='';
		if($_SESSION ['login_user_type_id']==2)
		{
			$login_user_id = $_SESSION ['login_user_id'];
			$login_user_type_id = $_SESSION ['login_user_type_id'];
			$qry = "SELECT * FROM tbl_participant p
			left join tbl_organizer o on p.organizer_id=o.organizer_id
			left join tbl_user u on p.organizer_id=u.user_id 
			where o.user_id=:login_user_id";
			$res = execute_query($qry,array(':login_user_id'=>$login_user_id)) or die ("query fail.");
			$res2 = new readonlyresultset($res);
			while($row = $res2->getNext())
			{
				if($id_str=='')
					$id_str="'".$row['participant_id']."'";
				else
					$id_str=$id_str.",'".$row['participant_id']."'";
			}
		}
		
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		
		// $query = "SELECT SQL_CALC_FOUND_ROWS *
		// 		FROM `tbl_transaction` t
		// 		left join tbl_topup top on t.topup_id=top.topup_id
		// 		left join tbl_redemption r on t.redempation_id=r.redemption_id
		// 		left join tbl_participant p on p.participant_id=t.participant_id";
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_food_topup_records";
		$query .= $cri_str;
		
		if(empty($param) && $id_str!='')
			$query .= " AND t.participant_id in ($id_str)";
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		
		$result = execute_query($query,$param) or die ("get_redemption_report query fail.");	
		return new readonlyresultset($result);
	}
	function get_order_schedule_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$cri_str = $cri_arr[0] . " ) as main GROUP BY participant_enroll_no , category_type_name";
		$param = $cri_arr[1] ;
		//print_r($param);exit();
		$query = "SELECT pre_order_id,preorder_date,participant_enroll_no,participant_name , category_type_name , GROUP_CONCAT( item_name ) as item_name 
					from ( SELECT p.pre_order_id,p.participant_id,p.preorder_date,p.item_id,p.qty,p.category_type_id, p.meal_status_id ,
					a.participant_enroll_no,a.participant_name,m.meal_status_name,c.category_type_name,i.item_name FROM tbl_pre_orders as p 
					Left Join tbl_participant as a on p.participant_id = a.participant_id 
					LEFT JOIN tbl_category_type as c on c.category_type_id = p.category_type_id 
					LEFT JOIN tbl_items as i on i.item_id = p.item_id 
					LEFT JOIN tbl_meal_status as m on m.meal_status_id =p.meal_status_id ";
		//WHERE p.meal_status_id = '2' AND p.preorder_date BETWEEN '2016-11-29 00:00:00' AND '2016-11-29 00:00:00' ) as main 
		
		$query .= $cri_str;
		$query .=$sorting;
		//print_r("query " . $query);exit();
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		//echo $query;exit();
		$result = execute_query($query,$param) or die ("get_order_schedule_report query fail.");
			
		return new readonlyresultset($result);	
	}
	
	function checkmealstatustofinish($pre_order_id)
	{
		$query="UPDATE tbl_pre_orders SET meal_status_id = '1' WHERE pre_order_id=:pre_order_id";
		//print_r("Update Query" . $query);
		//exit();
		$result = execute_non_query($query, array(':pre_order_id' => $pre_order_id));
		return $result;
	}
	function get_order_schedule_summary_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$cri_str = $cri_arr[0] . " GROUP BY p.preorder_date";
		$param = $cri_arr[1];
		//print_r($param);exit();
		$query = "SELECT DATE_FORMAT(p.preorder_date,'%d-%m-%Y - %W') as dd,p.preorder_date
					FROM tbl_pre_orders AS p ";
				
		$query .= $cri_str;
		$query .=$sorting;
		//print_r("query " . $query);exit();
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		
		$result = execute_query($query,$param) or die ("get_order_schedule_summary_report query fail.");			
		return new readonlyresultset($result);	
	}
	
	function get_order_schedule_table_report($preorder_date)
	{
		$query = "SELECT Sum(p.qty) as total,i.item_name,p.preorder_date
					FROM tbl_pre_orders AS p LEFT JOIN tbl_meal_status AS m ON p.meal_status_id = m.meal_status_id
					LEFT JOIN tbl_category_type AS c on p.category_type_id = c.category_type_id
					LEFT JOIN tbl_items AS i ON p.item_id = i.item_id 
					WHERE p.meal_status_id = '2' And p.preorder_date = :preorder_date GROUP BY i.item_name,p.preorder_date";
		
		$result = execute_query($query,array(':preorder_date'=>$preorder_date)) or die ("get_order_schedule_table_report query fail.");
		return new readonlyresultset($result);
	}
	
	function get_order_scheduledetail_table_report($preorder_date)
	{
		$query = "SELECT preorder_date,main.participant_id,participant_enroll_no,participant_name,item_name ,total, meal_status_name, 
					category_type_name , others_allergy_food_description, GROUP_CONCAT(IFNull(f.food_allergy_name,'')) as food_allergy
					from (SELECT preorder_date,participant_id,participant_enroll_no,participant_name,item_name , SUM(qty) as total, meal_status_name, 
					category_type_name ,others_allergy_food_description
					from ( SELECT p.preorder_date, p.participant_id,a.participant_enroll_no ,a.participant_name,i.item_name,p.qty ,
					m.meal_status_name, c.category_type_name,a.others_allergy_food_description
					FROM tbl_pre_orders AS p LEFT JOIN tbl_participant as a ON p.participant_id = a.participant_id 
					LEFT JOIN tbl_meal_status AS m ON p.meal_status_id = m.meal_status_id 
					LEFT JOIN tbl_category_type AS c on p.category_type_id = c.category_type_id 
					LEFT JOIN tbl_items AS i ON p.item_id = i.item_id 
					WHERE p.meal_status_id = '2' And p.preorder_date = :preorder_date ) as minor 
					GROUP BY preorder_date, participant_id,participant_enroll_no,participant_name, item_name ) as main
					LEFT JOIN tbl_participant_food_allergy as fa ON fa.participant_id = main.participant_id
					LEFT JOIN tbl_food_allergy as f ON f.food_allergy_id = fa.food_allergy_id 
					GROUP BY preorder_date, participant_id,participant_enroll_no,participant_name, item_name  ";
		
		$result = execute_query($query,array(':preorder_date'=>$preorder_date)) or die ("get_order_scheduledetail_table_report query fail.");
		return new readonlyresultset($result);
	}
	
	function get_topup_history_report($participant_id,$all_data)
	{
		if ($_SESSION ['login_user_type_id']==2 || $_SESSION ['login_user_type_id']==1)
		{
			if($all_data)
			{
				$query = "SELECT SQL_CALC_FOUND_ROWS p.participant_enroll_no,p.participant_name,
				c.card_number,t.topup_amt,t.payment_type,t.pos_slip_id,u.user_name,
				tt.transaction_datetime,DATE_FORMAT(tt.transaction_datetime,'%d-%m-%Y') as dd, 
				DATE_FORMAT(tt.transaction_datetime,'%I:%i:%s %p') as tt FROM tbl_transaction tt
				LEFT JOIN tbl_participant p 
				on tt.participant_id = p.participant_id
				LEFT JOIN tbl_topup t on t.topup_id = tt.topup_id
				Left JOIN tbl_card c on c.card_id = tt.card_id
				Left JOIN tbl_organizer o on p.organizer_id = o.organizer_id
				Left JOIN tbl_user u on u.user_id = t.login_user_id
				WHERE p.participant_id = :participant_id AND tt.trans_type = 'topup'";
	
				$query .= "ORDER BY tt.transaction_datetime desc";	
			}
			else
			{
				$query = "SELECT SQL_CALC_FOUND_ROWS p.participant_enroll_no,p.participant_name,
				c.card_number,t.topup_amt,t.payment_type,t.pos_slip_id,u.user_name,
				tt.transaction_datetime,DATE_FORMAT(tt.transaction_datetime,'%d-%m-%Y') as dd, 
				DATE_FORMAT(tt.transaction_datetime,'%I:%i:%s %p') as tt FROM tbl_transaction tt
				LEFT JOIN tbl_participant p 
				on tt.participant_id = p.participant_id
				LEFT JOIN tbl_topup t on t.topup_id = tt.topup_id
				Left JOIN tbl_card c on c.card_id = tt.card_id
				Left JOIN tbl_organizer o on p.organizer_id = o.organizer_id
				Left JOIN tbl_user u on u.user_id = t.login_user_id
				WHERE p.participant_id = :participant_id AND tt.trans_type = 'topup'";
	
				$query .= "ORDER BY tt.transaction_datetime desc LIMIT 0,20";	
			
			}
			
			//echo $query;exit();
			$result = execute_query($query,array(':participant_id'=>$participant_id)) or die ("get_topup_history_report query fail.");
		}
		return new readonlyresultset($result);	
	}
	
	function get_purchase_history_report($participant_id,$all_data)
	{
		if ($_SESSION ['login_user_type_id']==2 || $_SESSION ['login_user_type_id']==1)
		{
			if($all_data)
			{
				$query = "SELECT participant_id,participant_enroll_no,participant_name , transaction_datetime, category_type_name, 
					sum(totalqty) as tqty , GROUP_CONCAT(concat(`item_name`,' (',`totalqty` , ')') separator ',') as item_name,
					 SUM( (item_price * preorderqty) + (item_price * canteenqty)) as price , DATE_FORMAT(transaction_datetime,'%d-%m-%Y') as dd, 
					DATE_FORMAT(transaction_datetime,'%I:%i:%s %p') as tt
					from ( SELECT p.participant_id,p.participant_name,p.participant_enroll_no,t.transaction_datetime,
					r.pre_order_id,r.participant_canteen_order_id,i.item_name , IFNull(o.qty ,0) as preorderqty , IFNull(c.qty,0) as canteenqty , i.item_price,
					g.category_type_name, IFNull(o.qty ,0) + IFNull(c.qty,0)  as totalqty
					from tbl_redemption r left join tbl_transaction t on r.redemption_id=t.redempation_id 
					left join tbl_participant p on p.participant_id=t.participant_id
					LEFT JOIN tbl_pre_orders as o on o.pre_order_id = r.pre_order_id 
					LEFT JOIN tbl_participant_canteen_orders as c on c.participant_canteen_order_id = r.participant_canteen_order_id
					LEFT JOIN tbl_category_type as g on g.category_type_id = o.category_type_id or g.category_type_id = c.category_type_id
					LEFT JOIN tbl_items as i on i.item_id = o.item_id or i.item_id = c.item_id 
					WHERE p.participant_id = :participant_id) as main GROUP BY participant_enroll_no ,category_type_name, transaction_datetime
					ORDER by transaction_datetime DESC";
			}
			else
			{
				$query = "SELECT participant_id,participant_enroll_no,participant_name , transaction_datetime, category_type_name, 
					sum(totalqty) as tqty , GROUP_CONCAT(concat(`item_name`,' (',`totalqty` , ')') separator ',') as item_name,
					 SUM( (item_price * preorderqty) + (item_price * canteenqty)) as price , DATE_FORMAT(transaction_datetime,'%d-%m-%Y') as dd, 
					DATE_FORMAT(transaction_datetime,'%I:%i:%s %p') as tt
					from ( SELECT p.participant_id,p.participant_name,p.participant_enroll_no,t.transaction_datetime,
					r.pre_order_id,r.participant_canteen_order_id,i.item_name , IFNull(o.qty ,0) as preorderqty , IFNull(c.qty,0) as canteenqty , i.item_price,
					g.category_type_name, IFNull(o.qty ,0) + IFNull(c.qty,0)  as totalqty
					from tbl_redemption r left join tbl_transaction t on r.redemption_id=t.redempation_id 
					left join tbl_participant p on p.participant_id=t.participant_id
					LEFT JOIN tbl_pre_orders as o on o.pre_order_id = r.pre_order_id 
					LEFT JOIN tbl_participant_canteen_orders as c on c.participant_canteen_order_id = r.participant_canteen_order_id
					LEFT JOIN tbl_category_type as g on g.category_type_id = o.category_type_id or g.category_type_id = c.category_type_id
					LEFT JOIN tbl_items as i on i.item_id = o.item_id or i.item_id = c.item_id 
					WHERE p.participant_id = :participant_id) as main GROUP BY participant_enroll_no ,category_type_name, transaction_datetime
					ORDER by transaction_datetime DESC LIMIT 0,20";
			}
			

			//echo $query;exit();
			$result = execute_query($query,array(':participant_id'=>$participant_id)) or die ("get_topup_history_report query fail.");
		}
		return new readonlyresultset($result);	
	}

	function get_amount_of_user($user_id){
		$query = "SELECT `amount` FROM `tbl_user` u LEFT JOIN tbl_family_code_amount fa on u.family_code=fa.family_code";
		$param = " WHERE u.user_id='".$user_id."'";
		require_once("api/api_common.php");
		$result = $conn->query($query.$param);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$amount = $row['amount'];
			}
		}
		return $amount;
	}
}
?>