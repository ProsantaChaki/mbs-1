<?php 
session_start();
include("../config/dbConnect.php");
include("../config/dbClass.php");

$dbClass = new dbClass;
$conn       = $dbClass->getDbConn();
$loggedUser = $dbClass->getUserId();	

extract($_REQUEST);

switch ($q){	
	case "insert_customer":	
		$check_customer_email_availability = $dbClass->getSingleRow("select count(email) as no_of_customer from customer_info where email='$email' or personal_code='$personal_code'");
		if($check_customer_email_availability['no_of_customer']!=0) { echo "match"; die;}

		$columns_value = array(
			'customer_name'=>$customer_name,
			'email'=>$email,
			'personal_code'=>$personal_code,
			'password'=>md5($password)
		);	
	
		$return = $dbClass->insert("customer_info", $columns_value);
		if($return) echo 1;
		else 		echo 0;
	
	break;
	
	
	
	case "update_information":		
		$check_customer_email_availability = $dbClass->getSingleRow("select count(email) as no_of_customer from customer_info where email='$email' and id!=$loggedUser");
		if($check_customer_email_availability['no_of_customer']!=0) { echo "2"; die;}
		
		$columns_value = array(
			'customer_name'=>$customer_name,
			'email'=>$email,
			'personal_code'=>$personal_code
		);
		
		$condition_array = array(
			'id'=>$loggedUser
		);
		
		$return = $dbClass->update("customer_info", $columns_value, $condition_array);
		
		if($return==1) echo "1";
		else           echo "0";
	break;
	
	case "get_customer_info":
		$info = "SELECT * FROM customer_info where id = $loggedUser";
		$stmt = $conn->prepare($info);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $row){
			$data['records'] = $row;
		}			
		echo json_encode($data);
	break;
	
}
?>