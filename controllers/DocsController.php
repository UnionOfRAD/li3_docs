<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\controllers;

use li3_docs\models\Indexes;

class DocsController extends \lithium\action\Controller {

	public function index() {
		$data = Indexes::find('grouped');
		return compact('data');
	}
}

?>