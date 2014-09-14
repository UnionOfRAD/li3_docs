<?php

$up = array('url' => $this->docs->identifierUrl($meta['identifier']));

?>
<nav class="aside aside-right">
	<?php if ($meta['properties'] || ($meta['methods'] && $meta['methods']->count())) { ?>
		<h2 class="h-gamma">Contents</h2>
		<ul>
		<?php if ($up): ?>
			<li class="nav-up"><?= $this->html->link('../', $up['url'], array('rel' => 'up')) ?>
		<?php endif ?>
		</ul>

		<?php // Object properties ?>
		<?php if ($meta['properties']) { ?>
			<h2 class="h-gamma">Properties</h2>
			<ul class="properties">
				<?php foreach ($meta['properties'] as $property) { ?>
					<?php $url = $this->docs->identifierUrl("{$meta['identifier']}::\${$property['name']}"); ?>
					<li><?=$this->html->link($property['name'], $url); ?></li>
				<?php } ?>
			</ul>
		<?php } ?>

		<?php // Object methods ?>
		<?php if ($meta['methods'] && $meta['methods']->count()) { ?>
			<h2 class="h-gamma">Methods</h2>
			<ul class="methods">
				<?php foreach ($meta['methods'] as $method) { ?>
					<?php $url = $this->docs->identifierUrl("{$meta['identifier']}::{$method->name}()"); ?>
					<li><?php echo $this->html->link($method->name, $url); ?></li>
				<?php } ?>
			</ul>
		<?php } ?>
	<?php } ?>
</nav>

<section id="parent">
	<?php if ($meta['parent']) { ?>
		<?php $parent = $meta['parent']; ?>
		<span class="parent">Extends</span>
		<?=$this->html->link(
			$parent, $this->docs->identifierUrl($parent), array('class' => 'parent')
		); ?>
	<?php } ?>
</section>