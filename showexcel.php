<?php
require_once('library/reference.php');
require_once('autoload.php');

 require_once 'library/PHPExcel.php';
  require_once 'library/PHPExcel/IOFactory.php';
require_once 'bol/report_exportbol.php';
  require_once 'dal/report_exportdal.php';
  require_once 'dal/readonlyresultset.php';
  
	if(isset($_SESSION)==false)
		session_start();
//$ratesheetbook = new PHPExcel();	

	$report_exportbol=new report_exportbol();
	//print_r($report_exportbol);
 //	 require_once './library/PHPExcel.php';
 	//require_once './library/PHPExcel/IOFactory.php';
 //require_once('library/globalfunction.php');
	$pre_order_id_str="";
	 if(isset($_GET['pre_order_id_str']))
	 	$pre_order_id_str = $_GET['pre_order_id_str'];
	
	if(isset($_GET['export']) && $_GET['export']==1)
	{
		if (isset($_SESSION['webship_history_export_excel']))
		{	
			$filename = $_SESSION['webship_history_export_excel'];
			if (file_exists($filename)) 
			{
				chmod($filename, 0777);
				header('Content-type: application/xls;');
				header('Content-Disposition: attachment; filename="Order Delivery Schedule.xls"');
				readfile($filename);
			}
			exit();
		}
		else
			echo 'Export Failed';
	}
	else
		unset($_SESSION['webship_history_export_excel']);
	
	$result = $report_exportbol->order_delivery_export($pre_order_id_str);
	$iResultCount = $result->rowCount();
	//echo $iResultCount;
	
	if($iResultCount>0){ //check records
	$ratesheetbook = new PHPExcel();	
	$sheet = $ratesheetbook->getActiveSheet();
	
	$sheet->getColumnDimension('A')->setWidth(10);
	$sheet->getColumnDimension('B')->setWidth(10);
	$sheet->getColumnDimension('C')->setWidth(10);
	$sheet->getColumnDimension('D')->setWidth(10);
	$sheet->getColumnDimension('E')->setWidth(10);
	
	$sheet->getStyle('A1')->getFont()->setBold(true);		
	$sheet->getStyle('B1')->getFont()->setBold(true);		
	$sheet->getStyle('C1')->getFont()->setBold(true);		
	$sheet->getStyle('D1')->getFont()->setBold(true);
	$sheet->getStyle('E1')->getFont()->setBold(true);
	
	$sheet->setCellValue('A1', "Student ID");
	$sheet->setCellValue('B1', "Student Name");
	$sheet->setCellValue('C1', "Preorder Date");
	$sheet->setCellValue('D1', "Meal Type");
	$sheet->setCellValue('E1', "Food Items Name");
	
	$c=0;
	$i=0;
	$line=2;
	while($row=$result->getNext())
	{
		$participant_enroll_no = $row['participant_enroll_no'];
		$participant_name = $row['participant_name'];
		$preorder_date = $row['preorder_date'];
		$category_type_name = $row['category_type_name'];
		$item_name = $row['item_name'];
		$sheet->setCellValue("A$line", $participant_enroll_no);
		$sheet->setCellValue("B$line", $participant_name);
		$sheet->setCellValue("C$line", $preorder_date);
		$sheet->setCellValue("D$line", $category_type_name);
		$sheet->setCellValue("E$line", $item_name);
		$line++;
	}
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
		
		$line=$line-1;
		$sheet->duplicateStyleArray($styleArray,"A0".":E$line");
		
		$filename = tempfile_unique("./tmp", "xlsfile", ".xls");
		$fp = fopen($filename, "w");
		try 
		{ 
			$objWriter = PHPExcel_IOFactory::createWriter($ratesheetbook, 'Excel5');
			$objWriter->setTempDir("../tmp");
			$objWriter->save($filename);
		} 
		catch (Exception $err) 
		{ 
			echo "Excel save error : $err";
		}	
		
 
		
		fclose($fp);
		$_SESSION['webship_history_export_excel']=$filename;
	}
	else
	{
		echo 'No Record';
		exit();
	}
?>