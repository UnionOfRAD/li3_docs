<?php if (!isset($object) || !$object) {
	return;
} ?>
<ul class="crumbs">
	<?php foreach ($this->docs->crumbs($object) as $crumb): ?>
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
