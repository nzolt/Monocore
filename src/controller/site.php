<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Site extends Controller{

	public function index(){
		return Template::Factory('default')->get(array('title'=>'Wellcome to site!','message'=>'Please log in.'));
	}
	
	public function site(){
		return 'index/site';
	}
	
	public function listing(){
		return 'index/listing';
	}
}