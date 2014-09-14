<?php // Parent object ?>
<?php

$crumbs = $this->docs->crumbs($object);

if (count($crumbs) > 2) {
	array_pop($crumbs);
	$up = array_pop($crumbs);
} else {
	$up = false;
}

?>
<nav class="aside aside-right">
	<?php if ($object['properties'] || ($object['methods'] && $object['methods']->count())) { ?>
		<h2 class="h-gamma">Contents</h2>
		<ul>
		<?php if ($up): ?>
			<li class="nav-up"><?= $this->html->link('../', $up['url'], array('rel' => 'up')) ?>
		<?php endif ?>
		</ul>
		<?php // Object properties ?>
		<?php if ($object['properties']) { ?>
			<h2 class="h-gamma">Properties</h2>
			<ul class="properties">
				<?php foreach ($object['properties'] as $property) { ?>
					<?php $url = $this->docs->identifierUrl("{$namespace}::\${$property['name']}"); ?>
					<li><?=$this->html->link($property['name'], $url); ?></li>
				<?php } ?>
			</ul>
		<?php } ?>

		<?php // Object methods ?>
		<?php if ($object['methods'] && $object['methods']->count()) { ?>
			<h2 class="h-gamma">Methods</h2>
			<ul class="methods">
				<?php foreach ($object['methods'] as $method) { ?>
					<?php $url = $this->docs->identifierUrl("{$namespace}::{$method->name}()"); ?>
					<li><?php echo $this->html->link($method->name, $url); ?></li>
				<?php } ?>
			</ul>
		<?php } ?>
	<?php } ?>
</nav>

<section id="parent">
	<?php if ($object['parent']) { ?>
		<?php $parent = $object['parent']; ?>
		<span class="parent">Extends</span>
		<?=$this->html->link(
			$parent, $this->docs->identifierUrl($parent), array('class' => 'parent')
		); ?>
	<?php } ?>
</section>
<section>
	<?php if ($object['description']) { ?>
		<div class="description">
			<?php echo $this->markdown->parse($this->docs->cleanup($object['description'])); ?>
		</div>
	<?php } ?>
	<?php if ($object['text']) { ?>
		<div class="text">
			<?php echo $this->markdown->parse($this->docs->cleanup($object['text'])); ?>
		</div>
	<?php } ?>
</section>