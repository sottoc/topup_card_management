<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$setupbol = new setupbol();
	$studentcardbol = new studentcardbol();
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
	$cri_str = ' WHERE 1=1 ';
	$param = array();
	if ( isset($_GET['sSearch']))
	{	
		$criobj = json_decode($_GET['sSearch']);
		
		if(isset($criobj->search_card_number) &&  $criobj->search_card_number!='' ){
			$cri_str .= " AND card_number LIKE CONCAT('%',:search_card_number,'%') ";
			$param[':search_card_number'] = clean($criobj->search_card_number);
		}
		if(isset($criobj->hid_participant_id) &&  $criobj->hid_participant_id!=0 ){
			$cri_str .= " AND c.participant_id =:hid_participant_id ";
			$param[':hid_participant_id'] = clean($criobj->hid_participant_id);
		}
	}
	$cri_arr = array($cri_str,$param);
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$SortingCols = " ORDER BY  ";
		for ( $i=0 ; $i < $_GET['iSortingCols']; $i++ )
		{
			$SortingCols .= fnColumnToField($_GET['iSortCol_'.$i])."	".$_GET['sSortDir_'.$i].", ";
		}
		$SortingCols = substr_replace($SortingCols, " ", -2 );
		
		//for download xls (coming soon)
		$_SESSION['SESS_SORTINGCOLS']=$SortingCols;
	}
	
	$rResult= $studentcardbol->get_card_list($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	while( $aRow = $rResult->getNext() )
	{
		$card_id = $aRow['card_id'];
		$card_number=$aRow['card_number'];
		$participant_id=$aRow['participant_id'];
		$participant_name=$aRow['participant_name'];
		$card_description=$aRow['card_description'];
		$card_issued_datetime=$aRow['card_issued_datetime'];
		$card_expired_datetime=$aRow['card_expired_datetime'];
		$current_card_amt=$aRow['current_card_amt'];
		$card_status_id=$aRow['card_status_id'];
		$card_status_name=$aRow['card_status_name'];
		
		//edit btn
		$action ="<a title='Edit' href='edit_card.php?edit_card_id=$card_id&participant_id=$participant_id'><img src='images/blue_edit.gif' border='0'></a>&nbsp;&nbsp;";
		
		//del btn
		$card_del_params = $card_id."@@#$#@@".$card_number;
		$action .="<img title='Delete' src='images/blue_delete.gif' id='card_id$card_id' name='card_id$card_id' onClick=\"delete_card('$card_del_params');\" border='0' style='cursor:pointer;'>&nbsp;&nbsp;";
		
		$tmpentry = array();
		$tmpentry[] = htmlspecialchars($card_number);
		$tmpentry[] = htmlspecialchars($participant_name);
		$tmpentry[] = htmlspecialchars($card_description);
		$tmpentry[] = htmlspecialchars($card_issued_datetime);
		$tmpentry[] = htmlspecialchars($card_expired_datetime);
		$tmpentry[] = htmlspecialchars($card_status_name);
		$tmpentry[] = $action;
		$response['aaData'][] = $tmpentry;
	}
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
	header("Cache-Control: no-cache, must-revalidate" );
	header("Pragma: no-cache" );
	header("Content-type: text/x-json");
	echo json_encode($response);
	
	//for sorting
	function fnColumnToField( $i )
	{
		if ( $i == 0 )
			return "card_number";
		else if ( $i == 1 )
			return "participant_id";
		else if ( $i == 2 )
			return "card_description";
		else if ( $i == 3 )
			return "card_issued_datetime";
		else if ( $i == 4 )
			return "card_expired_datetime";
		else if ( $i == 5 )
			return "card_status_id";
		else 
			return true;			
	}
?>