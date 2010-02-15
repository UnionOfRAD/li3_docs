<?php

use \lithium\net\http\Router;

Router::connect('/docs', array('plugin' => 'li3_docs', 'controller' => 'browser'));
Router::connect('/docs/{:library}/{:args}', array(
	'plugin' => 'li3_docs', 'controller' => 'browser', 'action' => 'view'
));
Router::connect('/{:type:js|css}/li3_docs/{:asset}');

?>
