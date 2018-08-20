<?php
	require_once('library/reference.php');
	require_once('autoload.php');	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$current_amt=0;
	$transactionbol= new transactionbol();
	if(isset($_POST['sel_student_id']))
	{
		$p_id = $_POST['sel_student_id'];
		$topup_total_res = $transactionbol->get_total_topupamt_by_stdid($p_id);
		if($topup_total_res->rowCount()>0)
		{
			$row_topup = $topup_total_res->getNext();
			$total_topupamt = $row_topup['total_topupamt'];
		}

		$redemption_total_res = $transactionbol->get_total_redemptionamt_by_stdid($p_id);
		if($redemption_total_res->rowCount()>0)
		{
			$row_redem = $redemption_total_res->getNext();
			$total_redemptionamt = $row_redem['total_redemptionamt'];
		}
		if($total_topupamt > $total_redemptionamt)
			$current_amt= $total_topupamt - $total_redemptionamt;
		else
			$current_amt=0;
		echo '$'.$current_amt;
	}
?>