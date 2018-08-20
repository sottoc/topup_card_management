<?php
	class participant_canteen_ordersinfo
	{
		private $participant_canteen_order_id;
		private $card_id;
		private $participant_id;
		private $participant_canteen_order_datetime;
		private $item_id;
		private $qty;
		private $category_type_id;
		private $meal_status_id;
		
		public function set_participant_canteen_order_id($value)
		{
			$this->participant_canteen_order_id=$value;
		}
		public function get_participant_canteen_order_id()
		{
			return $this->participant_canteen_order_id;
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
		
		public function set_participant_canteen_order_datetime($value)
		{
			$this->participant_canteen_order_datetime=$value;
		}
		public function get_participant_canteen_order_datetime()
		{
			return $this->participant_canteen_order_datetime;
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
		
	}
?>