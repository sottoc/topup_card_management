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

$report_exportbol=new report_exportbol();

	if(isset($_GET['export_txtfromdate']))
	 	$export_txtfromdate = $_GET['export_txtfromdate'];
	if(isset($_GET['export_txttodate']))
	 	$export_txttodate = $_GET['export_txttodate'];
	
	if(isset($_GET['export']) && $_GET['export']==1)
	{
		if (isset($_SESSION['order_detail_export_excel']))
		{	
			$filename = $_SESSION['order_detail_export_excel'];
			if (file_exists($filename)) 
			{
				chmod($filename, 0777);
				header('Content-type: application/xls;');
				header('Content-Disposition: attachment; filename="Order Schedule Detail.xls"');
				readfile($filename);
			}
			exit();
		}
		else
			echo 'Export Failed';
	}
	else
		unset($_SESSION['order_detail_export_excel']);
	
	$result = $report_exportbol->order_schedule_detail_report($export_txtfromdate,$export_txttodate);
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
	$sheet->getColumnDimension('F')->setWidth(10);
	$sheet->getColumnDimension('G')->setWidth(10);
	
	$sheet->getStyle('A1')->getFont()->setBold(true);		
	$sheet->getStyle('B1')->getFont()->setBold(true);		
	$sheet->getStyle('C1')->getFont()->setBold(true);
	$sheet->getStyle('D1')->getFont()->setBold(true);
	$sheet->getStyle('E1')->getFont()->setBold(true);
	$sheet->getStyle('F1')->getFont()->setBold(true);
	$sheet->getStyle('G1')->getFont()->setBold(true);
	
	$sheet->setCellValue('A1', "Item Name");
	$sheet->setCellValue('B1', "Quantity");
	$sheet->setCellValue('C1', "Student EnrollNo");
	$sheet->setCellValue('D1', "Student Name");
	$sheet->setCellValue('E1', "Class");
	$sheet->setCellValue('F1', "Category Type");
	$sheet->setCellValue('G1', "Food Allergy");
	
	$c=0;
	$i=0;
	$line=2;
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
			$retstr .= $row['others_allergy_food_description'] . ' , ' . $row['food_allergy'];
		$sheet->setCellValue("A$line", $item_name);
		$sheet->setCellValue("B$line", $qty);
		$sheet->setCellValue("C$line", $participant_enroll_no);
		$sheet->setCellValue("D$line", $participant_name);
		$sheet->setCellValue("E$line", $participant_class_name);
		$sheet->setCellValue("F$line", $category_type_name);
		$sheet->setCellValue("G$line", $retstr);
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
		$sheet->duplicateStyleArray($styleArray,"A0".":C$line");
		
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
		$_SESSION['order_detail_export_excel']=$filename;
	}
	else
	{
		echo 'No Record';
		exit();
	}
?>