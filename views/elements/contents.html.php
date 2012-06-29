<div class="nav">
	<nav>
	<?php if ($object['children']) { ?>
		<h2><?=$t('Contents', array('scope' => 'li3_docs')); ?></h2>
		<ul class="children">
			<?= $this->docs->contents($object['children']); ?>
		</ul>
	<?php } ?>
	</nav>
</div>
