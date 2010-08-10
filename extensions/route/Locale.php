<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of Rad, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\route;

use \lithium\core\Environment;

class Locale extends \lithium\net\http\Route {

	protected function _init() {
		$this->_config['template'] = '/{:locale:[a-z]+[a-z]+}' . $this->_config['template'];
		$this->_config['params'] += array('locale' => null);
		parent::_init();
	}

	public function match(array $options = array(), $context = null) {
		$locale = Environment::get('locale');
		return parent::match($options + compact('locale'), $context);
	}
}

?>