<?php
use \lithium\action\Dispatcher;
use \lithium\net\http\Media;

Dispatcher::applyFilter('_callable', function($self, $params, $chain) {
	$assets = array('js','css');
	$isAsset = in_array($params['request']->type(), $assets);
	if ($isAsset) {
		return function ($request, $params) {
			extract($params);
			$media = Media::asset($asset, $type, array(
				'plugin' => 'li3_docs', 'absolute' => true
			));
			return file_get_contents($media);
		};
	}
	return $chain->next($self, $params, $chain);
});

require_once LITHIUM_APP_PATH . '/config/bootstrap/g11n.php';

?>