<?php

if (!isset($crumbs) || !$crumbs) {
	return;
}

?>
<nav class="crumbs">
	<ul>
	<?php foreach ($crumbs as $crumb): ?>
		<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<?php if ($crumb['url']): ?>
				<?= $this->html->link($crumb['title'], $crumb['url'], [
					'itemprop' => 'url title'
				]) ?>
			<?php else: ?>
				<span itemprop="title">
					<?= $crumb['title'] ?>
				</span>
			<?php endif ?>
		</li>
	<?php endforeach ?>
	</ul>
</nav>