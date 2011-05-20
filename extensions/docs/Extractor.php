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
use lithium\util\Inflector;
use lithium\analysis\Docblock;
use lithium\analysis\Inspector;
use li3_docs\extensions\docs\Code;

class Extractor extends \lithium\core\StaticObject {

	public static function get($library, $identifier, array $options = array()) {
		$defaults = array('namespaceDoc' => null, 'language' => 'en');
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
			$data[$key] = Code::embed($data[$key], compact('library'));
		}
		return $data;
	}

	public static function library($name, array $options = array()) {
		$defaults = array('docs' => 'config/docs/index.json', 'language' => 'en');
		$options += $defaults;

		if (!$config = Libraries::get($name)) {
			return array();
		}

		if (file_exists($file = "{$config['path']}/{$options['docs']}")) {
			$config += (array) json_decode(file_get_contents($file), true);
		}
		if (isset($config['languages']) && in_array($options['language'], $config['languages'])) {
			$config += $config[$options['language']];

			foreach ($config['languages'] as $language) {
				unset($config[$language]);
			}
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
		$path = rtrim('/' . str_replace('\\', '/', $path), '/');

		if (isset($options['contents'])) {
			$object['children'] = static::_contents($object, $options['contents']);
		} else {
			$object['children'] = static::_children($library, $path);
		}
		$path = $config['path'] . rtrim($path, '/');

		if (isset($options['language']) && is_dir("{$config['path']}/{$options['language']}")) {
			$path = str_replace($config['path'], "{$config['path']}/{$options['language']}", $path);
		}
		$doc = "{$path}/{$options['namespaceDoc']}";
		$object['text'] = file_exists($doc) ? file_get_contents($doc) : null;

		if (!file_exists($doc) && file_exists($path) && !is_dir($path)) {
			$object['text'] = file_get_contents($path);
		}
		return $object;
	}

	protected static function _contents(array $object, array $contents) {
		$path = str_replace('\\', '/', $object['identifier']);
		$library = $object['library'];
		$result = array();
		$nested = array();

		foreach (explode('\\', $object['identifier']) as $i => $key) {
			if ($i == 0 && $key == $library) {
				continue;
			}
			$nested[] = $key;
			$key = join('/', $nested);

			if (isset($contents[$key]['contents'])) {
				$contents = $contents[$key]['contents'];
			}
		}

		foreach ($contents as $key => $value) {
			$result[isset($value['title']) ? $value['title'] : $key] = array(
				'type' => "page", 'url' => "{$library}/{$key}"
			);
		}
		return $result;
	}

	protected static function _class(array $object, array $data, array $options = array()) {
		$identifier = $object['identifier'];
		$proto = array(
			'parent' => get_parent_class($identifier),
			'methods' => Inspector::methods($identifier, null, array('public' => false)),
			'properties' => Inspector::properties($identifier, array('public' => false))
		);
		$classes = Libraries::find($object['library'], array('recursive' => true));

		$proto['subClasses'] = array_filter($classes, function($class) use ($identifier) {
			if (preg_match('/\\\(libraries)\\\|\\\(mocks)\\\/', $class)) {
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
		$library = $object['library'];
		$config = Libraries::get($library);
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
			$data['children'] = static::_children($library, $path);
		}
		return $data;
	}

	protected static function _children($library, $path) {
		$result = array();
		$types = array(
			'namespace' => array('namespaces' => true),
			'class' => array('namespaces' => false),
			'file' => array('namespaces' => false, 'filter' => false, 'preFilter' => false),
		);

		foreach ($types as $type => $options) {
			foreach (Libraries::find($library, compact('path') + $options) as $child) {
				$result += array($child => $type);
			}
		}
		return $result;
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
					$doc[$key] = Code::embed($doc[$key]);
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