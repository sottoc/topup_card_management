<?php
class localizationdal{
	function get_localization_by_pagename($name,$language_id) 
	{
		$query = "Select localization_id from tbl_localization WHERE localization_name = :name union select 0 limit 1 ";
		$result = execute_query($query,array(':name'=>$name)) or die(mysql_error().$query);
		$Rresult = new readonlyresultset($result);
		$aRow = $Rresult->getNext();
		$page_id= $aRow['localization_id'];
		
		if($language_id==1)//english
		{
			/* $query2 ="SELECT *, default_text as detail FROM tbl_localization where(localization_id = $page_id or parent_id = $page_id)";
			$result2 = execute_query($query2) or die('get_localization_by_pagename query fail'); */
			$param_array = array(':page_id'=>$page_id,':page_pid'=>$page_id);
			$query2 = "SELECT *, default_text as detail FROM tbl_localization where localization_id =:page_id or (parent_id = :page_pid)";
			$result2 = execute_query($query2,$param_array) or die('get_localization_by_pagename query fail');
		}
		else 
		{
			/* $query2 = "SELECT L.*,If(LS.detail='' or LS.detail is null,L.default_text,LS.detail) as detail FROM tbl_localization L 
					left join (select * from tbl_localization_detail where language_id = $language_id) As LS on L.localization_id = LS.localization_id
					where (L.localization_id = $page_id or parent_id = $page_id)";
			$result2 = execute_query($query2) or die('get_localization_by_pagename query fail'); */
			$param_array = array(':page_id'=>$page_id,':page_pid'=>$page_id,':language_id'=>$language_id);
			$query2 = "SELECT L.*,If(LS.detail='' or LS.detail is null,L.default_text,LS.detail) as detail FROM tbl_localization L 
					left join (select * from tbl_localization_detail where language_id = :language_id) As LS on L.localization_id = LS.localization_id
					where (L.localization_id = :page_id or parent_id = :page_pid)";
			$result2 = execute_query($query2,$param_array) or die('get_localization_by_pagename query fail');
		}
		if($result2)			
			return new readonlyresultset($result2);		
	}
	
	
}
?>