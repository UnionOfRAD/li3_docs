<?php

use lithium\util\Inflector;

if (!isset($object) || !$object) {
	return;
}
$makeTitle = function($value) {
	if (strpos($value, '.md') === false) {
		return $value;
	}
	$value = str_replace('.md', '', $value);
	$value = str_replace('-', '_', $value);

	if (strlen($value) <= 3) {
		return strtoupper($value);
	}
	return Inflector::humanize($value);
}

?>
<nav class="crumbs">
	<?= $this->html->link('Documentation', ['library' => 'li3_docs', 'controller' => 'ApiBrowser']) ?>
	>
	<ul>
		<?php foreach (array_slice($this->docs->crumbs($object), 1) as $crumb): ?>
			<li
				class="<?= $crumb['class']; ?>"
				itemscope itemtype="http://data-vocabulary.org/Breadcrumb"
			>
				<?php
					if ($crumb['url']) {
						echo $this->html->link($makeTitle($crumb['title']), $crumb['url'], array(
							'itemprop' => 'url title'
						));
						continue;
					}
				?>
				<span itemprop="title">
					<?= $makeTitle($crumb['title']); ?>
				</span>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>