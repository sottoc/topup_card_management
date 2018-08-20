<?php
	class organizerinfo
	{
		private $organizer_id;
		private $user_id;
		private $organizer_description;
		
		public function set_organizer_id($value)
		{
			$this->organizer_id=$value;
		}
		public function get_organizer_id()
		{
			return $this->organizer_id;
		}
		
		public function set_user_id($value)
		{
			$this->user_id=$value;
		}
		public function get_user_id()
		{
			return $this->user_id;
		}
		
		public function set_organizer_description($value)
		{
			$this->organizer_description=$value;
		}
		public function get_organizer_description()
		{
			return $this->organizer_description;
		}
		
	}
?>