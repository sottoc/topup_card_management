<?php
	class meal_statusinfo
	{
		private $meal_status_id;
		private $meal_status_name;
		private $meal_status_created_datetime;
		private $meal_status_modified_datetime;
		
		public function set_meal_status_id($value)
		{
			$this->meal_status_id=$value;
		}
		public function get_meal_status_id()
		{
			return $this->meal_status_id;
		}
		
		public function set_meal_status_name($value)
		{
			$this->meal_status_name=$value;
		}
		public function get_meal_status_name()
		{
			return $this->meal_status_name;
		}
		
		public function set_meal_status_created_datetime($value)
		{
			$this->meal_status_created_datetime=$value;
		}
		public function get_meal_status_created_datetime()
		{
			return $this->meal_status_created_datetime;
		}
		
		public function set_meal_status_modified_datetime($value)
		{
			$this->meal_status_modified_datetime=$value;
		}
		public function get_meal_status_modified_datetime()
		{
			return $this->meal_status_modified_datetime;
		}
		
	}
?>