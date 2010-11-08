<?php

$scope = strtok($object['identifier'], '\\') . '_docs';
$namespace = str_replace('\\', '/', $name);
$this->title($namespace);

$params = isset($object['tags']['params']) && !empty($object['tags']['params']);
$return = isset($object['return']) && !empty($object['return']);
$filter = isset($object['tags']['filter']) && !empty($object['tags']['filter']);
$source = isset($object['source']) && !empty($object['source']);
$related = $this->view()->render(
	array('element' => 'related'), compact('object', 'scope'), array('library' => 'li3_docs')
);
$links = $this->view()->render(
	array('element' => 'links'),
	compact('namespace', 'object', 'scope'),
	array('library' => 'li3_docs')
);
$subClasses = $object['subClasses'];

$menu = array_filter(compact('params','return','filter','related','links','subClasses','source'));
?>

<div class="menu">
	<menu>
			<?php foreach (array_keys($menu) as $item) {
				echo $this->html->link(ucwords($item), "#{$item}");
			} ?>
	</menu>
</div>

<?php if ($meta) { ?>
	<?=$this->view()->render(
		array('element' => 'meta'),
		compact('namespace','meta'),
		array('library' => 'li3_docs')
	); ?>
<?php } ?>

<?=$this->view()->render(
	array('element' => $object['type']),
	compact('namespace', 'object', 'scope'),
	array('library' => 'li3_docs')
); ?>

<?php echo $related;?>

<?php echo $links;?>

<?php // Object subclasses ?>
<?php if ($object['subClasses']) { ?>
<div id="subClasses" class="section">
	<section>
		<h3><?=$t('Subclasses', array('scope' => 'li3_docs')); ?></h3>
		<ul class="subclasses">
			<?php foreach ($object['subClasses'] as $class) { ?>
				<?php $url = $this->docs->identifierUrl($class); ?>
				<li><?php echo $this->html->link($class, $url); ?></li>
			<?php } ?>
		</ul>
	</section>
</div>
<?php } ?>

<?php // Method source ?>
<?php if (isset($object['source']) && !empty($object['source'])) { ?>
<div id="source" class="section">
	<section>
		<h3>Source</h3>
		<div id="source" class="source-display">
			<div class="source-wrapper">
				<pre class="source-code">
					<code class="php"><?=$object['source']; ?></code>
				</pre>
			</div>
		</div>
	</section>
</div>
<?php } ?>