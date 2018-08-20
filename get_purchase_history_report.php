<?php
	require_once('library/reference.php');
	require_once('autoload.php');	
	require_once('library/tcpdf/config/lang/eng.php');
	require_once('library/tcpdf/tcpdf.php');
	require_once('library/pdf_lib.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	if(isset($_POST['sel_student_id']))
	{
		$p_id = $_POST['sel_student_id'];
		
		$retstr = '<h2>Purchase History</h2>';
		$retstr .= '<table cellpadding="0" cellspacing="0" border="1" width="100%"  >';
		$retstr .= '<tr style="background: lightblue; height: 30px; color: #fff;">
					<th>Student ID</th><th>Student Name</th><th>Entry Date</th>
					<th>Entry Time</th><th>Category Name</th><th>Details</th><th>Price</th>';
		
		$reportbol = new reportbol();
		$detail=$reportbol->get_purchase_history_report($p_id,false);
		
		while ($list=$detail->getNext())
		{
			$retstr .= '<tr height="30px" ><td style="text-align:center;">';
			$retstr .= htmlspecialchars($list['participant_enroll_no']);
			$retstr .= '</td><td style="text-align:center;">';
			$retstr .= $list['participant_name'];
			$retstr .= '</td><td style="text-align:center;">';
			$retstr .= $list['dd'];
			$retstr .= '</td><td style="text-align:center;">';
			$retstr .= $list['tt'];
			$retstr .= '</td><td style="text-align:center;">';
			$retstr .= $list['category_type_name'];
			$retstr .= '</td><td style="text-align:center;">';
			$retstr .= $list['item_name'];
			$retstr .= '</td><td style="text-align:right;">';
			$retstr .= $list['price'];
			$retstr .= '</td></tr>';						
		}	
		$retstr .= '</table>';
		$retstr .= "<div style='text-align:right;'><a title='Read More' href='all_purchase_history_report_stuid.php?participant_id=$p_id'>Read More...</a>&nbsp;&nbsp;</div>";
		//$retstr .= '</div>';	
		
		echo $retstr;		
		
	
	}
?>