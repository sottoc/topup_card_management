<?php
	class participant_food_allergyinfo
	{
		private $participant_food_allergy_id;
		private $participant_id;
		private $food_allergy_id;
		private $predefine_participant_id;

		public function set_participant_food_allergy_id($value)
		{
			$this->participant_food_allergy_id=$value;
		}
		public function get_participant_food_allergy_id()
		{
			return $this->participant_food_allergy_id;
		}
		
		public function set_participant_id($value)
		{
			$this->participant_id=$value;
		}
		public function get_participant_id()
		{
			return $this->participant_id;
		}
		
		public function set_food_allergy_id($value)
		{
			$this->food_allergy_id=$value;
		}
		public function get_food_allergy_id()
		{
			return $this->food_allergy_id;
		}

		public function set_predefine_participant_id($value)
		{
			$this->predefine_participant_id=$value;
		}
		public function get_predefine_participant_id()
		{
			return $this->predefine_participant_id;
		}
		
	}
?>