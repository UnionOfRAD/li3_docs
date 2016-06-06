<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\models;

use li3_docs\models\Pages;
use li3_docs\models\Symbols;
use lithium\util\Collection;

class Indexes extends \lithium\data\Model {

	protected $_meta = [
		'connection' => false
	];

	protected static $_data = [];

	public static function init() {
		static::finder('all', function($self, $params, $chain) {
			$params['options'] += ['conditions' => []];
			$conditions = $params['options']['conditions'];

			if (!$conditions) {
				return new Collection(['data' => static::$_data]);
			}
			$results = [];
			foreach (static::$_data as $id => $item) {
				foreach ($conditions as $k => $v) {
					if ($item->{$k} !== $v) {
						continue(2);
					}
				}
				$results[$id] = $item;
			}
			return new Collection(['data' => $results]);
		});
		// Group by name
		static::finder('grouped', function($self, $params, $chain) {
			$results = [];
			foreach (static::$_data as $id => $item) {
				if (!isset($results[$item->name])) {
					$results[$item->name] = [];
				}
				$results[$item->name][] = $item;
			}
			return new Collection(['data' => $results]);
		});
		static::finder('list', function($self, $params, $chain) {
			$results = [];

			foreach (static::$_data as $id => $item) {
				$results[$id] = $item->title;
			}
			return $results;
		});
		static::finder('first', function($self, $params, $chain) {
			$params['options'] += ['conditions' => []];
			$conditions = $params['options']['conditions'];

			if (isset($conditions['id'])) {
				return static::$_data[$conditions['id']];
			}
			foreach (static::$_data as $item) {
				foreach ($conditions as $k => $v) {
					if ($item->{$k} !== $v) {
						continue(2);
					}
				}
				return $item;
			}
			return false;
		});
	}

	public static function register(array $options = []) {
		if (!isset($options['path'])) {
			throw new Exception('No path given for index.');
		}
		$item  = $options;

		if (!isset($options['name'])) {
			throw new Exception("No name given for index in path `{$options['path']}`.");
		}
		if (!isset($options['type'])) {
			throw new Exception("No type given for index in path `{$options['path']}`.");
		}
		$item += [
			'type' => null,
			'title' => $options['name'],
			'version' => 'x.x',
			'description' => null,
			'namespace' => null
		];

		static::$_data[$id = static::_id($item)] = static::create(compact('id') + $item);
	}

	protected static function _id(array $item) {
		return "{$item['type']}#{$item['name']}#{$item['version']}";
	}

	public function symbol($entity, $name) {
		return Symbols::find('first', [
			'conditions' => [
				'name' => $name,
				'index' => $entity->id
			]
		]);
	}

	public function page($entity, $page) {
		return Pages::find('first', [
			'conditions' => [
				'name' => $page,
				'index' => $entity->id
			]
		]);
	}

	public function title($entity) {
		return $entity->title;
	}
}

Indexes::init();

?>