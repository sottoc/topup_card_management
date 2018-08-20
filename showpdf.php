<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	require_once('library/tcpdf/config/lang/eng.php');
	require_once('library/tcpdf/tcpdf.php');

	$report_exportbol=new report_exportbol();
	// session_start();
	$pre_order_id_str="";
	 if(isset($_GET['pre_order_id_str']))
	 	$pre_order_id_str = $_GET['pre_order_id_str'];
	 
		
	//$pdf = new TCPDF("P", PDF_UNIT, array(100, 200), true, 'UTF-8', false);
	$pdf = new TCPDF("L", PDF_UNIT, array(100, 200), true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetMargins(0, 0, 0 ,0);
	$pdf->SetAutoPageBreak(false, 0); //(TRUE, 10);
	$pref=array();
	$pref["PrintScaling"]="false";
	$pref["FitWindow"]="true";
	$pref["CenterWindow"]="true";
	$pref["PickTrayByPDFSize"]="true";
	$pdf->SetViewerPreferences($pref);
	
	// set core font
	$pdf->SetFont('helvetica', '', 2.5);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
    
	$tablestr = "<table border=\"1\">";
	$tablestr .= "<tr><td colspan=\"5\" style=\"font-size:10;\">Delivery Schedule List</td></tr>";
	$tablestr .= "<tr>";		
	$tablestr .= "<td style=\"font-size:10;\">Student ID</td>";		
	$tablestr .= "<td style=\"font-size:10;\">Student Name</td>";		
	$tablestr .= "<td style=\"font-size:10;\">Preorder Date</td>";		
	$tablestr .= "<td style=\"font-size:10;\">Meal Type</td>";		
	$tablestr .= "<td style=\"font-size:10;\">Food Items Name</td>";		
	$tablestr .= "</tr>";		
	
	$result = $report_exportbol->order_delivery_export($pre_order_id_str);
	while($row=$result->getNext())
	{
		$participant_enroll_no = $row['participant_enroll_no'];
		$participant_name = $row['participant_name'];
		$preorder_date = $row['preorder_date'];
		$category_type_name = $row['category_type_name'];
		$item_name = $row['item_name'];
		$tablestr .= "<tr>";		
		$tablestr .= "<td style=\"font-size:10;\">$participant_enroll_no</td>";		
		$tablestr .= "<td style=\"font-size:10;\">$participant_name</td>";		
		$tablestr .= "<td style=\"font-size:10;\">$preorder_date</td>";		
		$tablestr .= "<td style=\"font-size:10;\">$category_type_name</td>";		
		$tablestr .= "<td style=\"font-size:10;\">$item_name</td>";		
		$tablestr .= "</tr>";
	}
	
	$tablestr .= "</table>";
	
	$pdf->AddPage('L');
	$pdf->writeHTML($tablestr, false, 0, true, true);
	$filename = "delivery_schedule.pdf";
	
	$pdf->Output($filename,'I'); // D
	//print_r($pdf);
	
	//To save pdf file
	//fwrite($fp, $pdf->output($filename, 'S'));
	//fclose($fp);
 
	 
?>