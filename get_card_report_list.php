<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$reportbol = new reportbol();
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
		if(isset($criobj->search_txt_studentid) &&  $criobj->search_txt_studentid!='' ){
			$cri_str .= " AND p.participant_enroll_no LIKE CONCAT('%',:search_txt_studentid,'%') ";
			$param[':search_txt_studentid'] = clean($criobj->search_txt_studentid);
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
	
	$rResult= $reportbol->get_card_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	while( $aRow = $rResult->getNext() )
	{
		$card_number = $aRow['card_number'];
		$participant_name= $aRow['participant_name'];
		$participant_enroll_no=$aRow['participant_enroll_no'];
		$parent_name=$aRow['parent_name'];
		$org_name=$aRow['org_name'];
		$card_description=$aRow['card_description'];
		$card_status_name=$aRow['card_status_name'];
		$card_issued_datetime=$aRow['card_issued_datetime'];
		$card_expired_datetime=$aRow['card_expired_datetime'];
				
		$tmpentry = array();
		$tmpentry[] = htmlspecialchars($card_number);
		$tmpentry[] = htmlspecialchars($participant_name);
		$tmpentry[] = htmlspecialchars($participant_enroll_no);
		$tmpentry[] = htmlspecialchars($parent_name);
		$tmpentry[] = htmlspecialchars($org_name);
		$tmpentry[] = htmlspecialchars($card_description);
		$tmpentry[] = htmlspecialchars($card_status_name);
		$tmpentry[] = htmlspecialchars($card_issued_datetime);
		$tmpentry[] = htmlspecialchars($card_expired_datetime);
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
			return "participant_name";
		else if ( $i == 2 )
			return "participant_enroll_no";
		else if ( $i == 3 )
			return "parent_name";
		else if ( $i == 4 )
			return "org_name";
			else if ( $i == 5 )
			return "card_description";
		else if ( $i == 6 )
			return "card_status_name";
		else if ( $i == 7 )
			return "card_issued_datetime";
		else if ( $i == 8 )
			return "card_expired_datetime";
		else 
			return true;			
	}
?>