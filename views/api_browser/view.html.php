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

<?php // Related items ?>
<?=$this->view()->render(
	array('element' => 'related'), compact('object', 'scope'), array('library' => 'li3_docs')
); ?>

<?=$this->view()->render(
	array('element' => 'links'),
	compact('namespace', 'object', 'scope'),
	array('library' => 'li3_docs')
); ?>

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
	<div class="source-display">
		<div class="source-wrapper">
			<pre class="source-code">
				<code class="php"><?=$object['source']; ?></code>
			</pre>
		</div>
		<button class="source-toggle">
			<?=$t('Show source', array('scope' => 'li3_docs')); ?>
		</button>
	</div>
<?php } ?>