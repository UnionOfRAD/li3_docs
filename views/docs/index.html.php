<?php

$this->title('Documentation');

?>
<article class="docs-index">
	<h1 class="h-alpha">Documentation</h1>
	<?php foreach ($data as $indexes): ?>
		<div class="jumpboxes">
		<?php foreach ($indexes as $index): ?>
		<?php if ($index->type === 'book'): ?>
			<?php echo $this->html->link('<span class="jumpbox__title">' . $index->title() . '</span> <span class="jumpbox__version">' . $index->version . '</span>', [
				'library' => 'li3_docs',
				'controller' => $index->type . 's',
				'action' => 'view',
				'name' => $index->name,
				'version' => $index->version
			], ['escape' => false, 'class' => 'jumpbox']) ?>
		<?php else: ?>
			<?php echo $this->html->link('<span class="jumpbox__title">' . $index->title() . '</span> <span class="jumpbox__version">' . $index->version . '</span>', [
				'library' => 'li3_docs',
				'controller' => $index->type . 's',
				'action' => 'view',
				'name' => $index->name,
				'version' => $index->version,
				'symbol' => $index->namespace
			], ['escape' => false, 'class' => 'jumpbox']) ?>
		<?php endif ?>
		<?php endforeach ?>
		</div>
	<?php endforeach ?>
</article>