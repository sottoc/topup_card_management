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
	
	$rResult= $reportbol->get_order_schedule_summary_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
	$iResultCount = $rResult->rowCount();	
	$iTotal = $rResult->getFoundRows();
	
	$response = array('sEcho'=>$sEcho,'iTotalRecords'=>intval($iTotal),'iTotalDisplayRecords'=>intval($iTotal),'aaData'=>array());
	$row_count = $rResult->rowCount();
	if($row_count > 0)
	{
		while($aRow = $rResult->getNext())
		{
			$tmpentry = array();
			//$tmpentry[] = '<th>' . $aRow['preorder_date'] . '</th>';
			$tmpentry[] = ShowTable($aRow['preorder_date'],$aRow['dd']);			
			$response['aaData'][] = $tmpentry;
		}
	}
	
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
	header("Cache-Control: no-cache, must-revalidate" );
	header("Pragma: no-cache" );
	header("Content-type: text/x-json");
	echo json_encode($response);
	
	//Test for dynamic column
	function ShowTable($preorder_date,$dd)
	{
		
		 $retstr = '<div class="frm" style="margin: 4px 0px;">';
		 $retstr .= '<img id="timg" name="timg" src="images/plus__.png" onclick="changepic(this,document.getElementById(\'list'.$preorder_date.'\'));" style="cursor:pointer;" /> &nbsp;';
		 $retstr .= '<span onclick="changepic(document.getElementById(\'timg\'),document.getElementById(\'list'.$preorder_date.'\'));" style="cursor:pointer;">'.$dd.'</span>';
		 $retstr .= '</div>';		
		 $retstr .= '<div name="list'.$preorder_date.'" id="list'.$preorder_date.'" style="margin: 15px;display:none;">';
		
		//$retstr .= '<b>' .$preorder_date . '</b>'class="display";
		
		$retstr .= '<table cellpadding="0" cellspacing="0" border="1" width="40%">';
		$retstr .= '<tr style="background: lightblue; height: 22px; color: #fff;"><th>Item Name</th><th width="85">Quantity</th>';
		
		$reportbol = new reportbol();
		$detail=$reportbol->get_order_schedule_table_report($preorder_date);
		
		while ($list=$detail->getNext()) {
			$retstr .= '<tr><td>';
			$retstr .= htmlspecialchars($list['item_name']);
			$retstr .= '</td><td>';
			$retstr .= $list['total'];
			$retstr .= '</td></tr>';						
		}	
		$retstr .= '</table>';
		$retstr .= '</div>';	
		return $retstr;		
	}
	//End Test for dynamic column
	
	//for sorting
	function fnColumnToField( $i )
	{
		if ( $i == 0 )
			return "preorder_date";
		else if ( $i == 1 )
			return "item_name";
		else if ( $i == 2 )
			return "total";
		else 
			return true;			
	}
	
?>