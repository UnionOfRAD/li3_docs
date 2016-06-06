<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_docs\doc;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt;
use PhpParser\Comment;

class Visitor extends NodeVisitorAbstract {

	protected $_config = [];

	protected $_ns = null;

	protected $_object = null;

	public function __construct(array $config = []) {
		$this->_config = $config + [
			'collectSymbol' => function($name, array $data) {}
		];
	}

	public function enterNode(Node $node) {
		$collect = $this->_config['collectSymbol'];

		if ($node instanceof Stmt\Namespace_) {
			$this->_ns = (string) $node->name;
			$collect((string) $node->name, [
				'type' => 'namespace',
				'parent' =>	implode('\\', array_slice($node->name->parts, 0, -1))
			]);
		} elseif ($node instanceof Stmt\Class_) {
			$this->_object = (string) $node->name;
			$collect($this->_ns . '\\' . (string) $node->name, [
				'type' => 'class',
				'parent' => $this->_ns,
				'extends' => (string) $node->extends,
				'startLine' => $node->getAttribute('startLine'),
				'endLine' => $node->getAttribute('endLine'),
				'docblock' => $this->_docblock($node),
				'isAbstract' => $node->isAbstract(),
				'traits' => $this->_traits($node),
				'interfaces' => $this->_interfaces($node),
			]);
		} elseif ($node instanceof Stmt\Interface_) {
			$this->_object = (string) $node->name;
			$collect($this->_ns . '\\' . (string) $node->name, [
				'type' => 'trait',
				'parent' => $this->_ns,
				'extends' => (string) $node->extends,
				'startLine' => $node->getAttribute('startLine'),
				'endLine' => $node->getAttribute('endLine'),
				'docblock' => $this->_docblock($node),
			]);
		} elseif ($node instanceof Stmt\Trait_) {
			$this->_object = (string) $node->name;
			$collect($this->_ns . '\\' . (string) $node->name, [
				'type' => 'trait',
				'parent' => $this->_ns,
				'startLine' => $node->getAttribute('startLine'),
				'endLine' => $node->getAttribute('endLine'),
				'docblock' => $this->_docblock($node),
			]);
		} elseif ($node instanceof Stmt\ClassMethod) {
			$collect($this->_ns . '\\' . $this->_object . '::' . (string) $node->name . '()', [
				'type' => 'method',
				'parent' => $this->_ns . '\\' . $this->_object,
				'startLine' => $node->getAttribute('startLine'),
				'endLine' => $node->getAttribute('endLine'),
				'docblock' => $this->_docblock($node),
				'visibility' => $this->_visibility($node),
				'isStatic' => $node->isStatic(),
				'isAbstract' => $node->isAbstract(),
			]);
		} elseif ($node instanceof Stmt\Property) {
			$collect($this->_ns . '\\' . $this->_object . '::$' . (string) $node->props[0]->name, [
				'type' => 'property',
				'parent' => $this->_ns . '\\' . $this->_object,
				'startLine' => $node->getAttribute('startLine'),
				'endLine' => $node->getAttribute('endLine'),
				'docblock' => $this->_docblock($node),
				'visibility' => $this->_visibility($node),
				'isStatic' => $node->isStatic(),
			]);
		} elseif ($node instanceof Stmt\Const_) {
			// TODO currently treats constants defined in namespaces only
			//      as having global scope. Check first param of define.
			$collect((string) $node->name, [
				'type' => 'constant',
				'startLine' => $node->getAttribute('startLine'),
				'endLine' => $node->getAttribute('endLine'),
				'docblock' => $this->_docblock($node)
			]);
		} elseif ($node instanceof Stmt\ClassConst) {
			$collect($this->_ns . '\\' . $this->_object . '::' . $node->consts[0]->name, [
				'type' => 'constant',
				'parent' => $this->_ns . '\\' . $this->_object,
				'startLine' => $node->getAttribute('startLine'),
				'endLine' => $node->getAttribute('endLine'),
				'docblock' => $this->_docblock($node)
			]);
		} elseif (!$this->_object && $node->hasAttribute('comments')) {
			// TODO do something with class-less files but that have docblocks
		}
	}


	protected function _interfaces(Node $node) {
		$results = [];

		foreach ($node->implements as $stmt) {
			$results[] = (string) $stmt;
		}
		return $results;
	}

	protected function _traits(Node $node) {
		$results = [];

		foreach ($node->stmts as $stmt) {
			if ($stmt instanceof Stmt\TraitUse) {
				$results[] = (string) $stmt->traits[0];
			}
		}
		return $results;
	}

	protected function _visibility(Node $node) {
		if ($node->isPublic()) {
			return 'public';
		}
		if ($node->isProtected()) {
			return 'protected';
		}
		if ($node->isPrivate()) {
			return 'private';
		}
		if ($node->isFinal()) {
			return 'final';
		}
	}

	protected function _docblock(Node $node) {
		foreach ($node->getAttribute('comments', []) as $c) {
			if ($c instanceof Comment\Doc) {
				return $c;
			}
		}
		return null;
	}

	public function leaveNode(Node $node) {
		if ($node instanceof Stmt\Namespace_) {
			$this->_ns = null;
		} elseif ($node instanceof Stmt\Class_) {
			$this->_object = null;
		}
	}
}

?>