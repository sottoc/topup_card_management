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
	$cri_str = ' AND created_time';
	$param = array();
	if ( isset($_GET['sSearch']))
	{	
		$criobj = json_decode($_GET['sSearch']);
		
		if(isset($criobj->sel_student_id) &&  $criobj->sel_student_id!='-1' ){
			//$cri_str .= " AND t.participant_id=:sel_student_id";
			$param[':sel_student_id'] = clean($criobj->sel_student_id);
		}
		if(isset($criobj->sel_date_from) && isset($criobj->sel_date_to)){
			$from = $criobj->sel_date_from;
			$to = $criobj->sel_date_to;
			$cri_str .= " >= '".$from."' AND created_time < '".$to."' + interval 1 day";
		}
    }
    $date = new DateTime('7 days ago');
	$from = $date->format('Y-m-d');
    $to = date("Y-m-d");
    $cri_str .= " >= '".$from."' AND created_time <= '".$to."' + interval 1 day";
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
	
	$rResult= $reportbol->get_spending_history($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	
	while( $aRow = $rResult->getNext() )
	{
		$trans_amt='';
		$tmpentry = array();
		$date_time = explode(" ", $aRow['created_time']);
		$tmpentry[] = htmlspecialchars($date_time[0]);
		$tmpentry[] = htmlspecialchars($date_time[1]);
        
        $tmpentry[] = htmlspecialchars($aRow['First_name']);
        $tmpentry[] = htmlspecialchars($aRow['card_id']);
        $tmpentry[] = htmlspecialchars($aRow['pos_id']);
		$tmpentry[] = htmlspecialchars("$".$aRow['total_amount']);
		//$tmpentry[] = htmlspecialchars($cri_str);
		$tmpentry[] = htmlspecialchars($aRow['bill_id'].','.$aRow['Last_name']);

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
			return "created_time";
		else if ( $i == 1 )
			return "created_time";
		else if ( $i == 3 )
			return "created_time";
		else if ( $i == 4 )
			return "created_time";
		else 
			return true;			
	}
?>