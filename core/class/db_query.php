<?php class db_query{
	
	// select query 
	function select_query($table_name,$select_data="id",$where="1",$where_arr=array(),$sort_by="",$dd=""){
		global $conn;
		$select_query="SELECT ".$select_data." FROM ".$table_name." WHERE ".$where." $sort_by ";
		$stmt = $conn->prepare($select_query);
		$stmt->execute($where_arr);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if($dd==1)
			echo $stmt->debugDumpParams();
		return  $rows;
	}
	// insert query
	function insert_query($table_name,$insrt_array){
		global $conn;
		if(count($insrt_array)>0){
			$insrt_query="INSERT INTO ".$table_name." SET ";
			foreach($insrt_array as $va=>$key){
				$insrt_query.=$va."=:$va , ";
				$prp_smt_arr[":$va"]=$key;
			}
			$insrt_query =trim($insrt_query," , ");
			$stmt = $conn->prepare($insrt_query);
			$stmt->execute($prp_smt_arr);
			
			
			return $conn->lastInsertId();
			
		}
	}
	// update query
	function update_query($table_name,$updt_array, $where,$where_arr,$dd=""){
		global $conn;
		if(count($updt_array)>0){
			$update_query="UPDATE  ".$table_name." SET ";
			foreach($updt_array as $va=>$key){
				$update_query.=$va."=:$va , ";
				$prp_smt_arr[":$va"]=$key;
			}
			foreach($where_arr as $va=>$key){
				$prp_smt_arr[":$va"]=$key;
			}
			$update_query =trim($update_query," , ");
			$update_query.=" WHERE ".$where;
			
			$stmt = $conn->prepare($update_query);
			$stmt->execute($prp_smt_arr);
			if($dd==1)
				echo $stmt->debugDumpParams();
		}
	}
	// delete query
	function delete_query($table_name, $where, $where_arr){
		global $conn;
		$query_del="DELETE FROM ".$table_name." WHERE ".$where;
		$stmt = $conn->prepare($query_del);
			$stmt->execute($where_arr);
			if($dd==1)
				echo $stmt->debugDumpParams();
	}
} ?>