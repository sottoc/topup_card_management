<?php
	class orginfo
	{
		private $org_id;
		private $org_name;
		private $org_address;
		private $org_description;
		private $org_created_datetime;
		private $org_modified_datetime;
		
		public function set_org_id($value)
		{
			$this->org_id=$value;
		}
		public function get_org_id()
		{
			return $this->org_id;
		}
		
		public function set_org_name($value)
		{
			$this->org_name=$value;
		}
		public function get_org_name()
		{
			return $this->org_name;
		}
		
		public function set_org_address($value)
		{
			$this->org_address=$value;
		}
		public function get_org_address()
		{
			return $this->org_address;
		}
		
		public function set_org_description($value)
		{
			$this->org_description=$value;
		}
		public function get_org_description()
		{
			return $this->org_description;
		}
		
		public function set_org_created_datetime($value)
		{
			$this->org_created_datetime=$value;
		}
		public function get_org_created_datetime()
		{
			return $this->org_created_datetime;
		}
		
		public function set_org_modified_datetime($value)
		{
			$this->org_modified_datetime=$value;
		}
		public function get_org_modified_datetime()
		{
			return $this->org_modified_datetime;
		}
		
	}
?>