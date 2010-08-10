<?php

use \lithium\net\http\Router;
use \li3_docs\extensions\route\Locale;

Router::connect('/docs', array('library' => 'li3_docs', 'controller' => 'api_browser'));
Router::connect('/docs/{:lib}/{:args}', array(
	'library' => 'li3_docs', 'controller' => 'api_browser', 'action' => 'view'
));

Router::connect(new Locale(array(
	'template' => '/docs',
	'params' => array('library' => 'li3_docs', 'controller' => 'api_browser')
)));
Router::connect(new Locale(array(
	'template' => '/docs/{:lib}/{:args}',
	'params' => array('library' => 'li3_docs', 'controller' => 'api_browser', 'action' => 'view')
)));

?>