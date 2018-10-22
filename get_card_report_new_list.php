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
	
	$rResult= $reportbol->get_card_report_new($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	while( $aRow = $rResult->getNext() )
	{
		$user_code = $aRow['User_code'];
		$family_code = $aRow['Family_code'];
		$card_id = $aRow['Card_ID'];
		$last_name = $aRow['Last_name'];
		$first_name = $aRow['First_name'];
		$level = $aRow['Level'];
		$card_value = $aRow['Card_value'];
		$card_status = $aRow['Card_status'];
		$username = $aRow['Username'];
		$password = $aRow['Password'];
				
		$tmpentry = array();
		$tmpentry[] = htmlspecialchars($user_code);
		$tmpentry[] = htmlspecialchars($family_code);
		$tmpentry[] = htmlspecialchars($card_id);
		$tmpentry[] = htmlspecialchars($last_name);
		$tmpentry[] = htmlspecialchars($first_name);
		$tmpentry[] = htmlspecialchars($level);
		$tmpentry[] = htmlspecialchars($card_value);
		$tmpentry[] = htmlspecialchars($card_status);
		$tmpentry[] = htmlspecialchars($username);
		$tmpentry[] = htmlspecialchars($password);

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
			return "User_code";
		else if ( $i == 1 )
			return "Family_code";
		else if ( $i == 2 )
			return "Card_ID";
		else if ( $i == 3 )
			return "Last_name";
		else if ( $i == 4 )
			return "First_name";
		else if ( $i == 5 )
			return "Level";
		else if ( $i == 6 )
			return "Card_value";
		else if ( $i == 7 )
			return "Card_status";
		else if ( $i == 8 )
			return "Username";
		else if ( $i == 9 )
			return "Password";
		else 
			return true;			
	}
?>