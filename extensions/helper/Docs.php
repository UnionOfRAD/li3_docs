<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\helper;

class Docs extends \lithium\template\Helper {

	public function cleanup($text) {
		return preg_replace('/\n\s+-\s/msi', "\n\n - ", $text);
	}

	public function identifierUrl($class) {
		$parts = explode('\\', $class);
		$lib = array_shift($parts);
		$args = $parts;
		return array('Browser::view', 'library' => 'li3_docs') + compact('lib', 'args');
	}
}

?>