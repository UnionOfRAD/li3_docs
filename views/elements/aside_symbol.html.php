<nav class="aside aside-right">
	<?php if ($symbol->parent): ?>
		<h3 class="h-gamma">Contents</h3>
		<ul>
			<li class="nav-up"><?= $this->html->link('../', [
				'library' => 'li3_docs',
				'action' => 'view',
				'name' => $index->name,
				'version' => $index->version,
				'symbol' => $symbol->parent
			], ['rel' => 'up']) ?>
		</ul>
	<?php endif ?>

	<?php if (($children = $symbol->namespaces()) && $children->count()): ?>
		<h3 class="h-gamma">Namespaces</h3>
		<ul class="classes">
			<?php foreach ($children as $child): ?>
				<?php
					$classes = ['namespace'];
				?>
				<li class="<?= implode(' ', $classes) ?>">
				<?= $this->html->link($child->title(['namespace' => $symbol->name]), [
					'library' => 'li3_docs',
					'action' => 'view',
					'name' => $index->name,
					'version' => $index->version,
					'symbol' => $child->name
				]) ?>
			<?php endforeach ?>
		</ul>
	<?php endif ?>

	<?php if (($children = $symbol->classes()) && $children->count()): ?>
		<h3 class="h-gamma">Classes</h3>
		<ul class="classes">
			<?php foreach ($children as $child): ?>
				<?php
					$classes = ['class'];

					if ($child->isDeprecated()) {
						$classes[] = 'deprecated';
					}
				?>
				<li class="<?= implode(' ', $classes) ?>">
				<?= $this->html->link($child->title(['namespace' => $symbol->name]), [
					'library' => 'li3_docs',
					'action' => 'view',
					'name' => $index->name,
					'version' => $index->version,
					'symbol' => $child->name
				]) ?>
			<?php endforeach ?>
		</ul>
	<?php endif ?>

	<?php if (($children = $symbol->traits()) && $children->count()): ?>
		<h3 class="h-gamma">Traits</h3>
		<ul class="classes">
			<?php foreach ($children as $child): ?>
				<?php
					$classes = ['trait'];

					if ($child->isDeprecated()) {
						$classes[] = 'deprecated';
					}
				?>
				<li class="<?= implode(' ', $classes) ?>">
				<?= $this->html->link($child->title(['namespace' => $symbol->name]), [
					'library' => 'li3_docs',
					'action' => 'view',
					'name' => $index->name,
					'version' => $index->version,
					'symbol' => $child->name
				]) ?>
			<?php endforeach ?>
		</ul>
	<?php endif ?>

	<?php if (($children = $symbol->interfaces()) && $children->count()): ?>
		<h3 class="h-gamma">Interfaces</h3>
		<ul class="classes">
			<?php foreach ($children as $child): ?>
				<?php
					$classes = ['interface'];

					if ($child->isDeprecated()) {
						$classes[] = 'deprecated';
					}
				?>
				<li class="<?= implode(' ', $classes) ?>">
				<?= $this->html->link($child->title(['namespace' => $symbol->name]), [
					'library' => 'li3_docs',
					'action' => 'view',
					'name' => $index->name,
					'version' => $index->version,
					'symbol' => $child->name
				]) ?>
			<?php endforeach ?>
		</ul>
	<?php endif ?>

	<?php if (($children = $symbol->methods()) && $children->count()): ?>
		<h3 class="h-gamma">Methods</h3>
		<ul class="methods">
			<?php foreach ($children as $child): ?>
				<?php
					$classes = ['method', $child->visibility];

					if ($child->inherited) {
						$classes[] = 'inherited';
					}
					if ($child->isDeprecated()) {
						$classes[] = 'deprecated';
					}
				?>
				<li class="<?= implode(' ', $classes) ?>">
				<?= $this->html->link($child->title(['last' => true]), [
					'library' => 'li3_docs',
					'action' => 'view',
					'name' => $index->name,
					'version' => $index->version,
					'symbol' => $child->name
				]) ?>
			<?php endforeach ?>
		</ul>
	<?php endif ?>

	<?php if (($children = $symbol->properties()) && $children->count()): ?>
		<h3 class="h-gamma">Properties</h3>
		<ul class="methods">
			<?php foreach ($children as $child): ?>
				<?php
					$classes = ['property', $child->visibility];

					if ($child->inherited) {
						$classes[] = 'inherited';
					}
					if ($child->isDeprecated()) {
						$classes[] = 'deprecated';
					}
				?>
				<li class="<?= implode(' ', $classes) ?>">
				<?= $this->html->link($child->title(['last' => true]), [
					'library' => 'li3_docs',
					'action' => 'view',
					'name' => $index->name,
					'version' => $index->version,
					'symbol' => $child->name
				]) ?>
			<?php endforeach ?>
		</ul>
	<?php endif ?>

	<?php if (($children = $symbol->constants()) && $children->count()): ?>
		<h3 class="h-gamma">Constants</h3>
		<ul>
			<?php foreach ($children as $child): ?>
				<?php
					$classes = ['constant'];

					if ($child->inherited) {
						$classes[] = 'inherited';
					}
					if ($child->isDeprecated()) {
						$classes[] = 'deprecated';
					}
				?>
				<li class="<?= implode(' ', $classes) ?>">
				<?= $this->html->link($child->title(['last' => true]), [
					'library' => 'li3_docs',
					'action' => 'view',
					'name' => $index->name,
					'version' => $index->version,
					'symbol' => $child->name
				]) ?>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
</nav>