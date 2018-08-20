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
		if (isset($_SESSION['order_summary_export_excel']))
		{	
			$filename = $_SESSION['order_summary_export_excel'];
			if (file_exists($filename)) 
			{
				chmod($filename, 0777);
				header('Content-type: application/xls;');
				header('Content-Disposition: attachment; filename="Order Schedule Summary.xls"');
				readfile($filename);
			}
			exit();
		}
		else
			echo 'Export Failed';
	}
	else
		unset($_SESSION['order_summary_export_excel']);
	
	$result = $report_exportbol->order_schedule_summary_report($export_txtfromdate,$export_txttodate);
	$iResultCount = $result->rowCount();
	//echo $iResultCount;
	
	if($iResultCount>0){ //check records
	$ratesheetbook = new PHPExcel();	
	$sheet = $ratesheetbook->getActiveSheet();
	
	$sheet->getColumnDimension('A')->setWidth(10);
	$sheet->getColumnDimension('B')->setWidth(10);
	$sheet->getColumnDimension('C')->setWidth(10);
	
	$sheet->getStyle('A1')->getFont()->setBold(true);		
	$sheet->getStyle('B1')->getFont()->setBold(true);		
	$sheet->getStyle('C1')->getFont()->setBold(true);
	
	$sheet->setCellValue('A1', "Item Name");
	$sheet->setCellValue('B1', "Quantity");
	$sheet->setCellValue('C1', "Preorder Date");
	
	$c=0;
	$i=0;
	$line=2;
	while($row=$result->getNext())
	{
		$item_name = $row['item_name'];
		$qty = $row['total'];
		$preorder_date = $row['preorder_date'];
		$sheet->setCellValue("A$line", $item_name);
		$sheet->setCellValue("B$line", $qty);
		$sheet->setCellValue("C$line", $preorder_date);
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
		$_SESSION['order_summary_export_excel']=$filename;
	}
	else
	{
		echo 'No Record';
		exit();
	}
?>