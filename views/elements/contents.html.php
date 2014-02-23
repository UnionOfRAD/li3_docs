<nav class="aside aside-right toc">
<?php if ($object['children']) { ?>
	<h1 class="gamma"><?=$t('Contents', array('scope' => 'li3_docs')); ?></h1>
	<ul class="children">
		<?= $this->docs->contents($object['children']); ?>
	</ul>
<?php } ?>
</nav>