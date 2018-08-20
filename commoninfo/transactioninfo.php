<?php
	class transactioninfo
	{
		private $trans_id;
		private $trans_type;
		private $card_id;
		private $topup_id;
		private $redempation_id;
		private $transaction_amt;
		private $participant_id;
		private $transaction_datetime;
		
		public function set_trans_id($value)
		{
			$this->trans_id=$value;
		}
		public function get_trans_id()
		{
			return $this->trans_id;
		}
		
		public function set_trans_type($value)
		{
			$this->trans_type=$value;
		}
		public function get_trans_type()
		{
			return $this->trans_type;
		}
		
		public function set_card_id($value)
		{
			$this->card_id=$value;
		}
		public function get_card_id()
		{
			return $this->card_id;
		}
		
		public function set_topup_id($value)
		{
			$this->topup_id=$value;
		}
		public function get_topup_id()
		{
			return $this->topup_id;
		}
		
		public function set_redempation_id($value)
		{
			$this->redempation_id=$value;
		}
		public function get_redempation_id()
		{
			return $this->redempation_id;
		}
		
		public function set_transaction_amt($value)
		{
			$this->transaction_amt=$value;
		}
		public function get_transaction_amt()
		{
			return $this->transaction_amt;
		}
		
		public function set_participant_id($value)
		{
			$this->participant_id=$value;
		}
		public function get_participant_id()
		{
			return $this->participant_id;
		}
		
		public function set_transaction_datetime($value)
		{
			$this->transaction_datetime=$value;
		}
		public function get_transaction_datetime()
		{
			return $this->transaction_datetime;
		}
		
	}
?>