<?php
session_start();
include("../config/dbConnect.php");
include("../config/dbClass.php");

$dbClass = new dbClass;
$conn       = $dbClass->getDbConn();
$loggedUser = $dbClass->getUserId();

extract($_REQUEST);

switch ($q){

    case "insert_update_nominee":
        $columns_value = array(
            'n_name'=>$n_name,
            'nid'=>$nid,
            'relation'=>$relation,
            'account_number'=>$account_number,
            'priority' => 3,
            'customer_id' => $loggedUser,

        );
        if($Nominee_id<1){
            $return = $dbClass->insert("nominee", $columns_value);
            if($return) echo 1;
            else 		echo 0;
        }
        else{
            $columns_value['id'] =$Nominee_id;
            $condition_array = array('id'=>$Nominee_id);
            $return = $dbClass->update("nominee", $columns_value, $condition_array);
            if($return) echo 1;
            else 		echo 0;
        }


        break;

    case "delete_nominee":

        $condition_array = array('id'=>$Nominee_id);
        $return = $dbClass->delete("nominee", $condition_array);
        if($return) echo 1;
        else 		echo 0;

        break;

    case "grid_data":
        $data = array();
        $sql = "SELECT id, n_name, relation, account_number
				FROM nominee 
				WHERE customer_id = $loggedUser";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;


    case "get_nominee_info":
        $info = "SELECT * FROM nominee where id = $id";
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