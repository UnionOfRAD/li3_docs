<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\controllers;

use lithium\core\Libraries;

/**
 * This is the Lithium API browser controller. This class introspects your application's libraries,
 * plugins and classes to generate on-the-fly API documentation.
 */
class ApiBrowserController extends \lithium\action\Controller {

	/**
	 * The `Extractor` class dependency, which can be replaced with a proxy file to read from
	 * a cache or database.
	 *
	 * @var array
	 */
	protected $_classes = array(
		'media'     => 'lithium\net\http\Media',
		'response'  => 'lithium\action\Response',
		'indexer'   => 'li3_docs\extensions\docs\Indexer',
		'extractor' => 'li3_docs\extensions\docs\Extractor'
	);

	/**
	 * The name of the file used to document (describe) namespaces. By default, the document is read
	 * from the directory being examined, and the contents of it represent the "docblock" for the
	 * corresponding namespace.
	 *
	 * Additional names can be configured using the `namespaceDoc` key. Eg
	 * 'Libraries::add('li3_docs', array('namespaceDoc' => array('documentation.md')));'
	 * or multiple when an array is used.
	 *
	 * @var array
	 */
	protected $_namespaceDoc = array('readme.md', 'README.md');

	protected function _init() {
		parent::_init();
		$this->response->encoding = 'UTF-8';
	}

	/**
	 * This action introspects all libraries and plugins that exist in your app, including the app
	 * itself and the Lithium core.
	 *
	 * @return array
	 */
	public function index() {
		$indexer = $this->_classes['indexer'];
		$libraries = $indexer::libraries();

		$config = Libraries::get('li3_docs');
		$categories = '';
		if (isset($config['categories']) && is_array($config['categories'])) {
			$categories = array_keys($config['categories']);
		} else {
			$categories = array_values(array_unique(
				array_map(function($lib) { return $lib['category']; }, $libraries)
			));
		}

		return compact('libraries', 'categories');
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
	 */
	public function view() {
		$extractor = $this->_classes['extractor'];
		$config = Libraries::get('li3_docs');

		if (!$library = $extractor::library($this->request->lib)) {
			return $this->render(array('template' => '../errors/not_found'));
		}
		if (isset($config['index']) && !in_array($this->request->lib, $config['index'])) {
			return $this->render(array('template' => '../errors/not_found'));
		}
		$name = $library['prefix'] . join('\\', func_get_args());
		$object = $extractor::get($this->request->lib, $name, array(
			'namespaceDoc' => $this->_namespaceDoc
		) +  $library);
		$meta = array();

		if (strpos($name, '::') !== false) {
			list($class, $method) = explode('::', $name, 2);
			$meta = $extractor::get($this->request->lib, $class);
		}
		return compact('name', 'library', 'object', 'meta');
	}
}

?>