<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\command\docs;

use lithium\core\Libraries;

/**
 * Adds headers and docblocks to classes and methods
 *
 **/
class Generator extends \lithium\console\Command {

	public function run() {
		$classes = Libraries::find(true, array(
			'exclude' => "/webroot|index$|^app\\\\config|^app\\\\views/",
			'recursive' => true
		));

		foreach ($classes as $class) {
			$path = Libraries::path($class);
			$contents = explode("\n", file_get_contents($path));
			$contents = $this->_header($contents);
			if (file_put_contents($path, implode("\n", $contents))) {
				$this->out($path . ' written');
			}
		}
	}

	protected function _header($contents) {
		if (strpos($contents[1], '*') === false) {
			$header = explode("\n", file_get_contents(
				dirname(__DIR__) . '/template/docs/header.txt.php')
			);
			$one = array_shift($contents);
			$contents = array_merge($header, $contents);
			array_unshift($contents, $one);
		}
		return $contents;
	}
}

?>