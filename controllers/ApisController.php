<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\controllers;

use Exception;
use li3_docs\models\Indexes;

/**
 * This is the Lithium API browser controller. This class introspects your application's libraries,
 * plugins and classes to generate on-the-fly API documentation.
 */
class ApisController extends \lithium\action\Controller {

	protected function _init() {
		parent::_init();
		$this->response->encoding = 'UTF-8';
	}

	/**
	 * This action renders the detail page for all API elements, including namespaces, classes,
	 * properties and methods. The action determines what type of entity is being displayed, and
	 * gathers all available data on it. Any wiki text embedded in the data is then post-processed
	 * and prepped for display.
	 *
	 * Handles broken URL parsers by matching method URLs with no closing ) or no () at all
	 * and redirecting.
	 *
	 * @return array Returns an array with the following keys:
	 *               - `'name'`: A string containing the full name of the entity being displayed
	 *               - `'library'`: An array with the details of the current class library being
	 *                 browsed, in which the current entity is contained.
	 *               - `'object'`: A multi-level array containing all data extracted about the
	 *                 current entity.
	 */
	public function view() {
		$index = Indexes::find('first', [
			'conditions' => [
				'type' => 'api',
				'name' => $this->request->name,
				// $this->request->version gives HTTP version.
				'version' => isset($this->request->params['version']) ? $this->request->params['version'] : null
			]
		]);
		if (!$index) {
			throw new Exception('Index not found.');
		}
		if (!$this->request->symbol && $index->namespace) {
			return $this->redirect([
				'library' => 'li3_docs',
				'controller' => 'Apis',
				'action' => 'view',
				'name' => $index->name,
				'version' => $index->version,
				'symbol' => $index->namespace
			]);
		}
		if (!$symbol = $index->symbol($this->request->symbol)) {
			// Just class members are handled below.
			if (strpos($this->request->symbol, '::') === false) {
				throw new Exception("Symbol `{$this->request->symbol}` not found.");
			}
			// As Markdown does not allow closing () on method links or sends
			// them out as just ) or ( we'll see if there's method symbol
			// similiar to this one and redirect to that.
			$fixed = rtrim($this->request->symbol, '()') . '()';

			if ($symbol = $index->symbol($fixed)) {
				return $this->redirect([
					'library' => 'li3_docs',
					'controller' => 'Apis',
					'action' => 'view',
					'name' => $index->name,
					'version' => $index->version,
					'symbol' => $symbol->name
				]);
			}

			// From documentation links may be generated to inherited
			// members of a class. We redirect to the class where they
			// were defined. Document::$_parent -> Entity::$_parent
			list($class, $member) = explode('::', $this->request->symbol, 2) + [null, null];

			if (!$symbol = $index->symbol($class)) {
				throw new Exception("Symbol `{$this->request->symbol}` not found.");
			}
			if ($member[0] === '$') {
				$type = 'property';
			} elseif (strtoupper($member[0]) === $member[0]) {
				$type = 'constant';
			} else {
				$type = 'method';
			}
			$symbol = $symbol->members(compact('type'))->first(function($item) use ($member) {
				return $item->title(['last' => true]) === $member;
			});
			if (!$symbol) {
				throw new Exception("Symbol `{$this->request->symbol}` not found.");
			}
			return $this->redirect([
				'library' => 'li3_docs',
				'controller' => 'Apis',
				'action' => 'view',
				'name' => $index->name,
				'version' => $index->version,
				'symbol' => $symbol->name
			]);
		}
		$crumbs = $this->_crumbsForSymbol($index, $symbol);
		return compact('index', 'symbol', 'crumbs');
	}

	protected function _crumbsForSymbol($index, $symbol) {
		$crumbs = [];

		$crumbs[] = [
			'title' => 'Documentation',
			'url' => [
				'library' => 'li3_docs',
				'controller' => 'Docs',
				'action' => 'index'
			]
		];
		$crumbs[] = [
			'title' => $index->title() . ' (' . $index->version . ')',
			'url' => [
				'library' => 'li3_docs',
				'controller' => 'Apis',
				'action' => 'view',
				'name' => $index->name,
				'version' => $index->version,
				'symbol' => $index->namespace
			]
		];
		$segments = $symbol->segments();
		while (list($segment, $title) = each($segments)) {
			if (!is_string($segment)) {
				continue;
			}
			if (key($segments) === null) {
				$crumbs[] = [
					'title' => $title,
					'url' => null
				];
			} else {
				$crumbs[] = [
					'title' => $title,
					'url' => [
						'library' => 'li3_docs',
						'controller' => 'Apis',
						'action' => 'view',
						'name' => $index->name,
						'version' => $index->version,
						'symbol' => $segment
					]
				];
			}
		}
		return $crumbs;
	}
}

?>