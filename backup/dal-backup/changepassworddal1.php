<?php
class changepassworddal{

	function checktochangepass($user_id,$oldpass)
	{
		$password = md5($oldpass);
		$query="SELECT count(user_id) as count_record FROM tbl_user WHERE user_id=:user_id and user_password=:user_password";
		$result = execute_query($query,array(':user_id'=>$user_id,'user_password'=>$password)) or die ("checktochangepass query fail.");
		return new readonlyresultset($result);
	}
	function changepassword($newpassword,$user_id)
	{
		$password = md5($newpassword);
		$query="UPDATE tbl_user SET user_password= :user_password WHERE user_id= :user_id; ";		
		$param = array(
		':user_id'=>$user_id,
		':user_password'=>$password				
		);
		$result = execute_non_query($query,$param);
		return $result;
	}
}
?>