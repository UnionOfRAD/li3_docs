<?php

$title = [];
if (!$page->isRoot()) {
	$title[] = $page->title();
}
$title = array_merge($title, [
	$index->title() . ' v' . $index->version,
	'Documentation'
]);
$this->title(implode(' – ', $title));

$drawList = function($root) use ($index, $page, &$drawList) {
	$children = $root->children();

	if (!$children->count()) {
		return;
	}
	echo '<ul>';
	foreach ($children as $child) {
		$isActive = $child->name === $page->name;

		echo '<li>';
		echo $this->html->link($child->title(), [
			'library' => 'li3_docs',
			'action' => 'view',
			'name' => $index->name,
			'version' => $index->version,
			'page' => $child->name
		], [
			'class' => $isActive ? 'active' : null
		]);
		echo $drawList($child);
	}
	echo '</ul>';
};

?>
<article class="books-view has-aside-right">
	<nav class="aside aside-right">
		<h3 class="h-gamma">Contents</h3>
		<?php $drawList($root) ?>
	</nav>
	<div class="body">
		<?php echo $this->markdown->parse($page->content); ?>
	</div>
</article>