<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\models;

use Exception;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use li3_docs\doc\Docblock;
use li3_docs\doc\Visitor;
use li3_docs\models\Indexes;
use lithium\analysis\Logger;
use lithium\util\Collection;

class Symbols extends \lithium\data\Model {

	public static function harvest($index) {
		$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

		$traverser = new NodeTraverser();
		$traverser->addVisitor(new NameResolver());
		$traverser->addVisitor(new Visitor([
			'collectSymbol' => function($name, array $data) use (&$symbols) {
				$symbols[$name] = $data;
			}
		]));

		foreach (static::_objects($index->path) as $object) {
			$symbols = [];

			$contents = file_get_contents($object);
			$stmts = $parser->parse($contents);
			$stmts = $traverser->traverse($stmts);

			foreach ($symbols as $name => $data) {
				$data += [
					'type' => null,
					'parent' => null,
					'startLine' => null,
					'endLine' => null,
					'docblock' => null,
					'extends' => null,
					'visibility' => null,
					'isStatic' => false,
					'isAbstract' => false,
					'traits' => [],
					'interfaces' => [],
				];
				$item = static::create([
					'name' => static::_name($name),
					'type' => $data['type'],
					'parent' => $data['parent'] ? static::_name($data['parent']) : null,
					'extends' => $data['extends'] ? static::_name($data['extends']) : null,
					'file' => str_replace($index->path . '/', '', $object->getPathname()),
					'index' => $index->id,
					'docblock' => $data['docblock'],
					'source' => static::_source($contents, $data['startLine'], $data['endLine']),
					'visibility' => $data['visibility'],
					'is_static' => $data['isStatic'],
					'is_abstract' => $data['isAbstract'],
					'traits' => ($data['traits']
						?
						implode(',', array_map(function($v) {
							return static::_name($v);
						}, $data['traits']))
						:
						null),
					'interfaces' => ($data['interfaces']
						?
						implode(',', array_map(function($v) {
							return static::_name($v);
						}, $data['interfaces']))
						:
						null),
				]);

				if ($docblock = $item->docblock()) {
					$item->is_deprecated = (boolean) $docblock->tag('deprecated');
				}
				if ($item->type === 'namespace') {
					if (static::_hasItem($item)) {
						continue;
					}
				}
				if (!$item->save()) {
					return false;
				}
			}
		}
		if (!static::_addMissingNamespaces($index)) {
			return false;
		}
		return true;
	}

	protected static function _source($contents, $start, $end) {
		if (!$start || !$end) {
			return null;
		}
		return implode("\n", array_slice(explode("\n", $contents), $start - 1, $end - $start + 1));
	}

	protected static function _name($name) {
		return str_replace('\\', '/', $name);
	}

	protected static function _objects($path) {
		$files = new RecursiveCallbackFilterIterator(
			new RecursiveDirectoryIterator($path),
			function($current, $key, $iterator) {
				$noDescend = [
					'.git',
					'libraries',
					'vendor',
					'mocks',
					'tests',
					'docs'
				];
				if ($iterator->hasChildren()) {
					if ($current->isDir() && in_array($current->getBasename(), $noDescend)) {
						return false;
					}
					return true;
				}
				return $current->getExtension() === 'php';
			}
		);
		return new RecursiveIteratorIterator($files);
	}

	public function index($entity) {
		return Indexes::find('first', [
			'conditions' => [
				'id' => $entity->index
			]
		]);
	}

	protected static function _hasItem($item) {
		return (boolean) static::find('count', [
			'conditions' => [
				'index' => $item->index,
				'name' => $item->name,
				'type' => $item->type
			]
		]);
	}

	protected static function _addMissingNamespaces($index) {
		$namespaces = static::find('all', [
			'conditions' => [
				'index' => $index->id,
				'type' => 'namespace'
			],
			'fields' => ['DISTINCT' => 'name']
		]);
		foreach ($namespaces as $ns) {
			$parts = explode('/', $ns->name);

			while (array_pop($parts) && $parts) {
				$item = static::create([
					'index' => $index->id,
					'type' => 'namespace',
					'name' => implode('/', $parts),
					'parent' => implode('/', array_slice($parts, 0, -1))
				]);
					Logger::debug("Adding missing namespace? `{$item->name}`");
				if (!static::_hasItem($item)) {
					Logger::debug("Adding missing namespace `{$item->name}`");

					if (!$item->save()) {
						return false;
					}
				}
			}
		}
		return true;
	}

