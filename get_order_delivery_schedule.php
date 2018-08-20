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
	$cri_str = " WHERE p.meal_status_id = '2' ";
	$param = array();
	if ( isset($_GET['sSearch']))
	{	
		$criobj = json_decode($_GET['sSearch']);
		
		if(isset($criobj->txtfromdate)){
			$cri_str .= " AND p.preorder_date BETWEEN :txtfromdate AND :txttodate";
			$param[':txtfromdate'] = clean($criobj->txtfromdate);
			$param[':txttodate'] = clean($criobj->txttodate);
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
	
	$rResult= $reportbol->get_order_schedule_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	
	while( $aRow = $rResult->getNext() )
	{	
		$tmpentry = array();
		$pre_order_id =htmlspecialchars($aRow['pre_order_id']);
		$tmpentry[] = "<input type='checkbox' name='chkfinish[]' id='chkfinish[]' value='".$pre_order_id."' class='check' >";
		$tmpentry[] = htmlspecialchars($aRow['participant_enroll_no']);
		$tmpentry[] = htmlspecialchars($aRow['participant_name']);
		$tmpentry[] = htmlspecialchars($aRow['preorder_date']);
		$tmpentry[] = htmlspecialchars($aRow['category_type_name']);
		$tmpentry[] = htmlspecialchars($aRow['item_name']);
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
		if ( $i == 1 )
			return "participant_enroll_no";
		else if ( $i == 2 )
			return "participant_name";
		else if ( $i == 3 )
			return "preorder_date";
		else if ( $i == 4 )
			return "category_type_name";
		else if ( $i == 5 )
			return "item_name";
		else 
			return true;			
	}
?>