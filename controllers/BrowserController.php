<?php

namespace li3_docs\controllers;

use \Exception;
use \DirectoryIterator;
use \lithium\core\Libraries;
use \lithium\util\reflection\Inspector;

/**
 * This is the Lithium API browser controller. This class introspects your applications libraries,
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
		$pluginsDir = new DirectoryIterator(LITHIUM_APP_PATH . '/libraries/plugins');
		$libraries = Libraries::get();
		$plugins = array();

		foreach ($pluginsDir as $plugin) {
			if ($plugin->isDir() && !$plugin->isDot()) {
				$plugins[$plugin->getPathName()] = $plugin->getFileName();
			}
		}
		return compact('plugins', 'libraries');
	}

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
			'children'   => null
		);
		$object['type'] = Inspector::type($name);

		switch ($object['type']) {
			case 'namespace':
				$path = '/' . join('/', (array)$this->request->params['args']);
				$searchOptions = array('namespaces' => true) + compact('path');
				$object['children'] = array();

				foreach (Libraries::find($lib, $searchOptions) as $child) {
					$libPath = Libraries::path($child, array('dirs' => true));
					$type = is_dir($libPath) ? 'namespace' : 'class';
					$object['children'][$child] = $type;
				}

				$doc = $library['path'] . $path . '/' . $this->docFile;
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
		$object['info'] += (array)Inspector::info($object['identifier']);

		if (isset($object['info']['tags']['var'])) {
			$object['type'] = $object['info']['tags']['var'];
		}
		return compact('name', 'library', 'object');
	}
}

?>