	// Namespace symbols may have a corresponding page.
	public function page($entity) {
		if ($entity->type !== 'namespace') {
			return false;
		}
		return Pages::find('first', [
			'conditions' => [
				'index' => $entity->index,
				'name' => $entity->name
			]
		]);
	}

	public function title($entity, array $options = []) {
		$options += [
			'last' => false,
			'namespace' => null
		];
		if (!$options['last']) {
			$name = $entity->name;

			if ($options['namespace']) {
				$name = str_replace($options['namespace'] . '/', '', $name);
			}
			return str_replace('/', '\\', $name);
		}
		if (in_array($entity->type, ['class', 'trait', 'interface'])) {
			return substr($entity->name, strrpos($entity->name, '/') + 1);
		}
		if (in_array($entity->type, ['method', 'property', 'constant'])) {
			return substr($entity->name, strrpos($entity->name, '::') + 2);
		}
	}

	// Drives link path title/crumbs.
	public function segments($entity) {
		$result = [];

		if (strpos($entity->name, '::') !== false) {
			list($class, $child) = explode('::', $entity->name, 2);
			$result[$entity->name] = $child;
			$result[] = '::';
		} else {
			$class = $entity->name;
		}
		$parts = explode('/', $class);
		while ($parts) {
			$result[implode('/', $parts)] = end($parts);

			array_pop($parts);
			if ($parts) {
				$result[] = '\\';
			}
		}
		return array_reverse($result);
	}

	public function docblock($entity) {
		if (!$entity->docblock) {
			return null;
		}
		return new Docblock(['comment' => $entity->docblock]);
	}

	public function parent_($entity) {
		return static::find('first', [
			'conditions' => [
				'index' => $entity->index,
				'name' => $entity->parent
			]
		]);
	}

	public function classes($entity, array $options = []) {
		return $entity->children(['type' => substr(__FUNCTION__, 0, -1)] + $options);
	}

	public function traits($entity, array $options = []) {
		return $entity->children(['type' => substr(__FUNCTION__, 0, -1)] + $options);
	}

	public function interfaces($entity, array $options = []) {
		return $entity->children(['type' => substr(__FUNCTION__, 0, -1)] + $options);
	}

	public function namespaces($entity, array $options = []) {
		return $entity->children(['type' => substr(__FUNCTION__, 0, -1)] + $options);
	}

	public function children($entity, array $options = []) {
		if ($entity->type !== 'namespace') {
			return new Collection();
		}
		$options += [
			'recursive' => false,
			'deprecated' => true,
			'type' => null
		];
		$conditions = [
			'index' => $entity->index
		];
		if ($options['recursive']) {
			$conditions['name'] = ['LIKE' => $entity->name . '/%'];
		} else {
			$conditions['parent'] = $entity->name;
		}
		if (!$options['deprecated']) {
			$conditions['deprecated'] = false;
		}
		if ($options['type']) {
			$conditions['type'] = $options['type'];
		}
		return static::find('all', compact('conditions') + [
			'order' => ['name']
		]);
	}

	public function methods($entity, array $options = []) {
		return $entity->members(['type' => substr(__FUNCTION__, 0, -1)] + $options);
	}

	public function properties($entity, array $options = []) {
		return $entity->members(['type' => substr(__FUNCTION__, 0, -1)] + $options);
	}

	public function constants($entity, array $options = []) {
		return $entity->members(['type' => substr(__FUNCTION__, 0, -1)] + $options);
	}

