<?php

use \lithium\net\http\Router;
use \app\extensions\net\http\LocaleRoute;

Router::connect(new LocaleRoute(array(
	'template' => '/docs',
	'params' => array(
		'plugin' => 'li3_docs',
		'controller' => 'browser'
))));
Router::connect(new LocaleRoute(array(
	'template' => '/docs/{:library}/{:args}',
	'params' => array(
		'plugin' => 'li3_docs',
		'controller' => 'browser', 'action' => 'view',
		'args' => null
))));

?>