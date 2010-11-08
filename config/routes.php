<?php

use lithium\action\Response;
use lithium\net\http\Router;
use li3_docs\extensions\route\Locale;

Router::connect('/docs', array('library' => 'li3_docs', 'controller' => 'api_browser'));

Router::connect('/docs/{:lib}/{:args}', array(
	'library' => 'li3_docs', 'controller' => 'api_browser', 'action' => 'view'
));

Router::connect('/li3_docs/{:path:js|css}/{:file}.{:type}', array(), function($request) {
	$req = $request->params;
	$file = dirname(__DIR__) . "/webroot/{$req['path']}/{$req['file']}.{$req['type']}";

	if (!file_exists($file)) {
		return;
	}

	return new Response(array(
		'body' => file_get_contents($file),
		'headers' => array('Content-type' => str_replace(
			array('css', 'js'), array('text/css', 'text/javascript'), $req['type']
		))
	));
});

Router::connect(new Locale(array(
	'template' => '/docs',
	'params' => array('library' => 'li3_docs', 'controller' => 'api_browser')
)));

Router::connect(new Locale(array(
	'template' => '/docs/{:lib}/{:args}',
	'params' => array('library' => 'li3_docs', 'controller' => 'api_browser', 'action' => 'view')
)));

?>