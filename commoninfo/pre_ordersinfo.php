<?php
	class pre_ordersinfo
	{
		private $pre_order_id;
		private $card_id;
		private $participant_id;
		private $preorder_date;
		private $item_id;
		private $qty;
		private $category_type_id;
		private $meal_status_id;
		private $created_datetime;
		private $modified_datetime;
		
		public function set_pre_order_id($value)
		{
			$this->pre_order_id=$value;
		}
		public function get_pre_order_id()
		{
			return $this->pre_order_id;
		}
		
		public function set_card_id($value)
		{
			$this->card_id=$value;
		}
		public function get_card_id()
		{
			return $this->card_id;
		}
		
		public function set_participant_id($value)
		{
			$this->participant_id=$value;
		}
		public function get_participant_id()
		{
			return $this->participant_id;
		}
		
		public function set_preorder_date($value)
		{
			$this->preorder_date=$value;
		}
		public function get_preorder_date()
		{
			return $this->preorder_date;
		}
		
		public function set_item_id($value)
		{
			$this->item_id=$value;
		}
		public function get_item_id()
		{
			return $this->item_id;
		}
		
		public function set_qty($value)
		{
			$this->qty=$value;
		}
		public function get_qty()
		{
			return $this->qty;
		}
		
		public function set_category_type_id($value)
		{
			$this->category_type_id=$value;
		}
		public function get_category_type_id()
		{
			return $this->category_type_id;
		}
		
		public function set_meal_status_id($value)
		{
			$this->meal_status_id=$value;
		}
		public function get_meal_status_id()
		{
			return $this->meal_status_id;
		}
		
		public function set_created_datetime($value)
		{
			$this->created_datetime=$value;
		}
		public function get_created_datetime()
		{
			return $this->created_datetime;
		}
		
		public function set_modified_datetime($value)
		{
			$this->modified_datetime=$value;
		}
		public function get_modified_datetime()
		{
			return $this->modified_datetime;
		}
		
	}
?>