<?php

use lithium\action\Response;
use lithium\net\http\Router;

/**
 * Handles broken URL parsers by matching method URLs with no closing ) and redirecting.
 */
Router::connect("/docs/api/{:args}\(", [], function($request) {
	return new Response(['location' => "{$request->url})"]);
});

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