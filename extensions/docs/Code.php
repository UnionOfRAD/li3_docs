<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\docs;

use Exception;
use lithium\core\Libraries;
use lithium\analysis\Inspector;

class Code extends \lithium\core\StaticObject {

	protected static $_path = '/resources/docs.hashes.json';

	protected static $_index = array();

	public static function index() {
		if (static::$_index) {
			return static::$_index;
		}
		$path = LITHIUM_APP_PATH . static::$_path;

		if (!file_exists($path) || !is_readable($path)) {
			return $this->render(array('template' => 'error'));
		}
		return (array) json_decode(file_get_contents($path), true);
	}

	public static function hash($ref, $text = null) {
		$existing = isset(static::$_index[$ref]) ? static::$_index[$ref] : null;
		$hash = sha1($text);

		if (!$text) {
			return $existing;
		}
		if ($existing && $existing != $hash) {
			return false;
		}
		if (!$existing) {
			static::$_index[$ref] = $hash;
		}
		return true;
	}

	public static function embed($text, array $options = array()) {
		$defaults = array('pad' => "\t", 'library' => true);
		$options += $defaults;
		$regexes = array(
			'class' => '(?P<class>[A-Za-z0-9_\\\]+)::(?P<method>[A-Za-z0-9_]+)\((?P<lines>[0-9-]+)',
			'file' => '(?P<file>[A-Za-z0-9_\/.]+)::(?P<lines>[0-9-]+)',
		);

		foreach ($regexes as $method => $pattern) {
			if (preg_match_all("/\{\{\{\s*(embed:{$pattern}\))\s*\}\}\}/", $text, $matches)) {
				$method = '_embed' . ucfirst($method) . 'Code';
				$text = static::$method($text, $matches, $options);
			}
		}
		return $text;
	}

	/**
	 * Replaces class and method references with code snippets pulled from the class.
	 *
	 * @param string $text
	 * @param array $options
	 * @return string
	 */
	protected static function _embedClassCode($text, $matches, array $options = array()) {
		foreach ($matches['class'] as $i => $class) {
			$method = $matches['method'][$i];
			$markers = Inspector::methods($class, 'extents', array('methods' => array($method)));
			$methodStart = $markers[$method][0];
			$replace = $matches[0][$i];

			list($start, $end) = array_map('intval', explode('-', $matches['lines'][$i]));
			$lines = Inspector::lines($class, range($start + $methodStart, $end + $methodStart));

			$pad = substr_count(current($lines), $options['pad'], 0);
			$lines = array_map('substr', $lines, array_fill(0, count($lines), $pad));

			$code = '{{{' . "\n\n" . join("\n", $lines) . "\n\n" . '}}}';
			$text = str_replace($replace, $code, $text);
			static::hash("{$class}::{$method}({$start}-{$end})", join("\n", $lines));
		}
		return $text;
	}

	protected static function _embedFileCode($text, $matches, array $options = array()) {
		$library = Libraries::get($options['library']);

		foreach ($matches['file'] as $i => $file) {
			$path = realpath("{$library['path']}/{$file}");
			$replace = $matches[0][$i];

			list($start, $end) = array_map('intval', explode('-', $matches['lines'][$i]));
			$lines = Inspector::lines(file_get_contents($path), range($start - 1, $end - 1));
			$code = '{{{' . "\t" . join("\n\t", $lines) . ' }}}';
			$text = str_replace($replace, $code, $text);
			static::hash("{$file}::{$start}-{$end}", join("\n\t", $lines));
		}
		return $text;
	}
}

?>