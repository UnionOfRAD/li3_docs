<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\controllers;

use li3_docs\models\Indexes;
use Exception;

class BooksController extends \lithium\action\Controller {

	public function view() {
		$index = Indexes::find('first', [
			'conditions' => [
				'type' => 'book',
				'name' => $this->request->name,
				// $this->request->version gives HTTP version.
				'version' => isset($this->request->params['version']) ? $this->request->params['version'] : null
			]
		]);
		if (!$index) {
			throw new Exception('Index not found.');
		}
		if (!$page = $index->page($this->request->page ?: '.')) {
			throw new Exception('Page not found.');
		}
		$root = $index->page('.');
		$crumbs = $this->_crumbsForPage($index, $page);

		return compact('index', 'page', 'root', 'crumbs');
	}

	protected function _crumbsForPage($index, $page) {
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
				'controller' => 'Books',
				'action' => 'view',
				'name' => $index->name,
				'version' => $index->version
			]
		];

		foreach ($page->parents() as $p) {
			if ($p->isRoot()) {
				continue;
			}
			$crumbs[] = [
				'title' => $p->title(),
				'url' => [
					'library' => 'li3_docs',
					'controller' => 'Books',
					'action' => 'view',
					'name' => $index->name,
					'version' => $index->version,
					'page' => $p->name
				]
			];
		}
		if (!$page->isRoot()) {
			$crumbs[] = [
				'title' => $page->title(),
				'url' => null
			];
		}
		return $crumbs;
	}
}

?>