<span class="type"><?=$object['type']; ?></span>

<h3>
<?php
	$path = array_filter(array_merge(
		array($object['name']), explode('\\', $object['identifier'])
	));
	$url = '';

	foreach (array_slice($path, 0, -1) as $part) {
		$url .= '/' . $part;
		echo @$this->html->link($part, 'docs' . $url) . ' \ ';
	}
	echo end($path);
	$curPath = str_replace('\\', '/', $name);
?>
</h3>

<?php if ($object['children']) { ?>
	<h4>Package contents</h4>
	<ul class="children">
		<?php foreach ($object['children'] as $class) { ?>
			<?php $parts = explode('\\', $class); ?>
			<li><?=@$this->html->link(end($parts), 'docs/' . str_replace('\\', '/', $class)); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php if ($object['parent']) { ?>
	<?php $parent = $object['parent']; ?>
	<h4>Parent class</h4>
	<span class="parent">
		<?=@$this->html->link($parent, 'docs/' . str_replace('\\', '/', $parent)); ?>
	</span>
<?php } ?>

<?php if ($object['subClasses']) { ?>
	<h4>Subclasses</h4>
	<ul class="subclasses">
		<?php foreach ($object['subClasses'] as $class) { ?>
			<?php $url = 'docs/' . str_replace('\\', '/', $class); ?>
			<li><?=@$this->html->link($class, $url); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php if ($object['info']['description']) { ?>
	<h4>Description</h4>
	<p class="description wiki-text"><?=$object['info']['description']; ?></p>

	<?php if (!empty($object['info']['text'])) { ?>
		<p class="text wiki-text"><?=$object['info']['text']; ?></p>
	<?php } ?>
<?php } ?>

<?php if ($object['methods']) { ?>
	<h4>Methods</h4>
	<ul class="methods">
		<?php foreach ($object['methods'] as $method) { ?>
			<li><?=@$this->html->link($method->name, "docs/{$curPath}::{$method->name}()"); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php if (isset($object['info']['tags']['params'])) { ?>
	<h4>Parameters</h4>
	<ul class="parameters">
		<?php foreach ($object['info']['tags']['params'] as $name => $data) { ?>
			<li>
				<span class="type"><?=$data['type']; ?></span>
				<?=$name; ?>
				<span class="description wiki-text"><?=$data['text']; ?></span>
			</li>
		<?php } ?>
	</ul>
<?php } ?>

<?php if (isset($object['info']['tags']['return'])) { ?>
	<h4>Returns</h4>
	<span class="return wiki-text">
		<?=$object['info']['tags']['return']; ?>
	</span>
<?php } ?>
