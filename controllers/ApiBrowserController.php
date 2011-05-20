<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\controllers;

use Exception;
use DirectoryIterator;
use lithium\core\Libraries;
use lithium\analysis\Inspector;

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
	 * @var string
	 */
	public $docFile = 'readme.wiki';

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
		return array('libraries' => $indexer::libraries());
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
		$options = array('namespaceDoc' => $this->docFile);
		$object = $extractor::get($this->request->lib, $name, $options + $library);
		$meta = array();

		if (strpos($name, '::') !== false) {
			list($class, $method) = explode('::', $name, 2);
			$meta = $extractor::get($this->request->lib, $class);
		}

		return compact('name', 'library', 'object', 'meta');
	}
}

?>