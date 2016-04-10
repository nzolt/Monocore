<?php

namespace Monoco\Models;
/**
 * 
 */
interface ModelInterface {
	
	public function insert();
	public function find($where, $bind="", $fields="*");
	public function findAll($where="", $bind="", $fields="*");
	public function delete($where = "", $bind="");
	public function values($values);
}