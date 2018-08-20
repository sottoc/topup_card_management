<?php
	class participantinfo
	{
		private $participant_id;
		private $participant_name;
		private $participant_enroll_no;
		private $org_id;
		private $participant_allergy_flag;
		private $others_allergy_food_description;
		private $participant_description;
		private $participant_gender_id;
		private $organizer_id;
		private $participant_created_datetime;
		private $participant_modified_datetime;
		private $allow_canteen_order;
		private $upload_file;

		public function set_participant_id($value)
		{
			$this->participant_id=$value;
		}
		public function get_participant_id()
		{
			return $this->participant_id;
		}
		
		public function set_participant_name($value)
		{
			$this->participant_name=$value;
		}
		public function get_participant_name()
		{
			return $this->participant_name;
		}
		
		public function set_participant_enroll_no($value)
		{
			$this->participant_enroll_no=$value;
		}
		public function get_participant_enroll_no()
		{
			return $this->participant_enroll_no;
		}
		
		public function set_org_id($value)
		{
			$this->org_id=$value;
		}
		public function get_org_id()
		{
			return $this->org_id;
		}
		
		public function set_participant_allergy_flag($value)
		{
			$this->participant_allergy_flag=$value;
		}
		public function get_participant_allergy_flag()
		{
			return $this->participant_allergy_flag;
		}
		
		public function set_others_allergy_food_description($value)
		{
			$this->others_allergy_food_description=$value;
		}
		public function get_others_allergy_food_description()
		{
			return $this->others_allergy_food_description;
		}
		
		public function set_participant_description($value)
		{
			$this->participant_description=$value;
		}
		public function get_participant_description()
		{
			return $this->participant_description;
		}
		
		public function set_participant_gender_id($value)
		{
			$this->participant_gender_id=$value;
		}
		public function get_participant_gender_id()
		{
			return $this->participant_gender_id;
		}
		
		public function set_organizer_id($value)
		{
			$this->organizer_id=$value;
		}
		public function get_organizer_id()
		{
			return $this->organizer_id;
		}
		
		public function set_participant_created_datetime($value)
		{
			$this->participant_created_datetime=$value;
		}
		public function get_participant_created_datetime()
		{
			return $this->participant_created_datetime;
		}
		
		public function set_participant_modified_datetime($value)
		{
			$this->participant_modified_datetime=$value;
		}
		public function get_participant_modified_datetime()
		{
			return $this->participant_modified_datetime;
		}
		
		public function set_allow_canteen_order($value)
		{
			$this->allow_canteen_order=$value;
		}
		public function get_allow_canteen_order()
		{
			return $this->allow_canteen_order;
		}

		public function set_upload_file($value)
		{
			$this->upload_file=$value;
		}
		public function get_upload_file()
		{
			return $this->upload_file;
		}
	}
?>