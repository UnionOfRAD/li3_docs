<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\extensions\helper;

use lithium\core\Libraries;

class Docs extends \lithium\template\helper\Html {

	/**
	 * The li3_docs library configuration.
	 *
	 * @see lithium\core\Libraries::add()
	 * @var array
	 */
	protected $_libraryConfig;

	/**
	 * The configured base url for li3_docs.
	 * Used to determing breadcrumb urls
	 *
	 * @var string
	 */
	protected $_baseUrl;

	protected function _init() {
		parent::_init();
		$this->_libraryConfig = Libraries::get('li3_docs');
		$this->_baseUrl = $this->_libraryConfig['url'];
	}

	public function cleanup($text) {
		$result = preg_replace('/\n\s+-\s/msi', "\n\n - ", $text);

		// Fix indentation in docblock lists.
		$result = implode("\n", array_map('trim', explode("\n", $result)));

		// Allow text and fenced codeblocks without a blank line.
		$result = preg_replace('/\w*\n(```)/msi', "\n\n```", $result);

		return $result;
	}

	public function identifierUrl($class) {
		if (strpos($class, '://') !== false) {
			return $class;
		}

		$parts = explode('\\', $class);
		$lib = array_shift($parts);
		$args = $parts;
		return array('controller' => 'li3_docs.ApiBrowser', 'action' => 'view') + compact('lib', 'args');
	}

	public function pageUrl($page) {
		$parts = explode('/', $page);
		$lib = array_shift($parts);
		$args = $parts;
		return array('controller' => 'li3_docs.ApiBrowser', 'action' => 'view') + compact('lib', 'args');
	}

	public function crumbs($object) {
		$path = array_filter(array_merge(
			array($object['name']), explode('\\', $object['identifier'])
		));
		$crumbs[] = array(
			'title' => $object['type'],
			'url' => $this->_baseUrl,
			'class' => 'type ' . $object['type']
		);
		$url = '';

		foreach (array_slice($path, 0, -1) as $part) {
			$url .= '/' . $part;
			$crumbs[] = array('title' => $part, 'url' => "{$this->_baseUrl}{$url}", 'class' => 'namespace');
		}
		$ident = end($path);

		if (strpos($ident, '::') !== false) {
			list($class, $ident) = explode('::', $ident, 2);
			$crumbs[] = array('title' => $class, 'url' => "{$this->_baseUrl}{$url}/{$class}", 'class' => null);
			$isMethod = true;
		} else {
			$isMethod = false;
		}
		$crumbs[] = array('title' => $ident, 'url' => null, 'class' => $isMethod ? 'ident' : null);
		return $crumbs;
	}

	public function contents($children) {
		$list = '';
		foreach ($children as $name => $type) {
			if (is_array($type)) {
				extract($type, EXTR_OVERWRITE);
			}
			if (!isset($url)) {
				$url = $this->identifierUrl($name);
				$parts = explode('\\', $name);
				$name = basename(end($parts));
			} else {
				$url = $this->pageUrl($url);
			}
			if (!isset($contents)) {
				$list .= "<li class='$type'>" . $this->link($name, $url) . '</li>';
			} else {
				$list .= "<li class='$type'>" . $this->link($name, $url);
				$list .= '<ul class="children">' . $this->contents($contents) . '</ul>';
				$list .= '</li>';
			}

			unset($url);
		}
		return $list;
	}
}

?>