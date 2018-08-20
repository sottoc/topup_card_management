<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	require_once('library/tcpdf/config/lang/eng.php');
	require_once('library/tcpdf/tcpdf.php');

	$report_exportbol=new report_exportbol();
	// session_start();
	if(isset($_GET['export_txtfromdate']))
	 	$export_txtfromdate = $_GET['export_txtfromdate'];
	if(isset($_GET['export_txttodate']))
	 	$export_txttodate = $_GET['export_txttodate'];
	 	
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
	$tablestr .= "<tr><td colspan=\"7\" style=\"font-size:10;\">Order Schedule Detail List</td></tr>";
	$tablestr .= "<tr>";		
	$tablestr .= "<td style=\"font-size:10;\">Item Name</td>";		
	$tablestr .= "<td style=\"font-size:10;\">Quantity</td>";		
	$tablestr .= "<td style=\"font-size:10;\">Student ID</td>";		
	$tablestr .= "<td style=\"font-size:10;\">Student Name</td>";		
	$tablestr .= "<td style=\"font-size:10;\">Class Name</td>";		
	$tablestr .= "<td style=\"font-size:10;\">Category Type Name</td>";		
	$tablestr .= "<td style=\"font-size:10;\">Food Allergy Name</td>";		
	$tablestr .= "</tr>";		
	
	$result = $report_exportbol->order_schedule_detail_report($export_txtfromdate,$export_txttodate);
	while($row=$result->getNext())
	{
		$item_name = $row['item_name'];
		$qty = $row['total'];
		$participant_enroll_no = $row['participant_enroll_no'];
		$participant_name = $row['participant_name'];
		$participant_class_name = 'Class Name';
		$category_type_name = $row['category_type_name'];
		$retstr='';
		if ( $row['others_allergy_food_description'] == '')
			$retstr .= $row['food_allergy'];
		else if ( $row['food_allergy'] == '')
			$retstr .= $row['others_allergy_food_description'];
		else
			$retstr .= $row['others_allergy_food_description'] . ',' . $row['food_allergy'];
		$tablestr .= "<tr>";		
		$tablestr .= "<td style=\"font-size:10;\">$item_name</td>";		
		$tablestr .= "<td style=\"font-size:10;\">$qty</td>";		
		$tablestr .= "<td style=\"font-size:10;\">$participant_enroll_no</td>";	
		$tablestr .= "<td style=\"font-size:10;\">$participant_name</td>";	
		$tablestr .= "<td style=\"font-size:10;\">$participant_class_name</td>";	
		$tablestr .= "<td style=\"font-size:10;\">$category_type_name</td>";	
		$tablestr .= "<td style=\"font-size:10;\">$retstr</td>";	
		$tablestr .= "</tr>";
	}
	$tablestr .= "</table>";
	
	$pdf->AddPage('L');
	$pdf->writeHTML($tablestr, false, 0, true, true);
	$filename = "order_detail_export_pdf.pdf";
	
	$pdf->Output($filename,'I'); // D
	//print_r($pdf);
	
	//To save pdf file
	//fwrite($fp, $pdf->output($filename, 'S'));
	//fclose($fp);
?>