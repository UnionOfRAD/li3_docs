<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\controllers;

use li3_docs\models\Symbols;

/**
 * This is the Lithium API search controller. Once the symbol harvester has run,
 * this class queries that database for quick, ranked API search results.
 */
class ApiSearchController extends \lithium\action\Controller {

	/**
	 * AJAX response action for search autocomplete/results.
	 *
	 * @return array Returns an array detailing the symbol and its metadata.
	 */
	public function query() {
		$this->_render['type'] = 'json';
		$results = Symbols::find('all', array(
			'conditions' => array(
				'name' => array(
					'like' => '%' . $this->request->params['query'] . '%'
				)
			),
			'limit' => 10
		));
		$this->set(compact('results'));
	}
}

?>