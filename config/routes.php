<?php

use lithium\core\Libraries;
use lithium\action\Response;
use lithium\net\http\Router;
use lithium\net\http\Media;
use li3_docs\extensions\route\Locale;

$config = Libraries::get('li3_docs');
$base = isset($config['url']) ? $config['url'] : '/docs';
$root = $base;
if ($base === '/') {
	$base = '';
}

/**
 * Handles broken URL parsers by matching method URLs with no closing ) and redirecting.
 */
Router::connect("{$base}/{:args}\(", array(), function($request) {
	return new Response(array('location' => "{$request->url})"));
});

Router::connect($root, array('controller' => 'li3_docs.ApiBrowser', 'action' => 'index'));

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

Router::connect('/li3_docs/{:path:img}/{:args}.{:type}', array(), function($request) {
	$req = $request->params;
	$path = implode('/', $req['args']);
	$file = dirname(__DIR__) . "/webroot/{$req['path']}/{$path}.{$req['type']}";

	if (!file_exists($file)) {
		return;
	}

	$media = Media::type($req['type']);
	$content = (array) $media['content'];

	return new Response(array(
			'body' => file_get_contents($file),
			'headers' => array('Content-type' => reset($content))
	));
});

Router::connect('/li3_docs/search/{:query}', array(
	'controller' => 'li3_docs.ApiSearch',
	'action' => 'query'
));

Router::connect("{$base}/{:lib}/{:args}", array(
	'controller' => 'li3_docs.ApiBrowser', 'action' => 'view'
));

Router::connect(new Locale(array(
	'template' => $base,
	'params' => array('controller' => 'li3_docs.ApiBrowser')
)));

Router::connect(new Locale(array(
	'template' => "{$base}/{:lib}/{:args}",
	'params' => array('controller' => 'li3_docs.ApiBrowser', 'action' => 'view')
)));

?>
