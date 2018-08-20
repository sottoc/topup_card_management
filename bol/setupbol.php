<?php
class setupbol{
	//----------org setup----------//
		function get_org_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_org_list($offset, $rpage ,$sorting,$cri_arr);
			return $result;
		}
		function check_duplicate_org_saving($txt_org_name)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_org_saving($txt_org_name);
			return $result;
		}
		function save_org($orginfo)
		{
			$setupdal=new setupdal();
			$result=$setupdal->save_org($orginfo);
			return $result;
		}
		function get_org_byid($org_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_org_byid($org_id);
			return $result;
		}
		function get_all_org()
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_all_org();
			return $result;
		}
		function check_duplicate_org_updating($edt_txt_org_name,$org_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_org_updating($edt_txt_org_name,$org_id);
			return $result;
		}
		function update_org($orginfo,$edt_hid_org_values)
		{
			$setupdal=new setupdal();
			$result=$setupdal->update_org($orginfo,$edt_hid_org_values);
			return $result;
		}
		function check_org_using($org_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_org_using($org_id);
			return $result;
		}
		function delete_org($org_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->delete_org($org_id);
			return $result;
		}
	//--------------------------------//
	
	//----------food allergy setup----------//
		function get_food_allergy_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_food_allergy_list($offset, $rpage ,$sorting,$cri_arr);
			return $result;
		}
		function check_duplicate_foodallergy_saving($txt_foodallergy_name)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_foodallergy_saving($txt_foodallergy_name);
			return $result;
		}
		function save_foodallergy($food_allergyinfo)
		{
			$setupdal=new setupdal();
			$result=$setupdal->save_foodallergy($food_allergyinfo);
			return $result;
		}
		function get_food_allergy_byid($food_allergy_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_food_allergy_byid($food_allergy_id);
			return $result;
		}
		function get_all_food_allergy()
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_all_food_allergy();
			return $result;
		}
		function check_duplicate_food_allergy_updating($edt_txt_foodallergy_name,$food_allergy_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_food_allergy_updating($_POST['edt_txt_foodallergy_name'],$food_allergy_id);
			return $result;
		}
		function update_food_allergy($food_allergyinfo,$edt_hid_foodallergy_values)
		{
			$setupdal=new setupdal();
			$result=$setupdal->update_food_allergy($food_allergyinfo,$edt_hid_foodallergy_values);
			return $result;
		}
		function check_food_allergy_using($food_allergy_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_food_allergy_using($food_allergy_id);
			return $result;
		}
		function delete_food_allergy($food_allergy_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->delete_food_allergy($food_allergy_id);
			return $result;
		}
	//-------------------------------------//
	
	//----------card status setup----------//
		function get_card_status_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_card_status_list($offset, $rpage ,$sorting,$cri_arr);
			return $result;
		}
		function check_duplicate_cardstatus_saving($txt_cardstatus_name)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_cardstatus_saving($txt_cardstatus_name);
			return $result;
		}
		function save_cardstatus($card_statusinfo)
		{
			$setupdal=new setupdal();
			$result=$setupdal->save_cardstatus($card_statusinfo);
			return $result;
		}
		function get_card_status_byid($card_status_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_card_status_byid($card_status_id);
			return $result;
		}
		function check_duplicate_card_status_updating($edt_txt_cardstatus_name,$card_status_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_card_status_updating($edt_txt_cardstatus_name,$card_status_id);
			return $result;
		}
		function update_card_status($card_statusinfo,$edt_hid_cardstatus_values)
		{
			$setupdal=new setupdal();
			$result=$setupdal->update_card_status($card_statusinfo,$edt_hid_cardstatus_values);
			return $result;
		}
		function check_card_status_using($card_status_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_card_status_using($card_status_id);
			return $result;
		}
		function delete_card_status($card_status_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->delete_card_status($card_status_id);
			return $result;
		}
	//-------------------------------------//
	
	//----------category type setup----------//
		function get_category_type_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_category_type_list($offset, $rpage ,$sorting,$cri_arr);
			return $result;
		}
		function check_duplicate_categorytype_saving($txt_categorytype_name)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_categorytype_saving($txt_categorytype_name);
			return $result;
		}
		function save_categorytype($category_typeinfo)
		{
			$setupdal=new setupdal();
			$result=$setupdal->save_categorytype($category_typeinfo);
			return $result;
		}
		function get_category_type_byid($category_type_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_category_type_byid($category_type_id);
			return $result;
		}
		function check_duplicate_category_type_updating($edt_txt_categorytype_name,$category_type_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_category_type_updating($edt_txt_categorytype_name,$category_type_id);
			return $result;
		}
		function update_category_type($category_typeinfo,$edt_hid_categorytype_values)
		{
			$setupdal=new setupdal();
			$result=$setupdal->update_category_type($category_typeinfo,$edt_hid_categorytype_values);
			return $result;
		}
		function check_category_type_using_in_preorders($category_type_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_category_type_using_in_preorders($category_type_id);
			return $result;
		}
		function check_category_type_using_in_participant_canteenorders($category_type_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_category_type_using_in_participant_canteenorders($category_type_id);
			return $result;
		}
		function delete_category_type($category_type_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->delete_category_type($category_type_id);
			return $result;
		}
		function get_all_category_type()
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_all_category_type();
			return $result;
		}
	//--------------------------------------//
	
	//----------meal status setup----------//
		function get_meal_status_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_meal_status_list($offset, $rpage ,$sorting,$cri_arr);
			return $result;
		}
		function check_duplicate_usertype_saving($txt_mealstatus_name)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_usertype_saving($txt_mealstatus_name);
			return $result;
		}
		function save_meal_status($meal_statusinfo)
		{
			$setupdal=new setupdal();
			$result=$setupdal->save_meal_status($meal_statusinfo);
			return $result;
		}
		function get_meal_status_byid($meal_status_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_meal_status_byid($meal_status_id);
			return $result;
		}
		function check_duplicate_mealstatus_updating($edt_txt_mealstatus_name,$meal_status_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_mealstatus_updating($edt_txt_mealstatus_name,$meal_status_id);
			return $result;
		}
		function update_meal_status($meal_statusinfo,$edt_hid_mealstatus_values)
		{
			$setupdal=new setupdal();
			$result=$setupdal->update_meal_status($meal_statusinfo,$edt_hid_mealstatus_values);
			return $result;
		}
		function check_meal_status_using($meal_status_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_meal_status_using($meal_status_id);
			return $result;
		}
		function delete_meal_status($meal_status_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->delete_meal_status($meal_status_id);
			return $result;
		}
		function get_all_meal_status()
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_all_meal_status();
			return $result;
		}
	//-------------------------------------//
	
	
	//-------------item setup----------------//
		function get_item_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_item_list($offset, $rpage ,$sorting,$cri_arr);
			return $result;
		}
		function check_duplicate_item_saving($txt_item_name)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_item_saving($txt_item_name);
			return $result;
		}
		function save_item($itemsinfo)
		{
			$setupdal=new setupdal();
			$result=$setupdal->save_item($itemsinfo);
			return $result;
		}
		function get_item_byid($item_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_item_byid($item_id);
			return $result;
		}
		function check_duplicate_item_updating($edt_txt_item_name,$item_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_duplicate_item_updating($edt_txt_item_name,$item_id);
			return $result;
		}
		function update_item($itemsinfo,$edt_hid_item_values)
		{
			$setupdal=new setupdal();
			$result=$setupdal->update_item($itemsinfo,$edt_hid_item_values);
			return $result;
		}
		function check_item_using_in_preorder($item_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_item_using_in_preorder($item_id);
			return $result;
		}
		function check_item_using_in_canteenorder($item_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->check_item_using_in_canteenorder($item_id);
			return $result;
		}
		function delete_item($item_id)
		{
			$setupdal=new setupdal();
			$result=$setupdal->delete_item($item_id);
			return $result;
		}
		function get_all_item_list()
		{
			$setupdal=new setupdal();
			$result=$setupdal->get_all_item_list();
			return $result;
		}
		function save_item_image_name($result_id,$img_name)
		{
			$setupdal=new setupdal();
			$result=$setupdal->save_item_image_name($result_id,$img_name);
			return $result;
		}
	//-------------------------------------//
	
}
?>