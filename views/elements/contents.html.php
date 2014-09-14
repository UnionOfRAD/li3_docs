<nav class="aside aside-right toc">
<?php if ($object['children']) { ?>
	<h2 class="h-gamma">Contents</h2>
	<ul class="children">
		<?= $this->docs->contents($object['children']); ?>
	</ul>
<?php } ?>
</nav>