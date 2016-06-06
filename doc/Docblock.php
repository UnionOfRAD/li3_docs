<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\doc;

use lithium\core\ConfigException;

/**
 * A source code docblock parser which may be used as the basis for a variety of
 * secondary structural analysis tools.
 *
 * Summary and all descriptions support Markdown syntax, this class does leave the task of
 * parsing to an external class.
 *
 * @link https://github.com/cebe/markdown
 */
class Docblock {

	/**
	 * Parsed summary.
	 *
	 * @var string
	 */
	protected $_summary = '';

	/**
	 * Parsed description.
	 *
	 * @var string
	 */
	protected $_description = '';

	/**
	 * Parsed tag definitions.
	 *
	 * @var array
	 */
	protected $_tags = [];

	/**
	 * Constructor.
	 *
	 * @param array $config Available configuration options are:
	 *        - `'comment'` _string_: Required docblock source code.
	 */
	public function __construct(array $config = []) {
		$config += [
			'comment' => null
		];
		if (!$config['comment']) {
			throw new ConfigException("No docblock source in `'comment'` given.");
		}
		$this->_parse($config['comment']);
	}

	/**
	 * Returns the summary, the first paragraph of the docblock text.
	 *
	 * @return string
	 */
	public function summary() {
		return $this->_summary;
	}

	/**
	 * Returns the long description.
	 *
	 * @return string
	 */
	public function description() {
		return $this->_description;
	}

	/**
	 * Retrieve a single tag item. Good for i.e. `return` tags, that can
	 * by definition only appear once.
	 *
	 * @param string $name The tag name to retrieve.
	 * @return array|boolean The tag information or `false` if tag does not exist.
	 */
	public function tag($name) {
		if (!isset($this->_tags[$name])) {
			return false;
		}
		foreach ($this->_tags[$name] as $item) {
			return $item;
		}
	}

	/**
	 * Retrieve a multiple tag items. Good for i.e. `see` tags, that can
	 * by definition appear multiple times.
	 *
	 * @param string $name The tag name to retrieve.
	 * @return array An array of tag informations or an empty array if no tags
	 *         for given name exist.
	 */
	public function tags($name) {
		if (!isset($this->_tags[$name])) {
			return [];
		}
		return $this->_tags[$name];
	}

	/**
	 * Parses a docblock into its major components of `summary`, `description` and `tags`.
	 *
	 * @param string $comment The docblock string to be parsed
	 * @return void
	 */
	protected function _parse($comment) {
		$summary = null;
		$description = null;
		$tags = array();

		$comment = trim(preg_replace('/^(\s*\/\*\*|\s*\*{1,2}\/|\s*\* ?)/m', '', $comment));
		$comment = str_replace("\r\n", "\n", $comment);

		if ($items = preg_split('/\n@/ms', $comment, 2)) {
			list($summary, $tags) = $items + array('', '');
			$this->_tags = $tags ? $this->_parseTags("@{$tags}") : array();
		}
		if (strpos($summary, "\n\n")) {
			list($summary, $description) = explode("\n\n", $summary, 2);
		}
		$this->_summary = $this->_clean($summary);
		$this->_description = $this->_clean($description);
	}

	/**
	 * Parses `@<tagname>` docblock tags and their descriptions from a docblock.
	 *
	 * See the `$tags` property for the list of supported tags.
	 *
	 * @param string $string The string to be parsed for tags
	 * @return array Returns an array where each docblock tag is a key name, and the corresponding
	 *         values are either strings (if one of each tag), or arrays (if multiple of the same
	 *         tag).
	 */
	protected function _parseTags($string) {
		$string = trim($string);

		$result = preg_split('/\n@(?P<type>[a-z]+)/msi', "\n$string", -1, PREG_SPLIT_DELIM_CAPTURE);
		$tags = array();

		for ($i = 1; $i < count($result) - 1; $i += 2) {
			$tag = trim(strtolower($result[$i]));
			$description = trim($result[$i + 1]);

			switch ($tag) {
				case 'param':
					$tags[$tag][] = $this->_parseTag($description, [
						'type', 'name', 'description'
					]);
				break;
				case 'return':
					$tags[$tag][] = $this->_parseTag($description, [
						'type', 'description'
					]);
				break;
				case 'see':
					$tags[$tag][] = $this->_parseTag($description, [
						'symbol', 'description'
					]);
				break;
				case 'link':
					$tags[$tag][] = $this->_parseTag($description, [
						'url', 'description'
					]);
				break;
				default:
					$tags[$tag][] = compact('description');
				break;
			}
		}
		return $tags;
	}

	/**
	 * Parses space delimited docblock tags to separate out keys.
	 *
	 * @param string
	 * @param array Keys (order matters) to parse out.
	 * @return array Returns an array containing the given keys.
	 */
	protected function _parseTag($string, array $keys = []) {
		$parts = explode(' ', $string, count($keys));
		$result = array_fill_keys($keys, null);

		foreach ($keys as $i => $key) {
			if (isset($parts[$i])) {
				if ($key === 'description') {
					$result[$key] = $this->_clean($parts[$i]);
				} else {
					$result[$key] = $parts[$i];
				}
			}
		}
		return $result;
	}

	/**
	 * Cleans a (multi-line) description string by removing multiple whitespaces in front
	 * of lists (so they parse correctly), removes trailing and leading whitespaces on
	 * lines that are not contained in a markdown fenced code block.
	 *
	 * @param string $string;
	 * @return string
	 */
	protected function _clean($string) {
		$parts = explode("\n", $string);
		$code = false;

		foreach ($parts as &$part) {
			if (strpos($part, '```') !== false) {
				$code = !$code;
			}
			$part = $code ? rtrim($part) : trim($part);
		}
		return implode("\n", $parts);
	}
}

?>