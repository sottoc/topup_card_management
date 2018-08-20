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
	$cri_str = ' WHERE u.user_type_id=3 ';//for only staff
	$param = array();
	if ( isset($_GET['sSearch']))
	{	
		$criobj = json_decode($_GET['sSearch']);
		if(isset($criobj->search_txt_staffname) &&  $criobj->search_txt_staffname!='' ){
			$cri_str .= " AND user_name LIKE CONCAT('%',:search_txt_staffname,'%') ";
			$param[':search_txt_staffname'] = clean($criobj->search_txt_staffname);
		}
		if(isset($criobj->search_txt_staffemail) &&  $criobj->search_txt_staffemail!='' ){
			$cri_str .= " AND user_email LIKE CONCAT('%',:search_txt_staffemail,'%') ";
			$param[':search_txt_staffemail'] = clean($criobj->search_txt_staffemail);
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
	
	$rResult= $userbol->get_staff_list($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	while( $aRow = $rResult->getNext() )
	{
		$staff_id = $aRow['user_id'];
		$staff_name= $aRow['user_name'];
		$staff_email=$aRow['user_email'];
		$staff_address=$aRow['user_address'];
		$staff_phone=$aRow['user_phone'];
		$staff_gender_name=$aRow['gender_name'];
		$is_active=$aRow['is_active'];
		
		//edit btn
		$action ="<a title='Edit' href='edit_staff.php?staff_id=$staff_id'><img src='images/blue_edit.gif' border='0'></a>&nbsp;&nbsp;";
		
		//reset_password
		$action .="<a title='Reset Password' href='reset_password.php?user_id=$staff_id'><img src='images/reset_password_22px.png' border='0'></a>&nbsp;&nbsp;";
		
		if($is_active==1)
		{
			$staff_status = 'Active';
			//to show Inactive btn
			$inactive_params = $staff_id."@@#$#@@inactive@@#$#@@".$staff_email;
			$action .="<img title='To do in-active' src='images/inactive_staff_20px.png' id='staff_id$staff_id' name='staff_id$staff_id' onClick=\"active_inactive_staff('$inactive_params');\" border='0' style='cursor:pointer;'>&nbsp;&nbsp;";
		}
		else
		{
			$staff_status = 'In-Active';
			//to show Active btn
			$active_params = $staff_id."@@#$#@@active@@#$#@@".$staff_email;
			$action .="<img title='To do active' src='images/active_staff_20px.png' id='staff_id$staff_id' name='staff_id$staff_id' onClick=\"active_inactive_staff('$active_params');\" border='0' style='cursor:pointer;'>&nbsp;&nbsp;";
		}
		
		$tmpentry = array();
		$tmpentry[] = htmlspecialchars($staff_name);
		$tmpentry[] = htmlspecialchars($staff_email);
		$tmpentry[] = htmlspecialchars($staff_address);
		$tmpentry[] = htmlspecialchars($staff_phone);
		$tmpentry[] = htmlspecialchars($staff_gender_name);
		$tmpentry[] = htmlspecialchars($staff_status);
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
			return "user_name";
		else if ( $i == 1 )
			return "user_email";
		else if ( $i == 2 )
			return "user_address";
		else if ( $i == 3 )
			return "user_phone";
		else if ( $i == 4 )
			return "user_gender_id";
		else if ( $i == 5 )
			return "is_active";
		else 
			return true;			
	}
?>