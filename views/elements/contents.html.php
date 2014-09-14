<?php

$crumbs = $this->docs->crumbs($object);

if (count($crumbs) > 2) {
	array_pop($crumbs);
	$up = array_pop($crumbs);
} else {
	$up = false;
}

?>
<nav class="aside aside-right toc">
	<h2 class="h-gamma">Contents</h2>
	<ul class="children">
	<?php if ($up): ?>
		<li class="nav-up"><?= $this->html->link('../', $up['url'], array('rel' => 'up')) ?>
	<?php endif ?>
	<?php if ($object['children']): ?>
		<?= $this->docs->contents($object['children']); ?>
	<?php endif ?>
	</ul>
</nav>