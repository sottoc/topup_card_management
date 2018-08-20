<?php
	class user_typeinfo
	{
		private $user_type_id;
		private $user_type_name;
		private $user_type_description;
		private $user_type_created_datetime;
		private $user_type_modified_datetime;
		
		public function set_user_type_id($value)
		{
			$this->user_type_id=$value;
		}
		public function get_user_type_id()
		{
			return $this->user_type_id;
		}
		
		public function set_user_type_name($value)
		{
			$this->user_type_name=$value;
		}
		public function get_user_type_name()
		{
			return $this->user_type_name;
		}
		
		public function set_user_type_description($value)
		{
			$this->user_type_description=$value;
		}
		public function get_user_type_description()
		{
			return $this->user_type_description;
		}
		
		public function set_user_type_created_datetime($value)
		{
			$this->user_type_created_datetime=$value;
		}
		public function get_user_type_created_datetime()
		{
			return $this->user_type_created_datetime;
		}
		
		public function set_user_type_modified_datetime($value)
		{
			$this->user_type_modified_datetime=$value;
		}
		public function get_user_type_modified_datetime()
		{
			return $this->user_type_modified_datetime;
		}
		
	}
?>