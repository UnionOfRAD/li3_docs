<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\controllers;

use \Exception;
use \DirectoryIterator;
use \lithium\core\Libraries;
use \lithium\analysis\Inspector;

/**
 * This is the Lithium API browser controller. This class introspects your application's libraries,
 * plugins and classes to generate on-the-fly API documentation.
 */
class BrowserController extends \lithium\action\Controller {

	/**
	 * The name of the file used to document (describe) namespaces. By default, the document is read
	 * from the directory being examined, and the contents of it represent the "docblock" for the
	 * corresponding namespace.
	 *
	 * @var string
	 */
	public $docFile = 'readme.wiki';

	/**
	 * This action introspects all libraries and plugins that exist in your app (even if they are
	 * not loaded), including the app itself and the Lithium core.
	 *
	 * @return array
	 */
	public function index() {
		$libraries = Libraries::get();
		return compact('libraries');
	}

	/**
	 * This action renders the detail page for all API elements, including namespaces, classes,
	 * properties and methods. The action determines what type of entity is being displayed, and
	 * gathers all available data on it. Any wiki text embedded in the data is then post-processed
	 * and prepped for display.
	 *
	 * @return array Returns an array with the following keys:
	 *               - `'name'`: A string containing the full name of the entity being displayed
	 *               - `'library'`: An array with the details of the current class library being
	 *                 browsed, in which the current entity is contained.
	 *               - `'object'`: A multi-level array containing all data extracted about the
	 *                 current entity.
	 * @link http://www.faqs.org/rfcs/rfc2396.html
	 */
	public function view() {
		$lib = $this->request->params['library'];
		$library = Libraries::get($lib);
		$name = $library['prefix'] . join('\\', func_get_args());

		$object = array(
			'name'       => null,
			'identifier' => $name,
			'type'       => null,
			'info'       => array(),
			'classes'    => null,
			'methods'    => null,
			'properties' => null,
			'parent'     => null,
			'subClasses' => null,
			'children'   => null,
			'source'     => null,
		);
		$object['type'] = Inspector::type($name);

		switch ($object['type']) {
			case 'namespace':
				$path = '/' . join('/', (array) $this->request->params['args']);
				$searchOptions = array('namespaces' => true) + compact('path');
				$object['children'] = array();

				foreach (Libraries::find($lib, $searchOptions) as $child) {
					$libPath = Libraries::path($child, array('dirs' => true));
					$type = is_dir($libPath) ? 'namespace' : 'class';
					$object['children'][$child] = $type;
				}

				$doc = $library['path'] . rtrim($path, '/') . '/' . $this->docFile;
				$object['info']['description'] = file_exists($doc) ? file_get_contents($doc) : null;
			break;
			case 'class':
				$object['name'] = null;
				$object['parent'] = get_parent_class($name);
				$object['methods'] = Inspector::methods($name, null, array('public' => false));
				$object['properties'] = get_class_vars($name);

				if ($object['parent']) {
					$parentProps = get_class_vars($object['parent']);
					$object['properties'] = array_diff_key($object['properties'], $parentProps);
				}
				$classes = Libraries::find($lib, array('recursive' => true));

				$object['subClasses'] = array_filter($classes, function($class) use ($name) {
					if (preg_match('/\\\(libraries|plugins)\\\/', $class)) {
						return false;
					}
					try {
						return get_parent_class($class) == $name;
					} catch (Exception $e) {
						return false;
					}
				});
				sort($object['subClasses']);
			break;
		}
		$object['info'] += (array) Inspector::info($object['identifier']);

		if ($object['type'] == 'method') {
			$object['source'] = join("\n", Inspector::lines(
				$object['info']['file'], range($object['info']['start'], $object['info']['end'])
			));
		}

		$object = $this->_process($object);

		$crumbs = $this->_crumbs($object);
		return compact('name', 'library', 'object', 'crumbs');
	}

	protected function _crumbs($object) {
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
			$crumbs[] = array('title' => $part, 'url' => 'docs' . $url, 'class' => null);
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

	/**
	 * Handles post-processing of aggregated object data, including re-mapping properties and
	 * processing embedded text commands.
	 *
	 * @param array $object
	 * @return array
	 */
	protected function _process($object) {
		if (isset($object['info']['tags']['var'])) {
			$object['type'] = $object['info']['tags']['var'];
		}

		if ($object['info']['description']) {
			$object['info']['description'] = $this->_embed($object['info']['description']);
		}

		if (isset($object['info']['text'])) {
			$object['info']['text'] = $this->_embed($object['info']['text']);
		}

		if (isset($object['info']['tags']['return'])) {
			list($type, $text) = explode(' ', $object['info']['tags']['return'], 2) + array('', '');
			$object['info']['return'] = compact('type', 'text');
			$object['info']['return']['text'] = $this->_embed($object['info']['return']['text']);
		}
		return $object;
	}

	/**
	 * Replaces class and method references with code snippets pulled from the class.
	 *
	 * @param string $text
	 * @return string
	 */
	protected function _embed($text) {
		$regex = '(?P<class>[A-Za-z0-9_\\\]+)::(?P<method>[A-Za-z0-9_]+)\((?P<lines>[0-9-]+)';

		if (preg_match_all("/\{\{\{\s*(embed:{$regex}\))\s*\}\}\}/", $text, $matches)) {
			foreach ($matches['class'] as $i => $class) {
				$methods = array($matches['method'][$i]);
				$markers = Inspector::methods($class, 'extents', compact('methods'));
				$methodStart = $markers[current($methods)][0];
				$replace = $matches[0][$i];

				list($start, $end) = explode('-', $matches['lines'][$i]);
				$lines = range(intval($start) + $methodStart, intval($end) + $methodStart);
				$code = '{{{' . join("\n", Inspector::lines($class, $lines)) . '}}}';
				$text = str_replace($replace, $code, $text);
			}
		}
		return $text;
	}
}

?>