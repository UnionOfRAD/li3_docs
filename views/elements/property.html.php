<?php if ($object['description']) { ?>
		<section>
			<div class="description">
				<?php echo $this->markdown->parse($this->docs->cleanup($object['description'])); ?>
			</div>
		</section>
<?php } ?>
<?php if ($object['text']) { ?>
		<section>
			<p class="text">
				<?php echo $this->markdown->parse($this->docs->cleanup($object['text'])); ?>
			</p>
		</section>
<?php } ?>