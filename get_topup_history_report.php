<?php
	require_once('library/reference.php');
	require_once('autoload.php');	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');

	if(isset($_POST['sel_student_id']))
	{
		$p_id = $_POST['sel_student_id'];
		
		$retstr  = '<h2>TopUp History</h2>';
		$retstr .= '<table cellpadding="0" cellspacing="0" border="1" width="100%"  >';
		$retstr .= '<tr style="background: lightblue; height: 30px; color: #fff;">
					<th>Student ID</th><th>Student Name</th><th>Card No</th><th>Entry Date</th>
					<th>Entry Time</th><th>TopUp Amt</th>';
		
		$reportbol = new reportbol();
		$detail=$reportbol->get_topup_history_report($p_id,false);
		
		while ($list=$detail->getNext())
		{
			$retstr .= '<tr height="30px" ><td style="text-align:center;">';
			$retstr .= htmlspecialchars($list['participant_enroll_no']);
			$retstr .= '</td><td style="text-align:center;">';
			$retstr .= $list['participant_name'];
			$retstr .= '</td><td style="text-align:center;">';
			if($list['card_number']=='' && $list['card_number']==Null)
				$card_number = "No Card";
			else
				$card_number = $list['card_number'];
			$retstr .= $card_number;
			$retstr .= '</td><td style="text-align:center;">';
			$retstr .= $list['dd'];
			$retstr .= '</td><td style="text-align:center;">';
			$retstr .= $list['tt'];
			$retstr .= '</td><td style="text-align:right;">';
			$retstr .= $list['topup_amt'];
			$retstr .= '</td></tr>';						
		}	
		$retstr .= '</table>';
		$retstr .= "<div style='text-align:right;'><a title='Read More' href='all_topup_history_report_stuid.php?participant_id=$p_id'>Read More...</a>&nbsp;&nbsp;</div>";
		//$retstr .= '</div>';	
		echo $retstr;		
		
	
	}
?>