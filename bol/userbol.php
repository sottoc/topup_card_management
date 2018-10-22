<?php
class userbol{
	//----------user type setup----------//
		function get_user_type_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$userdal=new userdal();
			$result=$userdal->get_user_type_list($offset, $rpage ,$sorting,$cri_arr);
			return $result;
		}
		function check_duplicate_usertype_saving($txt_usertype_name)
		{
			$userdal=new userdal();
			$result=$userdal->check_duplicate_usertype_saving($txt_usertype_name);
			return $result;
		}
		function save_user_type($user_typeinfo)
		{
			$userdal=new userdal();
			$result=$userdal->save_user_type($user_typeinfo);
			return $result;
		}
		function get_user_type_byid($user_type_id)
		{
			$userdal=new userdal();
			$result=$userdal->get_user_type_byid($user_type_id);
			return $result;
		}
		function check_duplicate_usertype_updating($edt_txt_usertype_name,$user_type_id)
		{
			$userdal=new userdal();
			$result=$userdal->check_duplicate_usertype_updating($edt_txt_usertype_name,$user_type_id);
			return $result;
		}
		function update_user_type($user_typeinfo,$edt_hid_usertype_values)
		{
			$userdal=new userdal();
			$result=$userdal->update_user_type($user_typeinfo,$edt_hid_usertype_values);
			return $result;
		}
		function check_user_type_using($user_type_id)
		{
			$userdal=new userdal();
			$result=$userdal->check_user_type_using($user_type_id);
			return $result;
		}
		function delete_user_type($user_type_id)
		{
			$userdal=new userdal();
			$result=$userdal->delete_user_type($user_type_id);
			return $result;
		}
	//-------------------------------//
	
	
	//----------user type setup----------//
		function get_all_usertype()
		{
			$userdal=new userdal();
			$result=$userdal->get_all_usertype();
			return $result;
		}
		function get_user_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$userdal=new userdal();
			$result=$userdal->get_user_list($offset, $rpage ,$sorting,$cri_arr);
			return $result;
		}
		function get_user($offset, $rpage ,$sorting,$cri_arr)
		{
			$userdal=new userdal();
			$result=$userdal->get_user($offset, $rpage ,$sorting,$cri_arr);
			return $result;
		}
		function update_user_isactive($user_id,$is_active,$old_values)
		{
			$userdal=new userdal();
			$result=$userdal->update_user_isactive($user_id,$is_active,$old_values);
			return $result;
		}
	//-----------------------------//
	
	
	//------------staff setup-------------//
		function get_all_genders()
		{
			$userdal=new userdal();
			$result=$userdal->get_all_genders();
			return $result;
		}
		function get_staff_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$userdal=new userdal();
			$result=$userdal->get_staff_list($offset, $rpage ,$sorting,$cri_arr);
			return $result;
		}
		function check_duplicate_staff_saving($txt_staff_email)
		{
			$userdal=new userdal();
			$result=$userdal->check_duplicate_staff_saving($txt_staff_email);
			return $result;
		}
		function save_staff($userinfo)
		{
			$userdal=new userdal();
			$result=$userdal->save_staff($userinfo);
			return $result;
		}
		function get_staff_byid($staff_id)
		{
			$userdal=new userdal();
			$result=$userdal->get_staff_byid($staff_id);
			return $result;
		}
		function check_duplicate_staff_updating($edt_txt_staff_name,$staff_id)
		{
			$userdal=new userdal();
			$result=$userdal->check_duplicate_staff_updating($edt_txt_staff_name,$staff_id);
			return $result;
		}
		function update_staff($userinfo,$edt_hid_staff_values)
		{
			$userdal=new userdal();
			$result=$userdal->update_staff($userinfo,$edt_hid_staff_values);
			return $result;
		}
		function reset_change_pw($userinfo,$hid_old_user_value_str)
		{
			$userdal=new userdal();
			$result=$userdal->reset_change_pw($userinfo,$hid_old_user_value_str);
			return $result;
		}
	//------------------------------------//
	
	
	//------------register----------------//
		function save_organizer($userinfo)
		{
			$userdal=new userdal();
			$result=$userdal->save_organizer($userinfo);
			return $result;
		}
	//------------------------------------//
	
	
	//--------------login------------------//
		function check_user_login($loginemail,$loginpassword)
		{
			$userdal=new userdal();
			$result=$userdal->check_user_login($loginemail,$loginpassword);
			return $result;
		}
	//------------------------------------//
	
	//-------------register-----------------//
		function get_user_by_email($register_email)
		{
			$userdal=new userdal();
			$result=$userdal->get_user_by_email($register_email);
			return $result;
		}
	//------------------------------------//
}
?>