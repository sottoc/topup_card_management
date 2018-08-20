<?php
	session_start();
	require_once('library/reference.php');
	require_once('autoload.php');	
	require_once('library/mpdf/mpdf.php');

	$report_exportbol=new report_exportbol();
	
	error_reporting(E_ALL);
	$header ='';
	$footer = '';
	$body ='';
	$header ='<table width="99.3%" cellpadding="4" >';	
	$header .=' <tr>
					<td style="width:5%;font-size:15px;text-align:left;color:#253289;">PDF - </td>
					<td style="width:60%;font-size:15px;text-align:left;color:#253289;">Order Delivery List</td>
				</tr>';
	$header .='</table><br/>';

	$footer .="<br/><div style='text-align: center;'>";
	$footer .='<table width="99.3%">';
	$footer .='<tr ><td align="center" style="color:#253289;font-size:12px;"><b style="color:#253289;font-size:12px;">Butterfly Global Pte.Ltd</b></td></tr>';
	$footer .='<tr><td style="height:80px"></td></tr>';
	$footer .='<tr><td style="height:100px;">&nbsp;</td></tr>';
	$footer .='</table>';
	$footer .='<br/>';
	$footer .="</div>";
	/*If a table is split onto more than one page, the first row of the table will be repeated at the top of the new page if either:
<table repeat_header="1"> or
<thead> or <tfoot> is defined*/
	
	$body .= '<table border="1" cellpadding="4" style="width: 99.3%; border-collapse:collapse;" repeat_header="1">';
	$body .= 	'<thead>';
	$body .= 		'<tr>
						<td style="font-size:12px;width:25%;text-align:center;background:#CCCCCC;color:#253289;" ><b><i>Student ID</i></b></td>
						<td style="font-size:12px;width:42%;text-align:center;background:#CCCCCC;color:#253289;" ><b><i>Student Name</i></b></td>
						<td style="font-size:12px;width:8%;text-align:center;background:#CCCCCC;color:#253289;"><b><i>Preorder Date</i></b></td>
						<td style="font-size:12px;width:12%;text-align:center;background:#CCCCCC;color:#253289;"  ><b><i>Meal Type</i></b></td>
						<td style="font-size:12px;width:13%;text-align:center;background:#CCCCCC;color:#253289;"  ><b><i>Food Items Name</b></td>
					</tr>';
	$body .= 	'</thead>';		
	$body .= 	'<tbody>';

	if (isset($_POST['pre_order_id_str']))
	{				
		$pre_order_id_str = $_POST['pre_order_id_str'];	
		$result = $report_exportbol->order_delivery_export($pre_order_id_str);
		while($row=$result->getNext())
		{
			$participant_enroll_no = $row['participant_enroll_no'];
			$participant_name = $row['participant_name'];
			$preorder_date = $row['preorder_date'];
			$category_type_name = $row['category_type_name'];
			$item_name = $row['item_name'];
			$body .= '<tr>
					<td style="text-align:center;font-size:12px;color:#253289;">'.$participant_enroll_no.'</td>
					<td style="text-align:left;font-size:12px;color:#253289;">'.$participant_name.'</td>
					<td style="text-align:center;font-size:12px;color:#253289;">'.$preorder_date.'</td>
					<td style="text-align:center;font-size:12px;color:#253289;">'.$category_type_name.'</td> ';
			$body .='<td style="text-align:center;font-size:12px;color:#253289;">'.$item_name.'</td>
				  </tr>';
		}
		$body .= 	'</tbody>';	
		$body .=	'</table>';
	}
		
	$download_reportname = "PDF_order_delivery";
	$pdf = new mPDF('utf-8', 'A4', 0, 'Tharlon', 10, 4.5, 5, 3, 5, 3, 'P');
	ob_start();   
	$pdf->SetDisplayMode('fullwidth');	
	$pdf->setAutoBottomMargin='stretch';
	$pdf->setAutoTopMargin='stretch';
	$pdf->SetHtmlHeader($header,'','true');
	$pdf->SetHtmlFooter($footer,'');
	$pdf->WriteHTML($body);
	ob_end_clean();
	//echo $header;echo $body;echo $footer;exit();
	$xlsmyFileCutName = $download_reportname.".pdf";
	//$pdf->Output($xlsmyFileCutName,'D');
	$pdf->Output($xlsmyFileCutName,'I');//edit by yma
	
?>