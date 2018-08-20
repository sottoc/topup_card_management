<?php
	class genderinfo
	{
		private $gender_id;
		private $gender_name;
		private $gender_prefix;
		
		public function set_gender_id($value)
		{
			$this->gender_id=$value;
		}
		public function get_gender_id()
		{
			return $this->gender_id;
		}
		
		public function set_gender_name($value)
		{
			$this->gender_name=$value;
		}
		public function get_gender_name()
		{
			return $this->gender_name;
		}
		
		public function set_gender_prefix($value)
		{
			$this->gender_prefix=$value;
		}
		public function get_gender_prefix()
		{
			return $this->gender_prefix;
		}
		
	}
?>