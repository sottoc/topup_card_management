<?php
class setupdal{
	//----------org setup----------//
		function get_org_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_organization";
			$query .= $cri_str;
			$query .=$sorting;
			if($rpage!=-1)
				$query.= " LIMIT $offset,$rpage";	
			$result = execute_query($query,$param) or die ("get_org_list query fail.");
				
			return new readonlyresultset($result);
		}
		function check_duplicate_org_saving($txt_org_name)
		{
			$query="SELECT count(org_id) as count_duplicate_saving FROM tbl_organization WHERE org_name=:org_name";
			$result = execute_query($query,array(':org_name'=>$txt_org_name)) or die ("check_duplicate_org_saving query fail.");
			return new readonlyresultset($result);
		}
		function save_org($orginfo)
		{
			$org_name= $orginfo->get_org_name();
			$org_address= $orginfo->get_org_address();
			$org_description= $orginfo->get_org_description();
			$org_created_datetime= $orginfo->get_org_created_datetime();
			$org_modified_datetime= $orginfo->get_org_modified_datetime();
			$query ="INSERT INTO  tbl_organization (org_name,org_address,org_description,org_created_datetime,org_modified_datetime) VALUES ( :org_name,:org_address,:org_description,:org_created_datetime,:org_modified_datetime) ";
			$param = array(
			':org_name'=>$org_name,
			':org_address'=>$org_address,
			':org_description'=>$org_description,
			':org_created_datetime'=>$org_created_datetime,
			':org_modified_datetime'=>$org_modified_datetime
			);
			$result = execute_query($query,$param) or die ("save_user_type query fail.");
			if($result)
			{
				#eventlog for org save
				$org_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['org_id'] = $org_id;
				$new_values_arr['org_name'] = $org_name;
				$new_values_arr['org_address'] = $org_address;
				$new_values_arr['org_description'] = $org_description;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_organization',$new_values_arr,$old_values);
				return $org_id;	
			}
			else 
				return false;
		}
		function get_org_byid($org_id)
		{
			$query="SELECT * FROM tbl_organization WHERE org_id=:org_id";
			$result = execute_query($query,array(':org_id'=>$org_id)) or die ("get_org_byid query fail.");
			return new readonlyresultset($result); 
		}
		function get_all_org()
		{
			$query="SELECT * FROM tbl_organization";
			$result = execute_query($query) or die ("get_all_org query fail.");
			return new readonlyresultset($result); 
		}
		function check_duplicate_org_updating($edt_txt_org_name,$org_id)
		{
			$query="SELECT count(org_id) as count_duplicate_updating FROM tbl_organization WHERE org_name=:org_name and org_id <> :org_id";
			$result = execute_query($query,array(':org_name'=>$edt_txt_org_name,':org_id'=>$org_id)) or die ("check_duplicate_org_updating query fail.");
			return new readonlyresultset($result);
		}
		function update_org($orginfo,$edt_hid_org_values)
		{
			$org_id= $orginfo->get_org_id();
			$org_name= $orginfo->get_org_name();
			$org_address= $orginfo->get_org_address();
			$org_description= $orginfo->get_org_description();
			$org_modified_datetime= $orginfo->get_org_modified_datetime();
			
			#eventlog for org update
			$eventlogbol = new eventlogbol();
			$old_values=$edt_hid_org_values;
			$new_values_arr = array();
			$new_values_arr['org_id'] = $org_id;
			$new_values_arr['org_name'] = $org_name;
			$new_values_arr['org_address'] = $org_address;
			$new_values_arr['org_description'] = $org_description;
			$event_result = $eventlogbol->save_eventlog('Update','tbl_organization',$new_values_arr,$old_values);
			
			if($event_result)
			{
				$query="UPDATE  tbl_organization SET org_name= :org_name,org_address=:org_address,org_description=:org_description,org_modified_datetime=:org_modified_datetime WHERE org_id= :org_id; ";		
				$param = array(
				':org_id'=>$org_id,
				':org_name'=>$org_name,
				':org_address'=>$org_address,
				':org_description'=>$org_description,
				':org_modified_datetime'=>$org_modified_datetime
				);
				$result = execute_non_query($query,$param);
				return $result;
			}
			else 
				return false;
		}
		function check_org_using($org_id)
		{
			$query="SELECT count(org_id) as count_org_records FROM tbl_participant WHERE org_id=:org_id";
			$result = execute_query($query,array(':org_id'=>$org_id)) or die ("check_org_using query fail.");
			return new readonlyresultset($result);
		}
		function delete_org($org_id)
		{
			#eventlog for org delete
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['org_id'] = $org_id;
			$event_result = $eventlogbol->save_eventlog('Delete','tbl_organization',$new_values_arr,$old_values);
			if($event_result)
			{
				$query="DELETE FROM tbl_organization WHERE org_id=:org_id ";		
				$result = execute_non_query($query, array(':org_id' => $org_id));
				return $result;
			}
			else 
				return false;
		}
	//--------------------------------//

	//----------food allergy setup----------//
		function get_food_allergy_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_food_allergy ";
			$query .= $cri_str;
			$query .=$sorting;
			if($rpage!=-1)
				$query.= " LIMIT $offset,$rpage";	
			$result = execute_query($query,$param) or die ("get_food_allergy_list query fail.");
				
			return new readonlyresultset($result);
		}
		function check_duplicate_foodallergy_saving($txt_foodallergy_name)
		{
			$query="SELECT count(food_allergy_id) as count_duplicate_saving FROM tbl_food_allergy WHERE food_allergy_name=:food_allergy_name";
			$result = execute_query($query,array(':food_allergy_name'=>$txt_foodallergy_name)) or die ("check_duplicate_foodallergy_saving query fail.");
			return new readonlyresultset($result);
		}
		function save_foodallergy($food_allergyinfo)
		{
			$food_allergy_name= $food_allergyinfo->get_food_allergy_name();
			$food_allergy_created_datetime= $food_allergyinfo->get_food_allergy_created_datetime();
			$food_allergy_modified_datetime= $food_allergyinfo->get_food_allergy_modified_datetime();
			$query ="INSERT INTO  tbl_food_allergy (food_allergy_name,food_allergy_created_datetime,food_allergy_modified_datetime) VALUES ( :food_allergy_name,:food_allergy_created_datetime,:food_allergy_modified_datetime) ";
			$param = array(
			':food_allergy_name'=>$food_allergy_name,
			':food_allergy_created_datetime'=>$food_allergy_created_datetime,
			':food_allergy_modified_datetime'=>$food_allergy_modified_datetime
			);
			$result = execute_query($query,$param) or die ("save_foodallergy query fail.");
			if($result)
			{
				#eventlog for food allergy save
				$food_allergy_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['food_allergy_id'] = $food_allergy_id;
				$new_values_arr['food_allergy_name'] = $food_allergy_name;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_food_allergy',$new_values_arr,$old_values);
				return $food_allergy_id;	
			}
			else 
				return false;
		}
		function get_food_allergy_byid($food_allergy_id)
		{
			$query="SELECT * FROM tbl_food_allergy WHERE food_allergy_id=:food_allergy_id";
			$result = execute_query($query,array(':food_allergy_id'=>$food_allergy_id)) or die ("get_food_allergy_byid query fail.");
			return new readonlyresultset($result); 
		}
		function get_all_food_allergy()
		{
			$query="SELECT * FROM tbl_food_allergy";
			$result = execute_query($query) or die ("get_all_food_allergy query fail.");
			return new readonlyresultset($result); 
		}
		function check_duplicate_food_allergy_updating($edt_txt_foodallergy_name,$food_allergy_id)
		{
			$query="SELECT count(food_allergy_id) as count_duplicate_updating FROM tbl_food_allergy WHERE food_allergy_name=:food_allergy_name and food_allergy_id <> :food_allergy_id";
			$result = execute_query($query,array(':food_allergy_name'=>$edt_txt_foodallergy_name,':food_allergy_id'=>$food_allergy_id)) or die ("check_duplicate_org_updating query fail.");
			return new readonlyresultset($result);
		}
		function update_food_allergy($food_allergyinfo,$edt_hid_foodallergy_values)
		{
			$food_allergy_id= $food_allergyinfo->get_food_allergy_id();
			$food_allergy_name= $food_allergyinfo->get_food_allergy_name();
			$food_allergy_modified_datetime= $food_allergyinfo->get_food_allergy_modified_datetime();
			
			#eventlog for food allergy update
			$eventlogbol = new eventlogbol();
			$old_values=$edt_hid_foodallergy_values;
			$new_values_arr = array();
			$new_values_arr['food_allergy_id'] = $food_allergy_id;
			$new_values_arr['food_allergy_name'] = $food_allergy_name;
			$event_result = $eventlogbol->save_eventlog('Update','tbl_food_allergy',$new_values_arr,$old_values);
			
			if($event_result)
			{
				$query="UPDATE  tbl_food_allergy SET food_allergy_name= :food_allergy_name,food_allergy_modified_datetime=:food_allergy_modified_datetime WHERE food_allergy_id= :food_allergy_id; ";		
				$param = array(
				':food_allergy_id'=>$food_allergy_id,
				':food_allergy_name'=>$food_allergy_name,
				':food_allergy_modified_datetime'=>$food_allergy_modified_datetime
				);
				$result = execute_non_query($query,$param);
				return $result;
			}
			else 
				return false;
		}
		function check_food_allergy_using($food_allergy_id)
		{
			$query="SELECT count(participant_food_allergy_id) as count_food_allergy_records FROM tbl_participant_food_allergy WHERE food_allergy_id=:food_allergy_id";
			$result = execute_query($query,array(':food_allergy_id'=>$food_allergy_id)) or die ("check_food_allergy_using query fail.");
			return new readonlyresultset($result);
		}
		function delete_food_allergy($food_allergy_id)
		{
			#eventlog for food allergy delete
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['food_allergy_id'] = $food_allergy_id;
			$event_result = $eventlogbol->save_eventlog('Delete','tbl_food_allergy',$new_values_arr,$old_values);
			if($event_result)
			{
				$query="DELETE FROM tbl_food_allergy WHERE food_allergy_id=:food_allergy_id ";		
				$result = execute_non_query($query, array(':food_allergy_id' => $food_allergy_id));
				return $result;
			}
			else 
				return false;
		}
	//-------------------------------//
	
	//----------card status setup----------//
		function get_card_status_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_card_status ";
			$query .= $cri_str;
			$query .=$sorting;
			if($rpage!=-1)
				$query.= " LIMIT $offset,$rpage";	
			$result = execute_query($query,$param) or die ("get_card_status_list query fail.");
				
			return new readonlyresultset($result);
		}
		function check_duplicate_cardstatus_saving($txt_cardstatus_name)
		{
			$query="SELECT count(card_status_id) as count_duplicate_saving FROM tbl_card_status WHERE card_status_name=:card_status_name";
			$result = execute_query($query,array(':card_status_name'=>$txt_cardstatus_name)) or die ("check_duplicate_cardstatus_saving query fail.");
			return new readonlyresultset($result);
		}
		function save_cardstatus($card_statusinfo)
		{
			$card_status_name= $card_statusinfo->get_card_status_name();
			$card_status_created_datetime= $card_statusinfo->get_card_status_created_datetime();
			$card_status_modified_datetime= $card_statusinfo->get_card_status_modified_datetime();
			$query ="INSERT INTO  tbl_card_status (card_status_name,card_status_created_datetime,card_status_modified_datetime) VALUES ( :card_status_name,:card_status_created_datetime,:card_status_modified_datetime) ";
			$param = array(
			':card_status_name'=>$card_status_name,
			':card_status_created_datetime'=>$card_status_created_datetime,
			':card_status_modified_datetime'=>$card_status_modified_datetime
			);
			$result = execute_query($query,$param) or die ("save_cardstatus query fail.");
			if($result)
			{
				#eventlog for card status save
				$card_status_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['card_status_id'] = $card_status_id;
				$new_values_arr['card_status_name'] = $card_status_name;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_card_status',$new_values_arr,$old_values);
				return $card_status_id;	
			}
			else 
				return false;
		}
		function get_card_status_byid($card_status_id)
		{
			$query="SELECT * FROM tbl_card_status WHERE card_status_id=:card_status_id";
			$result = execute_query($query,array(':card_status_id'=>$card_status_id)) or die ("get_card_status_byid query fail.");
			return new readonlyresultset($result);
		}
		function check_duplicate_card_status_updating($edt_txt_cardstatus_name,$card_status_id)
		{
			$query="SELECT count(card_status_id) as count_duplicate_updating FROM tbl_card_status WHERE card_status_name=:card_status_name and card_status_id <> :card_status_id";
			$result = execute_query($query,array(':card_status_name'=>$edt_txt_cardstatus_name,':card_status_id'=>$card_status_id)) or die ("check_duplicate_card_status_updating query fail.");
			return new readonlyresultset($result);
		}
		function update_card_status($card_statusinfo,$edt_hid_cardstatus_values)
		{
			$card_status_id= $card_statusinfo->get_card_status_id();
			$card_status_name= $card_statusinfo->get_card_status_name();
			$card_status_modified_datetime= $card_statusinfo->get_card_status_modified_datetime();
			
			#eventlog for food allergy update
			$eventlogbol = new eventlogbol();
			$old_values=$edt_hid_cardstatus_values;
			$new_values_arr = array();
			$new_values_arr['card_status_id'] = $card_status_id;
			$new_values_arr['card_status_name'] = $card_status_name;
			$event_result = $eventlogbol->save_eventlog('Update','tbl_card_status',$new_values_arr,$old_values);
			
			if($event_result)
			{
				$query="UPDATE  tbl_card_status SET card_status_name= :card_status_name,card_status_modified_datetime=:card_status_modified_datetime WHERE card_status_id= :card_status_id; ";		
				$param = array(
				':card_status_id'=>$card_status_id,
				':card_status_name'=>$card_status_name,
				':card_status_modified_datetime'=>$card_status_modified_datetime
				);
				$result = execute_non_query($query,$param);
				return $result;
			}
			else 
				return false;
		}
		function check_card_status_using($card_status_id)
		{
			$query="SELECT count(card_id) as count_card_records FROM tbl_card WHERE card_status_id=:card_status_id";
			$result = execute_query($query,array(':card_status_id'=>$card_status_id)) or die ("check_card_status_using query fail.");
			return new readonlyresultset($result);
		}
		function delete_card_status($card_status_id)
		{
			#eventlog for card status delete
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['card_status_id'] = $card_status_id;
			$event_result = $eventlogbol->save_eventlog('Delete','tbl_card_status',$new_values_arr,$old_values);
			if($event_result)
			{
				$query="DELETE FROM tbl_card_status WHERE card_status_id=:card_status_id ";		
				$result = execute_non_query($query, array(':card_status_id' => $card_status_id));
				return $result;
			}
			else 
				return false;
		}
	//-------------------------------------//

	//----------category type setup----------//
		function get_category_type_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_category_type ";
			$query .= $cri_str;
			$query .=$sorting;
			if($rpage!=-1)
				$query.= " LIMIT $offset,$rpage";	
			$result = execute_query($query,$param) or die ("get_category_type_list query fail.");
				
			return new readonlyresultset($result);
		}
		function check_duplicate_categorytype_saving($txt_categorytype_name)
		{
			$query="SELECT count(category_type_id) as count_duplicate_saving FROM tbl_category_type WHERE category_type_name=:category_type_name";
			$result = execute_query($query,array(':category_type_name'=>$txt_categorytype_name)) or die ("check_duplicate_categorytype_saving query fail.");
			return new readonlyresultset($result);
		}
		function save_categorytype($category_typeinfo)
		{
			$category_type_name= $category_typeinfo->get_category_type_name();
			$category_type_created_datetime= $category_typeinfo->get_category_type_created_datetime();
			$category_type_modified_datetime= $category_typeinfo->get_category_type_modified_datetime();
			$query ="INSERT INTO  tbl_category_type (category_type_name,category_type_created_datetime,category_type_modified_datetime) VALUES ( :category_type_name,:category_type_created_datetime,:category_type_modified_datetime) ";
			$param = array(
			':category_type_name'=>$category_type_name,
			':category_type_created_datetime'=>$category_type_created_datetime,
			':category_type_modified_datetime'=>$category_type_modified_datetime
			);
			$result = execute_query($query,$param) or die ("save_cardstatus query fail.");
			if($result)
			{
				#eventlog for card status save
				$category_type_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['category_type_id'] = $category_type_id;
				$new_values_arr['category_type_name'] = $category_type_name;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_category_type',$new_values_arr,$old_values);
				return $category_type_id;	
			}
			else 
				return false;
		}
		function get_category_type_byid($category_type_id)
		{
			$query="SELECT * FROM tbl_category_type WHERE category_type_id=:category_type_id";
			$result = execute_query($query,array(':category_type_id'=>$category_type_id)) or die ("get_category_type_byid query fail.");
			return new readonlyresultset($result);
		}
		function check_duplicate_category_type_updating($edt_txt_categorytype_name,$category_type_id)
		{
			$query="SELECT count(category_type_id) as count_duplicate_updating FROM tbl_category_type WHERE category_type_name=:category_type_name and category_type_id <> :category_type_id";
			$result = execute_query($query,array(':category_type_name'=>$edt_txt_categorytype_name,':category_type_id'=>$category_type_id)) or die ("check_duplicate_category_type_updating query fail.");
			return new readonlyresultset($result);
		}
		function update_category_type($category_typeinfo,$edt_hid_categorytype_values)
		{
			$category_type_id= $category_typeinfo->get_category_type_id();
			$category_type_name= $category_typeinfo->get_category_type_name();
			$category_type_modified_datetime= $category_typeinfo->get_category_type_modified_datetime();
			
			#eventlog for category type update
			$eventlogbol = new eventlogbol();
			$old_values=$edt_hid_categorytype_values;
			$new_values_arr = array();
			$new_values_arr['category_type_id'] = $category_type_id;
			$new_values_arr['category_type_name'] = $category_type_name;
			$event_result = $eventlogbol->save_eventlog('Update','tbl_category_type',$new_values_arr,$old_values);
			
			if($event_result)
			{
				$query="UPDATE  tbl_category_type SET category_type_name= :category_type_name,category_type_modified_datetime=:category_type_modified_datetime WHERE category_type_id= :category_type_id; ";		
				$param = array(
				':category_type_id'=>$category_type_id,
				':category_type_name'=>$category_type_name,
				':category_type_modified_datetime'=>$category_type_modified_datetime
				);
				$result = execute_non_query($query,$param);
				return $result;
			}
			else 
				return false;
		}
		function check_category_type_using_in_preorders($category_type_id)
		{
			$query="SELECT count(pre_order_id) as count_pre_order_records FROM tbl_pre_orders WHERE category_type_id=:category_type_id";
			$result = execute_query($query,array(':category_type_id'=>$category_type_id)) or die ("check_category_type_using_in_preorders query fail.");
			return new readonlyresultset($result);
		}
		function check_category_type_using_in_participant_canteenorders($category_type_id)
		{
			$query="SELECT count(participant_canteen_order_id) as count_participant_canteen_order_records FROM tbl_participant_canteen_orders WHERE category_type_id=:category_type_id";
			$result = execute_query($query,array(':category_type_id'=>$category_type_id)) or die ("check_category_type_using_in_participant_canteenorders query fail.");
			return new readonlyresultset($result);
		}
		function delete_category_type($category_type_id)
		{
			#eventlog for card status delete
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['category_type_id'] = $category_type_id;
			$event_result = $eventlogbol->save_eventlog('Delete','tbl_category_type',$new_values_arr,$old_values);
			if($event_result)
			{
				$query="DELETE FROM tbl_category_type WHERE category_type_id=:category_type_id ";		
				$result = execute_non_query($query, array(':category_type_id' => $category_type_id));
				return $result;
			}
			else 
				return false;
		}
		function get_all_category_type()
		{
			$query="SELECT * FROM tbl_category_type";
			$result = execute_query($query) or die ("get_all_category_type query fail.");
			return new readonlyresultset($result);
		}
	//--------------------------------------//
	
	//----------meal status setup----------//
		function get_meal_status_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_meal_status ";
			$query .= $cri_str;
			$query .=$sorting;
			if($rpage!=-1)
				$query.= " LIMIT $offset,$rpage";	
			$result = execute_query($query,$param) or die ("get_meal_status_list query fail.");
				
			return new readonlyresultset($result);
		}
		function check_duplicate_usertype_saving($txt_mealstatus_name)
		{
			$query="SELECT count(meal_status_id) as count_duplicate_saving FROM tbl_meal_status WHERE meal_status_name=:meal_status_name";
			$result = execute_query($query,array(':meal_status_name'=>$txt_mealstatus_name)) or die ("check_duplicate_usertype_saving query fail.");
			return new readonlyresultset($result);
		}
		function save_meal_status($meal_statusinfo)
		{
			$meal_status_name= $meal_statusinfo->get_meal_status_name();
			$meal_status_created_datetime= $meal_statusinfo->get_meal_status_created_datetime();
			$meal_status_modified_datetime= $meal_statusinfo->get_meal_status_modified_datetime();
			$query ="INSERT INTO  tbl_meal_status (meal_status_name,meal_status_created_datetime,meal_status_modified_datetime) VALUES ( :meal_status_name,:meal_status_created_datetime,:meal_status_modified_datetime) ";
			$param = array(
			':meal_status_name'=>$meal_status_name,
			':meal_status_created_datetime'=>$meal_status_created_datetime,
			':meal_status_modified_datetime'=>$meal_status_modified_datetime
			);
			$result = execute_query($query,$param) or die ("save_meal_status query fail.");
			if($result)
			{
				#eventlog for card status save
				$meal_status_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['meal_status_id'] = $meal_status_id;
				$new_values_arr['meal_status_name'] = $meal_status_name;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_meal_status',$new_values_arr,$old_values);
				return $meal_status_id;	
			}
			else 
				return false;
		}
		function get_meal_status_byid($meal_status_id)
		{
			$query="SELECT * FROM tbl_meal_status WHERE meal_status_id=:meal_status_id";
			$result = execute_query($query,array(':meal_status_id'=>$meal_status_id)) or die ("get_meal_status_byid query fail.");
			return new readonlyresultset($result);
		}
		function check_duplicate_mealstatus_updating($edt_txt_mealstatus_name,$meal_status_id)
		{
			$query="SELECT count(meal_status_id) as count_duplicate_updating FROM tbl_meal_status WHERE meal_status_name=:meal_status_name and meal_status_id <> :meal_status_id";
			$result = execute_query($query,array(':meal_status_name'=>$edt_txt_mealstatus_name,':meal_status_id'=>$meal_status_id)) or die ("check_duplicate_mealstatus_updating query fail.");
			return new readonlyresultset($result);
		}
		function update_meal_status($meal_statusinfo,$edt_hid_mealstatus_values)
		{
			$meal_status_id= $meal_statusinfo->get_meal_status_id();
			$meal_status_name= $meal_statusinfo->get_meal_status_name();
			$meal_status_modified_datetime= $meal_statusinfo->get_meal_status_modified_datetime();
			
			#eventlog for category type update
			$eventlogbol = new eventlogbol();
			$old_values=$edt_hid_mealstatus_values;
			$new_values_arr = array();
			$new_values_arr['meal_status_id'] = $meal_status_id;
			$new_values_arr['meal_status_name'] = $meal_status_name;
			$event_result = $eventlogbol->save_eventlog('Update','tbl_meal_status',$new_values_arr,$old_values);
			
			if($event_result)
			{
				$query="UPDATE  tbl_meal_status SET meal_status_name= :meal_status_name,meal_status_modified_datetime=:meal_status_modified_datetime WHERE meal_status_id= :meal_status_id; ";		
				$param = array(
				':meal_status_id'=>$meal_status_id,
				':meal_status_name'=>$meal_status_name,
				':meal_status_modified_datetime'=>$meal_status_modified_datetime
				);
				$result = execute_non_query($query,$param);
				return $result;
			}
			else 
				return false;
		}
		function check_meal_status_using($meal_status_id)
		{
			$query="SELECT count(pre_order_id) as count_pre_order_records FROM tbl_pre_orders WHERE meal_status_id=:meal_status_id";
			$result = execute_query($query,array(':meal_status_id'=>$meal_status_id)) or die ("check_meal_status_using query fail.");
			return new readonlyresultset($result);
		}
		function delete_meal_status($meal_status_id)
		{
			#eventlog for meal_status delete
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['meal_status_id'] = $meal_status_id;
			$event_result = $eventlogbol->save_eventlog('Delete','tbl_meal_status',$new_values_arr,$old_values);
			if($event_result)
			{
				$query="DELETE FROM tbl_meal_status WHERE meal_status_id=:meal_status_id ";		
				$result = execute_non_query($query, array(':meal_status_id' => $meal_status_id));
				return $result;
			}
			else 
				return false;
		}
		function get_all_meal_status()
		{
			$query="SELECT * FROM tbl_meal_status";
			$result = execute_query($query) or die ("get_all_meal_status query fail.");
			return new readonlyresultset($result);
		}
	//-------------------------------------//
	
	//-------------item setup----------------//
		function get_item_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_items ";
			$query .= $cri_str;
			$query .=$sorting;
			if($rpage!=-1)
				$query.= " LIMIT $offset,$rpage";	
			$result = execute_query($query,$param) or die ("get_item_list query fail.");
				
			return new readonlyresultset($result);
		}
		function check_duplicate_item_saving($txt_item_name)
		{
			$query="SELECT count(item_id) as count_duplicate_saving FROM tbl_items WHERE item_name=:item_name";
			$result = execute_query($query,array(':item_name'=>$txt_item_name)) or die ("check_duplicate_item_saving query fail.");
			return new readonlyresultset($result);
		}
		function save_item($itemsinfo)
		{
			$item_name= $itemsinfo->get_item_name();
			$item_description= $itemsinfo->get_item_description();
			$item_price= $itemsinfo->get_item_price();
			$item_created_datetime= $itemsinfo->get_item_created_datetime();
			$item_modified_datetime= $itemsinfo->get_item_modified_datetime();
			$query ="INSERT INTO  tbl_items (item_name,item_description,item_price,item_created_datetime,item_modified_datetime) VALUES ( :item_name,:item_description,:item_price,:item_created_datetime,:item_modified_datetime) ";
			$param = array(
			':item_name'=>$item_name,
			':item_description'=>$item_description,
			':item_price'=>$item_price,
			':item_created_datetime'=>$item_created_datetime,
			':item_modified_datetime'=>$item_modified_datetime
			);
			$result = execute_query($query,$param) or die ("save_item query fail.");
			if($result)
			{
				#eventlog for card status save
				$item_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['item_id'] = $item_id;
				$new_values_arr['item_name'] = $item_name;
				$new_values_arr['item_description'] = $item_description;
				$new_values_arr['item_price'] = $item_price;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_items',$new_values_arr,$old_values);
				return $item_id;	
			}
			else 
				return false;
		}
		function get_item_byid($item_id)
		{
			$query="SELECT * FROM tbl_items WHERE item_id=:item_id";
			$result = execute_query($query,array(':item_id'=>$item_id)) or die ("get_item_byid query fail.");
			return new readonlyresultset($result);
		}
		function check_duplicate_item_updating($edt_txt_item_name,$item_id)
		{
			$query="SELECT count(item_id) as count_duplicate_updating FROM tbl_items WHERE item_name=:item_name and item_id <> :item_id";
			$result = execute_query($query,array(':item_name'=>$edt_txt_item_name,':item_id'=>$item_id)) or die ("check_duplicate_mealstatus_updating query fail.");
			return new readonlyresultset($result);
		}
		function update_item($itemsinfo,$edt_hid_item_values)
		{
			$item_id= $itemsinfo->get_item_id();
			$item_name= $itemsinfo->get_item_name();
			$item_description= $itemsinfo->get_item_description();
			$item_price= $itemsinfo->get_item_price();
			$item_modified_datetime= $itemsinfo->get_item_modified_datetime();
			
			#eventlog for category type update
			$eventlogbol = new eventlogbol();
			$old_values=$edt_hid_item_values;
			$new_values_arr = array();
			$new_values_arr['item_id'] = $item_id;
			$new_values_arr['item_name'] = $item_name;
			$new_values_arr['item_description'] = $item_description;
			$new_values_arr['item_price'] = $item_price;
			$event_result = $eventlogbol->save_eventlog('Update','tbl_items',$new_values_arr,$old_values);
			
			if($event_result)
			{
				$query="UPDATE  tbl_items SET item_name= :item_name,item_description= :item_description,item_price= :item_price,item_modified_datetime=:item_modified_datetime WHERE item_id= :item_id; ";			
				$param = array(
				':item_id'=>$item_id,
				':item_name'=>$item_name,
				':item_description'=>$item_description,
				':item_price'=>$item_price,
				':item_modified_datetime'=>$item_modified_datetime
				);
				$result = execute_non_query($query,$param);
				return $result;
			}
			else 
				return false;
		}
		function check_item_using_in_preorder($item_id)
		{
			$query="SELECT count(pre_order_id) as count_preorder_records FROM tbl_pre_orders WHERE item_id=:item_id";
			$result = execute_query($query,array(':item_id'=>$item_id)) or die ("check_item_using_in_preorder query fail.");
			return new readonlyresultset($result);
		}
		function check_item_using_in_canteenorder($item_id)
		{
			$query="SELECT count(participant_canteen_order_id) as count_canteen_order_records FROM tbl_participant_canteen_orders WHERE item_id=:item_id";
			$result = execute_query($query,array(':item_id'=>$item_id)) or die ("check_item_using_in_canteenorder query fail.");
			return new readonlyresultset($result);
		}
		function delete_item($item_id)
		{
			#eventlog for item delete
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['item_id'] = $item_id;
			$event_result = $eventlogbol->save_eventlog('Delete','tbl_items',$new_values_arr,$old_values);
			if($event_result)
			{
				$query="DELETE FROM tbl_items WHERE item_id=:item_id ";		
				$result = execute_non_query($query, array(':item_id' => $item_id));
				return $result;
			}
			else 
				return false;
		}
		function get_all_item_list()
		{
			$query="SELECT * FROM tbl_items";
			$result = execute_query($query) or die ("get_all_item_list query fail.");
			return new readonlyresultset($result);
		}
		function save_item_image_name($result_id,$itemsinfo)
		{
			$item_image_name= $itemsinfo->get_item_image_name();
			$query ="UPDATE  tbl_items SET item_image_name= :item_image_name WHERE item_id= :item_id;";
			$param = array(
			':item_id'=>$result_id,
			':item_image_name'=>$item_image_name
			);
			$result = execute_non_query($query,$param) or die ("save_item query fail.");
			if($result)
			{
				#eventlog for card status save
				$item_id = $result_id;
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['item_id'] = $item_id;
				$new_values_arr['item_image_name'] = $item_image_name;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_items',$new_values_arr,$old_values);
				return $item_id;	
			}
			else 
				return false;
		}
	//-------------------------------------//
	
}
?>