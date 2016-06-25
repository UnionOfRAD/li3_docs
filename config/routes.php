<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\net\http\Router;

Router::connect('/docs', [
	'library' => 'li3_docs',
	'controller' => 'Docs',
	'action' => 'index'
]);

Router::connect('/docs/api/{:name}/{:version}', [
	'library' => 'li3_docs',
	'controller' => 'Apis',
	'action' => 'view'
]);
Router::connect('/docs/api/{:name}/{:version}/{:symbol:.*}', [
	'library' => 'li3_docs',
	'controller' => 'Apis',
	'action' => 'view'
]);

Router::connect('/docs/book/{:name}/{:version}', [
	'library' => 'li3_docs',
	'controller' => 'Books',
	'action' => 'view'
]);
Router::connect('/docs/book/{:name}/{:version}/{:page:[a-zA-Z\/\-_0-9]+}', [
	'library' => 'li3_docs',
	'controller' => 'Books',
	'action' => 'view'
]);

?>