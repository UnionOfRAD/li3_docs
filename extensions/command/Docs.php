<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\command;

use li3_docs\extensions\command\docs\Generator;
use li3_docs\extensions\command\docs\Todo;

/**
 * Adds headers and docblocks to classes and methods.
 */
class Docs extends \lithium\console\Command {

	public function run() {

	}

	public function generator() {
		$generator = new Generator(array('request' => $this->request));
		return $generator->run();
	}
	
	public function todo() {
		$todo = new Todo(array('request' => $this->request));
		return $todo->run();
	}
}

?>