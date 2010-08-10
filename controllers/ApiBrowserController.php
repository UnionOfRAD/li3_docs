<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
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
		'media' => 'lithium\net\http\Media',
		'response' => 'lithium\action\Response',
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

	/**
	 * This action introspects all libraries and plugins that exist in your app (even if they are
	 * not loaded), including the app itself and the Lithium core.
	 *
	 * @return array
	 */
	public function index() {
		$libraries = Libraries::get();
		$config = Libraries::get('li3_docs');

		if (isset($config['index'])) {
			$libraries = array_combine(
				$config['index'],
				array_map(function($lib) { return Libraries::get($lib); }, $config['index'])
			);
		}
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
	 */
	public function view() {
		$extractor = $this->_classes['extractor'];
		$config = Libraries::get('li3_docs');

		if (!$library = $extractor::library($this->request->lib)) {
			return $this->render('../errors/not_found');
		}
		if (isset($config['index']) && !in_array($this->request->lib, $config['index'])) {
			return $this->render('../errors/not_found');
		}
		$name = $library['prefix'] . join('\\', func_get_args());
		$options = array('namespaceDoc' => $this->docFile);

		$object = $extractor::get($this->request->lib, $name, $options);
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

	protected function _oldProcess($object) {
		if (isset($object['info']['tags']['var'])) {
			$object['type'] = $object['info']['tags']['var'];
		}
		$object['info'] += array('description' => '');

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
}

?>