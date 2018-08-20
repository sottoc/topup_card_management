<?php
	class cardinfo
	{
		private $card_id;
		private $card_number;
		private $participant_id;
		private $card_description;
		private $card_issued_datetime;
		private $card_expired_datetime;
		private $current_card_amt;
		private $card_status_id;
		private $card_data_modified_datetime;
		
		public function set_card_id($value)
		{
			$this->card_id=$value;
		}
		public function get_card_id()
		{
			return $this->card_id;
		}
		
		public function set_card_number($value)
		{
			$this->card_number=$value;
		}
		public function get_card_number()
		{
			return $this->card_number;
		}
		
		public function set_participant_id($value)
		{
			$this->participant_id=$value;
		}
		public function get_participant_id()
		{
			return $this->participant_id;
		}
		
		public function set_card_description($value)
		{
			$this->card_description=$value;
		}
		public function get_card_description()
		{
			return $this->card_description;
		}
		
		public function set_card_issued_datetime($value)
		{
			$this->card_issued_datetime=$value;
		}
		public function get_card_issued_datetime()
		{
			return $this->card_issued_datetime;
		}
		
		public function set_card_expired_datetime($value)
		{
			$this->card_expired_datetime=$value;
		}
		public function get_card_expired_datetime()
		{
			return $this->card_expired_datetime;
		}
		
		public function set_current_card_amt($value)
		{
			$this->current_card_amt=$value;
		}
		public function get_current_card_amt()
		{
			return $this->current_card_amt;
		}
		
		public function set_card_status_id($value)
		{
			$this->card_status_id=$value;
		}
		public function get_card_status_id()
		{
			return $this->card_status_id;
		}
		
		public function set_card_data_modified_datetime($value)
		{
			$this->card_data_modified_datetime=$value;
		}
		public function get_card_data_modified_datetime()
		{
			return $this->card_data_modified_datetime;
		}
		
	}
?>