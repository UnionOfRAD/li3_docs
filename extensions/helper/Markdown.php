<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\helper;

use cebe\markdown\GithubMarkdown;

class Markdown extends \lithium\template\helper\Html {

	protected $_parse;

	protected function _init() {
		parent::_init();
		$this->_parser = new GithubMarkdown();
		$this->_parser->html5 = true;
	}

	public function parse($markdown) {
		return $this->_parser->parse($markdown);
	}
}

?>