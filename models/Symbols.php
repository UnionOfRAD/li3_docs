<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

 namespace li3_docs\models;

 /**
  * Symbols model.
  */
 class Symbols extends \lithium\data\Model {
	 /**
	  * Model metadata. Used to set alternate connection.
	  *
	  * @var array
	  */
	 protected $_meta = array(
		 'connection' => 'li3_docs'
	 );
 }

?>