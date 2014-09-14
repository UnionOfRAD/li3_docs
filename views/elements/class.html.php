<?php // Parent object ?>

<nav class="aside aside-right">
	<?php if ($object['properties'] || ($object['methods'] && $object['methods']->count())) { ?>
	<div class="contents">
		<?php // Object properties ?>
		<?php if ($object['properties']) { ?>
			<h2 class="h-gamma">Class Properties</h2>
			<ul class="properties">
				<?php foreach ($object['properties'] as $property) { ?>
					<?php $url = $this->docs->identifierUrl("{$namespace}::\${$property['name']}"); ?>
					<li><?=$this->html->link($property['name'], $url); ?></li>
				<?php } ?>
			</ul>
		<?php } ?>

		<?php // Object methods ?>
		<?php if ($object['methods'] && $object['methods']->count()) { ?>
			<h2 class="h-gamma">Class Methods</h2>
			<ul class="methods">
				<?php foreach ($object['methods'] as $method) { ?>
					<?php $url = $this->docs->identifierUrl("{$namespace}::{$method->name}()"); ?>
					<li><?php echo $this->html->link($method->name, $url); ?></li>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>
	<?php } ?>
</nav>


<div id="parent" class="section">
	<section>
		<?php if ($object['parent']) { ?>
			<?php $parent = $object['parent']; ?>
			<span class="parent">Extends</span>
			<?=$this->html->link(
				$parent, $this->docs->identifierUrl($parent), array('class' => 'parent')
			); ?>
		<?php } ?>
	</section>
</div>
<div class="section">
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
</div>