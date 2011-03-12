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

?>