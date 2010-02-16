<?php

use \lithium\net\http\Router;

Router::connect('/docs', array('plugin' => 'li3_docs', 'controller' => 'browser'));
Router::connect('/docs/{:library}/{:args}', array(
	'plugin' => 'li3_docs', 'controller' => 'browser', 'action' => 'view'
));
?>
