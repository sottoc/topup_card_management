<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$student_predefinebol = new student_predefinebol();
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
		if(isset($criobj->search_txt_studentname) &&  $criobj->search_txt_studentname!='' ){
			$cri_str .= " AND predefine_participant_name LIKE CONCAT('%',:search_txt_studentname,'%') ";
			$param[':search_txt_studentname'] = clean($criobj->search_txt_studentname);
		}
		if(isset($criobj->search_txt_finger_print_number) &&  $criobj->search_txt_finger_print_number!='' ){
			$cri_str .= " AND finger_print_number LIKE CONCAT('%',:search_txt_finger_print_number,'%') ";
			$param[':search_txt_finger_print_number'] = clean($criobj->search_txt_finger_print_number);
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
	
	$rResult= $student_predefinebol->get_student_predefine_list($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iTotal = $rResult->getFoundRows();
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>$iTotal,'iTotalDisplayRecords'=>$iTotal,'aaData'=>array());
	while( $aRow = $rResult->getNext() )
	{
		$predefine_participant_id = $aRow['predefine_participant_id'];
		$predefine_participant_name= $aRow['predefine_participant_name'];
		$predefine_participant_enroll_no=$aRow['predefine_participant_enroll_no'];
		$predefine_org_id=$aRow['org_name'];
		$finger_print_number=$aRow['finger_print_number'];
		$predefine_parent_name=$aRow['predefine_parent_name'];
		
		//edit btn
		$action ="<a title='Edit' href='edit_predefine_participant.php?predefine_participant_id=$predefine_participant_id'><img src='images/blue_edit.gif' border='0'></a>&nbsp;&nbsp;";
		
		//delete btn
		$student_del_params = $predefine_participant_id."@@#$#@@".$predefine_participant_name."@@#$#@@".$predefine_participant_enroll_no;
		$action .="<img title='Delete' src='images/blue_delete.gif' id='predefine_participant_id$predefine_participant_id' name='predefine_participant_id$predefine_participant_id' border='0' style='cursor:pointer;' onclick=\"delete_predefine_participant('$student_del_params');\">" ;
		
		$tmpentry = array();
		$tmpentry[] = htmlspecialchars($predefine_participant_name);
		$tmpentry[] = htmlspecialchars($predefine_participant_enroll_no);
		$tmpentry[] = htmlspecialchars($predefine_org_id);
		$tmpentry[] = htmlspecialchars($finger_print_number);
		$tmpentry[] = htmlspecialchars($predefine_parent_name);
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
			return "predefine_participant_name";
		else if ( $i == 1 )
			return "predefine_participant_enroll_no";
		else if ( $i == 2 )
			return "org_name";
		else if ( $i == 3 )
			return "finger_print_number";
		else if ( $i == 4 )
			return "predefine_parent_name";
		else 
			return true;			
	}
?>