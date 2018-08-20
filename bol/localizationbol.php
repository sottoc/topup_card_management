<?php
class localizationbol{
	function get_localization_by_pagename($name,$languageid) 
	{
		$localization=new localizationdal();
		$result=$localization->get_localization_by_pagename($name,$languageid);
		return $result;
	}
}
?>