<?php
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';//'lycee';
	$dbname = 'topup_card_management_1'; 
	//$rootpath = 'http://localhost/topup_card_management';
	function url(){
		return sprintf(
		  "%s://%s%s",
		  isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		  $_SERVER['SERVER_NAME'], ''
		);
	}
	$rootpath = url().'/2018-8-13-Singapore/work/topup_card_management';
	$classdirectory = array ('bol', 'commoninfo', 'dal' );	

	//$g_upload_path = "D:/topup_project_images/";

	$g_allow_filetype = array(
							'jpg'	=>'image/jpg', 
							'jpeg'	=>'image/jpeg', 
							'gif'	=>'image/gif', 
							'png'	=>'image/png', 
							'pdf'	=>'application/pdf', 
							'xls'	=>'application/vnd.ms-excel',
							'xlsx'	=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
							'doc'	=>'application/msword',
							'zip' 	=> 'application/octet-stream',
							'docx'	=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
							'txt'	=>'text/plain');
							
	$g_max_filesize = 5 * 1024 * 1024;  //it is Byte value,  5 MB 
	$g_file_overwrite = true;
	define('ENCODEKEY', 'gwtencode');
?>
