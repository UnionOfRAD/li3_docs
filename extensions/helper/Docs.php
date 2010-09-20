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
		return array('ApiBrowser::view', 'library' => 'li3_docs') + compact('lib', 'args');
	}

	public function crumbs($object) {
		$path = array_filter(array_merge(
			array($object['name']), explode('\\', $object['identifier'])
		));
		$crumbs[] = array(
			'title' => $object['type'],
			'url' => null,
			'class' => 'type ' . $object['type']
		);
		$url = '';

		foreach (array_slice($path, 0, -1) as $part) {
			$url .= '/' . $part;
			$crumbs[] = array('title' => $part, 'url' => 'docs' . $url, 'class' => 'namespace');
		}
		$ident = end($path);

		if (strpos($ident, '::') !== false) {
			list($class, $ident) = explode('::', $ident, 2);
			$crumbs[] = array('title' => $class, 'url' => "docs{$url}/{$class}", 'class' => null);
			$isMethod = true;
		} else {
			$isMethod = false;
		}
		$crumbs[] = array('title' => $ident, 'url' => null, 'class' => $isMethod ? 'ident' : null);
		return $crumbs;
	}
}

?>