<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\docs;

use Exception;
use lithium\core\Libraries;
use lithium\util\Inflector;
use lithium\analysis\Docblock;
use lithium\analysis\Inspector;

class Extractor extends \lithium\core\StaticObject {

	public static function get($library, $identifier, array $options = array()) {
		$defaults = array('namespaceDoc' => null);
		$options += $defaults;
		$path = Libraries::path($identifier);

		if (file_exists($path) && !static::_isClassFile($path)) {
			return static::_file(compact('library', 'path', 'identifier'), $options);
		}
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
			$data[$key] = static::_embedMethodCode($data[$key]);
			$data[$key] = static::_embedFileCode($data[$key], compact('library'));
		}
		return $data;
	}

	public static function library($name, array $options = array()) {
		$defaults = array('docs' => 'config/docs/index.json');
		$options += $defaults;

		if (!$config = Libraries::get($name)) {
			return array();
		}

		if (file_exists($file = "{$config['path']}/{$options['docs']}")) {
			$config += json_decode(file_get_contents($file), true);
		}
		return $config + array('title' => Inflector::humanize($name), 'category' => 'libraries');
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

	protected static function _file(array $object, array $options = array()) {
		$identifier = $object['identifier'];
		$config = Libraries::get($object['library']);
		$ds = DIRECTORY_SEPARATOR;

		$data = compact('identifier') + array(
			'name' => '',
			'type' => 'file',
			'children' => array(),
			'subClasses' => array(),
			'info' => static::_codeToDoc(file_get_contents($object['path']), array(
				'library' => $object['library']
			)),
		);
		$subPath = dirname($object['path']) . $ds . basename($object['path'], '.php');

		if (is_dir($subPath)) {
			$path = preg_replace('/^' . preg_quote($config['prefix'], '/') . '/', '', $identifier);
			$path = '/' . str_replace('\\', '/', $path);
			$searchOpts = array('recursive' => true, 'namespaces' => true, 'filter' => false);

			foreach (Libraries::find($object['library'], compact('path') + $searchOpts) as $file) {
				$libPath = Libraries::path($file, array('dirs' => true));
				$type = is_dir($libPath) ? 'namespace' : 'class';
				$data['children'][$file] = $type;
			}
		}
		return $data;
	}

	protected static function _codeToDoc($code) {
		$tokens = token_get_all($code);
		$display = array();
		$current = '';

		foreach ($tokens as $i => $token) {
			if ($i == 0 || ($token[0] == T_CLOSE_TAG && ($i + 1) == count($tokens))) {
				continue;
			}
			if ($token[0] == T_DOC_COMMENT) {
				if (preg_match('/@copyright/', $token[1])) {
					continue;
				}
				if (!trim($current)) {
					$current = '';
				}
				if ($current) {
					$display[] = "{{{\n{$current}}}}";
					$current = '';
				}
				$doc = Docblock::comment($token[1]);

				foreach (array('text', 'description') as $key) {
					$doc[$key] = static::_embedMethodCode($doc[$key]);
					$doc[$key] = static::_embedFileCode($doc[$key]);
				}
				$display[] = $doc;
				continue;
			}
			$current .= (is_array($token) ? $token[1] : $token);
		}
		if ($current) {
			$display[] = "{{{\n{$current}}}}";
		}
		return $display;
	}

	/**
	 * Replaces class and method references with code snippets pulled from the class.
	 *
	 * @param string $text
	 * @param array $options
	 * @return string
	 */
	protected static function _embedMethodCode($text, array $options = array()) {
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

			list($start, $end) = array_map('intval', explode('-', $matches['lines'][$i]));
			$lines = Inspector::lines($class, range($start + $methodStart, $end + $methodStart));

			$pad = substr_count(current($lines), $options['pad'], 0);
			$lines = array_map('substr', $lines, array_fill(0, count($lines), $pad));

			$code = '{{{' . "\n\n" . join("\n", $lines) . "\n\n" . '}}}';
			$text = str_replace($replace, $code, $text);
		}
		return $text;
	}

	protected static function _embedFileCode($text, array $options = array()) {
		$defaults = array('pad' => "\t", 'library' => true);
		$options += $defaults;
		$regex = '(?P<file>[A-Za-z0-9_\/.]+)::(?P<lines>[0-9-]+)';
		$library = Libraries::get($options['library']);

		if (!preg_match_all("/\{\{\{\s*(embed:{$regex})\s*\}\}\}/", $text, $matches)) {
			return $text;
		}

		foreach ($matches['file'] as $i => $file) {
			$path = realpath("{$library['path']}/{$file}");
			$replace = $matches[0][$i];

			list($start, $end) = array_map('intval', explode('-', $matches['lines'][$i]));
			$lines = Inspector::lines(file_get_contents($path), range($start - 1, $end - 1));
			$code = '{{{' . "\t" . join("\n\t", $lines) . ' }}}';
			$text = str_replace($replace, $code, $text);
		}
		return $text;
	}

	protected static function _isClassFile($path) {
		$tokens = token_get_all(file_get_contents($path));

		for ($i = 2; $i < count($tokens); $i++) {
			if (!($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE)) {
				continue;
			}
			if ($tokens[$i][0] == T_STRING) {
				return true;
			}
		}
		return false;
	}
}

?>