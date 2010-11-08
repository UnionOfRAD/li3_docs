<?php if ($object['children']) { ?>
	<div class="contents">
		<nav>
		<h4><?=$t('Package contents', array('scope' => 'li3_docs')); ?></h4>
		<ul class="children">
			<?php foreach ($object['children'] as $class => $type) { ?>
				<?php
					$parts = explode('\\', $class);
					$url = $this->docs->identifierUrl($class);
				?>
				<li class="<?=$type; ?>">
					<?=$this->html->link(basename(end($parts)), $url); ?>
				</li>
			<?php } ?>
		</ul>
		</nav>
	</div>
<?php } ?>

<section>
<?php foreach ($object['info'] as $info) { ?>
	<?php if (is_string($info)) { ?>
		<p class="markdown">
			<?=$info; ?>
		</p>
	<?php } else { ?>
		<p class="markdown"><?=$info['description']; ?></p>
		<p class="markdown"><?=$info['text']; ?></p>
		<?php if (isset($info['tags']['see'])) { ?>
			<?=$this->view()->render(
				array('element' => 'related'),
				compact('scope') + array('object' => $info),
				array('library' => 'li3_docs')
			); ?>
		<?php } ?>
		<?php if (isset($info['tags']['link'])) { ?>
			<?=$this->view()->render(
				array('element' => 'links'),
				compact('scope') + array('object' => $info),
				array('library' => 'li3_docs')
			); ?>
		<?php } ?>
	<?php } ?>
<?php } ?>
</section>