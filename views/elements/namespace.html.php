<?=$this->view()->render(
	array('element' => 'contents'),
	compact('scope', 'object'),
	array('library' => 'li3_docs')
); ?>
	<section>
<?php if ($object['description']) { ?>
	<p class="description">
		<?php echo $this->markdown->parse($this->docs->cleanup($object['description'])); ?>
	</p>
<?php } ?>

<?php if ($object['text']) { ?>
	<p class="text">
		<?php echo $this->markdown->parse($this->docs->cleanup($object['text'])); ?>
	</p>
<?php } ?>
	</section>