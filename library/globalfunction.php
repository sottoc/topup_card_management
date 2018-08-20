<?php

function Next_Excel_Col($curcolval) {
    $rcol = "";
    $next = false;
    $curcol = $curcolval;
    $increses = false;
    while ( $curcol != "" ) {
        $col = substr ( $curcol, - 1, 1 );
        $curcol = substr ( $curcol, 0, strlen ( $curcol ) - 1 );
        
        if ($col == "Z") {
            $next = true;
            $rcol = "A" . $rcol;
        
        } else {
            $next = false;
            if ($increses == false) {
                $rcol = chr ( ord ( $col ) + 1 ) . $rcol;
                $increses = true;
            } else {
                $rcol = $col . $rcol;
            }
        }
        if (($curcol == "") && ($next == true)) {
            $rcol = "A" . $rcol;
        }
    }
    return trim ( $rcol );
}

function getdisplayCell_constant()
{
	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	$browsers = array(
		'table-cell' => 'Opera',
		'table-cell'=> 'Firebird', // Use regular expressions as value to identify browser
		'table-cell'=> 'Firefox', // Use regular expressions as value to identify browser
		'table-cell' => 'Galeon',
		'table-cell'=>'Gecko',
		'table-cell'=>'MyIE',
		'table-cell' => 'Lynx',
		'table-cell' => 'Mozilla/4\.75',
		'table-cell' => 'Netscape6',
		'table-cell' => 'Mozilla/4\.08',
		'table-cell' => 'Mozilla/4\.5)',
		'table-cell' => 'Mozilla/4\.6',
		'table-cell' => 'Mozilla/4\.79',
		'table-cell'=>'Konqueror',
		'table-cell' => 'nuhk',
		'table-cell' => 'Googlebot',
		'table-cell' => 'Yammybot',
		'table-cell' => 'Openbot',
		'table-cell' => 'Slurp/cat',
		'table-cell' => 'msnbot',
		'table-cell' => 'ia_archiver',
		'block' => '(MSIE 7\.[0-9]+)',
		'block' => '(MSIE 6\.[0-9]+)',
		'block' => '(MSIE 5\.[0-9]+)',
		'block' => '(MSIE 4\.[0-9]+)',
	);

	foreach($browsers as $browser=>$pattern) { // Loop through $browsers array
    // Use regular expressions to check browser type
//		if (eregi($pattern, $userAgent)) { // Check if a value in $browsers array matches current user agent.
		if (preg_match("/$pattern/i", $userAgent)) { // Check if a value in $browsers array matches current user agent.
			return $browser; // Browser was matched so return $browsers key
		}
	}
	return 'table-cell'; // Cannot find browser so return Unknown
}

	function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec)*10000;
	}

	function validate_length($name,$min,$max)
	{
		if(strlen($name) >= $max || strlen($name) < $min)
			return true;
		else
			return false;
	}
	function validate_required($name)
	{
		if(trim($name) =='')
			return false;
		else
			return true;
	}
	function isChinese($Inputstr)
{	
	/*if($Inputstr=='')
		$Inputstr = 'test for delete';
	*/
	$chrarr = utf8ToUnicode($Inputstr);
	
	$found = 0;
	if($chrarr !=false)
	{
		foreach($chrarr as $c)
		{
			if(($c>=13312 && $c<40960) || ($c>=63744 && $c<64256) || ($c>=131072 && $c<196608))
			{
				$found = 1;
				break;
			}
		}	
	}
	return $found;
}
	function utf8ToUnicode(&$str)
{
  $mState = 0;     // cached expected number of octets after the current octet
                   // until the beginning of the next UTF8 character sequence
  $mUcs4  = 0;     // cached Unicode character
  $mBytes = 1;     // cached expected number of octets in the current sequence

  $out = array();

  $len = strlen($str);
  for($i = 0; $i < $len; $i++) {
    $in = ord($str{$i});
    if (0 == $mState) {
      // When mState is zero we expect either a US-ASCII character or a
      // multi-octet sequence.
      if (0 == (0x80 & ($in))) {
        // US-ASCII, pass straight through.
        $out[] = $in;
        $mBytes = 1;
      } else if (0xC0 == (0xE0 & ($in))) {
        // First octet of 2 octet sequence
        $mUcs4 = ($in);
        $mUcs4 = ($mUcs4 & 0x1F) << 6;
        $mState = 1;
        $mBytes = 2;
      } else if (0xE0 == (0xF0 & ($in))) {
        // First octet of 3 octet sequence
        $mUcs4 = ($in);
        $mUcs4 = ($mUcs4 & 0x0F) << 12;
        $mState = 2;
        $mBytes = 3;
      } else if (0xF0 == (0xF8 & ($in))) {
        // First octet of 4 octet sequence
        $mUcs4 = ($in);
        $mUcs4 = ($mUcs4 & 0x07) << 18;
        $mState = 3;
        $mBytes = 4;
      } else if (0xF8 == (0xFC & ($in))) {
        /* First octet of 5 octet sequence.
         *
         * This is illegal because the encoded codepoint must be either
         * (a) not the shortest form or
         * (b) outside the Unicode range of 0-0x10FFFF.
         * Rather than trying to resynchronize, we will carry on until the end
         * of the sequence and let the later error handling code catch it.
         */
        $mUcs4 = ($in);
        $mUcs4 = ($mUcs4 & 0x03) << 24;
        $mState = 4;
        $mBytes = 5;
      } else if (0xFC == (0xFE & ($in))) {
        // First octet of 6 octet sequence, see comments for 5 octet sequence.
        $mUcs4 = ($in);
        $mUcs4 = ($mUcs4 & 1) << 30;
        $mState = 5;
        $mBytes = 6;
      } else {
        /* Current octet is neither in the US-ASCII range nor a legal first
         * octet of a multi-octet sequence.
         */
        return false;
      }
    } else {
      // When mState is non-zero, we expect a continuation of the multi-octet
      // sequence
      if (0x80 == (0xC0 & ($in))) {
        // Legal continuation.
        $shift = ($mState - 1) * 6;
        $tmp = $in;
        $tmp = ($tmp & 0x0000003F) << $shift;
        $mUcs4 |= $tmp;

        if (0 == --$mState) {
          /* End of the multi-octet sequence. mUcs4 now contains the final
           * Unicode codepoint to be output
           *
           * Check for illegal sequences and codepoints.
           */

          // From Unicode 3.1, non-shortest form is illegal
          if (((2 == $mBytes) && ($mUcs4 < 0x0080)) ||
              ((3 == $mBytes) && ($mUcs4 < 0x0800)) ||
              ((4 == $mBytes) && ($mUcs4 < 0x10000)) ||
              (4 < $mBytes) ||
              // From Unicode 3.2, surrogate characters are illegal
              (($mUcs4 & 0xFFFFF800) == 0xD800) ||
              // Codepoints outside the Unicode range are illegal
              ($mUcs4 > 0x10FFFF)) {
            return false;
          }
          if (0xFEFF != $mUcs4) {
            // BOM is legal but we don't want to output it
            $out[] = $mUcs4;
          }
          //initialize UTF8 cache
          $mState = 0;
          $mUcs4  = 0;
          $mBytes = 1;
        }
      } else {
        /* ((0xC0 & (*in) != 0x80) && (mState != 0))
         * 
         * Incomplete multi-octet sequence.
         */
        return false;
      }
    }
  }
  return $out;
}

	function isEnglish($Inputstr)
	{
	 
		 $chrarr = utf8ToUnicode($Inputstr);
		 
		 $result = true;
		 if($chrarr !=false)
		 {
		  foreach($chrarr as $c)
		  {
		   if($c>=0x80)
		   {
			$result = false;
			break;
		   }
		  }
		 }
		 return $result;
	}
	function savecity($id,$name,$status=1)
	{
		$citybol=new citybol();
		$cityinfo=new cityinfo();
		$cityinfo->setdivisionid($id);
		$cityinfo->setcityname($name);
		$cityinfo->setverify_status($status);
		$cityid = $citybol->save($cityinfo);
		return $cityid ;
	}

	function savetownship($id,$name,$status=1)
	{		
		$townshipbol=new townshipbol();
		$townshipinfo=new townshipinfo();
		$townshipinfo->setcityid($id);
		$townshipinfo->settownshipname($name);
		$townshipinfo->setverify_status($status);
		$townshipid = $townshipbol->save($townshipinfo);
		return $townshipid ;
	}

	function savelocation($id,$name,$status=1)
	{	
		$locationbol=new locationbol();
		$locationinfo=new locationinfo();
		$locationinfo->settownshipid($id);
		$locationinfo->setlocationname($name);
		$locationinfo->setverify_status($status);
		$locationid = $locationbol->save($locationinfo);
		return $locationid ;
	}

	function clean($str) 
	{
		$str = @trim ( $str );
		if (get_magic_quotes_gpc ()) 
		{
			$str = stripslashes ( $str );
		}
		return $str;
	}
	 function tempfile_unique($dir, $prefix, $postfix){
    /* Creates a new non-existant file with the specified post and pre fixes */
   
    if ($dir[strlen($dir) - 1] == '/') {
        $trailing_slash = "";
    } else {
        $trailing_slash = "/";
    }
    /*The PHP function is_dir returns true on files that have no extension.
    The filetype function will tell you correctly what the file is */
    if (!is_dir(realpath($dir)) || filetype(realpath($dir)) != "dir") {
        // The specified dir is not actualy a dir
        return false;
    }
    if (!is_writable($dir)){
        // The directory will not let us create a file there
        return false;
    }
   
    do{    
    	$seed = substr(md5(microtime()), 0, 8);
        $filename = $dir . $trailing_slash . $prefix . $seed . $postfix;
    } while (file_exists($filename));
    $fp = fopen($filename, "w");
    fclose($fp);
    return $filename;
}

	function ReplaceLineFeed($str)
	{
		$str =  str_ireplace("\r\n", "<br>", $str);
		return $str;
	}
	
	function create_div( $lbl, $obj, $type, $val='')
	{
		$str='<div class="frm"><div class="frm_label">'.$lbl.'</div>';
		if($type=='textarea')
			$str .= '<textarea name="'.$obj.'" id="'.$obj.'" >'.$val.'</textarea></div>';
		else
			$str .= '<input type="'.$type.'" name="'.$obj.'" id="'.$obj.'" value="'.$val.'" /></div>';
		echo $str;
	}
	
	function isemail($email) 
	{
		//return preg_match('|^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$|i', $email);
		$emaillistarr=preg_split("/[;,]/",$email);
		foreach ($emaillistarr as $emailadd)
		{
			if(!preg_match('|^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[_a-z0-9-]+(\.[a-z0-9-]+)+$|i', trim($emailadd)))
			{
				return false;
			}
		}
		return true;
	}
	
	//new
	function makemenutreeupdate($nodeid, $nodename, $value, $show, $usertype = 0, $parent= 0) 
	{
		//echo "nodeid=".$nodeid;
		//echo "nodename=".$nodename."<br/>";
		global $countcol;
		global $imgpath;
		global $usertypebol;
		$tmpstr1 = '';
		$tmpstr2 = '';
		$tmpstr = '';
		$retstr2 = '';
		$checkstring="";	
		
		$display = 'enabled';
		if ( $usertype == 1 )
			$display = 'disabled';
		
		$result = $usertypebol->getmenutree($nodeid);
		$rownum = $result->rowCount();
		
		while($row=$result->getNext())
		{
			if($row['submenu'] == 1)
			{
				$tmpstr1 .= makemenutreeupdate($row['menuid'],$row['menuname'],$row['menuid'],$show,$usertype,$row['parent']);
				//echo "tmpstr==>".$row['menuid']."-".$tmpstr1;
			}
			else
			{
				$checkstring = "";
				if($usertype > 0 && $usertype!=1)
				{
					$menu = $usertypebol->getusermenu($usertype);
					
					if(count($menu) > 0)
					{
						foreach($menu as $mval) 
						{
							if($row['menuid'] == $mval)
							{
								$checkstring = "checked";
								break;
							}
						}
					}
				}
				else if ($usertype==1)
					$checkstring = "checked";
				
				$tmpstr2 .= makemenutreeupdate($row['menuid'],$row['menuname'],$row['menuid'],$usertype,$row['parent']);
				$retstr2 .= "<input type ='checkbox' id ='menu[".$row['menuid']."]' name ='menu[]' ";
				$cancel_check = ($show==FALSE) ? "return false;":"checkmnu(document.getElementById(\"trvm$row[menuid]\"),this); ";
				$retstr2 .= " value='".$row['menuid']."' " . $checkstring . " onclick='$cancel_check' ".$display."/>$row[menuname] &nbsp;&nbsp;";
			}
		}
		
		$checkstring = "";
		if($usertype > 0 && $usertype!=1)
		{
			$menu=$usertypebol->getusermenu($usertype);
			
			if(count($menu) > 0)
			{
				foreach($menu as $mval)
				{
					if($value == $mval)
					{
						$checkstring="checked";
						break;
					}
				}
			}
		}
		else if ($usertype==1)
					$checkstring = "checked";
		if($tmpstr1 != '')
		{
			if($nodeid==0)
				$disrow = "";
			else
				$disrow = "style='display:none;'";
			if($nodename == "Menu Name")
			{
				$retstr = "<div class = 'frm_label'>$nodename:</div>";
			}
			else
			{
				$retstr = "<tr><td name='tdmeu$parent' id='tdmeu$value'>";
				$retstr .= "<img id= 'img$parent' border='0' src='images/icon-plus.png' value='$value' onclick='changeimageroot(this,\"trvm$value\");' class='btn-link' /> ";
				$retstr .= "<input type='checkbox' ";
				$cancel_check = ($show==FALSE) ? "return false;":"checkmnu(document.getElementById(\"trvm$value\"),this);";
				$retstr .= " id='menu[$value]' name ='menu[]' value='$value' " . $checkstring . " onclick='$cancel_check' ".$display."/>$nodename</td></tr>";
			}
			
			$retstr .= "<tr><td $disrow id='trvm$value' class='offset1'><table width='100%' cellpadding='0' cellspacing='0' border='0' class='tree_view_tbl'>";
			if($retstr2 != "")
			{
					$retstr .= "<tr><td>$retstr2</td></tr>";
			}
			$retstr .= "$tmpstr1</table></td></tr>";
		}
		else
		{
			$retstr = "<tr><td>";
			if($tmpstr2 != "")
				$retstr .= "<img id= 'img$parent' border='0' src='images/icon-plus.png' value='$value' onclick='changeimageroot(this,\"trvm$value\");' class='btn-link' /> ";
			
			$retstr .= "<input type='checkbox' ";
			$cancel_check = ($show==FALSE) ? "return false;":"checkmnu(document.getElementById(\"trvm$value\"),this);";
			$retstr .= " id ='menu[$value]' name ='menu[]' value='$value' " . $checkstring . " onclick='$cancel_check' ".$display." />$nodename</td></tr>";
			
			if($retstr2 != "")
			{
				$retstr .= "<tr><td style='display:none' class='offset1' id='trvm$value'><table border='0' width='100%''><tr><td>$retstr2</td></tr></table></td></tr>";
			}
		}
		
		return $retstr;
	}
	
	function makemenutree($nodeid, $nodename, $value, $usertype = 0, $parent = 0)
	{
		global $usertypebol;
		$tmpstr = '';
		$checkstring = "";
		
		$result = $usertypebol->getmenutree($nodeid);
		$rownum = $result->rowCount();
		
		while($row=$result->getNext())
		{
			$tmpstr .= makemenutree($row['menuid'], $row['menuname'], $row['menuid'], $usertype, $row['parent']);
		}
		
		if($usertype>0)
		{
			$menu = $usertypebol->getusermenu($usertype);
			
			if(count($menu) > 0)
			{
				foreach($menu as $mval) 
				{
					if($value == $mval)
					{
						$checkstring = "checked";
						break;
					}
					else
						$checkstring = "";
				}	
			}
		}
		
		if($tmpstr != '')
		{
			if($nodename == "Menu List")
				$retstr = "<tr><td><h2>$nodename</h2></td></tr>";
			else
				$retstr = "<tr><td name='tdmeu$parent' id='$value'>sss<input type ='checkbox' id ='menu[$value]' name ='menu[]' value='$value' " . $checkstring . " onclick='check(this,1);' ".$display." />$nodename</td></tr>";
			
			$tmpstr = "<tr><td id='$value'><table style='margin-left:25px;'>$tmpstr</table></td></tr>";
		}
		else
			$retstr = "<tr><td name='tdmeu$parent' id='$value'>aaaa<input type ='checkbox' id ='menu[$value]' name ='menu[]' value='$value' " . $checkstring . " onclick='check(this,1);' ".$display." />$nodename</td></tr>";
		
		return $retstr .$tmpstr;
	}
	
	function get_download_file_64($filename, $directory, $movepath="")
	{
		global $g_upload_path;
		$url = '';
		if($directory.trim("/") <> "")
		{
			$download_file = $directory . '/' . basename($filename);
		}
		else
		{
			$download_file = basename($filename);
		}
		$filename = $g_upload_path . $download_file;
		if (file_exists($filename)) {
			return base64_encode(file_get_contents($filename));
		}
		return false;
	}
	
	function get_download_file_url($filename, $directory, $movepath="")
	{
		global $g_upload_path;
		$url = '';
		if($directory.trim("/") <> "")
		{
			$download_file = $directory . '/' . basename($filename);
		}
		else
		{
			$download_file = basename($filename);
		}
		$filename = $g_upload_path . $download_file;
		//echo "filename=".$filename;
		//echo "download_file=".$download_file;
		if (file_exists($filename)) {
			$url = $movepath."download.php?fp=".rawurlencode(encrypt($download_file));
		}
		return $url;
	}
	
	function upload_file($fileobj, $directory, $customized_filename) 
	{
		//global $g_upload_path;
		global $g_allow_filetype;
		global $g_max_filesize;
		global $g_file_overwrite;
		
		$upload_path = "";
		if($directory.trim("/") <> "")
		{
			//$upload_path = $g_upload_path . $directory . '/';
			$upload_path = $directory . '/';
		}
		/*else
		{
			$upload_path = $g_upload_path;
		}*/
		
		if(!file_exists($upload_path))
		{
			$oldumask = umask(0);  //to work recursive create directory with correct permission
			if (!mkdir($upload_path, 0777, true))
				$err_msg[] = "Invalid file path";
			
			chmod_R($upload_path, 0774);
			umask($oldumask);
		}
		$filename = basename($fileobj["name"]);
		if($customized_filename!='')
			$filename = $customized_filename;
		/*echo $filename."<br/>";
		echo $upload_path."<br/>";
		echo basename($fileobj["name"]);exit();*/
		$upload_file = $upload_path . $filename;
	
		$upload_file_type = $fileobj["type"];
		$err_msg = array();
		$upload_status = 0;
		
		/////////////////required criteria/////////////
		if ($fileobj['error'] != UPLOAD_ERR_OK)
		{
			$err_msg[] = "File error code: ".$fileobj['error'];
		}
		
		/////////////////optional criteria/////////////
		if ($g_max_filesize > 0 && $fileobj["size"] > $g_max_filesize) {	
			$err_msg[] = "File is too large.";
		}
		
		if ($g_file_overwrite == false && file_exists($upload_file)) {
			$err_msg[] = "File already exists.";
		}

		if (count($g_allow_filetype) > 0)
		{
			if (in_array($upload_file_type, $g_allow_filetype)) {
				// file is okay continue
			} else {
				$err_msg[] =  $upload_file_type . " file type not allow.";
			} 
		}
		
		if(count($err_msg) == 0)
		{
			echo "<br/>tmp_name=".$fileobj["tmp_name"];
			echo "<br/>upload_file=".$upload_file;
			if (move_uploaded_file($fileobj["tmp_name"], $upload_file)) {
				chmod($upload_file, 0774);
			} else {
				$err_msg[] = "There was an error uploading your file.";
			}
		}
		
		return $err_msg;
	}
	
	function encrypt($text) 
	{		
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, ENCODEKEY, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
	} 

	function decrypt($text) 
	{
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, ENCODEKEY, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
	}
	
	function chmod_R($pathname, $filemode) { 
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pathname));
		foreach($iterator as $item) {
			chmod($item, $filemode);
		}
	}

	function getallowedproducttypes()
	{
		$current_login_user = $_SESSION['gwtcrm_user_id'];
		$string_producttype_ids = 0;
		$allowed_productidtypes_arr = array();

		$all_producttype_qry = "SELECT product_type_id FROM gwtcrm_tbl_technician_producttype_relation WHERE technician_id = $current_login_user";
		$presult = execute_query($all_producttype_qry);
		$pResult = new readonlyresultset($presult);
		while($aRow = $pResult->getNext())
		{
			array_push($allowed_productidtypes_arr, $aRow['product_type_id']);
		}

		if(sizeof($allowed_productidtypes_arr) > 0)
			$string_producttype_ids = Join(",", $allowed_productidtypes_arr);

		$result = array($string_producttype_ids,$allowed_productidtypes_arr);

		return $result;
	}

	function getallowedservicetypes()
	{
		$current_login_user = $_SESSION['gwtcrm_user_id'];
		$string_servicetype_ids = 0;
		$allowed_servicetypes_arr = array();

		$all_servicetype_qry = "SELECT servicetype_id FROM gwtcrm_tbl_technician_servicetype_relation WHERE technician_id = $current_login_user";
		$sresult = execute_query($all_servicetype_qry);
		$sResult = new readonlyresultset($sresult);
		while($bRow = $sResult->getNext())
		{
			array_push($allowed_servicetypes_arr, $bRow['servicetype_id']);
		}

		if(sizeof($allowed_servicetypes_arr) > 0)
			$string_servicetype_ids = Join(",", $allowed_servicetypes_arr);
		
		$result = array($string_servicetype_ids,$allowed_servicetypes_arr);
		
		return $result;
	}

	function calculateActualCost($product_price,$product_cost,$netamount,$othercost,$quantity)
	{
		//product_cost = (qty *  product_price) - discount
		//product_price = original purchase price
		//othercost = duty fee + shipping cost + other charges

		$actual_cost = 0;
		$price_percentage = 0;
		$other_charges_bypercentage = 0;
		$product_cost = floatval(str_replace(",","",$product_cost));
		$netamount = floatval(str_replace(",","",$netamount));
		$othercost = floatval(str_replace(",","",$othercost));
		$product_price = floatval(str_replace(",","",$product_price));
		$actual_cost += $product_price; //original purchase price 
		if($netamount!=0 && $netamount!=''){
			$price_percentage = (((100/$netamount)*$product_cost)/100);
		}
		//calculation on other charges
		if($quantity && $quantity!=0){
			$other_charges_bypercentage = ($othercost/$quantity) * $price_percentage ;
		}
		$actual_cost += $other_charges_bypercentage;
		return $actual_cost;
	}

	$timezonelist = array('UTC-12:00'=>'(UTC-12:00) International Date Line West',
						'UTC-11:00'=>'(UTC-11:00) Coordinated Universal Time-11',
						'UTC-11:00'=>'(UTC-11:00) Samoa',
						'UTC-10:00'=>'(UTC-10:00) Hawaii',
						'UTC-09:00'=>'(UTC-09:00) Alaska',
						'UTC-08:00'=>'(UTC-08:00) Baja California',
						'UTC-08:00'=>'(UTC-08:00) Pacific Time (US & Canada)',
						'UTC-07:00'=>'(UTC-07:00) Arizona',
						'UTC-07:00'=>'(UTC-07:00) Chihuahua, La Paz, Mazatlan',
						'UTC-07:00'=>'(UTC-07:00) Mountain Time (US & Canada)',
						'UTC-06:00'=>'(UTC-06:00) Central America',
						'UTC-06:00'=>'(UTC-06:00) Central Time (US & Canada)',
						'UTC-06:00'=>'(UTC-06:00) Guadalajara, Mexico City, Monterrey',
						'UTC-06:00'=>'(UTC-06:00) Saskatchewan',
						'UTC-05:00'=>'(UTC-05:00) Bogota, Lima, Quito',
						'UTC-05:00'=>'(UTC-05:00) Eastern Time (US & Canada)',
						'UTC-05:00'=>'(UTC-05:00) Indiana (East)',
						'UTC-04:30'=>'(UTC-04:30) Caracas',
						'UTC-04:00'=>'(UTC-04:00) Asuncion',
						'UTC-04:00'=>'(UTC-04:00) Atlantic Time (Canada)',
						'UTC-04:00'=>'(UTC-04:00) Cuiaba',
						'UTC-04:00'=>'(UTC-04:00) Georgetown, La Paz, Manaus, San Juan',
						'UTC-04:00'=>'(UTC-04:00) Santiago',
						'UTC-03:30'=>'(UTC-03:30) Newfoundland',
						'UTC-03:00'=>'(UTC-03:00) Brasilia',
						'UTC-03:00'=>'(UTC-03:00) Buenos Aires',
						'UTC-03:00'=>'(UTC-03:00) Cayenne, Fortaleza',
						'UTC-03:00'=>'(UTC-03:00) Greenland',
						'UTC-03:00'=>'(UTC-03:00) Montevideo',
						'UTC-02:00'=>'(UTC-02:00) Coordinated Universal Time-02',
						'UTC-02:00'=>'(UTC-02:00) Mid-Atlantic',
						'UTC-01:00'=>'(UTC-01:00) Azores',
						'UTC-01:00'=>'(UTC-01:00) Cape Verde Is.',
						'UTC'=>'(UTC) Casablanca',
						'UTC'=>'(UTC) Coordinated Universal Time',
						'UTC'=>'(UTC) Dublin, Edinburgh, Lisbon, London',
						'UTC'=>'(UTC) Monrovia, Reykjavik',
						'UTC+01:00'=>'(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
						'UTC+01:00'=>'(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague',
						'UTC+01:00'=>'(UTC+01:00) Brussels, Copenhagen, Madrid, Paris',
						'UTC+01:00'=>'(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb',
						'UTC+01:00'=>'(UTC+01:00) West Central Africa',
						'UTC+01:00'=>'(UTC+01:00) Windhoek',
						'UTC+02:00'=>'(UTC+02:00) Amman',
						'UTC+02:00'=>'(UTC+02:00) Athens, Bucharest, Istanbul',
						'UTC+02:00'=>'(UTC+02:00) Beirut',
						'UTC+02:00'=>'(UTC+02:00) Cairo',
						'UTC+02:00'=>'(UTC+02:00) Damascus',
						'UTC+02:00'=>'(UTC+02:00) Harare, Pretoria',
						'UTC+02:00'=>'(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius',
						'UTC+02:00'=>'(UTC+02:00) Jerusalem',
						'UTC+02:00'=>'(UTC+02:00) Minsk',
						'UTC+03:00'=>'(UTC+03:00) Baghdad',
						'UTC+03:00'=>'(UTC+03:00) Kuwait, Riyadh',
						'UTC+03:00'=>'(UTC+03:00) Moscow, St. Petersburg, Volgograd',
						'UTC+03:00'=>'(UTC+03:00) Nairobi',
						'UTC+03:30'=>'(UTC+03:30) Tehran',
						'UTC+04:00'=>'(UTC+04:00) Abu Dhabi, Muscat',
						'UTC+04:00'=>'(UTC+04:00) Baku',
						'UTC+04:00'=>'(UTC+04:00) Port Louis',
						'UTC+04:00'=>'(UTC+04:00) Tbilisi',
						'UTC+04:00'=>'(UTC+04:00) Yerevan',
						'UTC+04:30'=>'(UTC+04:30) Kabul',
						'UTC+05:00'=>'(UTC+05:00) Ekaterinburg',
						'UTC+05:00'=>'(UTC+05:00) Islamabad, Karachi',
						'UTC+05:00'=>'(UTC+05:00) Tashkent',
						'UTC+05:30'=>'(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi',
						'UTC+05:30'=>'(UTC+05:30) Sri Jayawardenepura',
						'UTC+05:45'=>'(UTC+05:45) Kathmandu',
						'UTC+06:00'=>'(UTC+06:00) Astana',
						'UTC+06:00'=>'(UTC+06:00) Dhaka',
						'UTC+06:00'=>'(UTC+06:00) Novosibirsk',
						'UTC+06:30'=>'(UTC+06:30) Yangon (Rangoon)',
						'UTC+07:00'=>'(UTC+07:00) Bangkok, Hanoi, Jakarta',
						'UTC+07:00'=>'(UTC+07:00) Krasnoyarsk',
						'UTC+08:00'=>'(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi',
						'UTC+08:00'=>'(UTC+08:00) Irkutsk',
						'UTC+08:00'=>'(UTC+08:00) Kuala Lumpur, Singapore',
						'UTC+08:00'=>'(UTC+08:00) Perth',
						'UTC+08:00'=>'(UTC+08:00) Taipei',
						'UTC+08:00'=>'(UTC+08:00) Ulaanbaatar',
						'UTC+09:00'=>'(UTC+09:00) Osaka, Sapporo, Tokyo',
						'UTC+09:00'=>'(UTC+09:00) Seoul',
						'UTC+09:00'=>'(UTC+09:00) Yakutsk',
						'UTC+09:30'=>'(UTC+09:30) Adelaide',
						'UTC+09:30'=>'(UTC+09:30) Darwin',
						'UTC+10:00'=>'(UTC+10:00) Brisbane',
						'UTC+10:00'=>'(UTC+10:00) Canberra, Melbourne, Sydney',
						'UTC+10:00'=>'(UTC+10:00) Guam, Port Moresby',
						'UTC+10:00'=>'(UTC+10:00) Hobart',
						'UTC+10:00'=>'(UTC+10:00) Vladivostok',
						'UTC+11:00'=>'(UTC+11:00) Magadan',
						'UTC+11:00'=>'(UTC+11:00) Solomon Is., New Caledonia',
						'UTC+12:00'=>'(UTC+12:00) Auckland, Wellington',
						'UTC+12:00'=>'(UTC+12:00) Coordinated Universal Time+12',
						'UTC+12:00'=>'(UTC+12:00) Fiji',
						'UTC+12:00'=>'(UTC+12:00) Petropavlovsk-Kamchatsky - Old',
						'UTC+13:00'=>'(UTC+13:00) Nuku\'alofa');
