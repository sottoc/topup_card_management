<?php
require_once('mpdf/mpdf.php');

class PDF_Lib extends mPDF
{
	var $widths;
	var $aligns;
	var $tablerow='';
	var $dimensions;
	var $totalwidth=0;
	var $cellcount=0;
	var $rowheight=0;
	var $page_Header = "";
	var $page_Header_arr = array();
	var $page_Footer = "";
	var $header_text = "";
	var $hasborder;
	var $type=0;
	    
	function CheckRowFont($rowdata)
	{
		foreach ($rowdata as $celldata)
		{
			if(isChinese($celldata))
			{
				return true;
			}
		}
		return false;
	}
	
	function OpenTable($w, $a)
	{
		$this->setOpenCell(false);
		$this->widths=$w;
		$this->aligns=$a;
		$this->tablerow='';
	}
	
	function CloseTable()
	{
		$this->writeHTML("<table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">$this->tablerow</table>", true, 0, true, 0);	
	}
	
	function AddRow($data, $r, $g, $b)
	{
		$strbgcolor = $this->rgb2html($r,$g,$b);
		$this->tablerow.="<tr bgcolor=\"$strbgcolor\">";
	
		for($i1=0;$i1<count($data) ;$i1++)
		{
			$cellwidth=$this->widths[$i1];
			$cellalign=$this->aligns[$i1];
			
			if($data[$i1]=='')
				$cellcontent = '&nbsp;';
			else
				$cellcontent = str_ireplace("\r\n", "<br />", $data[$i1]);		
			
			$this->tablerow.="<td width=\"$cellwidth\"  valign='top' align=\"$cellalign\">$cellcontent</td>";
		}
	
		$this->tablerow.="</tr>";
	}
	
	function AddHTML($htmltext)
	{
		if($this->CheckRowFont(array($htmltext)))
	 	{
	 		$this->SetFont('arialunicid0');
	 	}
	 	else 
	 	{
	 		//$this->SetFont('arial');
	 		$this->SetFont('');
	 	}
		$this->writeHTML($htmltext, true, 0, true, 0);
	}
	
	function rgb2html($r, $g=-1, $b=-1)
	{
	    if (is_array($r) && sizeof($r) == 3)
	        list($r, $g, $b) = $r;
	
	    $r = intval($r); $g = intval($g);
	    $b = intval($b);
	
	    $r = dechex($r<0?0:($r>255?255:$r));
	    $g = dechex($g<0?0:($g>255?255:$g));
	    $b = dechex($b<0?0:($b>255?255:$b));
	
	    $color = (strlen($r) < 2?'0':'').$r;
	    $color .= (strlen($g) < 2?'0':'').$g;
	    $color .= (strlen($b) < 2?'0':'').$b;
	    return '#'.$color;
	}
	
	function Output($filename='version_file.pdf',$dest='S')
	{
			$this->lastPage();
			return parent::Output($filename,$dest);
	}
	
	 public function Header() {
        switch ($this->type) 
        {
		 	case '0': $this->fundefaultHeader($this->page_Header); break;
		 	case '1': $this->transactionanalysis($this->page_Header_arr); break;
		 	case '2': $this->wagesandbenefits($this->page_Header_arr); break;
		 	default: $this->fundefaultHeader($this->page_Header);
        }	
    }
    
    public function fundefaultHeader($txt)
    {
    	$this->SetY(5);
    	$this->writeHTMLCell(0,5,10,5, $this->page_Header);
    	$this->Ln(20);
    }
    
    public function transactionanalysis($txt)
    {
		$this->SetY(5);
        $this->SetFontSize(10); 
        $this->SetFont('helvetica', 'B');
        if(count($this->page_Header_arr) != 0) {
	        $this->Line($this->GetX(),$this->GetY(), $this->GetX() + 280, $this->GetY());
        	
	        foreach ($txt as $value)
			{	
				$tmpwidth= (float) $value['width']+ 0.5;
				
				if(isset($value['align']))
					$align = $value['align'];
				else $align = "L";
				$this->MultiCell($value['width'] * 3, 20, "$value[headername]", 0, $align , 0, 0, '', '', true);
				//$pdftablestr2 .="<td width=\"".$tmpwidth."%\" ><b>$value[headername]</b></td>";
			}
	        //$this->writeHTMLCell(0,5,10,5, $this->page_Header);
	        $this->Ln(15);
	        $this->Line($this->GetX(),$this->GetY(), $this->GetX() + 280, $this->GetY());
        }
    }
    
    public function wagesandbenefits($txt)
    {
    	if($this->CurOrientation=='P')
    		$linewidth= 200;
    	else 
    		$linewidth= 280;
    		
		$this->SetY(5);
        $this->SetFontSize(10); 
        $this->SetFont('', 'B');
        if(count($this->page_Header_arr) != 0) {
	        $this->Line($this->GetX(),$this->GetY(), $this->GetX() + $linewidth, $this->GetY());
        	$this->Ln();
	        foreach ($txt as $value)
			{	
				$tmpwidth= (float) $value['width']+ 0.5;
				
				$this->MultiCell($value['width'] * 3, 8, "$value[headername]", 0, $value['align'], 0, 0, '', '', true);				
			}
	        $this->Ln();
	        $this->Line($this->GetX(),$this->GetY(), $this->GetX() + $linewidth, $this->GetY());
        }
    }
    
	public function Footer() 
	{
	 switch ($this->type) 
        {
		 	case '0': $this->funDefaultFooter($this->page_Footer); break;
		 	case '1': $this->transactionanalysisFooter($this->page_Footer); break;
		 	case '2': $this->wagesandbenefitsFooter($this->page_Footer); break;
		 	default: $this->funDefaultFooter($this->page_Footer);
        }
    }
    
    public function funDefaultFooter($txt)
    {
    	$timezone = "Asia/Singapore"; //Asia/Rangoon";"Asia/Singapore"
		date_default_timezone_set($timezone);
    	// Position at 1.5 cm from bottom
        $localtime_assoc = localtime(time(), true);
		$year =$localtime_assoc['tm_year'] + 1900;
		$month =$localtime_assoc['tm_mon'] + 1;
		$month = (strlen($month)==1) ? "0".$month : $month;
		$day =$localtime_assoc['tm_mday'];
		$day = (strlen($day)==1) ? "0".$day : $day;
		$dayname = date_format(date_create(date("y-m-d")),'l'); //tm_wday
		$hour =$localtime_assoc['tm_hour'];
		$hour = (strlen($hour)==1) ? "0".$hour : $hour;
		$minutes =$localtime_assoc['tm_min'];
		$minutes = (strlen($minutes)==1) ? "0".$minutes : $minutes;
		$currentdate="$year-$month-$day $hour:$minutes";
		$monthName = date("F", mktime(0, 0, 0, $month, 10));
		$time=date("A");
	

		$this->SetY(-10);
        $this->Line(10,$this->GetY(), 290, $this->GetY());
        // Set font
        $this->SetFont('helvetica', 'I', 8);

        //Time
        $this->Cell(0, 10, "$dayname $day-$monthName-$year  $hour:$minutes", 0, 0, 'L');
        
        // Page number
        $pagenumtxt = 'Page ' . $this->l['w_page'].' '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
        $this->Cell(0, 10, $pagenumtxt, 0, 0, 'R');
        
		/*
        $this->SetY(-10);
        $this->Line(10,$this->GetY(), 290, $this->GetY());
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, "$day-$monthName-$year  $hour:$minutes", 0, 0, 'L');
*/
    }
    
    public function transactionanalysisFooter($txt)
    {
    	$timezone = "Asia/Singapore"; //Asia/Rangoon";"Asia/Singapore"
		date_default_timezone_set($timezone);
    	// Position at 1.5 cm from bottom
        $localtime_assoc = localtime(time(), true);
		$year =$localtime_assoc['tm_year'] + 1900;
		$month =$localtime_assoc['tm_mon'] + 1;
		$month = (strlen($month)==1) ? "0".$month : $month;
		$dayname = date_format(date_create(date("y-m-d")),'l'); //tm_wday
		$day =$localtime_assoc['tm_mday'];
		$day = (strlen($day)==1) ? "0".$day : $day;
		$hour =$localtime_assoc['tm_hour'];
		$hour = (strlen($hour)==1) ? "0".$hour : $hour;
		$minutes =$localtime_assoc['tm_min'];
		$minutes = (strlen($minutes)==1) ? "0".$minutes : $minutes;
		$currentdate="$year-$month-$day $hour:$minutes";
		$monthName = date("F", mktime(0, 0, 0, $month, 10));
		$time=date("A");
	
        $this->SetY(-10);
        $this->Line(10,$this->GetY(), 290, $this->GetY());
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Tim on Left
        $this->Cell(0, 10, "$dayname $day-$monthName-$year  $hour:$minutes", 0, 0, 'L');
        
        // Page number on Right
        $pagenumtxt = 'Page ' . $this->l['w_page'].' '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
        $this->Cell(0, 10, $pagenumtxt, 0, 0, 'R');
    }
    
    public function wagesandbenefitsFooter($txt)
    {
    	$timezone = "Asia/Singapore"; //Asia/Rangoon";"Asia/Singapore"
		date_default_timezone_set($timezone);
		
    	// Position at 1.5 cm from bottom
        $localtime_assoc = localtime(time(), true);
		$year =$localtime_assoc['tm_year'] + 1900;
		$month =$localtime_assoc['tm_mon'] + 1;
		$month = (strlen($month)==1) ? "0".$month : $month;
		$dayname = date_format(date_create(date("y-m-d")),'l'); //tm_wday
		$day =$localtime_assoc['tm_mday'];
		$day = (strlen($day)==1) ? "0".$day : $day;
		$hour =$localtime_assoc['tm_hour'];
		$hour = (strlen($hour)==1) ? "0".$hour : $hour;
		$minutes =$localtime_assoc['tm_min'];
		$minutes = (strlen($minutes)==1) ? "0".$minutes : $minutes;
		$currentdate="$year-$month-$day $hour:$minutes";
		$monthName = date("F", mktime(0, 0, 0, $month, 10));
		$time=date("A");
	
        $this->SetY(-10);
        $this->Line(10,$this->GetY(), 290, $this->GetY());
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, "$dayname $day-$monthName-$year  $hour:$minutes", 0, 0, 'L');
        $pagenumtxt = 'Page ' . $this->l['w_page'].' '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
        $this->Cell(0, 10, $pagenumtxt, 0, 0, 'R');
    }
}
?>