<?php // Parent object ?>
<?php if ($object['parent']) { ?>
	<?php $parent = $object['parent']; ?>
	<h4><?=$t('Parent class', array('scope' => 'li3_docs')); ?></h4>
	<div class="parent">
		<?=$this->html->link($parent, $this->docs->identifierUrl($parent)); ?>
	</div>
<?php } ?>

<?php if ($object['description']) { ?>
	<p class="description markdown">
		<?=$t($this->docs->cleanup($object['description']), compact('scope')); ?>
	</p>
<?php } ?>

<?php if ($object['properties'] || ($object['methods'] && $object['methods']->count())) { ?>
<div class="contents">
	<?php // Object properties ?>
	<?php if ($object['properties']) { ?>
		<h4><?=$t('Properties', array('scope' => 'li3_docs')); ?></h4>
		<ul class="properties">
			<?php foreach ($object['properties'] as $property) { ?>
				<?php $url = $this->docs->identifierUrl("{$namespace}::\${$property['name']}"); ?>
				<li><?=$this->html->link($property['name'], $url); ?></li>
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
<?php } ?>

<?php if ($object['text']) { ?>
	<p class="text markdown">
		<?=$t($this->docs->cleanup($object['text']), compact('scope')); ?>
	</p>
<?php } ?>
