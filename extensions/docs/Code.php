<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\docs;

use lithium\core\Libraries;
use lithium\analysis\Inspector;

class Code extends \lithium\core\StaticObject {

	protected static $_patterns = array(
		'class' => '(?P<class>[A-Za-z0-9_\\\]+)::(?P<method>[A-Za-z0-9_]+)\((?P<lines>[0-9-]+)\)',
		'file' => '(?P<file>[A-Za-z0-9_\/.]+)::(?P<lines>[0-9-]+)\)'
	);

	protected static $_index = array();

	public static function index($index = null) {
		if (is_array($index)) {
			return static::$_index = $index + static::$_index;
		}
		if ($index === false) {
			return static::$_index = array();
		}
		return static::$_index;
	}

	public static function hash($ref, $text = null) {
		if (!$text) {
			list($ref, $text) = static::extract($ref);
		}
		$hash = sha1($text);
		$existing = isset(static::$_index[$ref]) ? static::$_index[$ref] : null;

		if ($existing && $existing != $hash) {
			return false;
		}
		if (!$existing) {
			static::$_index[$ref] = $hash;
		}
		return true;
	}

	public static function embed($text, array $options = array()) {
		$defaults = array('library' => true);
		$options += $defaults;

		foreach (static::$_patterns as $method => $pattern) {
			if (preg_match_all("/\{\{\{\s*(embed:{$pattern})\s*\}\}\}/", $text, $matches)) {
				foreach ($matches[0] as $i => $replace) {
					$ref = array(
						'file'   => isset($matches['file'][$i]) ? $matches['file'][$i] : null,
						'class'  => isset($matches['class'][$i]) ? $matches['class'][$i] : null,
						'method' => isset($matches['method'][$i]) ? $matches['method'][$i] : null,
						'lines'  => $matches['lines'][$i]
					);
					list($ref, $code) = static::extract($ref, $options);

					$replacement = $ref['class'] ? "{{{\n\n{$code}\n\n}}}" : "{{{{$code} }}}";
					$text = str_replace($replace, $replacement, $text);
					static::hash($ref, $code);
				}
			}
		}
		return $text;
	}

	public static function extract($ref, array $options = array()) {
		if (is_string($ref)) {
			foreach (static::$_patterns as $name => $pattern) {
				if (preg_match("/^{$pattern}$/", $ref, $match)) {
					$ref = $match;
					break;
				}
			}
			if (is_string($ref)) {
				return false;
			}
		}

		if (isset($ref['class'])) {
			return static::_extractClassCode($ref, $options);
		}
		if (isset($ref['file'])) {
			return static::_extractFileCode($ref, $options);
		}
	}

	protected static function _extractClassCode($ref) {
		$method = $ref['method'];
		$class = $ref['class'];

		$markers = Inspector::methods($class, 'extents', array('methods' => array($ref['method'])));
		$methodStart = $markers[$method][0];

		list($start, $end) = array_map('intval', explode('-', $ref['lines']));
		$code = Inspector::lines($class, range($start + $methodStart, $end + $methodStart));

		$pad = substr_count(current($code), "\t", 0);
		$lines = array_map('substr', $code, array_fill(0, count($code), $pad));
		return array("{$class}::{$method}({$start}-{$end})", join("\n", $lines));
	}

	protected static function _extractFileCode($ref, $options) {
		$library = Libraries::get($options['library']);
		$file = $ref['file'];

		if (!$path = realpath("{$library['path']}/{$file}")) {
			return;
		}
		list($start, $end) = array_map('intval', explode('-', $ref['lines']));
		$lines = Inspector::lines(file_get_contents($path), range($start - 1, $end - 1));
		return "\t" . join("\n\t", $lines);
	}
}

?>