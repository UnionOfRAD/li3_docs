<?=$this->view()->render(
	array('element' => 'contents'),
	compact('scope', 'object'),
	array('library' => 'li3_docs')
); ?>

<div class="section">
	<section>
<?php if ($object['description']) { ?>
	<p class="description">
		<?php echo $this->markdown->parse($t($this->docs->cleanup($object['description']), compact('scope'))); ?>
	</p>
<?php } ?>

<?php if ($object['text']) { ?>
	<p class="text">
		<?php echo $this->markdown->parse($t($this->docs->cleanup($object['text']), compact('scope'))); ?>
	</p>
<?php } ?>
	</section>
</div>