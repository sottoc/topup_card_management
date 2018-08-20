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
		if(isset($criobj->search_txtusertype) &&  $criobj->search_txtusertype!='' ){
			$cri_str .= " AND user_type_name LIKE CONCAT('%',:search_txtusertype,'%') ";
			$param[':search_txtusertype'] = clean($criobj->search_txtusertype);
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
	
	$rResult= $userbol->get_user_type_list($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	while( $aRow = $rResult->getNext() )
	{
		$user_type_id = $aRow['user_type_id'];
		$user_type_name=$aRow['user_type_name'];
		$user_type_desc=$aRow['user_type_description'];
		
		//edit btn
		$action ="<a title='Edit' href='edit_user_type.php?user_type_id=$user_type_id'><img src='images/blue_edit.gif' border='0'></a>&nbsp;&nbsp;";
		
		//del btn
		$user_type_del_params = $user_type_id."@@#$#@@".$user_type_name;
		$action .="<img title='Delete' src='images/blue_delete.gif' id='user_type_id$user_type_id' name='user_type_id$user_type_id' onClick=\"delete_user_type('$user_type_del_params');\" border='0' style='cursor:pointer;'>&nbsp;&nbsp;";
		
		$tmpentry = array();
		$tmpentry[] = htmlspecialchars($user_type_name);
		$tmpentry[] = htmlspecialchars($user_type_desc);
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
			return "user_type_name";
		else if ( $i == 1 )
			return "user_type_description";	
		else 
			return true;			
	}
?>