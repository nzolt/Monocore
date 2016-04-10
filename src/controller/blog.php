<?php

namespace Monoco\Controller;
/**
 * Class Category
 * @package Monoco\Controller
 */
class Category extends Controller{

	public function index(){
		return $this->listing();
	}
	
	public function listing(){
		$fields = 'id AS ID, name AS NAME, active AS Active ';
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['active'] >= 0){
			$categories = Model::Factory('category')->findAll('active = '.$_POST['active'],'',$fields);
		} else {
			$categories = Model::Factory('category')->findAll('','',$fields);
		}
		$table = NULL;
		if(isset($categories[0])){
			$table = Functions::assoc2table($categories, 'ID', FALSE, 'category');
		} else {
			$table = 'No registered category';
		}
		return Template::Factory('categorylist')->get(array('table'=>$table));
	}
	
	public function activate(){
		$set_to = 0;
		$cat = Model::Factory('category')->findById($_GET['id']);
		if($cat[0]['active'] == 0){
			$set_to = 1;
		}
		Model::Factory('category')->setId($_GET['id'])->values(array('active'=>$set_to))->insert();
		if($set_to == 0 && Config::factory('siteconfig.catInActDelete')){
			// if one category is deactivated, delete the product->category assigment for this category
			Model::Factory('catassign')->delete('categoryid = '.$_GET['id']);
		}
		return $this->listing();
	}

	public function delete(){
		if($_GET['id']){
			if(Model::Factory('category')->setId($_GET['id'])->delete()){
				return Template::Factory('default')->get(array('message'=>'a Kategória törölve'));
			}
			return Template::Factory('default')->get(array('message'=>'a Kategóriát nem lehet törölni'));
		}
		
	}
	
	public function add(){
		$addedTxt = '';
		$errorMsg = '';
		$added = '';
		$post = new Post();
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$added = Model::Factory('category')->values($_POST)->insert();
		}
		if($added){ 
			$addedTxt = 'Successfully added';
			$post->name = NULL;
		} elseif($added === NULL) {
			$errorMsg = 'Unable to add';
		}
		return Template::Factory('categoryadd')->get(array('addedTxt'=>$addedTxt, 'title'=>'Add category', 'errorMsg'=>$errorMsg, 'post'=>$post));
	}
	
	public function modify(){
		$addedTxt = '';
		$errorMsg = '';
		$added = '';
		$post = new Post();
		$category = Model::Factory('category')->findById($_REQUEST['id']);
		foreach ($category[0] as $key => $value) {
			$post->{$key} = $value;
		}
		$categories = Model::Factory('category')->findAll();
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$added = Model::Factory('category')->setId($_POST['id'])->values($_POST)->insert();
			$category = Model::Factory('category')->findById($_POST['id']);
			foreach ($category[0] as $key => $value) {
				$post->{$key} = $value;
			}	
		}
		if($added){ 
			$addedTxt = 'Successful modification';
			$post->name = NULL;
		} elseif($added === NULL) {
			$errorMsg = 'Failed to modify';
		}
		return Template::Factory('categoryadd')->get(array('addedTxt'=>$addedTxt, 'categories'=>$categories, 'title'=>'Modify category', 'errorMsg'=>$errorMsg, 'post'=>$post));
	}
}