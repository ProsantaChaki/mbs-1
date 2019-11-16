<?php 
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "mbs_db";
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		echo "Cannot Connect to Database : " . $e->getMessage();
	}
?>