<?php

namespace Monoco\Controller;
/**
 * class for handling HTTP request to selected controller
 */
class Controller{
	public static function factory($name, $function){
		$class = new $name();
		if(method_exists($class, $function)){
			return $class->$function();
		} else {
			throw new Exception404;
		}
	}
	 
}