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
	
	$rResult= $reportbol->get_pre_order_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	
	while( $aRow = $rResult->getNext() )
	{	
		$tmpentry = array();
		$tmpentry[] = htmlspecialchars($aRow['participant_enroll_no']);
		$tmpentry[] = htmlspecialchars($aRow['participant_name']);
		$tmpentry[] = htmlspecialchars($aRow['preorder_date']);
		$tmpentry[] = htmlspecialchars($aRow['category_type_name']);
		$tmpentry[] = htmlspecialchars($aRow['item_name']);
		$tmpentry[] = htmlspecialchars($aRow['qty']);
		$tmpentry[] = htmlspecialchars($aRow['item_unit_price']);
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
			return "preorder_date";
		else if ( $i == 3 )
			return "item_name";
		else if ( $i == 4 )
			return "qty";
		else if ( $i == 5 )
			return "item_price";
		else 
			return true;			
	}
?>