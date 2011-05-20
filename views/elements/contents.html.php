<div class="nav">
	<nav>
	<?php if ($object['children']) { ?>
		<h2><?=$t('Package contents', array('scope' => 'li3_docs')); ?></h2>
		<ul class="children">
			<?php foreach ($object['children'] as $name => $type) { ?>
				<?php
					if (is_array($type)) {
						extract($type, EXTR_OVERWRITE);
					}
					if (!isset($url)) {
						$url = $this->docs->identifierUrl($name);
						$parts = explode('\\', $name);
						$name = basename(end($parts));
					} else {
						$url = $this->docs->pageUrl($url);
					}
				?>
				<li class="<?=$type; ?>"><?=$this->html->link($name, $url); ?></li>
				<?php unset($url); ?>
			<?php } ?>
		</ul>
	<?php } ?>
	</nav>
</div>
