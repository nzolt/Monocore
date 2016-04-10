<?php

namespace Monoco\Config;
/**
 * Class for handling config vars
 */
class Config{
	
	const NAME = 'config'; 
	protected $_config;
	protected static $_configdir = './config/';
	protected static $_file;
	
	/**
	 * 
	 * @param type string
	 * @return mixed
	 */
	public static function factory($file){
		$file = explode('.', $file);
		self::$_file = $file[0].'.php';
		$config = self::load();
		if(isset($file[1]) && is_array($config)){
			return $config[$file[1]];
		} else {
			return $config;
		}
	}
	
	/**
	 * load config file
	 * @param string $file
	 * @return array
	 */
	protected static function load(){
		try {
			if(file_exists(self::$_configdir.self::$_file)){
				return include self::$_configdir.self::$_file;
			} else {
				throw new ConfigLoadException;
			}
		} catch (ConfigLoadException $exc) {
			//echo $exc->getTraceAsString();
			return FALSE;
		}
	}
	
}