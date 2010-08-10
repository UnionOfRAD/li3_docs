<?php

use \lithium\net\http\Router;
// use \app\extensions\route\Locale;

Router::connect('/docs', array('library' => 'li3_docs', 'controller' => 'api_browser'));
Router::connect('/docs/{:lib}/{:args}', array(
	'library' => 'li3_docs', 'controller' => 'api_browser', 'action' => 'view'
));
// Router::connect(new Locale(array(
// 	'template' => '/docs',
// 	'params' => array(
// 		'plugin' => 'li3_docs',
// 		'controller' => 'browser'
// ))));
// Router::connect(new Locale(array(
// 	'template' => '/docs/{:library}/{:args}',
// 	'params' => array(
// 		'plugin' => 'li3_docs',
// 		'controller' => 'browser', 'action' => 'view',
// 		'args' => null
// ))));

?>