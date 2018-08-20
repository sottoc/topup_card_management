<?php
	require_once ('library/reference.php');
	require_once ('autoload.php');
	session_start ();
	require_once ('userauth.php');
	
	$DisplayStart = 0;
	$DisplayLength = 10;	
	$sEcho = intval($_GET['sEcho']);
		
	if ( isset( $_GET['iDisplayStart'] ) )
	{
		$DisplayStart = $_GET['iDisplayStart'];		
	}
	
	if ( isset( $_GET['iDisplayLength'] ) )
	{
		$DisplayLength = $_GET['iDisplayLength'];
	}
	
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$SortingCols = " ORDER BY  ";
		for ( $i=0 ; $i < $_GET['iSortingCols']; $i++ )
		{
			$SortingCols .= fnColumnToField($_GET['iSortCol_'.$i])."	".$_GET['sSortDir_'.$i].", ";
		}
		$SortingCols = substr_replace($SortingCols, "", -2 );
		$_SESSION['SESS_SORTINGCOLS']=$SortingCols;
	}

	$cri_str = ' WHERE 1=1 ';
	$param = array();
	
	if ( isset($_GET['sSearch']))
	{	
	    $criobj = json_decode($_GET['sSearch']);	
		if($criobj->search_txt_participant){
			$cri_str .= " AND participant_name LIKE CONCAT('%',:search_txt_participant,'%') ";
			$param[':search_txt_participant'] = clean(trim($criobj->search_txt_participant)) ;
		}
	}

	$cri_arr = array($cri_str, $param);

	$participantbol = new participantbol();
	$techResult = $participantbol->select_participant_list($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iResultCount = $techResult->rowCount();
	$iTotal = $techResult->getFoundRows();
	$no = $DisplayStart;
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());	
	while($participantRow = $techResult->getNext())
	{	
		$action ="";

		$participant_id = $participantRow['participant_id'];	
		$participant_name = $participantRow['participant_name'];	
		
		//edit btn
		$action ="<a title='Edit' href='under_construction.php?participant_id=$participant_id'><img src='images/blue_edit.gif' border='0'></a>&nbsp;&nbsp;";
		
		//del btn
		$participant_del_params = $participant_id."@@#$#@@".$participant_name;
		$action .="<img title='Delete' src='images/blue_delete.gif' id='participant_id$participant_id' name='participant_id$participant_id' onClick=\"delete_participant('$participant_del_params');\" border='0' style='cursor:pointer;'>&nbsp;&nbsp;";
		
		//card btn
		/* $studentcardbol= new studentcardbol();
		$card_res = $studentcardbol->get_card_count_by_pid($participant_id);
		$card_row = $card_res->getNext();
		$card_count = $card_row['card_count'];
		if($card_count>0) */
		$action .="<a title='Card' href='card_list.php?participant_id=$participant_id'><img src='images/card_24px.png' border='0'></a>&nbsp;&nbsp;";
		
		$tmpentry = array();		
		$tmpentry[] = htmlspecialchars($participant_name);				
		$tmpentry[] = $participantRow['participant_enroll_no'];		
		$allergy_foods_desc = '';
		if(isset($participantRow['others_allergy_food_description'])){
			$allergy_foods_desc .= htmlspecialchars($participantRow['others_allergy_food_description']);
		}
		
		/* take allergy foods from tbl_participant_food_allergy concern with participant_id */	
		$allergy_foods = $participantbol->select_allergy_foods($participantRow['participant_id']);
		while($afoods = $allergy_foods->getNext())
		{
			$allergy_foods_desc .= ','.$afoods['food_allergy_name'];
		}
		$allergy_foods_desc = trim($allergy_foods_desc, ",");
		$tmpentry[] = $allergy_foods_desc;
		$tmpentry[] = htmlspecialchars($participantRow['gender_name']);	
		$tmpentry[] = $action;
		$response['aaData'][] = $tmpentry;
	}
	
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
	header("Cache-Control: no-cache, must-revalidate" );
	header("Pragma: no-cache" );
	header("Content-type: text/x-json");
	//echo $sOutput;
	echo json_encode($response);
	
	function fnColumnToField( $i )
	{
		if ( $i == 0 )
			return "participant_name";
		else if ( $i == 1 )
			return "participant_enroll_no";
		else if ( $i == 3 )
			return "gender_name";		
	}	
?>