<?php

namespace Monoco\Http;
/**
 * 
 */
class Post {
	public function __construct() {
            foreach ($_POST as $key => $value) {
                $this->$key = $value;
            }
        }
	
	public function __get($name) {
		if(isset($this->{$name})){
			return $this->{$name};
		} else {
			return '';
		}
	}
	
	public function __set($name, $value) {
		$this->{$name} = $value;
		return $this;
	}
}