<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\helper;

use li3_docs\doc\UorMarkdown;

class Markdown extends \lithium\template\helper\Html {

	protected $_parser;

	protected function _init() {
		parent::_init();
		$this->_parser = new UorMarkdown();
	}

	public function parse($markdown) {
		return $this->_parser->parse($markdown);
	}
}

?>