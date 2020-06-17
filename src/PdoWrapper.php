<?php
namespace Easypdo;
 /**
  * Pdo wrapper
  * for simple db interaction
  */
class PdoWrapper{
    private $host;
	private $database;
	private $user;
	private $pass;
	private $charset;
	private $connection;
	
	function __construct($charset='utf8') {
		require_once __DIR__ .'/../config.php';
		$this->host 		= $host;
		$this->database 	= $db;
		$this->user 		= $user;
		$this->password 	= $password;
		$this->charset 		= $charset;
	    $this->connection 	= $this->db_pdo_connect();
    }

	public function db_pdo_connect(){
		try {
				$db_conn = new \PDO("mysql:host=$this->host;dbname=$this->database;charset=$this->charset", $this->user, $this->pass);
				return $db_conn;
		} catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
		}
	}
	
	public function Select($query,$params=array()){
		$this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, FALSE);
		$stmt = $this->connection->prepare($query);
		$stmt->execute($params);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);		
	}
	public function Update($query,$params=array()){
		$stmt = $this->connection->prepare($query);
		if($stmt->execute($params)){
			return $stmt->rowCount();
		}else{
			return false;
		}
	}
	public function Insert($query,$params=array()){
		$stmt = $this->connection->prepare($query);
		$stmt->execute($params);
		$affected_rows = $stmt->rowCount();
		if($affected_rows>0){
			return $this->connection->lastInsertId();
		}else{
			return false;
		}
	}
	
	public function Delete($query,$params=array()){
		$stmt = $this->connection->prepare($query);
		if($stmt->execute($params)){
			return $stmt->rowCount();
		}else{
			return false;
		}
	}
	public function Other($query,$params=array()){
		$stmt = $this->connection->prepare($query);
		if($stmt->execute($params)){
			return true;
		}else{
			return false;
		}
	}
}