<?php

/**
 * Initialize code index.
 */
use lithium\core\Libraries;
use lithium\action\Dispatcher;
use lithium\console\Dispatcher as ConsoleDispatcher;
use li3_docs\extensions\docs\Code;

$filter = function($self, $params, $chain) {
	$indexPath = Libraries::get(true, 'path') . '/resources/docs.index.json';

	if (file_exists($indexPath) && is_readable($indexPath)) {
		Code::index((array) json_decode(file_get_contents($indexPath), true));
	}
	$result = $chain->next($self, $params, $chain);

	if (($index = Code::index()) && is_array($index) && is_writable(dirname($indexPath))) {
		file_put_contents($indexPath, json_encode($index));
	}
	return $result;
};
Dispatcher::applyFilter('run', $filter);
ConsoleDispatcher::applyFilter('run', $filter);

/**
 * Setup default options:
 *
 * - `'index'` _array|void_: Allows to restrict indexing to provided set of libraries.
 *   By default all libraries registered in the application are indexed.
 * - `'categories'` _array|void_: Allows manually provide a set of category names. By
 *    default categories are extracted from all indexed libraries.
 * - `'cache'` _boolean_: When `true` will cache most parsing operations. Defaults to `false`.
 */
Libraries::add('li3_docs', array('bootstrap' => false) + Libraries::get('li3_docs') + array(
	'url' => '/docs',
	'index' => null,
	'categories' => null,
	'cache' => false
));

?>