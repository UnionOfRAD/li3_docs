<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\models;

use lithium\core\Libraries;
use lithium\util\Collection;

/**
 * Symbols model. Each entity has following fields:
 * id, name, type, class and description.
 */
class Symbols extends \lithium\data\Model {

	protected $_meta = array(
		'connection' => false
	);

	protected $_classes = array(
		'indexer'   => 'li3_docs\extensions\docs\Indexer',
		'extractor' => 'li3_docs\extensions\docs\Extractor'
	);

	public static function harvest() {
		$extractor = $this->_classes['extractor'];
		$results = array();

		foreach (Libraries::get() as $library => $info) {
			$libFiles = Libraries::find($library, array(
				'recursive' => true,
				'exclude' => '/mocks|tests|libraries/'
			));
			foreach ($libFiles as $file) {
				$this->out("\n" . $file);
				$fileData = $extractor::get($library, $file);

				$results = array_merge(
					static::_harvestMethods($fileData),
					static::_harvestProperties($fileData),
					array(static::_harvestClass($fileData))
				);
			}
		}
		return new Collection(array('data' => $results));
	}

	/**
	 * Harvests method meta data from the specified file.
	 *
	 * @param string $fileData
	 * @return array
	 */
	protected static function _harvestMethods($fileData) {
		$results = array();

		foreach ($fileData['methods'] as $method) {
			$results[] = static::create(array(
				'name' => $method->name,
				'type' => 'method',
				'class' => $fileData['identifier'],
				'description' => $method->getDocComment()
			));
		}
		return $results;
	}

	/**
	 * Harvests class property data from the specified file.
	 *
	 * @param string $fileData
	 * @return array
	 */
	protected static function _harvestProperties($fileData) {
		$results = array();

		foreach ($fileData['properties'] as $property) {
			$results[] = static::create(array(
				'name' => $property['name'],
				'type' => 'property',
				'class' => $fileData['identifier'],
				'description' => $property['docComment']
			));
		}
		return $results;
	}

	/**
	 * Harvests class information from the specified file.
	 *
	 * @param string $fileData
	 * @return object
	 */
	protected static function _harvestClass($fileData) {
		return static::create(array(
			'name' => array_pop(explode("\\", $fileData['identifier'])),
			'type' => 'class',
			'class' => $fileData['identifier'],
			'description' => $property['description']
		));
	}
}

?>