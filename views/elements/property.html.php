<?php if ($object['description']) { ?>
	<div class="section">
		<section>
			<div class="description">
				<?php echo $this->markdown->parse($this->docs->cleanup($object['description'])); ?>
			</div>
		</section>
	</div>
<?php } ?>
<?php if ($object['text']) { ?>
	<div class="section">
		<section>
			<p class="text">
				<?php echo $this->markdown->parse($this->docs->cleanup($object['text'])); ?>
			</p>
		</section>
	</div>
<?php } ?>