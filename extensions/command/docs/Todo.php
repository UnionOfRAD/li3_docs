<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\command\docs;

use \lithium\core\Libraries;
use \lithium\util\Inflector;
use \lithium\analysis\Parser;

/**
 * Searches and displays @todo, @discuss, @fix and @important comments in your code.
 */
class Todo extends \lithium\console\Command {

	/**
	 * undocumented variable
	 *
	 * @var id
	 */
	public $show = true;

	/**
	 * undocumented function
	 *
	 * @param string $dir
	 * @return void
	 */
	public function run($library = 'lithium') {
		$libs = Libraries::find($library, array('recursive' => true));
		$files = array();

		foreach ((array)$libs as $lib) {
			$file = Libraries::path($lib);
			$this->_display($file);
		}
	}

	protected function _display($file) {
		$matches = Parser::tokenize(file_get_contents($file));
		if (empty($matches)) {
			$this->stop(1, 'no matches');
		}
		if (!$this->show) {
			$this->out($file.':');
		}
		$rows = array(array('', 'ID', 'LINE', 'TYPE', 'TEXT'));
		foreach ($matches as $match) {
			$id = substr(sha1($file . $match['line']), 0, 4);
			if ($id == $this->show) {
				$this->stop(0, $file);
			}
			$rows[] = array('', $id, $match['line'], $match['type'], $match['text']);
		}
		if (!$this->show) {
			$this->out($this->columns($rows));
			$this->hr();
			$this->nl();
		}
	}
}

?>