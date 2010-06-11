<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\analysis;

use \Exception;
use \lithium\core\Libraries;
use \lithium\util\Inflector;
use \lithium\analysis\Inspector;

class DocExtractor extends \lithium\core\StaticObject {

	public static function get($library, $identifier, array $options = array()) {
		$defaults = array('namespaceDoc' => null);
		$options += $defaults;
		$data = Inspector::info($identifier);

		$proto = compact('identifier', 'library') + array(
			'name'        => null,
			'type'        => Inspector::type($identifier),
			'info'        => array(),
			'classes'     => null,
			'methods'     => null,
			'properties'  => null,
			'parent'      => null,
			'children'    => null,
			'source'      => null,
			'subClasses'  => array(),
			'description' => isset($data['description']) ? $data['description'] : null,
			'text'        => isset($data['text']) ? $data['text'] : null,
		);
		$format = "_{$proto['type']}";
		$data = static::$format($proto, (array) $data, $options);

		foreach (array('text', 'description') as $key) {
			$data[$key] = static::_embedCode($data[$key]);
		}
		return $data;
	}

	public static function library($name, array $options = array()) {
		$defaults = array('docs' => 'config/docs.json');
		$options += $defaults;

		if (!$config = Libraries::get($name)) {
			return array();
		}

		if (file_exists($file = "{$config['path']}/{$options['docs']}")) {
			$config += (array) json_decode(file_get_contents($file));
		}
		return $config + array('title' => Inflector::humanize($name));
	}

	protected static function _method(array $object, array $data, array $options = array()) {
		if (!$data) {
			return array();
		}
		$lines = Inspector::lines($data['file'], range($data['start'], $data['end']));
		$object = array('source' => join("\n", $lines)) + $object;
		$object += array('tags' => isset($data['tags']) ? $data['tags'] : array());

		if (isset($object['tags']['return'])) {
			list($type, $text) = explode(' ', $object['tags']['return'], 2) + array('', '');
			$object['return'] = compact('type', 'text');
		}
		return $object;
	}

	protected static function _namespace(array $object, array $data, array $options = array()) {
		$library = $object['library'];
		$identifier = $object['identifier'];
		$config = Libraries::get($library);

		$path = preg_replace('/^' . preg_quote($config['prefix'], '/') . '/', '', $identifier);
		$path = '/' . str_replace('\\', '/', $path);
		$object['children'] = array();

		foreach (Libraries::find($library, array('namespaces' => true) + compact('path')) as $c) {
			$libPath = Libraries::path($c, array('dirs' => true));
			$type = is_dir($libPath) ? 'namespace' : 'class';
			$object['children'][$c] = $type;
		}

		$path = $config['path'] . rtrim($path, '/');
		$doc = "{$path}/{$options['namespaceDoc']}";
		$object['text'] = file_exists($doc) ? file_get_contents($doc) : null;
		return $object;
	}

	protected static function _class(array $object, array $data, array $options = array()) {
		$identifier = $object['identifier'];
		$proto = array(
			'parent' => get_parent_class($identifier),
			'methods' => Inspector::methods($identifier, null, array('public' => false)),
			'properties' => get_class_vars($identifier)
		);

		if ($proto['parent']) {
			$parentProps = get_class_vars($proto['parent']);
			$proto['properties'] = array_diff_key($proto['properties'], $parentProps);
		}
		$classes = Libraries::find($object['library'], array('recursive' => true));

		$proto['subClasses'] = array_filter($classes, function($class) use ($identifier) {
			if (preg_match('/\\\(libraries)\\\/', $class)) {
				return false;
			}
			try {
				return get_parent_class($class) == $identifier;
			} catch (Exception $e) {
				return false;
			}
		});
		sort($proto['subClasses']);
		return $proto + $object + array('tags' => isset($data['tags']) ? $data['tags'] : array());
	}

	protected static function _property(array $object, array $data, array $options = array()) {
		return $object + $data;
	}

	/**
	 * Replaces class and method references with code snippets pulled from the class.
	 *
	 * @param string $text
	 * @param array $options
	 * @return string
	 */
	protected static function _embedCode($text, array $options = array()) {
		$defaults = array('pad' => "\t");
		$options += $defaults;
		$regex = '(?P<class>[A-Za-z0-9_\\\]+)::(?P<method>[A-Za-z0-9_]+)\((?P<lines>[0-9-]+)';

		if (!preg_match_all("/\{\{\{\s*(embed:{$regex}\))\s*\}\}\}/", $text, $matches)) {
			return $text;
		}

		foreach ($matches['class'] as $i => $class) {
			$methods = array($matches['method'][$i]);
			$markers = Inspector::methods($class, 'extents', compact('methods'));
			$methodStart = $markers[current($methods)][0];
			$replace = $matches[0][$i];

			list($start, $end) = explode('-', $matches['lines'][$i]);
			$lines = range(intval($start) + $methodStart, intval($end) + $methodStart);
			$lines = Inspector::lines($class, $lines);

			$pad = substr_count(current($lines), $options['pad'], 0, 4);
			$lines = array_map('substr', $lines, array_fill(0, count($lines), 2));

			$code = '{{{' . join("\n", $lines) . '}}}';
			$text = str_replace($replace, $code, $text);
		}
		return $text;
	}
}

?>