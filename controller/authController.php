<?php 
session_start();
include("../config/dbConnect.php");	

extract($_REQUEST);

switch ($q){	
	case "login":
		$email	    = htmlspecialchars($_POST['email'],ENT_QUOTES);
		$password	= $_POST['password'];

		$query = "SELECT id, customer_name, email, password FROM customer_info WHERE email = '".$email."'";
		//echo $query;die;
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$data = array();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		foreach ($result as $row) {
			$data['records'][] = $row;
		}	
		//if username exists
		if($stmt -> rowCount()>0){
			//compare the password
			if($row['password'] == md5($password)){	
				// need to get these info dynamicly later
				$_SESSION['id']	            = $row['id'];
				$_SESSION['customer_name']	= $row['customer_name'];
				$_SESSION['email']	        = $row['email'];																

				echo 1;
			}
			else
				echo 2; 
		}
		else
			echo 3; //Invalid Login 
	break;	
}
?>