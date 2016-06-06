<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\doc;

use lithium\util\Inflector;

/**
 * Union of RAD flavored Markdown.
 */
class UorMarkdown extends \cebe\markdown\GithubMarkdown {

	/**
	 * Overwritten to default to HTML5 output.
	 */
	public $html5 = true;

	/**
	 * Overwritten to make headlines linkable.
	 */
	protected function renderHeadline($block) {
		$tag = 'h' . $block['level'];
		$text = $this->renderAbsy($block['content']);
		$slug = strtolower(Inflector::slug($text));

		return sprintf(
			'<%s><a id="%s" class="anchor" href="%s">%s</a></%s>' . "\n",
			$tag,
			$slug,
			'#' . $slug,
			$text,
			$tag
		);
	}

	/**
	 * Overwritten to default to PHP language in code blocks.
	 */
	protected function consumeFencedCode($lines, $current) {
		list($block, $i) = parent::consumeFencedCode($lines, $current);

		if (empty($block['language'])) {
			$block['language'] = 'php';
		}
		return [$block, $i];
	}
}

?>