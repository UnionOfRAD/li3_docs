<div class="nav">
	<nav>
	<?php if ($object['children']) { ?>
		<h2><?=$t('Package contents', array('scope' => 'li3_docs')); ?></h2>
		<ul class="children">
			<?php foreach ($object['children'] as $class => $type) { ?>
				<?php
					$parts = explode('\\', $class);
					$url = $this->docs->identifierUrl($class);
				?>
				<li class="<?=$type; ?>"><?=$this->html->link(basename(end($parts)), $url); ?></li>
			<?php } ?>
		</ul>
	<?php } ?>
	</nav>
</div>
