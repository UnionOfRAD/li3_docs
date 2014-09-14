<?=$this->view()->render(
	array('element' => 'contents'),
	compact('scope', 'object'),
	array('library' => 'li3_docs')
); ?>

<?php foreach ($object['info'] as $info) { ?>
	<?php if (is_string($info)) { ?>
<div class="section">
	<section>
		<?php echo $this->markdown->parse($info); ?>
	</section>
</div>
	<?php } else { ?>
<div class="section">
	<section>
		<?php if ($info['description']): ?>
			<?php echo $this->markdown->parse($info['description']); ?>
		<?php endif ?>
		<?php if ($info['text']): ?>
			<?php echo $this->markdown->parse($info['text']); ?>
		<?php endif ?>
	</section>
</div>
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