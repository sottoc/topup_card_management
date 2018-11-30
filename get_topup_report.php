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
	$cri_str = ' WHERE date_created';
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
			$cri_str .= " >= '".$from."' AND date_created < '".$to."' + interval 1 day";
		}

		$cri_str .= " AND 1=1 ";
		if(isset($criobj->search_txt) &&  $criobj->search_txt!='' ){
			if($criobj->search_filter_by == '0'){
				$cri_str .= " AND family_code LIKE CONCAT('%',:search_txt,'%') ";
				$param[':search_txt'] = clean($criobj->search_txt);
			}
			if($criobj->search_filter_by == '1'){
				$cri_str .= " AND topup_amount LIKE CONCAT('%',:search_txt,'%') ";
				$param[':search_txt'] = clean($criobj->search_txt);
			}
			if($criobj->search_filter_by == '2'){
				$cri_str .= " AND payment_type LIKE CONCAT('%',:search_txt,'%') ";
				$param[':search_txt'] = clean($criobj->search_txt);
			}
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
		$trans_amt='';
		$tmpentry = array();
		$date_time = explode(" ", $aRow['date_created']);
		$tmpentry[] = htmlspecialchars($date_time[0]);
		$tmpentry[] = htmlspecialchars($date_time[1]);
		$tmpentry[] = htmlspecialchars($aRow['family_code']);
		// if($aRow['trans_type']=='redemption')
		// 	$trans_amt = "<font color='red'>".$aRow['redemption_amt']."</font>";
		// else
		// 	$trans_amt = "<font color='blue'>".$aRow['topup_amt']."</font>";
		$tmpentry[] = htmlspecialchars("$".$aRow['topup_amount']);
		//$tmpentry[] = htmlspecialchars($cri_str);
		$payment_type = "Cash";
		if($aRow['payment_type'] != 1){
			$payment_type = "Paypal";
		}
		$tmpentry[] = htmlspecialchars($payment_type);
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
			return "date_created";
		else if ( $i == 1 )
			return "date_created";
		else if ( $i == 3 )
			return "date_created";
		else if ( $i == 4 )
			return "date_created";
		else 
			return true;			
	}
?>