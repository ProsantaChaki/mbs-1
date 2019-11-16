<?php 			
class dbClass {		
	private $dbCon;	
	private $id;
	
	public function __construct() {
		include("dbConnect.php");			
		$this->dbCon  = $conn;
		if(isset($_SESSION['id']))		$this->id = $_SESSION['id'];
	}
	
	function getDbConn(){
		return $this->dbCon;
	}
	
	function getUserId(){
		return $this->id;
	}
	
	// insert function for all table
	// created ny moynul
	// parameter table name, inserted table column_name and value array
	function insert($table_name ,$columns_values){		
		try {				
			$this->dbCon->beginTransaction();	
			//var_dump($columns_values);die;
			$bind = ':'.implode(',:', array_keys($columns_values));
			$columns =  implode(',', array_keys($columns_values));
				
			$master_sql = "Insert into $table_name ($columns)  VALUES ($bind)";	
			//echo $master_sql;die;	
			$stmt = $this->dbCon->prepare($master_sql);
    		$return = $stmt->execute(array_combine(explode(',',$bind), array_values($columns_values)));
			if($return == 1){
				$just_inserted_id = $this->dbCon->lastInsertId();
				if($just_inserted_id) $original_return = $just_inserted_id;
				else 				  $original_return = 1;
			}
			else 
				$original_return = 0;
			
			$this->dbCon->commit();
			return $original_return; 
			
		} catch(PDOException $e) {
			$this->dbCon->rollback();
			echo "Insert:Error: " . $e->getMessage();
		}		
	}
	
	
	function update($table_name, $columns_values,$condition_array){		
		try {				
			$this->dbCon->beginTransaction();
			$condition_bind = ':'.implode(',:', array_keys($condition_array));			
			$bind = ':'.implode(',:', array_keys($columns_values));
			
			$set_string = "";
			foreach($columns_values as $key=>$value) {
				$set_string .= "$key =:$key, ";
			}
			
			$con_string = "";
			$count_i = 1;
			foreach($condition_array as $key=>$value) {
				if(count($condition_array) != $count_i)
					$con_string .= "$key =:$key AND ";
				else 
					$con_string .= "$key =:$key";				
				$count_i++;
			}
			
			
			$updatesql = "update $table_name set ".rtrim($set_string,", ")."  where $con_string";
			
		//	echo $updatesql;die;
			$stmt = $this->dbCon->prepare($updatesql);
		
			$condition_combined_array  = array_combine(explode(',',$condition_bind), array_values($condition_array));
			$columns_combined_array   = array_combine(explode(',',$bind), array_values($columns_values));
			$bind_array = array_merge($condition_combined_array, $columns_combined_array );  
			$return = $stmt->execute($bind_array); 
			$this->dbCon->commit();
			return $return;
			
		} catch(PDOException $e) {
			$this->dbCon->rollback();
			echo "Insert:Error: " . $e->getMessage();
		}		
	}	
	
	function delete($table_name ,$condition_array){		
		try {	
			$this->dbCon->beginTransaction();
			$condition_bind = ':'.implode(',:', array_keys($condition_array));
			$con_string = "";
			$count_i = 1;
			foreach($condition_array as $key=>$value) {
				if(count($condition_array) != $count_i)
					$con_string .= "$key =:$key AND ";
				else 
					$con_string .= "$key =:$key";				
				$count_i++;
			}
			$deletesql = "delete  from $table_name where $con_string";
			$stmt = $this->dbCon->prepare($deletesql);
			$condition_combined_array  = array_combine(explode(',',$condition_bind), array_values($condition_array));	
    		$return = $stmt->execute($condition_combined_array);
			$this->dbCon->commit();
			return $return; 
			
		} catch(PDOException $e) {
			$this->dbCon->rollback();
			echo "Insert:Error: " . $e->getMessage();
		}		
	}
	
	function getSingleRow($sql){
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return 	$result;	
	}
	
	function getResultList($sql){		
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return 	$result;	
	}	
	
}

?>