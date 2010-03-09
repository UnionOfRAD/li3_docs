<?php
$cleanup = function($text) {
	return preg_replace('/\n\s+-\s/msi', "\n\n - ", $text);
};
$scope = strtok($object['identifier'], '\\') . '_docs';
$namespace = str_replace('\\', '/', $name);
$this->title($namespace);
?>
<?php if ($object['children']) { ?>
	<h4><?=$t('Package contents', array('scope' => 'li3_docs')); ?></h4>
	<ul class="children">
		<?php foreach ($object['children'] as $class => $type) { ?>
			<?php
				$parts = explode('\\', $class);
				$url = 'docs/' . str_replace('\\', '/', $class);
			?>
			<li class="<?=$type; ?>"><?php echo $this->html->link(end($parts), $url); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Parent object ?>
<?php if ($object['parent']) { ?>
	<?php $parent = $object['parent']; ?>
	<h4><?=$t('Parent class', array('scope' => 'li3_docs')); ?></h4>
	<span class="parent">
		<?php echo $this->html->link($parent, 'docs/' . str_replace('\\', '/', $parent)); ?>
	</span>
<?php } ?>

<?php if ($object['info']['description']) { ?>
	<h4><?=$t('Description', array('scope' => 'li3_docs')); ?></h4>
	<p class="description markdown">
		<?=$t($cleanup($object['info']['description']), compact('scope')); ?>
	</p>

	<?php if (!empty($object['info']['text'])) { ?>
		<p class="text markdown">
			<?=$t($cleanup($object['info']['text']), compact('scope')); ?>
		</p>
	<?php } ?>
<?php } ?>

<?php // Method parameters ?>
<?php if (isset($object['info']['tags']['params'])) { ?>
	<h4><?=$t('Parameters', array('scope' => 'li3_docs')); ?></h4>
	<ul class="parameters">
		<?php foreach ($object['info']['tags']['params'] as $name => $data) { ?>
			<li>
				<span class="type"><?=$data['type']; ?></span>
				<?=$name; ?>
				<span class="description markdown">
					<?=$t($cleanup($data['text']), compact('scope')); ?>
				</span>
			</li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Method return value ?>
<?php if (isset($object['info']['return'])) { ?>
	<h4><?=$t('Returns', array('scope' => 'li3_docs')); ?></h4>
	<span class="type"><?=$object['info']['return']['type']; ?></span>
	<span class="return markdown">
		<?=$t($cleanup($object['info']['return']['text']), compact('scope')); ?>
	</span>
<?php } ?>

<?php // Method filtering info ?>
<?php if (isset($object['info']['tags']['filter'])) { ?>
	<span class="flag markdown">
		<?=$t('This method can be filtered.', array('scope' => 'li3_docs')); ?>
	</span>
<?php } ?>

<?php // Related items ?>
<?php if (isset($object['info']['tags']['see'])) { ?>
	<h4><?=$t('Related', array('scope' => 'li3_docs')); ?></h4>
	<ul class="related">
		<?php foreach ((array)$object['info']['tags']['see'] as $name) { ?>
			<li><?php echo$this->html->link($name, 'docs/' . str_replace('\\', '/', $name)); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Object properties ?>
<?php if ($object['properties']) { ?>
	<h4><?=$t('Properties', array('scope' => 'li3_docs')); ?></h4>
	<ul class="properties">
		<?php foreach ($object['properties'] as $name => $value) { ?>
			<li><?php echo $this->html->link($name, "docs/{$namespace}::\${$name}"); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Object methods ?>
<?php if ($object['methods'] && $object['methods']->count()) { ?>
	<h4><?=$t('Methods', array('scope' => 'li3_docs')); ?></h4>
	<ul class="methods">
		<?php foreach ($object['methods'] as $method) { ?>
			<?php $url = "docs/{$namespace}::{$method->name}()"; ?>
			<li><?php echo $this->html->link($method->name, $url); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Object subclasses ?>
<?php if ($object['subClasses']) { ?>
	<h4><?=$t('Subclasses', array('scope' => 'li3_docs')); ?></h4>
	<ul class="subclasses">
		<?php foreach ($object['subClasses'] as $class) { ?>
			<?php $url = 'docs/' . str_replace('\\', '/', $class); ?>
			<li><?php echo $this->html->link($class, $url); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Method source ?>
<?php if (isset($object['source'])) { ?>
	<pre class="source-code">
		<code class="php"><?php echo $object['source']; ?></code>
	</pre>
	<button class="source-toggle">Show source</button>
<?php } ?>
