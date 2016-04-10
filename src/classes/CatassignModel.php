<?php

namespace Monoco\Models;
/**
 * catergory assign model
 */
class CatassignModel extends Model {
	/**
	 * MySQL table name
	 * @var string 
	 */
	protected $_table = 'categoryassign';

	public function getCatNames($id){
		//on other case this can be resolved with "ORM->has_many"
		$cats = NULL;
		$sql = 'SELECT `name` FROM `category` AS `c` LEFT JOIN `'.$this->_table.'` AS `ca` ON `ca`.`categoryid` = `c`.`id` WHERE `ca`.`productid` = '.$id.';';
		$catNames = $this->run($sql);
		foreach ($catNames as $value) {
			$cats[] = $value['name'];
		}
		return $cats;
	}
	
	public function getAssigned(){
		if(is_array($this->values)){
			$category[] = array();
			foreach ($this->values as $key => $value) {
				$category[] = $value["categoryid"];
			}
			return $category;
		}
		return FALSE;
	}
}
