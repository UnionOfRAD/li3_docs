<?php

if (!isset($crumbs) || !$crumbs) {
	return;
}

?>
<nav class="crumbs">
	<ul itemscope itemtype="http://schema.org/BreadcrumbList">
	<?php foreach (array_values($crumbs) as $pos => $crumb): ?>
		<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
			<meta itemprop="position" content="<?= $pos + 1 ?>" />
			<?php if ($crumb['url']): ?>
				<a href="<?= $this->url($crumb['url']) ?>" itemscope itemprop="item" itemtype="http://schema.org/Thing"><span itemprop="name"><?= $crumb['title'] ?></span></a>
			<?php else: ?>
				<span itemscope itemprop="item" itemtype="http://schema.org/Thing">
					<span itemprop="name"><?= $crumb['title'] ?></span>
				</span>
			<?php endif ?>
		</li>
	<?php endforeach ?>
	</ul>
</nav>