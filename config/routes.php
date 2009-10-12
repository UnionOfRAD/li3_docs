<?php

use \lithium\http\Router;

Router::connect('/docs', array('plugin' => 'lithium_docs', 'controller' => 'browser'));
Router::connect('/docs/{:library}/{:args}', array(
	'plugin' => 'lithium_docs', 'controller' => 'browser', 'action' => 'view'
));

?>