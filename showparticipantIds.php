<?php
	session_start();
	require_once('library/reference.php');
	require_once('autoload.php');
	require_once('userauth.php');

	if(isset($_GET['participant_name']) && isset($_GET['organizer_name']))
	{
		$participant_name = $_GET['participant_name'];
		$organizer_name = $_GET['organizer_name'];
		$participantbol = new participantbol();

		$result = $participantbol->select_predefined_studentIDs($participant_name, $organizer_name);
		$resultrows = '';
		while($row = $result->getNext())
		{
			$resultrows.='<tr>';			
			$resultrows.="<td width='25%'><a style='cursor: pointer;color:blue;'>".$row['predefine_participant_enroll_no']."</a></td>";		
			$resultrows.="<td width='25%'>".htmlspecialchars($row['predefine_participant_name'])."</td>";	
			$resultrows.="<td width='25%'>".htmlspecialchars($row['predefine_parent_name'])."</td>";
			$resultrows.="<td width='25%'>".htmlspecialchars($row['org_name'])."</td>";			
			$resultrows.="</tr>";			
		}

		$result = array('studentIDs_result'=>$resultrows);
		header("Content-type: text/x-json");
		echo json_encode($result);
	}
?>