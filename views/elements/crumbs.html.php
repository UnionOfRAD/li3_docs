<?php

if (!isset($object) || !$object) {
	return;
}

?>
<nav class="crumbs">
	<?= $this->html->link('Documentation', ['library' => 'li3_docs', 'controller' => 'ApiBrowser']) ?> >
	<ul>
		<?php foreach (array_slice($this->docs->crumbs($object), 1) as $crumb): ?>
			<li class="<?= $crumb['class']; ?>">
				<?php
					if ($crumb['url']) {
						echo $this->html->link($crumb['title'], $crumb['url']);
						continue;
					}
				?>
				<span><?=$crumb['title']; ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>