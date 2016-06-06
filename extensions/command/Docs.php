<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\command;

use li3_docs\models\Indexes;
use li3_docs\models\Pages;
use li3_docs\models\Symbols;

class Docs extends \lithium\console\Command {

	/**
	 * Generates index for registered libraries.
	 */
	public function index() {
		$pdo = Pages::connection()->connection;
		$pdo->beginTransaction();

		$this->out('Removing all pages and symbols...');
		Pages::remove();
		Symbols::remove();

		foreach (Indexes::find('all') as $index) {
			$this->out('Processing index:' . var_export($index->data(), true));

			if ($index->type === 'api') {
				$this->out('Harvesting symbols...');

				if (!Symbols::harvest($index)) {
					$this->error('FAILED');
					$pdo->rollback();
					return false;
				}
			}
			$this->out('Harvesting pages...');
			if (!Pages::harvest($index)) {
				$this->error('FAILED');
				$pdo->rollback();
				return false;
			}
		}

		$pdo->commit();
	}
}

?>