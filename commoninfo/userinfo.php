<?php
	class userinfo
	{
		private $user_id;
		private $user_email;
		private $user_password;
		private $user_first_name;
		private $user_last_name;
		private $user_card_id;
		private $user_type_id;
		private $user_name;
		private $user_address;
		private $user_phone;
		private $user_gender_id;
		private $is_active;
		private $user_created_datetime;
		private $user_modified_datetime;
		
		public function set_user_id($value)
		{
			$this->user_id=$value;
		}
		public function get_user_id()
		{
			return $this->user_id;
		}
		
		public function set_user_email($value)
		{
			$this->user_email=$value;
		}
		public function get_user_email()
		{
			return $this->user_email;
		}
		
		public function set_user_password($value)
		{
			$this->user_password=$value;
		}
		public function get_user_password()
		{
			return $this->user_password;
		}

		public function set_user_first_name($value){
			$this->user_first_name=$value;
		}
		public function get_user_first_name(){
			return $this->user_first_name;
		}

		public function set_user_last_name($value){
			$this->user_last_name=$value;
		}
		public function get_user_last_name(){
			return $this->user_last_name;
		}

		public function set_user_card_id($value){
			$this->user_card_id=$value;
		}
		public function get_user_card_id(){
			return $this->user_card_id;
		}
		
		public function set_user_type_id($value)
		{
			$this->user_type_id=$value;
		}
		public function get_user_type_id()
		{
			return $this->user_type_id;
		}
		
		public function set_user_name($value)
		{
			$this->user_name=$value;
		}
		public function get_user_name()
		{
			return $this->user_name;
		}
		
		public function set_user_address($value)
		{
			$this->user_address=$value;
		}
		public function get_user_address()
		{
			return $this->user_address;
		}
		
		public function set_user_phone($value)
		{
			$this->user_phone=$value;
		}
		public function get_user_phone()
		{
			return $this->user_phone;
		}
		
		public function set_user_gender_id($value)
		{
			$this->user_gender_id=$value;
		}
		public function get_user_gender_id()
		{
			return $this->user_gender_id;
		}
		
		public function set_is_active($value)
		{
			$this->is_active=$value;
		}
		public function get_is_active()
		{
			return $this->is_active;
		}
		
		public function set_user_created_datetime($value)
		{
			$this->user_created_datetime=$value;
		}
		public function get_user_created_datetime()
		{
			return $this->user_created_datetime;
		}
		
		public function set_user_modified_datetime($value)
		{
			$this->user_modified_datetime=$value;
		}
		public function get_user_modified_datetime()
		{
			return $this->user_modified_datetime;
		}
		
	}
?>