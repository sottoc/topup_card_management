<?php
	class card_statusinfo
	{
		private $card_status_id;
		private $card_status_name;
		private $card_status_created_datetime;
		private $card_status_modified_datetime;
		
		public function set_card_status_id($value)
		{
			$this->card_status_id=$value;
		}
		public function get_card_status_id()
		{
			return $this->card_status_id;
		}
		
		public function set_card_status_name($value)
		{
			$this->card_status_name=$value;
		}
		public function get_card_status_name()
		{
			return $this->card_status_name;
		}
		
		public function set_card_status_created_datetime($value)
		{
			$this->card_status_created_datetime=$value;
		}
		public function get_card_status_created_datetime()
		{
			return $this->card_status_created_datetime;
		}
		
		public function set_card_status_modified_datetime($value)
		{
			$this->card_status_modified_datetime=$value;
		}
		public function get_card_status_modified_datetime()
		{
			return $this->card_status_modified_datetime;
		}
		
	}
?>