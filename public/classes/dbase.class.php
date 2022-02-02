<?php
if( count(get_included_files()) == 1 ) header("Location: index.html"); //if accessed directly, go to the home page
/*All Database functions here, cut down on edits in case the database connection change in the future*/
class DBase {
	/* for better security, suggest saving this information in a directory outside of htdocs in an ini file (say /etc/host.ini ) and then call it in */
	private $user = 'php_user';
	private $password = 'P@ss_W0rd';
	private $host = 'localhost';
	private $database = 'dealer_inspire';
	private $dblink = null;
	
	private function error_logging($msg){ //log error message with timestamp
		$log = $msg . ' '. date('Y-m-d h:i:s A');
		file_put_contents('db_errors.txt', $log);
	}
	
	function __construct(){//On creation, connect to the database
		try {
			$this->dblink = mysqli_connect($this->host, $this->user, $this->password, $this->database);
		}
		catch (Exception $e){
			$this->error_logging($e->getMessage());
		}
	}
	
	/*
	param set to null since some db connections do not user parameters when binding
	*/
	
	//For when the sql just needs to execute
	public function db_execute($sql, $values, $param = null){
		$stmt = null;
		try{
			//prepare and execute sql
			$stmt = $this->dblink->prepare($sql);
			$stmt->bind_param($param, ...$values);
			$stmt->execute();
		} catch (Exception $e){
			$this->error_logging($e->getMessage());
		}
		
		return $stmt;
	}
	
	//For when result rows are needed
	public function db_results($sql, $values, $param = null){
		$row = array();
		
		try{
			//send off to db_execute
			$stmt = db_execute($sql, $values, $param);
			
			//get results
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			
			//change array keys to lower case
			$row = array_change_key_case($row, CASE_LOWER);
		} catch (Exception $e){
			$this->error_logging($e->getMessage());
		}
		return $row;
	}
}
?>