<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
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
		$parts = explode("\n", $markdown);
		$clean = [];
		$code = false;

		foreach ($parts as $part) {
			if (strpos($part, '```') !== false) {
				if (!$code) { // we're opening
					// Allow text and fenced codeblocks without a blank line.
					if (end($clean) !== '') {
						$clean[] = '';
					}
				}
				$code = $code ? false : true;
			}
			$clean[] = $part;
		}
		$markdown = implode("\n", $clean);

		return $this->_parser->parse($markdown);
	}
}

?>