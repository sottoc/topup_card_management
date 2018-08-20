<?php
	class student_predefineinfo
	{
		private $predefine_participant_id;
		private $predefine_participant_name;
		private $predefine_participant_enroll_no;
		private $predefine_org_id;
		private $finger_print_number;
		private $predefine_parent_name;
		private $student_allergy_flag;
		private $others_allergy_food_description;
		private $student_description;
		private $student_gender_id;
		private $created;
		private $modified;
		private $allow_canteen_order;
		private $upload_file;
		private $student_class;
		
		public function set_predefine_participant_id($value)
		{
			$this->predefine_participant_id=$value;
		}
		public function get_predefine_participant_id()
		{
			return $this->predefine_participant_id;
		}
		
		public function set_predefine_participant_name($value)
		{
			$this->predefine_participant_name=$value;
		}
		public function get_predefine_participant_name()
		{
			return $this->predefine_participant_name;
		}
		
		public function set_predefine_participant_enroll_no($value)
		{
			$this->predefine_participant_enroll_no=$value;
		}
		public function get_predefine_participant_enroll_no()
		{
			return $this->predefine_participant_enroll_no;
		}
		
		public function set_predefine_org_id($value)
		{
			$this->predefine_org_id=$value;
		}
		public function get_predefine_org_id()
		{
			return $this->predefine_org_id;
		}
		
		public function set_finger_print_number($value)
		{
			$this->finger_print_number=$value;
		}
		public function get_finger_print_number()
		{
			return $this->finger_print_number;
		}
		
		public function set_predefine_parent_name($value)
		{
			$this->predefine_parent_name=$value;
		}
		public function get_predefine_parent_name()
		{
			return $this->predefine_parent_name;
		}

		public function set_student_allergy_flag($value)
		{
			$this->student_allergy_flag=$value;
		}
		public function get_student_allergy_flag()
		{
			return $this->student_allergy_flag;
		}
		
		public function set_others_allergy_food_description($value)
		{
			$this->others_allergy_food_description=$value;
		}
		public function get_others_allergy_food_description()
		{
			return $this->others_allergy_food_description;
		}	

		public function set_student_description($value)
		{
			$this->student_description=$value;
		}
		public function get_student_description()
		{
			return $this->student_description;
		}	

		public function set_student_gender_id($value)
		{
			$this->student_gender_id=$value;
		}
		public function get_student_gender_id()
		{
			return $this->student_gender_id;
		}	

		public function set_created($value)
		{
			$this->created=$value;
		}
		public function get_created()
		{
			return $this->created;
		}	

		public function set_modified($value)
		{
			$this->modified=$value;
		}
		public function get_modified()
		{
			return $this->modified;
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

		public function set_student_class($value)
		{
			$this->student_class=$value;
		}
		public function get_student_class()
		{
			return $this->student_class;
		}
	}
?>