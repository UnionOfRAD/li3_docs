<?php

$scope = strtok($object['identifier'], '\\') . '_docs';
$namespace = str_replace('\\', '/', $name);

$title = $namespace;
if ($object['text'] && preg_match('/^# ([\w\s\:]+)$/m', $object['text'], $matches)) {
	$title = $matches[1];
} elseif (strpos($name, '.md') === false && strpos($name, '/') === false) {
	$title = $name;
}
$this->title($title . ' – ' . $object['library'] . ' – ' . 'docs');

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

$trimSource = function($value) {
	$lines = explode("\n", $value);

	while (true) {
		foreach ($lines as $line) {
			if (!preg_match('/^\t/', $line) && $line !== '') {
				return implode("\n", $lines);
			}
		}
		foreach ($lines as &$line) {
			$line = preg_replace('/^\t/', '', $line);
		}
		unset($line);
	}
};
if ($source) {
	$object['source'] = str_replace("\t", '    ', $trimSource($object['source']));
}
?>

<article class="view-<?= $this->_config['controller'] . '-' . $this->_config['template'] ?> has-aside-right">
	<?php if ($title && $object['type'] !== 'namespace'): ?>
		<h1 class="h-alpha"><?= $name ?></h1>
	<?php endif ?>
	<?php if ($meta) { ?>
		<?=$this->view()->render(
			array('element' => 'meta'),
			compact('namespace','meta'),
			array('library' => 'li3_docs')
		); ?>
	<?php } ?>

	<?=$this->view()->render(
		array('element' => $object['type']),
		compact('namespace', 'object', 'scope', 'library'),
		array('library' => 'li3_docs')
	); ?>

	<?php echo $related;?>

	<?php echo $links;?>

	<?php // Object subclasses ?>
	<?php if ($object['subClasses']) { ?>
	<div id="subClasses" class="section">
		<section>
			<h3 class="h-beta">Subclasses</h3>
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
			<h3 class="h-beta">Source</h3>
			<div id="source" class="source-display">
				<div class="source-wrapper">
					<pre class="source-code"><code class="php"><?=$object['source']; ?></code></pre>
				</div>
			</div>
		</section>
	</div>
	<?php } ?>

</article>