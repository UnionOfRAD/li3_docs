<?php

$scope = strtok($object['identifier'], '\\') . '_docs';
$namespace = str_replace('\\', '/', $name);
$this->title($namespace);

?>

<?=$this->view()->render(
	array('element' => $object['type']),
	compact('namespace', 'object', 'scope'),
	array('library' => 'li3_docs')
); ?>

<?php if ($object['description']) { ?>
	<p class="description markdown">
		<?=$t($this->docs->cleanup($object['description']), compact('scope')); ?>
	</p>
<?php } ?>

<?php if ($object['text']) { ?>
	<p class="text markdown">
		<?=$t($this->docs->cleanup($object['text']), compact('scope')); ?>
	</p>
<?php } ?>

<?php // Related items ?>
<?php if (isset($object['tags']['see'])) { ?>
	<h4><?=$t('Related', array('scope' => 'li3_docs')); ?></h4>
	<ul class="related">
		<?php foreach ((array) $object['tags']['see'] as $name) { ?>
			<li><?=$this->html->link($name, $this->docs->identifierUrl($name)); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Object subclasses ?>
<?php if ($object['subClasses']) { ?>
	<h4><?=$t('Subclasses', array('scope' => 'li3_docs')); ?></h4>
	<ul class="subclasses">
		<?php foreach ($object['subClasses'] as $class) { ?>
			<?php $url = $this->docs->identifierUrl($class); ?>
			<li><?php echo $this->html->link($class, $url); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Method source ?>
<?php if (isset($object['source'])) { ?>
	<div class="source-wrapper">
		<pre class="source-code">
			<code class="php"><?php echo $h($object['source']); ?></code>
		</pre>
	</div>
	<button class="source-toggle"><?=$t('Show source', array('scope' => 'li3_docs')); ?></button>
<?php } ?>