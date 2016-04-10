<?php

namespace Monoco\Models;
/**
 * DataModel for Products
 */
class productModel extends Model implements ModelInterface {
	/**
	 * MySQL table name
	 * @var string 
	 */
	protected $_table = 'product';
	/**
	 * MySQL table id name if other than "id" (eg: product_id
	 * @var string 
	 */
	//protected $_id = 'productid';

}
