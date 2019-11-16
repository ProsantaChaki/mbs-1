<?php 
session_start();
include("../config/dbConnect.php");
include("../config/dbClass.php");

$dbClass = new dbClass;
$conn       = $dbClass->getDbConn();
$loggedUser = $dbClass->getUserId();	

extract($_REQUEST);

switch ($q){	
	
	case "insert_or_update":		
		$check_account_name_availability = $dbClass->getSingleRow("select count(account_no) as no_of_account from customer_account_info WHERE account_no = '".$account_no."'");
		if($check_account_name_availability['no_of_account']!=0) { echo 5; die;}
		$columns_value = array(
			'account_no'=>$account_no,
			'customer_id'=>$loggedUser
		);	
		$return = $dbClass->insert("customer_account_info", $columns_value);		
		if($return) echo "1";
		else 	    echo "0";
	break;	
	
	
	case "grid_data":	
		$data = array();		
		$sql = "SELECT id, customer_id, account_no, balance
				FROM customer_account_info 
				WHERE customer_id = $loggedUser";				
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		foreach ($result as $row) {
			$data['records'][] = $row;
		}			
		echo json_encode($data);		
	break;
	
	
	case "new_account_no":				
		$data = array();
		$last_account_no = $dbClass->getSingleRow("SELECT MAX(SUBSTR(account_no,9,7)) account_no from customer_account_info");	
		if(empty($last_account_no)) $last_account_no['account_no'] = "1000000"; 
		$account_no = $last_account_no['account_no'] + 1;
		$new_account_no = "125.101.".$account_no;		
		echo $new_account_no;		
	break;
	
	
	
	case "load_account_list":				
		$data = array();
		$sql = "SELECT id, account_no FROM customer_account_info WHERE customer_id = $loggedUser";				
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		foreach ($result as $row) {
			$data['records'][] = $row;
		}			
		echo json_encode($data);		
	break;
	
	case "grid_data_d_w":				
		$data = array();
		$last_account_no = $dbClass->getSingleRow("SELECT count(id) as balance_row  from balance_info where customer_account_id=$account_id");	
		if($last_account_no['balance_row'] !=0){			
			$sql = "SELECT ac.account_no , ifnull(FORMAT(b.amount,2),0) total_amount
					FROM customer_account_info ac 
					LEFT JOIN balance_info b  ON ac.id = b.customer_account_id 
					WHERE ac.id = $account_id AND type = $type";				
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
			foreach ($result as $row) {
				$data['records'][] = $row;
			}					
		}
		echo json_encode($data);		
	break;
	
	
	
	case "save_d_w":
		$prev_acc_balance = $dbClass->getSingleRow("SELECT balance  from customer_account_info where id=$account_id");	
		// if balance less the requested withdrow amount
		if($type==2 && $prev_acc_balance['balance']<$amountt){
			echo 3; die;	
		}
		
		$columns_value = array(
			'customer_account_id'=>$account_id,
			'amount'=>$amountt, 
			'type'=>$type, 
		);	
		$return = $dbClass->insert("balance_info", $columns_value);		
		if($return) {
			if($type ==1 )$new_acc_balance = $prev_acc_balance['balance']+$amountt;
			else 		  $new_acc_balance = $prev_acc_balance['balance']-$amountt;
			
			$columns_value = array(
					'balance'=>$new_acc_balance
			);	
			$condition_array = array(
				'id'=>$account_id
			);
		
			$return = $dbClass->update("customer_account_info", $columns_value, $condition_array);	
			if($return)	echo "1";
			else{
				$sql = "DELETE  FROM balance_info WHERE customer_account_id=$account_id AND type = $type ORDER BY id DESC  LIMIT 1";				
				$stmt = $conn->prepare($sql);
				$stmt->execute();				
				echo "0";				
			}
		}
		else 	    echo "0";
	break;
	
	
	case "save_transfer":
		$prev_acc_balance_from = $dbClass->getSingleRow("SELECT balance  from customer_account_info where id=$account_id");	
		// if balance less the requested withdrow amount
		if($prev_acc_balance_from['balance']<$amountt){echo 3; die;}
		
		// if account number is not available
		
		$to_account_id = $dbClass->getSingleRow("SELECT id from customer_account_info where account_no='$to_account_no'");	
		if(empty($to_account_id['id'])){echo 4; die;}
		
		$prev_acc_balance_to = $dbClass->getSingleRow("SELECT balance  from customer_account_info where id=".$to_account_id['id']);	
		
		//echo $prev_acc_balance_to['balance'].'----',$prev_acc_balance_from['balance'];die;
		
		
		$columns_value = array(
			'from_account_id'=>$account_id,
			'to_account_id'=>$to_account_id['id'],
			'amount'=>$amountt
		);	
		$return = $dbClass->insert("transection_balance", $columns_value);
		
		if($return) {
			// add amount with to account number
			$new_acc_balance_to_acc = $prev_acc_balance_to['balance']+$amountt;
			$columns_value = array(	'balance'=>$new_acc_balance_to_acc);	
			$condition_array = array('id'=>$to_account_id['id']);		
			$return1 = $dbClass->update("customer_account_info", $columns_value, $condition_array);	

			// deduyct amount with to account number
			$new_acc_balance_from_acc = $prev_acc_balance_from['balance']-$amountt;
			$columns_value = array(	'balance'=>$new_acc_balance_from_acc);	
			$condition_array = array('id'=>$account_id);		
			$return2 = $dbClass->update("customer_account_info", $columns_value, $condition_array);
			
			if($return1 && $return2)	echo "1";			
			// rollback the balance and transection 
			else{
				if($return1 ==true && $return2 ==false){
					$columns_value = array(	'balance'=>$prev_acc_balance_to['balance']);	
					$condition_array = array('id'=>$to_account_id['id']);		
					$return1 = $dbClass->update("customer_account_info", $columns_value, $condition_array);					
				}
				else if($return2 ==true && $return1 ==false){
					$columns_value = array(	'balance'=>$prev_acc_balance_from['balance']);	
					$condition_array = array('id'=>$account_id);		
					$return1 = $dbClass->update("customer_account_info", $columns_value, $condition_array);					
				}				
				$sql = "DELETE  FROM transection_balance WHERE from_account_id=$account_id AND to_account_id=".$to_account_id['id']." ORDER BY id DESC  LIMIT 1";				
				$stmt = $conn->prepare($sql);
				$stmt->execute();				
				echo "0";				
			}			
		}
		else 	    echo "0";
	break;
	
	
	
	case "grid_data_transfer":				
		$data = array();
		$last_account_no = $dbClass->getSingleRow("SELECT count(id) as no_of_transfer  from transection_balance where from_account_id=$account_id  OR to_account_id = $account_id");	
		if($last_account_no['no_of_transfer'] !=0){			
			$sql = "SELECT amount, from_account_number, to_account_number, fr_id, tr_id
					FROM(
					SELECT t.amount, fr.account_no AS from_account_number, tr.account_no AS to_account_number, fr.id as fr_id,  tr.id as tr_id
					from transection_balance t
					LEFT JOIN customer_account_info fr ON fr.id= t.from_account_id
					LEFT JOIN customer_account_info tr ON tr.id= t.to_account_id
					 where from_account_id=$account_id  OR to_account_id = $account_id
					 )A";				
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
			foreach ($result as $row) {
				if($row['fr_id'] == $account_id) $row['status'] ="Out";
				else 							 $row['status'] ="In";
				$data['records'][] = $row;
			}					
		}
		echo json_encode($data);		
	break;
	
	
	
}
?>