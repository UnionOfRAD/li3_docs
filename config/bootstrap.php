<?php

// use \lithium\g11n\Catalog;
//
// Catalog::config(array(
// 	'li3_docs'	=> array(
// 		'adapter' => 'Gettext',
// 		'path' => dirname(dirname(__DIR__)) . '/resources/g11n'
// 	)
// ));

use \lithium\net\http\Media;
use \lithium\g11n\Message;

Media::applyFilter('_handle', function($self, $params, $chain) {
	$params['handler'] += array('outputFilters' => array());
	$params['handler']['outputFilters'] += Message::contentFilters();
	return $chain->next($self, $params, $chain);
});
?>