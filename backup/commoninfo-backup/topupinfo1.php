<?php
	class topupinfo
	{
		private $topup_id;
		private $topup_amt;
		private $payment_type;
		private $payment_status;
		private $pos_slip_id;
		
		public function set_topup_id($value)
		{
			$this->topup_id=$value;
		}
		public function get_topup_id()
		{
			return $this->topup_id;
		}
		
		public function set_topup_amt($value)
		{
			$this->topup_amt=$value;
		}
		public function get_topup_amt()
		{
			return $this->topup_amt;
		}
		
		public function set_payment_type($value)
		{
			$this->payment_type=$value;
		}
		public function get_payment_type()
		{
			return $this->payment_type;
		}
		
		public function set_payment_status($value)
		{
			$this->payment_status=$value;
		}
		public function get_payment_status()
		{
			return $this->payment_status;
		}
		
		public function set_pos_slip_id($value)
		{
			$this->pos_slip_id=$value;
		}
		public function get_pos_slip_id()
		{
			return $this->pos_slip_id;
		}
		
	}
?>