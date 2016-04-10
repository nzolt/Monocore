<?php

namespace Monoco\Etc;
/**
 * 
 */
class I18n {
	protected static $_name = 'I18n';
	public static $_lang;
	protected static $data = array();

	public function __construct() {
		return $this;
	}
	
	public static function lang($lang = NULL){
		$available = Config::factory('siteconfig.availableLanguages');
		if($lang !== NULL && in_array($lang, $available)){
			self::$_lang = $lang;
			self::setLang();
		}
		return self::$_lang;
	}
	
	public static final function setLang(){
		$_SESSION['lang'] = self::$_lang;
		setcookie("lang", self::$_lang);
	}
	
	public static final function setDefaultSet($language){
		if(isset($_SESSION['lang'])){
			setcookie("lang", $language);
		} elseif(isset($_COOKIE['lang'])){
			$_SESSION['lang'] = $_COOKIE['lang'];
		} else {
			self::$_lang = $language;
			self::setLang();
		}
		I18n::lang($language);
	}

	public static function get($str){
		if($str != ''){
			if (($handle = fopen('i18n/'.$_SESSION['lang'].".csv", "r")) !== FALSE) {
				while (($line = fgetcsv($handle, 1000, ",")) !== FALSE) {
					if(isset($line[0]) && isset($line[1])){
						if($line[0] != '' && $line[1] != '')
							self::$data[$line[0]] = $line[1];
					}
				}
				fclose($handle);
			}
			if(key_exists($str,  self::$data)){
				$str = self::$data[$str];
			} else {
				if(Config::factory('siteconfig.displayUntranslated')){
					self::write($str);
				}
			}
		}
		return $str;
	}

	public static function write($txt){
		if(!isset($_SESSION['untranslated'])){
			$_SESSION['untranslated'] = '';
		}
		$_SESSION['untranslated'] .= '"'.$txt.'",""<br/>'.PHP_EOL;
	}
	
	public static final function display(){
		if(Config::factory('siteconfig.displayUntranslated')){
			if(isset($_SESSION['untranslated'])){
				$untranslated = $_SESSION['untranslated'];
				unset($_SESSION['untranslated']);
				return '<div class="untranslated"><p>'.$untranslated.'</p></div>';
			}
			return '<div class="untranslated"><p>'.self::get('All strings are translated').'</p></div>';
		}
		return NULL;
	}
	
}