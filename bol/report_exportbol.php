<?php
class report_exportbol{
	
	function order_delivery_export($cri_str)
	{
		$report_exportdal=new report_exportdal();
		$result=$report_exportdal->order_delivery_export($cri_str);
		return $result;
	}
	function order_schedule_summary_report($export_txtfromdate,$export_txttodate)
	{
		$report_exportdal=new report_exportdal();
		$result=$report_exportdal->order_schedule_summary_report($export_txtfromdate,$export_txttodate);
		return $result;
	}
	function order_schedule_detail_report($export_txtfromdate,$export_txttodate)
	{
		$report_exportdal=new report_exportdal();
		$result=$report_exportdal->order_schedule_detail_report($export_txtfromdate,$export_txttodate);
		return $result;
	}
}
?>