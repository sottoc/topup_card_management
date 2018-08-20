<?php
	class student_predefineinfo
	{
		private $predefine_participant_id;
		private $predefine_participant_name;
		private $predefine_participant_enroll_no;
		private $predefine_org_id;
		private $finger_print_number;
		private $predefine_parent_name;
		
		
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
			
	}
?>