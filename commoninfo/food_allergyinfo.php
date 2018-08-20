<?php
	class food_allergyinfo
	{
		private $food_allergy_id;
		private $food_allergy_name;
		private $food_allergy_created_datetime;
		private $food_allergy_modified_datetime;
		
		public function set_food_allergy_id($value)
		{
			$this->food_allergy_id=$value;
		}
		public function get_food_allergy_id()
		{
			return $this->food_allergy_id;
		}
		
		public function set_food_allergy_name($value)
		{
			$this->food_allergy_name=$value;
		}
		public function get_food_allergy_name()
		{
			return $this->food_allergy_name;
		}
		
		public function set_food_allergy_created_datetime($value)
		{
			$this->food_allergy_created_datetime=$value;
		}
		public function get_food_allergy_created_datetime()
		{
			return $this->food_allergy_created_datetime;
		}
		
		public function set_food_allergy_modified_datetime($value)
		{
			$this->food_allergy_modified_datetime=$value;
		}
		public function get_food_allergy_modified_datetime()
		{
			return $this->food_allergy_modified_datetime;
		}
		
	}
?>