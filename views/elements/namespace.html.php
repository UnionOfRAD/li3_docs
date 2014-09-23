<?=$this->view()->render(
	array('element' => 'contents'),
	compact('scope', 'object'),
	array('library' => 'li3_docs')
); ?>
	<section>
<?php if ($object['description']) { ?>
	<p class="description">
		<?php echo $this->markdown->parse($object['description']); ?>
	</p>
<?php } ?>

<?php if ($object['text']) { ?>
	<p class="text">
		<?php echo $this->markdown->parse($object['text']); ?>
	</p>
<?php } ?>
	</section>