<?php
class userdal{
	//----------user type setup----------//
		function get_user_type_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_user_type";
			$query .= $cri_str;
			$query .=$sorting;
			if($rpage!=-1)
				$query.= " LIMIT $offset,$rpage";	
			$result = execute_query($query,$param) or die ("get_user_type_list query fail.");
				
			return new readonlyresultset($result);
		}
		function check_duplicate_usertype_saving($txt_usertype_name)
		{
			$query="SELECT count(user_type_id) as count_duplicate_saving FROM tbl_user_type WHERE user_type_name=:user_type_name";
			$result = execute_query($query,array(':user_type_name'=>$txt_usertype_name)) or die ("check_duplicate_usertype_saving query fail.");
			return new readonlyresultset($result);
		}
		function save_user_type($user_typeinfo)
		{
			$user_type_name= $user_typeinfo->get_user_type_name();
			$user_type_description= $user_typeinfo->get_user_type_description();
			$user_type_created_datetime= $user_typeinfo->get_user_type_created_datetime();
			$user_type_modified_datetime= $user_typeinfo->get_user_type_modified_datetime();
			$query ="INSERT INTO  tbl_user_type (user_type_name,user_type_description,user_type_created_datetime,user_type_modified_datetime) VALUES ( :user_type_name,:user_type_description,:user_type_created_datetime,:user_type_modified_datetime) ";
			$param = array(
			':user_type_name'=>$user_type_name,
			':user_type_description'=>$user_type_description,
			':user_type_created_datetime'=>$user_type_created_datetime,
			':user_type_modified_datetime'=>$user_type_modified_datetime
			);
			$result = execute_query($query,$param) or die ("save_user_type query fail.");
			if($result)
			{
				#eventlog for user_type save
				$user_type_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['user_type_id'] = $user_type_id;
				$new_values_arr['user_type_name'] = $user_type_name;
				$new_values_arr['user_type_description'] = $user_type_description;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_user_type',$new_values_arr,$old_values);
				return $user_type_id;	
			}
			else 
				return false;
		}
		function get_user_type_byid($user_type_id)
		{
			$query="SELECT * FROM tbl_user_type WHERE user_type_id=:user_type_id";
			$result = execute_query($query,array(':user_type_id'=>$user_type_id)) or die ("get_user_type_byid query fail.");
			return new readonlyresultset($result); 
		}
		function check_duplicate_usertype_updating($edt_txt_usertype_name,$user_type_id)
		{
			$query="SELECT count(user_type_id) as count_duplicate_updating FROM tbl_user_type WHERE user_type_name=:user_type_name and user_type_id <> :user_type_id";
			$result = execute_query($query,array(':user_type_name'=>$edt_txt_usertype_name,':user_type_id'=>$user_type_id)) or die ("check_duplicate_usertype_updating query fail.");
			return new readonlyresultset($result);
		}
		function update_user_type($user_typeinfo,$edt_hid_usertype_values)
		{
			$user_type_id= $user_typeinfo->get_user_type_id();
			$user_type_name= $user_typeinfo->get_user_type_name();
			$user_type_description= $user_typeinfo->get_user_type_description();
			$user_type_modified_datetime= $user_typeinfo->get_user_type_modified_datetime();
			
			#eventlog for user_type update
			$eventlogbol = new eventlogbol();
			$old_values=$edt_hid_usertype_values;
			$new_values_arr = array();
			$new_values_arr['user_type_id'] = $user_type_id;
			$new_values_arr['user_type_name'] = $user_type_name;
			$new_values_arr['user_type_description'] = $user_type_description;
			$event_result = $eventlogbol->save_eventlog('Update','tbl_user_type',$new_values_arr,$old_values);
			
			if($event_result)
			{
				$query="UPDATE  tbl_user_type SET user_type_name= :user_type_name,user_type_description=:user_type_description,user_type_modified_datetime=:user_type_modified_datetime WHERE user_type_id= :user_type_id; ";		
				$param = array(
				':user_type_id'=>$user_type_id,
				':user_type_name'=>$user_type_name,
				':user_type_description'=>$user_type_description,
				':user_type_modified_datetime'=>$user_type_modified_datetime
				);
				$result = execute_non_query($query,$param);
				return $result;
			}
			else 
				return false;
		}
		function check_user_type_using($user_type_id)
		{
			$query="SELECT count(user_id) as count_user_records FROM tbl_user WHERE user_type_id=:user_type_id";
			$result = execute_query($query,array(':user_type_id'=>$user_type_id)) or die ("check_user_type_using query fail.");
			return new readonlyresultset($result);
		}
		function delete_user_type($user_type_id)
		{
			#eventlog for user_type delete
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['user_type_id'] = $user_type_id;
			$event_result = $eventlogbol->save_eventlog('Delete','tbl_user_type',$new_values_arr,$old_values);
			if($event_result)
			{
				$query="DELETE FROM tbl_user_type WHERE user_type_id=:user_type_id ";		
				$result = execute_non_query($query, array(':user_type_id' => $user_type_id));
				return $result;
			}
			else 
				return false;
		}
	//----------user type setup----------//
	
	//----------user setup----------//
		function get_all_usertype()
		{
			$query="SELECT * from tbl_user_type";
			$result = execute_query($query);
			return new readonlyresultset($result);
		}
		function get_user_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_user u
					lEFT JOIN tbl_user_type u_t on u.user_type_id=u_t.user_type_id
					LEFT JOIN tbl_gender g on u.user_gender_id=g.gender_id";
			$query .= $cri_str;
			$query .=$sorting;
			if($rpage!=-1)
				$query.= " LIMIT $offset,$rpage";	
			$result = execute_query($query,$param) or die ("get_user_list query fail.");
				
			return new readonlyresultset($result);
		}
		function get_user($offset, $rpage ,$sorting,$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			// $query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_user u
			// 		lEFT JOIN tbl_user_type u_t on u.user_type_id=u_t.user_type_id
			// 		LEFT JOIN tbl_gender g on u.user_gender_id=g.gender_id LEFT JOIN tbl_family_code_amount fa on u.family_code=fa.family_code";
			$query = "SELECT SQL_CALC_FOUND_ROWS family_id, fa.family_code, amount, user_id, user_gender_id, gender_prefix, user_email, user_type_name, user_address, user_phone, is_active, user_first_name, user_last_name, First_name, Last_name FROM tbl_family_code_amount fa LEFT JOIN tbl_user u ON u.family_code=fa.family_code LEFT JOIN tbl_user_type u_t ON u.user_type_id=u_t.user_type_id LEFT JOIN tbl_gender g ON u.user_gender_id=g.gender_id LEFT JOIN tbl_card1 c1 ON fa.family_code = c1.Family_code";
			$query .= $cri_str;
			$query .=$sorting;
			if($rpage!=-1)
				$query.= " LIMIT $offset,$rpage";	
			$result = execute_query($query,$param) or die ("get_user_list query fail.");
				
			return new readonlyresultset($result);
		}
		function update_user_isactive($user_id,$is_active,$old_values)
		{
			#eventlog for user status update
			$eventlogbol = new eventlogbol();
			$new_values_arr = array();
			$new_values_arr['user_id'] = $user_id;
			$new_values_arr['is_active'] = $is_active;
			$event_result = $eventlogbol->save_eventlog('Update','tbl_user',$new_values_arr,$old_values);
			
			if($event_result)
			{
				$query="UPDATE  tbl_user SET is_active= :is_active WHERE user_id= :user_id; ";		
				$param = array(
				':user_id'=>$user_id,
				':is_active'=>$is_active
				);
				$result = execute_non_query($query,$param);
				return $result;
			}
			else 
				return false;
		}
	//-----------------------------//
	
	
	//-------------staff setup -------------//
		function get_all_genders()
		{
			$query="SELECT * from tbl_gender";
			$result = execute_query($query);
			return new readonlyresultset($result);
		}
		function get_staff_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_user u
					lEFT JOIN tbl_user_type u_t on u.user_type_id=u_t.user_type_id
					LEFT JOIN tbl_gender g on u.user_gender_id=g.gender_id";
			$query .= $cri_str;
			$query .=$sorting;
			if($rpage!=-1)
				$query.= " LIMIT $offset,$rpage";	
			$result = execute_query($query,$param) or die ("get_user_list query fail.");
				
			return new readonlyresultset($result);
		}
		function check_duplicate_staff_saving($txt_staff_email)
		{
			$query="SELECT count(user_id) as count_duplicate_saving FROM tbl_user WHERE user_email=:user_email";
			$result = execute_query($query,array(':user_email'=>$txt_staff_email)) or die ("check_duplicate_staff_saving query fail.");
			return new readonlyresultset($result);
		}
		function save_staff($userinfo)
		{
			$user_type_id= $userinfo->get_user_type_id();
			$user_name= $userinfo->get_user_name();
			$user_email= $userinfo->get_user_email();
			$user_password= $userinfo->get_user_password();
			$md5_user_password = md5($user_password);
			$user_first_name = $userinfo->get_user_first_name();
			$user_last_name = $userinfo->get_user_last_name();
			$user_card_id = $userinfo->get_user_card_id();
			$user_address= $userinfo->get_user_address();
			$user_phone= $userinfo->get_user_phone();
			$user_gender_id= $userinfo->get_user_gender_id();
			$user_created_datetime= $userinfo->get_user_created_datetime();
			$user_modified_datetime= $userinfo->get_user_modified_datetime();
			$user_status = $userinfo->get_is_active();
			$query ="INSERT INTO  tbl_user (user_name,user_email,user_password,user_first_name,user_last_name,Card_ID,user_address,user_phone,user_gender_id,user_type_id,is_active,user_created_datetime,user_modified_datetime) VALUES (:user_name,:user_email,:user_password,:user_first_name,:user_last_name,:user_card_id,:user_address,:user_phone,:user_gender_id,:user_type_id,:is_active,:user_created_datetime,:user_modified_datetime) ";
			$param = array(
			':user_name'=>$user_name,
			':user_email'=>$user_email,
			':user_password'=>$md5_user_password,
			':user_first_name'=>$user_first_name,
			':user_last_name'=>$user_last_name,
			':user_card_id'=>$user_card_id,
			':user_address'=>$user_address,
			':user_phone'=>$user_phone,
			':user_gender_id'=>$user_gender_id,
			':user_type_id'=>$user_type_id,
			':is_active'=>$user_status,
			':user_created_datetime'=>$user_created_datetime,
			':user_modified_datetime'=>$user_modified_datetime
			);
			$result = execute_query($query,$param) or die ("save_item query fail.");
			if($result)
			{
				#eventlog for card status save
				$user_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['user_id'] = $user_id;
				$new_values_arr['user_name'] = $user_name;
				$new_values_arr['user_email'] = $user_email;
				$new_values_arr['md5_user_password'] = $md5_user_password;
				$new_values_arr['org_user_password'] = $user_password;
				$new_values_arr['user_address'] = $user_address;
				$new_values_arr['user_phone'] = $user_phone;
				$new_values_arr['user_gender_id'] = $user_gender_id;
				$new_values_arr['user_type_id'] = $user_type_id;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_user',$new_values_arr,$old_values);
				return $user_id;	
			}
			else 
				return false;
		}
		function get_staff_byid($staff_id)
		{
			$query="SELECT * FROM tbl_user WHERE user_id=:user_id";
			$result = execute_query($query,array(':user_id'=>$staff_id)) or die ("get_staff_byid query fail.");
			return new readonlyresultset($result);
		}
		function check_duplicate_staff_updating($edt_txt_staff_name,$staff_id)
		{
			$query="SELECT count(user_id) as count_duplicate_updating FROM tbl_user WHERE user_name=:user_name and user_id <> :user_id";
			$result = execute_query($query,array(':user_name'=>$edt_txt_staff_name,':user_id'=>$staff_id)) or die ("check_duplicate_usertype_updating query fail.");
			return new readonlyresultset($result);
		}
		function update_staff($userinfo,$edt_hid_staff_values)
		{
			$user_id= $userinfo->get_user_id();
			$user_email= $userinfo->get_user_email();
			$user_name= $userinfo->get_user_name();
			$user_address= $userinfo->get_user_address();
			$user_phone= $userinfo->get_user_phone();
			$user_gender_id= $userinfo->get_user_gender_id();
			$user_modified_datetime= $userinfo->get_user_modified_datetime();
			
			#eventlog for category type update
			$eventlogbol = new eventlogbol();
			$old_values=$edt_hid_staff_values;
			$new_values_arr = array();
			$new_values_arr['user_id'] = $user_id;
			$new_values_arr['user_email'] = $user_email;
			$new_values_arr['user_name'] = $user_name;
			$new_values_arr['user_address'] = $user_address;
			$new_values_arr['user_phone'] = $user_phone;
			$new_values_arr['user_gender_id'] = $user_gender_id;
			$event_result = $eventlogbol->save_eventlog('Update','tbl_user',$new_values_arr,$old_values);
			
			if($event_result)
			{
				$query="UPDATE  tbl_user SET user_email= :user_email,user_name= :user_name,user_address= :user_address,user_phone=:user_phone,user_gender_id=:user_gender_id,user_modified_datetime=:user_modified_datetime WHERE user_id= :user_id;";			
				$param = array(
				':user_id'=>$user_id,
				':user_name'=>$user_name,
				':user_email'=>$user_email,
				':user_address'=>$user_address,
				':user_phone'=>$user_phone,
				':user_gender_id'=>$user_gender_id,
				':user_modified_datetime'=>$user_modified_datetime
				);
				$result = execute_non_query($query,$param);
				return $result;
			}
			else 
				return false;
		}
		function reset_change_pw($userinfo,$hid_old_user_value_str)
		{
			$user_id= $userinfo->get_user_id();
			$user_password= $userinfo->get_user_password();
			$user_modified_datetime= $userinfo->get_user_modified_datetime();
			$md5_new_pw = md5($user_password);
			
			#eventlog for reset password
			$eventlogbol = new eventlogbol();
			$old_values=$hid_old_user_value_str;
			$new_values_arr = array();
			$new_values_arr['user_id'] = $user_id;
			$new_values_arr['user_password'] = $user_password;
			$new_values_arr['md5_user_password'] = $md5_new_pw;
			
			$event_result = $eventlogbol->save_eventlog('Reset Password','tbl_user',$new_values_arr,$old_values);
			
			if($event_result)
			{
				$query="UPDATE  tbl_user SET user_password= :user_password,user_modified_datetime=:user_modified_datetime WHERE user_id= :user_id;";			
				$param = array(
				':user_id'=>$user_id,
				':user_password'=>$md5_new_pw,
				':user_modified_datetime'=>$user_modified_datetime
				);
				$result = execute_non_query($query,$param);
				return $result;
			}
			else 
				return false;
		}
	//-------------------------------------//
	
	
	//--------------register-----------------//
		function save_organizer($userinfo)
		{
			$user_id= $userinfo->get_user_id();
			$organizer_description= $userinfo->get_organizer_description();
			
			$query ="INSERT INTO  tbl_organizer (user_id,organizer_description) VALUES (:user_id,:organizer_description) ";
			$param = array(
			':user_id'=>$user_id,
			':organizer_description'=>$organizer_description
			);
			$result = execute_query($query,$param) or die ("save_item query fail.");
			if($result)
			{
				#eventlog for card status save
				$organizer_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['parent/organizer_id'] = $organizer_id;
				$new_values_arr['user_id'] = $user_id;
				$new_values_arr['organizer_description'] = $organizer_description;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_organizer',$new_values_arr,$old_values);
				return $user_id;	
			}
			else 
				return false;
		}
	//-------------------------------------//
	
	
	//--------------login------------------//
		function check_user_login($loginemail,$loginpassword)
		{
			$password = md5($loginpassword);			
			$qry = "SELECT * FROM tbl_user WHERE user_email = :email AND user_password = :password AND is_active = 1";
			$param = array(':email' => $loginemail, ':password' => $password);		
			$result = execute_query($qry, $param) or die("check_user_login query fail.");		
			return new readonlyresultset($result);
		}
	//-------------------------------------//
	
	//-------------register------------------//
		function get_user_by_email($register_email)
		{
			$query="SELECT * FROM tbl_user WHERE user_email=:register_email";
			$result = execute_query($query,array(':register_email'=>$register_email)) or die ("get_user_by_email query fail.");
			return new readonlyresultset($result);
		}
	//--------------------------------------//
	
	
	
	
}
?>