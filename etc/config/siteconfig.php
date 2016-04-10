<?php defined('SYSPATH') or die('No direct script access.');
/**
 * SITE configs
 */
return array(
	'path' => '', //path
	'layout' => 'layout', //site layout. /view/layout.php
	'defaultController' => 'site',
	'defaultFunction' => 'index',
	'availableLanguages' => array('en','hu'),
	'useAJAX' => TRUE,
	'productImageForlder' => 'prodimages/', //folder of product iages.
	'catInActDelete' => TRUE, //delete product->category assigment on category inactivation TRUE|FALSE
	'displayUntranslated' => FALSE, //if TRUE display the untranslated strings on the bottom of the on the curent page for {lang}.php
);