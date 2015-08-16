<?php

use lithium\core\Libraries;
use lithium\action\Response;
use lithium\net\http\Router;

$config = Libraries::get('li3_docs');
$base = $config['url'] == '/' ? '' : $config['url'];

/**
 * Handles broken URL parsers by matching method URLs with no closing ) and redirecting.
 */
Router::connect("{$base}/{:args}\(", array(), function($request) {
	return new Response(array('location' => "{$request->url})"));
});

Router::connect($base ?: '/', array('controller' => 'li3_docs.ApiBrowser', 'action' => 'index'));

Router::connect("{$base}/{:lib}/{:args}", array(
	'controller' => 'li3_docs.ApiBrowser', 'action' => 'view'
));

?>