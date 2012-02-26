<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\command;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use lithium\core\Libraries;

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
		$extractor = $this->_classes['extractor'];
		foreach(Libraries::get() as $library => $info) {
			$libFiles = Libraries::find($library, array('recursive' => true));
			foreach($libFiles as $file) {
				$fileData = $extractor::get($library, $file);
			}
		}
	}
}

?>