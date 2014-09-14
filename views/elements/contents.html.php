<nav class="aside aside-right toc">
<?php if ($object['children']) { ?>
	<h1 class="h-gamma">Contents</h1>
	<ul class="children">
		<?= $this->docs->contents($object['children']); ?>
	</ul>
<?php } ?>
</nav>