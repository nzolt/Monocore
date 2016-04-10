<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Product extends Controller{

	public function index(){
		return 'user/index';
	}
	
	public function add(){
		$addedTxt = '';
		$errorMsg = '';
		$added = '';
		$post = new Post();
		$category =  Model::Factory('user')->findAll('active = 1','');
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['name'] != ''){
			$image_name = '';
			if($_FILES['image']["name"] != ''){
				$image_name = Image::Factory()->upload();
			}
			$values = $_POST;
			$values['image'] = $image_name;
			$added = Model::Factory('product')->setId(NULL)->values($values)->insert();
		}
		if($added){ 
			$prodId = Model::Instance('product')->getId();
			if(isset($_POST['category'])){
				foreach ($_POST['category'] as $value) {
					Model::Factory('catassign')->setId(NULL)->values(array('productid'=>$prodId,'categoryid'=>$value))->insert();
				}
			}
			$addedTxt = 'Successfully added';
			$post->name = NULL;
			$post->info = NULL;
		} elseif($added === NULL) {
			$errorMsg = 'Unable to add';
		}
		return Template::Factory('productadd')->get(array('category'=>$category,'addedTxt'=>$addedTxt, 'title'=>'Add product', 'errorMsg'=>$errorMsg, 'post'=>$post));
	}
	
	protected function assigned($id){
		
	}

	public function modify(){
		$addedTxt = '';
		$errorMsg = '';
		$added = '';
		$post = new Post();
		$category =  Model::Factory('category')->findAll('active = 1','');
		$prod =  Model::Factory('product')->findById($_REQUEST['id']);
		foreach ($prod[0] as $key => $value) {
			$post->{$key} = $value;
		}
		$post->category = Model::Factory('catassign')->findAll('productid = '.$_REQUEST['id'],'','categoryid', FALSE)->getAssigned();
		$assigned = FALSE;
		if(isset($_POST['name'])){
			if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['name'] != ''){
				$values = array();
				if($_FILES['image']["name"] != ''){
					$image_name = Image::Factory()->upload();
					@unlink(Config::factory('siteconfig.productImageForlder').$prod[0]['image']);
					$values['image'] = $image_name;
				}
				foreach ($_POST as $key => $value) {
					if($key != 'category'){
						if($value != $prod[0][$key]){
							$values[$key] = $value;
						}
					} else {
						if(is_array($_POST['category'])){
							if(count(array_diff($_POST['category'], $post->category[0])) || 
									count(array_diff($post->category[0], $_POST['category']))){
								// if $_POST['category'], $post->category differ delete all assignment
								// could be done on two steps: delete only unassigned and assign only the new
								Model::Factory('catassign')->delete('productid = '.$_POST['id']);
								foreach ($_POST['category'] as $value) {
									$assigned = Model::Factory('catassign')->setId(NULL)->values(array('productid'=>$_POST['id'],'categoryid'=>$value))->insert();
								}
							}	
						}
					}
				}
				if(count($values)){
					$added = Model::Factory('product')->setId($_POST['id'])->values($values)->insert();
				}
			}
		}
		if($added || $assigned){ 
			$addedTxt = 'Successful modification';
			$post->category = Model::Factory('catassign')->findAll('productid = '.$_REQUEST['id'],'','categoryid', FALSE)->getAssigned();
		} elseif($added === NULL) {
			$errorMsg = 'Failed to modify';
		}
		$prod =  Model::Factory('product')->findById($_REQUEST['id']);
		foreach ($prod[0] as $key => $value) {
			$post->{$key} = $value;
		}
		$products = Model::Factory('product')->findAll();
		return Template::Factory('productadd')->get(array('category'=>$category,'addedTxt'=>$addedTxt, 'title'=> 'Modify product', 'errorMsg'=>$errorMsg, 'post'=>$post, 'products'=>$products));
	}
	
	public function activate(){
		$set_to = 0;
		$cat = Model::Factory('product')->findById($_GET['id']);
		if($cat[0]['active'] == 0){
			$set_to = 1;
		}
		Model::Factory('product')->setId($_GET['id'])->values(array('active'=>$set_to))->insert();
		return $this->listing();
	}

	public function listing(){
		$fields = 'id AS ID, name AS NAME, active AS Active ';
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['active'] >= 0){
			$products = Model::Factory('product')->select('active = '.$_POST['active'],'',$fields);
		} else {
			$products = Model::Factory('product')->findAll('','',$fields);
		}
		$table = NULL;
		if(isset($products[0])){
			$table = Functions::assoc2table($products, 'ID', FALSE, 'product', TRUE);
		} else {
			$table = I18n::get('No registered product');
		}
		return Template::Factory('productlist')->get(array('table'=>$table));
	}
	
	public function details(){
		if($_GET['id']){
			$products = Model::Factory('product')->findById($_GET['id']);
		}
		$categories = Model::Factory('catassign')->findAll('productid = '.$_GET['id'],'', 'categoryid');
		//on other case this can be resolved with "ORM->has_many" on the ProductModel
		$products[0]['Categories'] = Model::Factory('catassign')->getCatNames($_GET['id']);
		return Template::Factory('productdtl')->get(array('table'=>Functions::assoc2table($products, 'id', TRUE, 'product', TRUE),'id'=>$_GET['id']));
	}

	public function delete(){
		if($_GET['id']){
			$products = Model::Factory('product')->findById($_GET['id']);
			if(Model::Factory('product')->setId($_GET['id'])->delete()){
				@unlink(Config::factory('siteconfig.productImageForlder').$products[0]['image']);
				return Template::Factory('default')->get(array('message'=>'a Termék törölve'));
			}
			return Template::Factory('default')->get(array('message'=>I18n::get('This product can not be deleted')));
		}
		
	}
}