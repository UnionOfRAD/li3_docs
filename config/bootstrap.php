<?php

/**
 * Register g11n resource.
 */
use \lithium\g11n\Catalog;

Catalog::config(array(
	'li3_docs' => array(
		'adapter' => 'Gettext',
		'path' => dirname(__DIR__) . '/resources/g11n'
)) + Catalog::config());

/**
 * Filter to serve the assets from plugins.
 *
 */
use \lithium\action\Dispatcher;
use \lithium\core\Libraries;
use \lithium\net\http\Media;

Dispatcher::applyFilter('_callable', function($self, $params, $chain) {
	list($plugin, $asset) = explode('/', $params['request']->url, 2) + array("", "");
	if ($asset && $library = Libraries::get($plugin)) {
		$asset = "{$library['path']}/webroot/{$asset}";

		if (file_exists($asset)) {
			return function () use ($asset) {
				$info = pathinfo($asset);
				$type = Media::type($info['extension']);
				header("Content-type: {$type['content']}");
				return file_get_contents($asset);
			};
		}
	}
	return $chain->next($self, $params, $chain);
});

?>