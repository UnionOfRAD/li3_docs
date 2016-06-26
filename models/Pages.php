<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\models;

use Exception;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Yaml\Yaml;
use li3_docs\models\Indexes;
use lithium\analysis\Logger;
use lithium\util\Collection;
use lithium\util\Inflector;

class Pages extends \lithium\data\Model {

	protected $_meta = [
		'source' => 'docs_pages'
	];

	public static function harvest($index) {
		$results = [];

		foreach (static::_objects($index->path) as $object) {
			try {
				list($info, $content) = static::_parse($object);
			} catch (Exception $e) {
				Logger::debug("Failed to parse object `{$object}`; skipping");
				continue;
			}

			$file = str_replace($index->path . '/', '', $object);
			$dirname = dirname($file);
			$basename = basename($file);

			if ($basename === 'README.md') {
				if ($dirname === '.') { // root readme
					$name = '.';
					$parent	= null;
				} else { // nested readme
					$name = $dirname;
					$parent = dirname($dirname);
				}
			} elseif ($dirname === '.') { // .md docs in the root
				$name = $basename;
				$parent = $dirname;
			} else { // nested docs
				$name = $dirname . '/' . $basename;
				$parent = $dirname;
			}

			$item = static::create([
				'name' => static::_name($index, $name),
				'parent' => static::_name($index, $parent),
				'file' => $file,
				'info' => $info ? json_encode($info) : null,
				'content' => $content,
				'index' => $index->id
			]);
			if (!$item->save()) {
				return false;
			}
		}
		return true;
	}

	protected static function _name($index, $name) {
		if (!$name) {
			return null;
		}
		$name = str_replace('.md', '', $name);
		$name = explode('/', $name);

		if ($index->type !== 'api') {
			foreach ($name as &$n) {
				$n = str_replace('_', '-', $n);
				if (preg_match('/^[0-9]+-(.*)$/', $n, $matches)) {
					$n = $matches[1];
				}
			}
		}
		if ($index->namespace) {
			if ($name === ['.']) {
				$name = [$index->namespace];
			} else {
				array_unshift($name, $index->namespace);
			}
		}
		return implode('/', $name);
	}

	// Splits and parses YAML frontmatter from file.
	// Does not parse file body.
	protected static function _parse($file) {
		$header = '';
		$content = '';

		if (!$stream = fopen($file, 'r')) {
			throw new Exception('Failed to open file.');
		}

		$isHeader = false;

		while (!feof($stream)) {
			$line = rtrim(fgets($stream));

			if ($line === '---') {
				$isHeader = !$isHeader;
				continue;
			}
			if ($isHeader) {
				$header .= $line . "\n";
			} else {
				$content .= $line . "\n";
			}
		}
		if ($header) {
			$info = Yaml::parse($header);
		} else {
			$info = [];
		}

		fclose($stream);
		return [$info, $content];
	}

	public static function _objects($path) {
		$files = new RecursiveCallbackFilterIterator(
			new RecursiveDirectoryIterator($path),
			function($current, $key, $iterator) {
				$noDescend = [
					'.git',
					'libraries',
					'vendor',
					'config',
					'docs',
					'examples'
				];
				$noIndex = [
					'TODO.md',
					'LICENSE.txt',
					'CONTRIBUTING.md',
					'CHANGELOG.md'
				];
				if ($iterator->hasChildren()) {
					if ($current->isDir() && in_array($current->getBasename(), $noDescend)) {
						return false;
					}
					return true;
				}
				return !in_array($current->getBasename(), $noIndex) && $current->getExtension() === 'md';
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

	public function info($entity) {
		return $entity->info ? json_decode($entity->info, true) : [];
	}

	public function title($entity) {
		$info = $entity->info();

		if (isset($info['title'])) {
			return $info['title'];
		}
		$name = $entity->name;
		$name = basename($name);
		$name = str_replace('-', '_', $name);

		// Obvious abbreviation i.e. AOP
		if (strlen($name) <= 3) {
			return strtoupper($name);
		}
		return Inflector::humanize($name);
	}

	public function hasChildren($entity) {
		return (boolean) Pages::find('count', [
			'conditions' => [
				'index' => $entity->index,
				'parent' => $entity->name
			]
		]);
	}

	public function children($entity) {
		return Pages::find('all', [
			'conditions' => [
				'index' => $entity->index,
				'parent' => $entity->name
			]
		]);
	}

	public function parents($entity) {
		$results = [];
		$current = $entity;

		while ($current && !$current->isRoot()) {
			$results[] = $current = Pages::find('first', [
				'conditions' => [
					'index' => $current->index,
					'name' => $current->parent
				]
			]);
		}
		return new Collection(['data' => $results]);
	}

	public function isRoot($entity) {
		return $entity->name === '.';
	}
}

?>