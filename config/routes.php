<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\net\http\Router;
use lithium\action\Response;
use li3_docs\models\Indexes;

Router::connect('/docs', [
	'library' => 'li3_docs',
	'controller' => 'Docs',
	'action' => 'index'
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

$findLatestIndex = function($name, $version) {
	$indexes = Indexes::find('all', [
		'conditions' => [
			'type' => 'api',
			'name' => $name
		]
	]);
	if (!$indexes->count()) {
		return false;
	}
	$pattern = rtrim(preg_quote($version, '/'), 'x') . '.';

	foreach ($indexes as $index) {
		if (preg_match('/' . $pattern . '/', $index->version)) {
			return $index;
		}
	}
	return false;
};
Router::connect('/docs/api/{:name}/latest:{:version}', [], function($request) use ($findLatestIndex) {
	if (!$latest = $findLatestIndex($request->name, $request->params['version'])) {
		return false;
	}
	return new Response(['location' => [
		'library' => 'li3_docs',
		'controller' => 'Apis',
		'action' => 'view',
		'name' => $latest->name,
		'version' => $latest->version,
		'symbol' => $latest->namespace
	]]);
});
Router::connect('/docs/api/{:name}/latest:{:version}/{:symbol:.*}', [], function($request) use ($findLatestIndex) {
	if (!$latest = $findLatestIndex($request->name, $request->params['version'])) {
		return false;
	}
	return new Response(['location' => [
		'library' => 'li3_docs',
		'controller' => 'Apis',
		'action' => 'view',
		'name' => $latest->name,
		'version' => $latest->version,
		'symbol' => $request->symbol
	]]);
});
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

?>