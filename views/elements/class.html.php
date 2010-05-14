<?php // Parent object ?>
<?php if ($object['parent']) { ?>
	<?php $parent = $object['parent']; ?>
	<h4><?=$t('Parent class', array('scope' => 'li3_docs')); ?></h4>
	<span class="parent">
		<?php echo $this->html->link($parent, $this->docs->identifierUrl($parent)); ?>
	</span>
<?php } ?>

<div class="contents">
	<?php // Object properties ?>
	<?php if ($object['properties']) { ?>
		<h4><?=$t('Properties', array('scope' => 'li3_docs')); ?></h4>
		<ul class="properties">
			<?php foreach ($object['properties'] as $name => $value) { ?>
				<?php $url = $this->docs->identifierUrl("{$namespace}::\${$name}"); ?>
				<li><?=$this->html->link($name, $url); ?></li>
			<?php } ?>
		</ul>
	<?php } ?>

	<?php // Object methods ?>
	<?php if ($object['methods'] && $object['methods']->count()) { ?>
		<h4><?=$t('Methods', array('scope' => 'li3_docs')); ?></h4>
		<ul class="methods">
			<?php foreach ($object['methods'] as $method) { ?>
				<?php $url = $this->docs->identifierUrl("{$namespace}::{$method->name}()"); ?>
				<li><?php echo $this->html->link($method->name, $url); ?></li>
			<?php } ?>
		</ul>
	<?php } ?>
</div>
