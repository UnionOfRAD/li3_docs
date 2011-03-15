<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\adapter\g11n\catalog;

use Exception;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use lithium\core\Libraries;
use lithium\analysis\Inspector;
use lithium\analysis\Docblock;
use lithium\util\Set;

class Docs extends \lithium\g11n\catalog\Adapter {

	/**
	 * Constructor.
	 *
	 * @param array $config Available configuration options are:
	 *        - `'library'`
	 *        - `'scope'`: Scope to use.
	 * @return void
	 */
	public function __construct($config = array()) {
		$defaults = array('library' => null, 'scope' => null);
		parent::__construct($config + $defaults);
	}

	/**
	 * Initializer.  Checks if the configured path exists.
	 *
	 * @return void
	 * @throws \Exception
	 */
	protected function _init() {
		parent::_init();

		if (!Libraries::get($this->_config['library'])) {
			throw new Exception("Library `{$this->_config['library']}` is not configured");
		}
	}

	/**
	 * Extracts data from files within configured path recursively.
	 *
	 * @param string $category Dot-delimited category.
	 * @param string $locale A locale identifier.
	 * @param string $scope The scope for the current operation.
	 * @return mixed
	 */
	public function read($category, $locale, $scope) {
		if ($scope != $this->_config['scope']) {
			return null;
		}
		$library = Libraries::get($this->_config['library']);
		$data = array();

		$classes = Libraries::find($this->_config['library'], array(
			'recursive' => true,
			'exclude' => '/\w+Test$|Mock+\w|webroot|index$|^app\\\\config|^\w+\\\\views\/|\./'
		));
		foreach ($classes as $class) {
			if (preg_match('/\\\(libraries|plugins)\\\/', $class)) {
				continue;
			}
			$data += $this->_parseClass($class);
		}
		return $data;
	}

	public function _parseClass($class) {
		$data = array();

		// $methods = Inspector::methods($class, null, array('public' => false));
		$methods = get_class_methods($class);
		$properties = array_keys(get_class_vars($class));

		$ident = $class;
		$info = Inspector::info($ident);
		$info = Docblock::comment($info['comment']);
		$data = $this->_merge($data, array(
			'id' => $info['description'],
			'comments' => array($ident)
		));
		$this->_merge($data, array(
			'id' => $info['text'],
			'comments' => array($class)
		));

		foreach ($methods as $method) {
			$ident = "{$class}::{$method}()";
			$info = Inspector::info($ident);
			$info = Docblock::comment($info['comment']);

			$this->_merge($data, array(
				'id' => $info['description'],
				'comments' => array($ident)
			));
			$this->_merge($data, array(
				'id' => $info['text'],
				'comments' => array($ident)
			));

			if (isset($info['tags']['return'])) {
				$this->_merge($data, array(
					'id' => $info['tags']['return'],
					'comments' => array($ident)
				));
			}

			foreach (Set::extract($info, '/tags/params/text') as $text) {
				$this->_merge($data, array(
					'id' => $text,
					'comments' => array($ident)
				));
			}
		}
		foreach ($properties as $property) {
			$ident = "{$class}::\${$property}";
			$info = Inspector::info($ident);
			$info = Docblock::comment($info['comment']);
			$data = $this->_merge($data, array(
				'id' => $info['description'],
				'comments' => array($ident)
			));
			$data = $this->_merge($data, array(
				'id' => $info['text'],
				'comments' => array($ident)
			));
		}
		return $data;
	}

	/**
	 * Cleans and merges a message item into given data.
	 *
	 * The implementation of the `$cleanup` closure should correspond to the one
	 * used in the templates.
	 *
	 * @param array $data Data to merge item into.
	 * @param array $item Item to merge into $data.
	 * @return void
	 * @see lithium\g11n\catalog\adapter\Base::_merge()
	 */
	protected function _merge(array $data, array $item) {
		$cleanup = function($text) {
			return preg_replace('/\n\s+-\s/msi', "\n\n - ", $text);
		};
		if (isset($item['id'])) {
			$item['id'] = $cleanup($item['id']);
		}
		return parent::_merge($data, $item);
	}
}

?>