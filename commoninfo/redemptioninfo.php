<?php
	class redemptioninfo
	{
		private $redemption_id;
		private $redemption_amt;
		private $user_id;//staff_id who sell food
		private $pre_order_id;
		private $participant_canteen_order_id;
		
		public function set_redemption_id($value)
		{
			$this->redemption_id=$value;
		}
		public function get_redemption_id()
		{
			return $this->redemption_id;
		}
		
		public function set_redemption_amt($value)
		{
			$this->redemption_amt=$value;
		}
		public function get_redemption_amt()
		{
			return $this->redemption_amt;
		}
		
		public function set_user_id($value)
		{
			$this->user_id=$value;
		}
		public function get_user_id()
		{
			return $this->user_id;
		}
		
		public function set_pre_order_id($value)
		{
			$this->pre_order_id=$value;
		}
		public function get_pre_order_id()
		{
			return $this->pre_order_id;
		}
		
		public function set_participant_canteen_order_id($value)
		{
			$this->participant_canteen_order_id=$value;
		}
		public function get_participant_canteen_order_id()
		{
			return $this->participant_canteen_order_id;
		}
		
	}
?>