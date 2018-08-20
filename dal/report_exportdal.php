<?php
class report_exportdal{
	function order_delivery_export($cri_str)
	{
		//echo "cri_str=".$cri_str;
		$query = "SELECT pre_order_id,preorder_date,participant_enroll_no,participant_name , category_type_name , item_name
				FROM (SELECT pre_order_id,preorder_date,participant_enroll_no,participant_name , category_type_name , GROUP_CONCAT( item_name ) as item_name 
				from ( SELECT p.pre_order_id,p.participant_id,p.preorder_date,p.item_id,p.qty,p.category_type_id, p.meal_status_id , a.participant_enroll_no,a.participant_name,m.meal_status_name,c.category_type_name,i.item_name 
				FROM tbl_pre_orders as p Left Join tbl_participant as a on p.participant_id = a.participant_id 
				LEFT JOIN tbl_category_type as c on c.category_type_id = p.category_type_id 
				LEFT JOIN tbl_items as i on i.item_id = p.item_id 
				LEFT JOIN tbl_meal_status as m on m.meal_status_id =p.meal_status_id WHERE p.meal_status_id = '2') as main 
				GROUP BY participant_enroll_no , category_type_name) as minor
				where pre_order_id In ($cri_str)";
		$result = execute_query($query) or die ("order_delivery_export query fail.");
		return new readonlyresultset($result);
	}
	function order_schedule_summary_report($export_txtfromdate,$export_txttodate)
	{
		$query="SELECT Sum(p.qty) as total,i.item_name,p.preorder_date
				FROM tbl_pre_orders AS p LEFT JOIN tbl_meal_status AS m ON p.meal_status_id = m.meal_status_id
				LEFT JOIN tbl_category_type AS c on p.category_type_id = c.category_type_id
				LEFT JOIN tbl_items AS i ON p.item_id = i.item_id 
				WHERE p.meal_status_id = '2' And p.preorder_date BETWEEN '$export_txtfromdate' AND '$export_txttodate'
                GROUP BY i.item_name,p.preorder_date";
		//echo "query=".$query;
		$result = execute_query($query) or die ("order_delivery_export query fail.");
		return new readonlyresultset($result);
	}
	function order_schedule_detail_report($export_txtfromdate,$export_txttodate)
	{
		$query="SELECT preorder_date,main.participant_id,participant_enroll_no,participant_name,item_name ,total, meal_status_name, 
				category_type_name , others_allergy_food_description, GROUP_CONCAT(IFNull(f.food_allergy_name,'')) as food_allergy
				from (SELECT preorder_date,participant_id,participant_enroll_no,participant_name,item_name , SUM(qty) as total, meal_status_name, 
				category_type_name ,others_allergy_food_description
				from ( SELECT p.preorder_date, p.participant_id,a.participant_enroll_no ,a.participant_name,i.item_name,p.qty ,
				m.meal_status_name, c.category_type_name,a.others_allergy_food_description
				FROM tbl_pre_orders AS p LEFT JOIN tbl_participant as a ON p.participant_id = a.participant_id 
				LEFT JOIN tbl_meal_status AS m ON p.meal_status_id = m.meal_status_id 
				LEFT JOIN tbl_category_type AS c on p.category_type_id = c.category_type_id 
				LEFT JOIN tbl_items AS i ON p.item_id = i.item_id 
				WHERE p.meal_status_id = '2' And 
				p.preorder_date BETWEEN '$export_txtfromdate' AND '$export_txttodate' ) as minor 
				GROUP BY preorder_date, participant_id,participant_enroll_no,participant_name, item_name ) as main
				LEFT JOIN tbl_participant_food_allergy as fa ON fa.participant_id = main.participant_id
				LEFT JOIN tbl_food_allergy as f ON f.food_allergy_id = fa.food_allergy_id 
				GROUP BY preorder_date, participant_id,participant_enroll_no,participant_name, item_name";
		//echo "query=".$query;
		$result = execute_query($query) or die ("order_schedule_detail_report query fail.");
		return new readonlyresultset($result);
	}
}
?>