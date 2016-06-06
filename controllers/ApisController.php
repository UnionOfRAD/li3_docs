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
		if (!$symbol = $index->symbol($this->request->symbol)) {
			throw new Exception('Symbol not found.');
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