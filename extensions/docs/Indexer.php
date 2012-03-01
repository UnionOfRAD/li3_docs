<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\docs;

use lithium\core\Libraries;
use li3_docs\extensions\docs\Extractor;

class Indexer extends \lithium\core\StaticObject {

	public static $_classes = array(
		'writer' => 'li3_docs\extensions\docs\Writer'
	);

	public static function libraries() {
		$config = Libraries::get('li3_docs');
		$libs = isset($config['index']) ? $config['index'] : array_keys(Libraries::get());
		$configs = array_map(function($lib) { return Extractor::library($lib); }, $libs);
		return array_combine($libs, $configs);
	}

	public static function run(array $options = array()) {
		$defaults = array('libraries' => array());
		$options += $defaults;
		$writer = static::_instance('writer');

		$searchOptions = array(
			'recursive' => true,
			'exclude' => '/\w+Test$|Mock\w+$|index$|^\w+\\\\views\/|\.|\w+\\\\libraries/'
		);

		foreach (static::libraries() as $name => $config) {
			if ($options['libraries'] && !in_array($name, $options['libraries'])) {
				continue;
			}
		}
	}
}

?>