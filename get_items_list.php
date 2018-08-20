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
		if(isset($criobj->search_txt_itemname) &&  $criobj->search_txt_itemname!='' ){
			$cri_str .= " AND item_name LIKE CONCAT('%',:search_txt_itemname,'%') ";
			$param[':search_txt_itemname'] = clean($criobj->search_txt_itemname);
		}
		if(isset($criobj->search_txt_itemprice) &&  $criobj->search_txt_itemprice!='' ){
			$cri_str .= " AND item_price = :search_txt_itemprice";
			$param[':search_txt_itemprice'] = clean($criobj->search_txt_itemprice);
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
	
	$rResult= $setupbol->get_item_list($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	while( $aRow = $rResult->getNext() )
	{
		$item_id = $aRow['item_id'];
		$item_name=$aRow['item_name'];
		$item_desc=$aRow['item_description'];
		$item_price=$aRow['item_price'];
		$item_image_name=$aRow['item_image_name'];
		if($item_image_name==null)
			$item_image_name='item_logo_default.jpg';
		
		//edit btn
		$action ="<a title='Edit' href='edit_item.php?item_id=$item_id'><img src='images/blue_edit.gif' border='0'></a>&nbsp;&nbsp;";
		
		//del btn
		$item_del_params = $item_id."@@#$#@@".$item_name;
		$action .="<img title='Delete' src='images/blue_delete.gif' id='item_id$item_id' name='item_id$item_id' onClick=\"delete_item('$item_del_params');\" border='0' style='cursor:pointer;'>&nbsp;&nbsp;";
		
		$tmpentry = array();
		$tmpentry[] = htmlspecialchars($item_name);
		$tmpentry[] = htmlspecialchars($item_desc);
		$tmpentry[] = $item_price;
		$tmpentry[] = "<img src='ItemImages/".$item_image_name."' width='100px' height='100px'/>";
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
			return "item_name";
		else if ( $i == 1 )
			return "item_description";
		else if ( $i == 2 )
			return "item_price";	
		else 
			return true;			
	}
?>