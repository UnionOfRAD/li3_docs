<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\command;

use lithium\core\Libraries;
use li3_docs\models\Symbols;

/**
 * Harvests local symbols for easy API searchability.
 */
class Harvest extends \lithium\console\Command {

	/**
	 * undocumented class
	 *
	 * @var array
	 */
	protected $_classes = array(
		'response'  => 'lithium\console\Response',
		'indexer'   => 'li3_docs\extensions\docs\Indexer',
		'extractor' => 'li3_docs\extensions\docs\Extractor'
	);

	/**
	 * Main command logic.
	 *
	 * @return void
	 */
	public function run() {
		$this->_readyTables();
		$extractor = $this->_classes['extractor'];
		$libraries = Libraries::get();
		foreach ($libraries as $library => $info) {
			$this->header($library);
			$libFiles = Libraries::find($library, array(
				'recursive' => true,
				'exclude' => '/mocks|tests|libraries/'
			));
			foreach ($libFiles as $file) {
				$this->out("\n" . $file);
				$fileData = $extractor::get($library, $file);
				$this->_harvestMethods($fileData);
				$this->_harvestProperties($fileData);
				$this->_harvestClass($fileData);
			}
			$this->out("\n\n");
		}
		$this->out("\n\n");
	}

	/**
	 * Clears the harvest database.
	 *
	 * @return void
	 */
	public function clear() {
		$this->_readyTables();
	}

	/**
	 * Harvests method meta data from the specified file.
	 *
	 * @param string $fileData
	 * @return void
	 */
	protected function _harvestMethods($fileData) {
		foreach ($fileData['methods'] as $method) {
			$symbol = Symbols::create();
			$symbol->name = $method->name;
			$symbol->type = 'method';
			$symbol->class = $fileData['identifier'];
			$symbol->description = $method->getDocComment();
			$symbol->save();
			echo '.';
		}
	}

	/**
	 * Harvests class property data from the specified file.
	 *
	 * @param string $fileData
	 * @return void
	 */
	protected function _harvestProperties($fileData) {
		foreach ($fileData['properties'] as $property) {
			$symbol = Symbols::create();
			$symbol->name = $property['name'];
			$symbol->type = 'property';
			$symbol->class = $fileData['identifier'];
			$symbol->description = $property['docComment'];
			$symbol->save();
			echo '.';
		}
	}

	/**
	 * Harvests class information from the specified file.
	 *
	 * @param string $fileData
	 * @return void
	 */
	protected function _harvestClass($fileData) {
		$symbol = Symbols::create();
		$symbol->name = array_pop(explode("\\", $fileData['identifier']));
		$symbol->type = 'class';
		$symbol->class = $fileData['identifier'];
		$symbol->description = $fileData['description'];
		$symbol->save();
		echo '.';
	}

	/**
	 * Drops, then re-creates the Sqlite3 tables used for searching.
	 *
	 * @return void
	 */
	protected function _readyTables() {
		$dropSql = 'DROP TABLE IF EXISTS `symbols`';
		Symbols::connection()->invokeMethod('_execute', array($dropSql));

		$createSql = 'CREATE TABLE IF NOT EXISTS `symbols` (
			`id` INTEGER NOT NULL PRIMARY KEY,
			`name` VARCHAR (255) NOT NULL,
			`type` VARCHAR (255) NOT NULL,
			`class` TEXT NOT NULL,
			`description` TEXT NOT NULL
		);';
		Symbols::connection()->invokeMethod('_execute', array($createSql));
	}
}

?>