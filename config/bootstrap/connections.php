<?php

use lithium\data\Connections;
use lithium\core\Libraries;

/**
 * Sets up Sqlite3 database for searching.
 */
Connections::add('li3_docs', array(
  'type'     => 'database',
  'adapter'  => 'Sqlite3',
  'database' => Libraries::get('li3_docs', 'path') . '/resources/data/symbols.db'
));

?>