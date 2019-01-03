<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$userbol = new userbol();
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
		if(isset($criobj->search_txt) &&  $criobj->search_txt!='' ){
			if($criobj->search_filter_by == '0'){
				$cri_str .= " AND user_email LIKE CONCAT('%',:search_txt,'%') ";
				$param[':search_txt'] = clean($criobj->search_txt);
			}
			if($criobj->search_filter_by == '1'){
				$cri_str .= " AND user_type_name LIKE CONCAT('%',:search_txt,'%') ";
				$param[':search_txt'] = clean($criobj->search_txt);
			}
			if($criobj->search_filter_by == '2'){
				$cri_str .= " AND user_last_name LIKE CONCAT('%',:search_txt,'%') ";
				$param[':search_txt'] = clean($criobj->search_txt);
			}
			if($criobj->search_filter_by == '3'){
				$cri_str .= " AND user_first_name LIKE CONCAT('%',:search_txt,'%') ";
				$param[':search_txt'] = clean($criobj->search_txt);
			}
			if($criobj->search_filter_by == '4'){
                $cri_str .= " AND u.family_code LIKE CONCAT('%',:search_txt,'%') ";
                $param[':search_txt'] = clean($criobj->search_txt);
			}
			if($criobj->search_filter_by == '5'){
				$cri_str .= " AND amount LIKE CONCAT('',:search_txt,'') ";
				$param[':search_txt'] = clean($criobj->search_txt);
			}
			if($criobj->search_filter_by == '6'){
				$cri_str .= " AND is_active LIKE CONCAT('',:search_txt,'') ";
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
	
	$rResult= $userbol->get_user($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	while( $aRow = $rResult->getNext() )
	{
		$family_code = $aRow['family_code'];
		$user_id = $aRow['user_id'];
		$user_gender_id=$aRow['user_gender_id'];
		$user_gender_prefix=$aRow['gender_prefix'];
		$user_name = $user_gender_prefix." ".$aRow['user_email'];
		$user_email=$aRow['user_email'];
		$user_type_name=$aRow['user_type_name'];
		$user_address=$aRow['user_address'];
		$user_phone=$aRow['user_phone'];
        $is_active=$aRow['is_active'];
		
		$amount = $aRow['amount'];
		$first_name = $aRow['user_first_name'];
		$last_name = $aRow['user_last_name'];
		if($first_name == '') $first_name = $aRow['First_name'];
		if($last_name == '') $last_name = $aRow['Last_name'];
		$user_id_family_code = $user_id.'-'.$family_code;
		if($is_active==1)
		{
			$user_status = 'Active';
			//to show Inactive btn
			$inactive_params = $user_id."@@#$#@@inactive@@#$#@@".$user_name;
			$action ="<img title='To do in-active' src='images/inactive_user.png' id='user_id$user_id' name='user_id$user_id' onClick=\"active_inactive_user('$inactive_params');\" border='0' style='cursor:pointer;'>&nbsp;&nbsp;";
		}
		else
		{
			$user_status = 'InActive';
			//to show Active btn
			$active_params = $user_id."@@#$#@@active@@#$#@@".$user_name;
			$action ="<img title='To do active' src='images/active_user.png' id='user_id$user_id' name='user_id$user_id' onClick=\"active_inactive_user('$active_params');\" border='0' style='cursor:pointer;'>&nbsp;&nbsp;";
		}
		
		$tmpentry = array();
		$tmpentry[] = htmlspecialchars($user_email);
		$tmpentry[] = htmlspecialchars($user_type_name);
		$tmpentry[] = htmlspecialchars($first_name);
		$tmpentry[] = htmlspecialchars($last_name);
        $tmpentry[] = htmlspecialchars($family_code);
        $tmpentry[] = htmlspecialchars($amount);
		$tmpentry[] = $user_status;
		$tmpentry[] = $user_id_family_code;
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
			return "family_id";
		else if ( $i == 1 )
			return "user_email";
		else if ( $i == 2 )
			return "user_type_id";
		else if ( $i == 3 )
			return "user_address";
		else if ( $i == 4 )
			return "user_phone";
		else if ( $i == 5 )
			return "is_active";
		else 
			return true;			
	}
?>