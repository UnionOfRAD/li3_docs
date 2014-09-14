<div id="method" class="section">
	<section>
<?php if ($object['description']) { ?>
	<div class="description">
		<?php $this->markdown->parse($this->docs->cleanup($object['description'])); ?>
	</div>
<?php } ?>
	</section>
</div>
<?php if ($object['text']) { ?>
<div class="section">
	<section>
		<div class="text">
			<?php echo $this->markdown->parse($this->docs->cleanup($object['text'])); ?>
		</div>
	</section>
</div>
<?php } ?>

<?php // Method parameters ?>
<?php if (isset($object['tags']['params'])) { ?>
<div id="params" class="section">
	<section>
		<h3 class="h-beta"><Parameters</h3>
		<ul class="parameters">
			<?php foreach ((array) $object['tags']['params'] as $name => $data) { ?>
				<li>
					<span class="type"><?=$data['type']; ?></span>
					<code class="name"><?=$name; ?></code>
					<span class="parameter text">
						<?php echo $this->markdown->parse($this->docs->cleanup($data['text'])); ?>
					</span>
				</li>
			<?php } ?>
		</ul>
	</section>
</div>
<?php } ?>

<?php // Method return value ?>
<?php if (isset($object['return'])) { ?>
<div id="return" class="section">
	<section>
		<h3 class="h-beta">Returns</h3>
		<span class="type"><?=$object['return']['type']; ?></span>
		<span class="return">
			<?php echo $this->markdown->parse($this->docs->cleanup($object['return']['text'])); ?>
		</span>
	</section>
</div>
<?php } ?>

<?php // Method filtering info ?>
<?php if (isset($object['tags']['filter'])) { ?>
<div id="filter" class="section">
	<section>
		<span class="flag">
			This method can be filtered.
		</span>
	</section>
</div>
<?php } ?>