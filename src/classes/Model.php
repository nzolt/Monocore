<?php

namespace Monoco\Models;

use Monoco\Db;
use Monoco\Exception;

/**
 * Class Model
 * @package Monoco\Models
 */
class Model extends Db implements ModelInterface{
	protected $_table = NULL;
	protected $_id = 'id';
	protected static $_name;
	protected static $instance;

	/**
	 * Singleton pattern for MODEL
	 * @param type string
	 * @return object
	 */
	public static function Instance($name){
		if (!is_object(self::$instance) || get_class(self::$instance) != $name.'Model') {
			Model::Factory($name);
		} 
		return self::$instance;
	}

	/**
	 * 
	 * @param string $model
	 * @param string $table
	 * @return object
	 */
	public static function Factory($name){
		$name = $name.'Model';
		try {
			self::$instance = new $name();
		return self::$instance;
		} catch (Exception $exc) {
			throw new DbException();
		}
	}
	
	public function setId($v = NULL){
		$this->{$this->_id} = $v;
		return $this;
	}
	
	public function getId(){
		return $this->{$this->_id};
	}

	public static function setName($name){
		if($name !== NULL){
			self::setName($name);
		}
	}
	
	public function getName(){
		return self::$_name;
	}

	/**
	 * 
	 * @param type string
	 */
	public function setTableName($tableName){
		$this->_table = $tableName;
		return $this;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getTableName(){
		return $this->_table;
	}
	
}