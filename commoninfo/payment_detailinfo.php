<?php
	class payment_detailinfo
	{
		private $payment_detail_id;
		private $topup_id;
		private $account_number;
		private $paypal_status;
		private $transaction_code;//return value from paypal
		
		public function set_payment_detail_id($value)
		{
			$this->payment_detail_id=$value;
		}
		public function get_payment_detail_id()
		{
			return $this->payment_detail_id;
		}
		
		public function set_transaction_id($value)
		{
			$this->transaction_id=$value;
		}
		public function get_transaction_id()
		{
			return $this->transaction_id;
		}
		
		public function set_topup_id($value)
		{
			$this->topup_id=$value;
		}
		public function get_topup_id()
		{
			return $this->topup_id;
		}
		
		public function set_receiver_email($value)
		{
			$this->receiver_email=$value;
		}
		public function get_receiver_email()
		{
			return $this->receiver_email;
		}
		
		public function set_paypal_status($value)
		{
			$this->paypal_status=$value;
		}
		public function get_paypal_status()
		{
			return $this->paypal_status;
		}
		
		public function set_payer_email($value)
		{
			$this->payer_email=$value;
		}
		public function get_payer_email()
		{
			return $this->payer_email;
		}
		
		public function set_payment_amount($value)
		{
			$this->payment_amount=$value;
		}
		public function get_payment_amount()
		{
			return $this->payment_amount;
		}
		
		public function set_paypal_return_data($value)
		{
			$this->paypal_return_data=$value;
		}
		public function get_paypal_return_data()
		{
			return $this->paypal_return_data;
		}
		
	}
?>