	public function members($entity, array $options = []) {
		if (!in_array($entity->type, ['class', 'trait', 'interface'])) {
			return new Collection();
		}
		$options += [
			'publicOnly' => false,
			'inherited' => true,
			'deprecated' => true,
			'type' => null
		];
		if (!in_array($options['type'], ['method', 'property', 'constant'])) {
			throw new Exception("Invalid member type `{$options['type']}`.");
		}
		$conditions = [
			'index' => $entity->index,
		];
		if ($options['type'] === 'method' || $options['type'] === 'constant') {
			$conditions += [
				'type' => $options['type'],
				'name' => ['LIKE' => $entity->name . '::%']
			];
		} elseif ($options['type'] === 'property') {
			$conditions += [
				'type' => $options['type'],
				'name' => ['LIKE' => $entity->name . '::$%']
			];
		}
		if ($options['publicOnly']) {
			$conditions['visibility'] = 'public';
		}
		if (!$options['deprecated']) {
			$conditions['deprecated'] = false;
		}
		$results = static::find('all', compact('conditions') + [
			// keep order of appearance even using db indexes
			'order' => ['id']
		]);

		if (!$options['inherited']) {
			return $results;
		}
		$current = $entity;

		$traits = static::find('all', [
			'conditions' => [
				'index' => $entity->index,
				'type' => 'trait',
				'name' => explode(',', $entity->traits)
			]
		]);
		foreach ($traits as $trait) {
			foreach ($trait->members($options) as $member) {
				$title = $member->title(['last' => true]);

				$found = $results->first(function($v) use ($title) {
					return $v->title(['last' => true]) === $title;
				});
				if ($found) {
					$found->overrides = $member->name;
				} else {
					$results[$member->id] = clone $member;
					$results[$member->id]->inherited = $current->name;
				}
			}
		}

		while ($current = $current->extends_()) {
			foreach ($current->members($options) as $member) {
				$title = $member->title(['last' => true]);

				$found = $results->first(function($v) use ($title) {
					return $v->title(['last' => true]) === $title;
				});
				if ($found) {
					$found->overrides = $member->name;
				} else {
					$results[$member->id] = clone $member;
					$results[$member->id]->inherited = $current->name;
				}
			}
		}
		return $results;
	}

	public function subclasses($entity) {
		if ($entity->type !== 'class') {
			return new Collection();
		}
		return static::find('all', [
			'conditions' => [
				'index' => $entity->index,
				'type' => 'class',
				'extends' => $entity->name
			],
			'order' => ['name' => 'ASC']
		]);
	}

	public function extends_($entity) {
		if ($entity->type !== 'class' || !$entity->extends) {
			return;
		}
		$symbol = static::find('first', [
			'conditions' => [
				'index' => $entity->index,
				'type' => 'class',
				'name' => $entity->extends
			]
		]);
		if ($symbol) {
			return $symbol;
		}
		return static::create([
			'index' => $entity->index,
			'type' => 'class',
			'name' => $entity->extends,
			'is_external' => true
		]);
	}

	public function implements_($entity) {
		if ($entity->type !== 'class' || !$entity->interfaces) {
			return new Collection();
		}
		$results = [];

		foreach (explode(',', $entity->interfaces) as $symbol) {
			$result = static::find('first', [
				'conditions' => [
					'index' => $entity->index,
					'type' => 'class',
					'name' => $symbol
				]
			]);
			if (!$result) {
				$result = static::create([
					'index' => $entity->index,
					'type' => 'interface',
					'name' => $symbol,
					'is_external' => true
				]);
			}
			$results[] = $result;
		}
		return new Collection(['data' => $results]);
	}

	public function uses_($entity) {
		if ($entity->type !== 'class' || !$entity->traits) {
			return new Collection();
		}
		$results = [];

		foreach (explode(',', $entity->traits) as $symbol) {
			$result = static::find('first', [
				'conditions' => [
					'index' => $entity->index,
					'type' => 'trait',
					'name' => $symbol
				]
			]);
			if (!$result) {
				$result = static::create([
					'index' => $entity->index,
					'type' => 'trait',
					'name' => $symbol,
					'is_external' => true
				]);
			}
			$results[] = $result;
		}
		return new Collection(['data' => $results]);
	}

	public function overrides($entity) {
		if ($entity->overrides) {
			return $entity->overrides;
		}
		if (!in_array($entity->type, ['property', 'method'])) {
			return null;
		}
		foreach ($entity->parent_()->members(['type' => $entity->type]) as $child) {
			if ($child->title(['last' => true]) === $entity->title(['last' => true])) {
				if ($child->overrides) {
					return static::find('first', [
						'conditions' => [
							'index' => $entity->index,
							'name' => $child->overrides
						]
					]);
				}
				return;
			}
		}
	}

	public function isDeprecated($entity) {
		return $entity->is_deprecated;
	}

	public function isStatic($entity) {
		return $entity->is_static;
	}

	public function isAbstract($entity) {
		return $entity->is_abstract;
	}

	public function isExternal($entity) {
		return (boolean) $entity->is_external;
	}

	public function isRoot($entity) {
		if ($entity->type !== 'namespace') {
			return false;
		}
		if (!$entity->parent) {
			return true;
		}
		if ($entity->index()->namespace === $entity->name) {
			return true;
		}
		return false;
	}
}

?>