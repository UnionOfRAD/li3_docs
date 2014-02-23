<nav class="aside aside-right">
	<?php if ($meta['properties'] || ($meta['methods'] && $meta['methods']->count())) { ?>
	<div class="contents">
		<?php // Object properties ?>
		<?php if ($meta['properties']) { ?>
			<h2 class="gamma"><?=$t('Class Properties', array('scope' => 'li3_docs')); ?></h2>
			<ul class="properties">
				<?php foreach ($meta['properties'] as $property) { ?>
					<?php $url = $this->docs->identifierUrl("{$meta['identifier']}::\${$property['name']}"); ?>
					<li><?=$this->html->link($property['name'], $url); ?></li>
				<?php } ?>
			</ul>
		<?php } ?>

		<?php // Object methods ?>
		<?php if ($meta['methods'] && $meta['methods']->count()) { ?>
			<h2 class="gamma"><?=$t('Class Methods', array('scope' => 'li3_docs')); ?></h2>
			<ul class="methods">
				<?php foreach ($meta['methods'] as $method) { ?>
					<?php $url = $this->docs->identifierUrl("{$meta['identifier']}::{$method->name}()"); ?>
					<li><?php echo $this->html->link($method->name, $url); ?></li>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>
	<?php } ?>
</nav>

<div id="parent" class="section">
	<section>
		<?php if ($meta['parent']) { ?>
			<?php $parent = $meta['parent']; ?>
			<span class="parent"><?=$t('Extends', array('scope' => 'li3_docs')); ?></span>
			<?=$this->html->link(
				$parent, $this->docs->identifierUrl($parent), array('class' => 'parent')
			); ?>
		<?php } ?>
	</section>
</div>