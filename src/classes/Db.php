<?php

namespace Monoco\Db;

use Monoco\Config;
use Monoco\BufferLog;
/**
 * Class DB
 * @package Monoco\Db
 */
class Db extends PDO {
	private static $_name = 'DB';

	private $error;
	private $sql;
	private $bind;
	private $errorCallbackFunction;
	private $errorMsgFormat;
	protected $_values;
	protected $_id = 'id';
	public $values = NULL;

	public function __construct()
	{
		$config = new Config(DOCROOT . 'etc/config.json');
		$logger = new BufferLog($config->get(DOCROOT . 'config.Db.logfile'));
		$this->{$this->_id} = NULL;
		$options = array(
			PDO::ATTR_PERSISTENT => false,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		try {
			$this->conn_prm();
			parent::__construct("mysql:host=$this->dbserver;dbname=$this->dbname",$this->dbuser,$this->dbpass);
		} catch (PDOException $e) {
			$logger->error($e->getMessage());
		}
		return $this;
	}

	/**
	 * 
	 * SET Config params
	 */
	public function conn_prm($config){
		$this->dbserver = $config->get('config.Db.host');
		$this->dbname = $config->get('config.Db.user');
		$this->dbuser = $config->get('config.Db.secret');
		$this->dbpass = $config->get('config.Db.dbname');
	}

	public function values($values){
		foreach ($values as $key => $value) {
			if(!is_array($value)){
				$this->_values[$key] = $value;
			}
		}
		return $this;
	}

	/**
	 * 
	 * CRUD Functions START
	 */

	public function select($where="", $bind="", $fields="*") {
		$sql = "SELECT " . $fields . " FROM " . $this->_table;
		if(!empty($where)){
			$sql .= " WHERE " . $where;
		}
		$sql .= ";";
		return $this->run($sql, $bind);
	}
	
	public function find($where, $bind="", $fields="*"){
		return $this->select($where, $bind="", $fields="*");
	}
	
	public function findById($id, $asArray = TRUE){
		$this->values = $this->select($this->_id.'='. PDO::quote($id));
		if($asArray){
			return $this->values;
		}
		return $this;
		
	}

	public function findAll($where="", $bind="", $fields="*", $asArray = TRUE){
		$this->values = $this->select($where, $bind, $fields);
		if($asArray){
			return $this->values;
		}
		return $this;
	}

	public function insert() {
		$bind = array();
		if($this->{$this->_id} === NULL){
			$fields = $this->filter($this->_table, $this->_values);
			$sql = "INSERT INTO " . $this->_table . " (" . implode($fields, ", ") . ") VALUES (:" . implode($fields, ", :") . ");";
			foreach($fields as $field){
				$bind[":$field"] = $this->_values[$field];
			}
			//Functions::dump($sql);
			//Functions::dump($bind);
			return $this->run($sql, $bind);
		} else {
			$where = $this->_id.'='. $this->{$this->_id};
			return $this->update($where, $bind);
		}
	}

	public function update($where, $bind="") {
		$fields = $this->filter($this->_table, $this->_values);
		$fieldSize = sizeof($fields);
		$sql = "UPDATE " . $this->_table . " SET ";
		for($f = 0; $f < $fieldSize; ++$f) {
			if($f > 0)
				$sql .= ", ";
			$sql .= $fields[$f] . " = :update_" . $fields[$f]; 
		}
		$sql .= " WHERE " . $where . ";";
		foreach($fields as $field){
			$bind[":update_$field"] = $this->_values[$field];
		}
		return $this->run($sql, $bind);
	}

	public function delete($where = "", $bind="") {
		if($where == ''){
			$where = $this->_id.'='. PDO::quote($this->{$this->_id});
		}
		$sql = "DELETE FROM " . $this->_table . " WHERE " . $where . ";";
		$res = $this->run($sql, $bind);
		if($res){
			$this->{$this->_id} = NULL;
			$this->_values = NULL;
			return $res;
		}
		return FALSE;
		
	}

	/**
	 * 
	 * CRUD Functions END
	 * Extend Functions START
	 */
	private function filter() {
		$driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
		if($driver == 'sqlite') {
			$sql = "PRAGMA table_info('" . $this->_table . "');";
			$key = "name";
		}
		elseif($driver == 'mysql') {
			$sql = "DESCRIBE " . $this->_table . ";";
			$key = "Field";
		}
		else {	
			$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $this->_table . "';";
			$key = "column_name";
		}
		if(false !== ($list = $this->run($sql))) {
			$fields = array();
			foreach($list as $record)
				$fields[] = $record[$key];
			return array_values(array_intersect($fields, array_keys($this->_values)));
		}
		return array();
	}

	private function cleanup($bind) {
		if(!is_array($bind)) {
			if(!empty($bind))
				$bind = array($bind);
			else
				$bind = array();
		}
		return $bind;
	}

	/**
	 * 
	 * EXECUTE Function
	 */
	public function run($sql, $bind="") {
		$this->sql = trim($sql);
		$this->bind = $this->cleanup($bind);
		$this->error = "";

		try {
			$pdostmt = $this->prepare($this->sql);
			if($pdostmt->execute($this->bind) !== false) {
				if(preg_match("/^(" . implode("|", array("select", "describe", "pragma")) . ") /i", $this->sql))
					return $pdostmt->fetchAll(PDO::FETCH_ASSOC);
				elseif(preg_match("/^(" . implode("|", array("delete", "insert", "update")) . ") /i", $this->sql))
					if(PDO::lastInsertId()){
						$this->{$this->_id} = PDO::lastInsertId();
					}
					return $pdostmt->rowCount();
			}	
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			$this->debug();
			return false;
		}
	}

	/**
	 * DEBUG Function
	 */
	private function debug() {
		if(!empty($this->errorCallbackFunction)) {
			$error = array("Error" => $this->error);
			if(!empty($this->sql))
				$error["SQL Statement"] = $this->sql;
			if(!empty($this->bind))
				$error["Bind Parameters"] = trim(print_r($this->bind, true));

			$backtrace = debug_backtrace();
			if(!empty($backtrace)) {
				foreach($backtrace as $info) {
					if($info["file"] != __FILE__)
						$error["Backtrace"] = $info["file"] . " at line " . $info["line"];	
				}		
			}

			$msg = "";
			if($this->errorMsgFormat == "html") {
				if(!empty($error["Bind Parameters"]))
					$error["Bind Parameters"] = "<pre>" . $error["Bind Parameters"] . "</pre>";
				$css = trim(file_get_contents(dirname(__FILE__) . "../css/error.css"));
				$msg .= '<style type="text/css">' . "\n" . $css . "\n</style>";
				$msg .= "\n" . '<div class="db-error">' . "\n\t<h3>SQL Error</h3>";
				foreach($error as $key => $val)
					$msg .= "\n\t<label>" . $key . ":</label>" . $val;
				$msg .= "\n\t</div>\n</div>";
			}
			elseif($this->errorMsgFormat == "text") {
				$msg .= "SQL Error\n" . str_repeat("-", 50);
				foreach($error as $key => $val)
					$msg .= "\n\n$key:\n$val";
			}

			$func = $this->errorCallbackFunction;
			$func($msg);
		}
	}

	/**
	 * 
	 * ERROR Function
	 */
	public function setErrorCallbackFunction($errorCallbackFunction, $errorMsgFormat="html") {
		if(in_array(strtolower($errorCallbackFunction), array("echo", "print")))
			$errorCallbackFunction = "print_r";

		if(function_exists($errorCallbackFunction)) {
			$this->errorCallbackFunction = $errorCallbackFunction;	
			if(!in_array(strtolower($errorMsgFormat), array("html", "text")))
				$errorMsgFormat = "html";
			$this->errorMsgFormat = $errorMsgFormat;	
		}	
	}
}	
?>
