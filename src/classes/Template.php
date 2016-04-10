<?php

namespace Monoco\View;
/**
 * 
 */
class Template {
	protected static $_name = 'Template';
	protected $_layout = NULL;
	protected $_file;
	public function __construct($file) {
		$this->_file = 'view/'.Config::factory('siteconfig.layout').'.php';
		if ($file !== NULL) {
			$this->_file = 'view/pages/'.$file.'.php';
		}
		return $this;
	}
	
	public static function Factory($file = NULL){
		return new self::$_name($file);
	}

	public function get($content = NULL){
		if(file_exists($this->_file)){
			ob_start();
			require $this->_file;
			return ob_get_clean();
		}
	}
}