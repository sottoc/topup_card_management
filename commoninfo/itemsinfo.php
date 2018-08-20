<?php
	class itemsinfo
	{
		private $item_id;
		private $item_name;
		private $item_description;
		private $item_price;
		private $item_image_name;
		private $item_created_datetime;
		private $item_modified_datetime;
		
		public function set_item_id($value)
		{
			$this->item_id=$value;
		}
		public function get_item_id()
		{
			return $this->item_id;
		}
		
		public function set_item_name($value)
		{
			$this->item_name=$value;
		}
		public function get_item_name()
		{
			return $this->item_name;
		}
		
		public function set_item_description($value)
		{
			$this->item_description=$value;
		}
		public function get_item_description()
		{
			return $this->item_description;
		}
		
		public function set_item_price($value)
		{
			$this->item_price=$value;
		}
		public function get_item_price()
		{
			return $this->item_price;
		}
		
		public function set_item_image_name($value)
		{
			$this->item_image_name=$value;
		}
		public function get_item_image_name()
		{
			return $this->item_image_name;
		}
		
		public function set_item_created_datetime($value)
		{
			$this->item_created_datetime=$value;
		}
		public function get_item_created_datetime()
		{
			return $this->item_created_datetime;
		}
		
		public function set_item_modified_datetime($value)
		{
			$this->item_modified_datetime=$value;
		}
		public function get_item_modified_datetime()
		{
			return $this->item_modified_datetime;
		}
		
	}
?>