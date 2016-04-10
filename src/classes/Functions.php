<?php

namespace Monoco\Etc;
/**
 * 
 */
class Functions{
	const NAME = 'Functions'; 
	
	/**
	 * 
	 * @param type $file
	 * @return mixed
	 */
	public static function path(){
		$url = explode('/',trim($_SERVER['REQUEST_URI']));
		if(isset($url[2])){
			if($url[2] == 'index.php'){
				$path = 'index.php/';
			} else {
				$path = '';
			}
		}
		return '/'.Config::factory('siteconfig.path').'/'.$path;
	}
	
	public static function getRoute(){
		$shift = 0;
		$class = Config::factory('siteconfig.defaultController');
		$function = Config::factory('siteconfig.defaultFunction');
		$url = explode('/',trim($_SERVER['REQUEST_URI']));
		if($url[1] != Config::factory('siteconfig.path')){
			$shift = -1;
		}
		if(isset($url[2]) && $url[2] != ''){
			if($url[2] == 'index.php'){
				$class = $url[3+$shift];
				if(isset($url[4+$shift])){
					$function = $url[4+$shift];
				}
			} else {
				$class = $url[2+$shift];
				if(isset($url[3+$shift]) && $url[3+$shift] != ''){
					$function = $url[3+$shift];
				}
			}
		}
		return array($class, $function);
	}
	
	public static function dump($var, $die = FALSE) {
		echo '<pre>';
		echo var_dump($var);
		echo '</pre>';
		if ($die) {
			die();
		}
	}
	
	public static function assoc2table($assoc, $id = NULL, $vertical = FALSE, $type = 'request', $details=FALSE) {
		$table = '<table summary="List of user requests" name="'.$type.'" id="'.$type.'" style="width:100%;"><col /><thead><tr>';
		if (!$vertical) {
			foreach ($assoc[0] as $key => $value) {
				$table .= '<th scope="col">'.__($key).'</th>';
			}
			if($details){
				$table .= '<th scope="col">'.__('Details').'</th>';
			}
			$table .= '<th scope="col">Delete</th><th scope="col">'.__('Modify').'</th><th scope="col">'.__('Activate').'</th>';
			$table .= '</tr></thead><tbody>';
			foreach ($assoc as $arrValue) {
				$table .= '<tr>';
				foreach ($arrValue as $key => $value) {
					if(strtolower($key) == 'active'){
						$value = Functions::getActStr($value);
					}
					$table .= '<td ';
					if(strtolower($key) == 'name'){
						$table .= ' class="left"';
					}
					$table .= '>' . $value . '</td>';
				}
				if($details){
					$table .= '<td>' . '<a title="'.__('Detail page').'" href="'.Functions::path().$type.'/details/?id=' . $arrValue[$id] . '">'.__('Details').'</a></td>';
				}
				$table .= '<td><a title="'.__('Delete').'" href="'.Functions::path().$type.'/delete/?id=' . $arrValue[$id] . '">'.__('Delete').'</a></td>
					<td><a title="'.__('Modify').'" href="'.Functions::path().$type.'/modify/?id=' . $arrValue[$id] . '">'.__('Modify').'</a></td>';
				$table .= '<td><a title="'.__('Activate/Deactivate').'" class="act" href="'.Functions::path().$type.'/activate/?id=' . $arrValue[$id] . '">';
				if($arrValue['Active']){
					$table .= __('Deactivate');
				} else {
					$table .= __('Activate');
				}
				$table .= '</a></td></tr>';
			}
		} else {
			$table .= '</tr></thead><tbody>';
			foreach ($assoc as $arrValue) {
				$table .= '';
				foreach ($arrValue as $key => $value) {
					if(is_array($value)){
						$value = implode(', ', $value);
					}
					if(strtolower($key) == 'active'){
						$value = Functions::getActStr($value);
					}
					if(strtolower($key) != 'image'){
						$table .= '<tr><td class="left">' . ucfirst($key) . ': </td><td class="left">' . $value . '</td></tr>';
					}
				}
			}
			if(isset($assoc[0]['image'])){
				$table .= '<tr><td>'.__('Image').':</td><td>' . Image::Factory($assoc[0]['image'])->display() . '</td></tr>';
			}
		}
		$table .= '</tbody></table>';
		return $table;
	}
	
	public static function getMtHash(){
		$mctime = explode(' ', microtime());
		$mctime1 = explode('.', $mctime[0]);
		$mct = $mctime[1].$mctime1[1];
		return base_convert((float) $mct, 10, 16);
	}
	
	public static function selectContent($lines, $selected){
		$res_lines = '';
		foreach($lines as $k=>$v){
			 $res_lines .= '<option value="'.$k.'"';
			 if($k == $selected){
				 $res_lines .= ' selected';
			 }
			 $res_lines .= '>'.$v.'</option>';
		}
		return $res_lines;
	}
	
	public static function getActStr($val){
		if($val){
			return 'Aktív';
		}
		return 'Inaktív';
	}
}