<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\command;

use lithium\core\Libraries;
use li3_docs\extensions\docs\Code;
use li3_docs\extensions\docs\Indexer;
use li3_docs\extensions\command\docs\Generator;
use li3_docs\extensions\command\docs\Todo;

/**
 * Adds headers and docblocks to classes and methods.
 */
class Docs extends \lithium\console\Command {

	/**
	 * The path to use for docs extraction. Defaults to `<app>/resources/docs`.
	 *
	 * @var string
	 */
	public $path = '';

	public $libraries = '';

	public function generator() {
		$generator = new Generator(array('request' => $this->request));
		return $generator->run();
	}

	public function todo() {
		$todo = new Todo(array('request' => $this->request));
		return $todo->run();
	}

	public function verify() {
		$this->out("{:white}Verifying that code matches documentation signatures...{:end}");

		foreach (Code::index() as $ref => $hash) {
			if (Code::hash($ref) != $hash) {
				$this->out("{:error}Warning: {$ref} is out-of-date.{:end}");
			}
		}
		$this->out("{:white}Fin.{:end}");
	}

	public function index() {
		$this->path = $this->path ?: Libraries::get(true, 'path') . '/resources/docs';
		$this->libraries = array_filter(explode(',', $this->libraries));

		if (!is_dir($this->path)) {
			mkdir($this->path, 0755, true);
		}
		Indexer::run();
		return;

		foreach (Indexer::libraries() as $name => $config) {
		}
	}
}

?>