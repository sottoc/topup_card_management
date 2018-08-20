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
		
		if(isset($criobj->sel_student_id) &&  $criobj->sel_student_id!='-1' ){
			$cri_str .= " AND p.participant_id=:sel_student_id";
			$param[':sel_student_id'] = clean($criobj->sel_student_id);
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
	
	$rResult= $reportbol->get_topup_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	
	while( $aRow = $rResult->getNext() )
	{	
		$tmpentry = array();
		$tmpentry[] = htmlspecialchars($aRow['participant_enroll_no']);
		$tmpentry[] = htmlspecialchars($aRow['participant_name']);
		if($aRow['card_number']=='' && $aRow['card_number']==Null)
			$card_number = "No Card";
		else
			$card_number = $aRow['card_number'];
		$tmpentry[] = htmlspecialchars($card_number);
		$tmpentry[] = htmlspecialchars($aRow['topup_amt']);
		$tmpentry[] = htmlspecialchars($aRow['payment_type']);
		$tmpentry[] = htmlspecialchars($aRow['pos_slip_id']);
		$tmpentry[] = htmlspecialchars($aRow['transaction_datetime']);
		$tmpentry[] = htmlspecialchars($aRow['user_name']);
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
			return "participant_enroll_no";
		else if ( $i == 1 )
			return "participant_name";
		else if ( $i == 2 )
			return "card_number";
		else if ( $i == 3 )
			return "topup_amt";
		else if ( $i == 4 )
			return "payment_type";
		else if ( $i == 5 )
			return "pos_slip_id";
		else if ( $i == 6 )
			return "transaction_datetime";
		else if ( $i == 7)
			return "user_name";
		else 
			return true;			
	}
?>