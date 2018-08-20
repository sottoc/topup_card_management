<?php
	class category_typeinfo
	{
		private $category_type_id;
		private $category_type_name;
		private $category_type_created_datetime;
		private $category_type_modified_datetime;
		
		public function set_category_type_id($value)
		{
			$this->category_type_id=$value;
		}
		public function get_category_type_id()
		{
			return $this->category_type_id;
		}
		
		public function set_category_type_name($value)
		{
			$this->category_type_name=$value;
		}
		public function get_category_type_name()
		{
			return $this->category_type_name;
		}
		
		public function set_category_type_created_datetime($value)
		{
			$this->category_type_created_datetime=$value;
		}
		public function get_category_type_created_datetime()
		{
			return $this->category_type_created_datetime;
		}
		
		public function set_category_type_modified_datetime($value)
		{
			$this->category_type_modified_datetime=$value;
		}
		public function get_category_type_modified_datetime()
		{
			return $this->category_type_modified_datetime;
		}
		
	}
?>