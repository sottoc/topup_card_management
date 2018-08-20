<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$setupbol = new setupbol();
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
		if(isset($criobj->search_orgname) &&  $criobj->search_orgname!='' ){
			$cri_str .= " AND org_name LIKE CONCAT('%',:search_orgname,'%') ";
			$param[':search_orgname'] = clean($criobj->search_orgname);
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
	
	$rResult= $setupbol->get_org_list($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	while( $aRow = $rResult->getNext() )
	{
		$org_id = $aRow['org_id'];
		$org_name=$aRow['org_name'];
		$org_address=$aRow['org_address'];
		$org_description=$aRow['org_description'];
		
		//edit btn
		$action ="<a title='Edit' href='edit_organization.php?org_id=$org_id'><img src='images/blue_edit.gif' border='0'></a>&nbsp;&nbsp;";
		
		//del btn
		$org_del_params = $org_id."@@#$#@@".$org_name;
		$action .="<img title='Delete' src='images/blue_delete.gif' id='org_id$org_id' name='org_id$org_id' onClick=\"delete_org('$org_del_params');\" border='0' style='cursor:pointer;'>&nbsp;&nbsp;";
		
		$tmpentry = array();
		$tmpentry[] = htmlspecialchars($org_name);
		$tmpentry[] = htmlspecialchars($org_address);
		$tmpentry[] = htmlspecialchars($org_description);
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
			return "org_name";
		else if ( $i == 1 )
			return "org_address";
		else if ( $i == 2 )
			return "org_description";
		else 
			return true;			
	}
?>