$packagelist=array(
	'1'=>'Time Attendance',
	'2'=>'Payroll',
	'3'=>'Human Resource'
);
function cleanjavascript($strings)
{
	/*
	$val=$strings;  
	$array = array("\r\n", "\n\r", "\n", "\r",chr(13),"\\","\"","'"," ");
	$array1 = array("", "", "", "","","\\\\","\\x22","\\x27", "");
	$val=str_replace($array, $array1, $val);
	return $val;
*/	
	
	$str = json_encode($strings);
	$str = substr($str,1,-1);
	return $str;
	
}
 function jsonaddslash($text)
{		
	$text=json_encode($text);
	return substr($text, 1, strlen($text)-2);   //remove first and last double quote
}
 function cleanslash($str) 
	{
		global $conn;
		$str = @trim($str); //echo "a=".$str;
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		} //echo "<br/>b=".$str;
		//return mysql_real_escape_string($str); 
		//echo "<br/>final=".$conn->quote($str);
		return $conn->quote($str);
		//$conn->quote("AAAA ' BBB  ' CCC ");
	}

function showlinebyline($str)
{
	$new_str =  str_ireplace("@, @", "<br/>", $str);
	$final_str =rtrim($new_str, "@");
	return $final_str;
}

 //COM functions are only available for the Windows version of PHP. .Net support requires PHP 5 and the .Net runtime.
 function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}
function currentTimeStamp()
{
	$date = new DateTime();
	return $date->getTimestamp();
}
function send_mail($email,$message,$subject)//toemail,email body,email subject
{ 
	require_once('class.phpmailer.php');
	$mail = new PHPMailer();
	$mail->IsSMTP(); 
	$mail->SMTPDebug  = 0;                     
	$mail->SMTPAuth   = true;                  
	$mail->SMTPSecure = "ssl";                 
	$mail->Host       = "smtp.gmail.com";      
	$mail->Port       = 465;             
	$mail->AddAddress($email);
	$mail->Username="Yinmon@butterfly.sg";  
	$mail->Password="ymayma123";            
	//$mail->SetFrom('Yinmon@butterfly.sg','Coding Cage');
	$mail->From = "Yinmon@butterfly.sg";
	$mail->FromName = "Admin";
	$mail->AddReplyTo("Yinmon@butterfly.sg","Admin Email");
	$mail->Subject    = $subject;
	$mail->MsgHTML($message);
	if(!$mail->Send())
			return false;
		else 	
			return true;
} 
?>