<?php

$this->title('Documentation');

?>
<article class="docs-index">
	<h1 class="h-alpha">Documentation</h1>
	<div class="jumpboxes">
	<?php foreach ($data as $name => $indexes): ?>
		<div class="jumpbox jumpbox--<?= $indexes[0]->type ?>">
			<div class="jumpbox__title">
				<?= $indexes[0]->title() ?>
			</div>
			<div class="jumpbox__actions">
				<?php foreach ($indexes as $index): ?>
					<?php if ($index->type === 'book'): ?>
						<?php echo $this->html->link($index->version, [
							'library' => 'li3_docs',
							'controller' => $index->type . 's',
							'action' => 'view',
							'name' => $index->name,
							'version' => $index->version
						], ['class' => 'jumpbox__version']) ?>
					<?php else: ?>
						<?php echo $this->html->link($index->version, [
							'library' => 'li3_docs',
							'controller' => $index->type . 's',
							'action' => 'view',
							'name' => $index->name,
							'version' => $index->version,
							'symbol' => $index->namespace
						], ['class' => 'jumpbox__version']) ?>
					<?php endif ?>
				<?php endforeach ?>
			</div>
			<div class="jumpbox__description"><?= $indexes[0]->description ?></div>
		</div>
	<?php endforeach ?>
		</div>
</article>