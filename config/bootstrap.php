<?php

/**
 * Register g11n resource.
 */
use lithium\g11n\Catalog;

$catalog = array('li3_docs' => array(
	'adapter' => 'Gettext',
	'path' => dirname(__DIR__) . '/resources/g11n'
));
Catalog::config($catalog + Catalog::config());

if (file_exists(LITHIUM_APP_PATH . '/config/bootstrap/g11n.php')) {
	require_once LITHIUM_APP_PATH . '/config/bootstrap/g11n.php';
}

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
 * Setup default options.
 */
Libraries::add('li3_docs', array('bootstrap' => false) + Libraries::get('li3_docs') + array(
	'url' => '/docs',
));

/**
 * Set up Sqlite3 database for search functionality.
 */
require __DIR__ . '/bootstrap/connections.php';

?>