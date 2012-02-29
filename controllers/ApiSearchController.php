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
		$query = $this->request->params['query'];
		$conditions = array();
		
		// If the leading character is upper-case, only search models.
		if(preg_match('/^[A-Z]/', $query)) {
			$conditions['type'] = 'class';
		}
		
		// If it contains a '$', only search properties.
		if(preg_match('/\$/', $query)) {
			$query = str_replace('$', '', $query);
			$conditions['type'] = 'property';
		}
		
		// If it contains parens, only search methods.
		if(preg_match('/[\(\)]/', $query)) {
			$query = str_replace('(', '', $query);
			$query = str_replace(')', '', $query);
			$conditions['type'] = 'method';
		}
		
		$conditions['name'] = array(
			'like' => '%' . $query . '%'
		);
		
		$results = Symbols::find('all', array(
			'conditions' => $conditions
		));
			
		$this->set(compact('results'));
	}
}

?>