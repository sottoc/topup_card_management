<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	require_once('userauth.php');
	require_once 'bol/report_exportbol.php';
	require_once 'dal/report_exportdal.php';
	require_once 'dal/readonlyresultset.php';

	$report_exportbol = new report_exportbol();
	$cri_str = '';
	
	if(isset($_GET['export']) && $_GET['export']==1)
	{
		if (isset($_SESSION['order_delivery_export_excel']))
		{	
			$filename = $_SESSION['order_delivery_export_excel'];
			if (file_exists($filename)) 
			{
				chmod($filename, 0777);
				header('Content-type: application/xls;');
				header('Content-Disposition: attachment; filename="Xls_Order_Delivery.xls"');
				readfile($filename);
			}
			exit();
		}
		else
			echo "Export Fail.";
	}
	else
		unset($_SESSION['order_delivery_export_excel']);	
	
	if(isset($_GET['pre_order_id_str']))
		$cri_str = $_GET['pre_order_id_str'];
	
	require_once 'library/PHPExcel.php';
	require_once 'library/PHPExcel/IOFactory.php';
		
	$ratesheetbook = new PHPExcel();	
	$sheet = $ratesheetbook->getActiveSheet();
	
	$sheet->getColumnDimension('A')->setWidth(25);
	$sheet->getColumnDimension('B')->setWidth(25);
	$sheet->getColumnDimension('C')->setWidth(25);
	$sheet->getColumnDimension('D')->setWidth(25);
	$sheet->getColumnDimension('E')->setWidth(25);
	
	$sheet->getStyle('A1')->getFont()->setBold(true);		
	$sheet->getStyle('B1')->getFont()->setBold(true);		
	$sheet->getStyle('C1')->getFont()->setBold(true);		
	$sheet->getStyle('D1')->getFont()->setBold(true);
	$sheet->getStyle('E1')->getFont()->setBold(true);
		
	$sheet->setCellValue('A1', 'Student ID');
	$sheet->setCellValue('B1', 'Student Name');
	$sheet->setCellValue('C1', 'Preorder Date');
	$sheet->setCellValue('D1', 'Meal Type');
	$sheet->setCellValue('E1', 'Food Items Name');
	
		
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array( 'rgb' => '000000' ),
				),
			'inside' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array( 'rgb' => '000000' ),
				),
		)
	);
	$sheet->duplicateStyleArray($styleArray, "A1:E1");
	//echo "cri_str=".$cri_str;
	$result = $report_exportbol->order_delivery_export($cri_str);
	$iResultCount = $result->rowCount();
	
	$c = 2;	
	$i = 1;
	
	if($iResultCount>0)
	{
		while($row=$result->getNext())	
		{
			$sheet->setCellValue("A$c", $row['participant_enroll_no']);			
			$sheet->setCellValue("B$c", $row['participant_name']);
			$sheet->setCellValue("C$c", $row['preorder_date']);			
			$sheet->setCellValue("D$c", $row['category_type_name']);			
			$sheet->setCellValue("E$c", $row['item_name']);
			$c++;	
			$i++;	
		}
		$sheet->duplicateStyleArray($styleArray,"A".($c-$i).":E".($c-1));
		$filename = tempfile_unique("../tmp", "xlsfile", ".xls");
		$fp = fopen($filename, "w");
		try 
		{ 
			$objWriter = PHPExcel_IOFactory::createWriter($ratesheetbook, 'Excel5');
			$objWriter->setTempDir("../tmp");
			$objWriter->save($filename);
			//chmod($xlsmyFileCut, 0777);
		} 
		catch (MyException $e) 
		{ 
			echo "Excel save error : $err";
		}
		
		fclose($fp);
		$_SESSION['order_delivery_export_excel']=$filename;
	}
	else
	{
		echo 'norecord';			 
		exit();
	}
?>