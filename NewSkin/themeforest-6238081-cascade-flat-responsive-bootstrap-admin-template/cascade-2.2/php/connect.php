<?php
	//Real database
	$mdrservername = "ps2.crsnet";
	$mdrusername = "root";
	$mdrpassword = "fc3582";
	$mdrdbname = "mdr";
try{
	$mdr = new PDO("mysql:dbname=mdr;host=ps2.crsnet;port=3306",$mdrusername,$mdrpassword);
} catch (Exception $e) {
	echo $e->getMessage();
}
phpinfo();
?>