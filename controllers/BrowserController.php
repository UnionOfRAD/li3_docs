<?php

namespace li3_docs\controllers;

use \DirectoryIterator;
use \lithium\core\Libraries;
use \lithium\util\reflection\Inspector;

class BrowserController extends \lithium\action\Controller {

	public function index() {
		$pluginsDir = new DirectoryIterator(LITHIUM_APP_PATH . '/libraries/plugins');
		$plugins = array();

		foreach ($pluginsDir as $plugin) {
			if ($plugin->isDir() && !$plugin->isDot()) {
				$plugins[$plugin->getPathName()] = $plugin->getFileName();
			}
		}
		$libraries = Libraries::get();
		$this->set(compact('plugins', 'libraries'));
	}

	public function view() {
		$lib = $this->request->params['library'];
		$library = Libraries::get($lib);
		$name = $library['prefix'] . join('\\', func_get_args());

		$object = array(
			'name' => null,
			'identifier' => $name,
			'type'       => null,
			'info'       => null,
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
				$object['children'] = Libraries::find($lib, array(
					'namespaces' => true,
					'path' => '/' . join('/', (array)$this->request->params['args'])
				));
			break;
			case 'class':
				$object['name'] = null;
				$object['parent'] = get_parent_class($name);
				$object['methods'] = Inspector::methods($name, null, array('public' => false));

				if ($object['parent']) {
					$object['properties'] = array_diff_key(
						get_class_vars($name),
						get_class_vars($object['parent'])
					);
				}
				$classes = Libraries::find($lib, array('recursive' => true));

				$object['subClasses'] = array_filter($classes, function($class) use ($name) {
					if (preg_match('/\\\(libraries|plugins)\\\/', $class)) {
						return false;
					}
					return get_parent_class($class) == $name;
				});
				sort($object['subClasses']);
			break;
		}
		$object['info'] = Inspector::info($object['identifier']);

		return compact('name', 'library', 'object');
	}
}

?>
