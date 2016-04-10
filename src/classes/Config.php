<?php
Namespace Monoco;

use Peekmo\JsonPath\JsonPath;

/**
 * Class Config
 * @package Monoco
 */
class Config
{

    var $data = array();
    
    public function __construct($arr = array())
    {
        if (is_array($arr))
        {
            $this->set($arr);
        }
        else if (isset($arr))
        {
            if (file_exists($arr) && is_file($arr))
            {
                $this->set(json_decode(file_get_contents($arr),true));
            }
        }
    }

    public function set($arr)
    {
        $this->data = $arr;
    }
    
    public function get($path)
    {
        $jpath = new JsonPath();
	$value = $jpath->jsonPath($this->data, $path);
	return $value[0];
    }

}
