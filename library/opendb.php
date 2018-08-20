<?php
	$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);//silent, warning, exception
	$conn->exec('SET character_set_results=utf8');
	$conn->exec('SET names=utf8');
	$conn->exec('SET character_set_client=utf8');
	$conn->exec('SET character_set_connection=utf8');
	$conn->exec('SET character_set_results=utf8');
	$conn->exec('SET collation_connection=utf8_general_ci');
?>