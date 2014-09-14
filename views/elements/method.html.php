<div id="method" class="section">
	<section>
<?php if ($object['description']) { ?>
	<div class="description">
		<?php $this->markdown->parse($t($this->docs->cleanup($object['description']), compact('scope'))); ?>
	</div>
<?php } ?>
	</section>
</div>
<?php if ($object['text']) { ?>
<div class="section">
	<section>
		<div class="text">
			<?php echo $this->markdown->parse($t($this->docs->cleanup($object['text']), compact('scope'))); ?>
		</div>
	</section>
</div>
<?php } ?>

<?php // Method parameters ?>
<?php if (isset($object['tags']['params'])) { ?>
<div id="params" class="section">
	<section>
		<h3 class="h-beta"><?=$t('Parameters', array('scope' => 'li3_docs')); ?></h3>
		<ul class="parameters">
			<?php foreach ((array) $object['tags']['params'] as $name => $data) { ?>
				<li>
					<span class="type"><?=$data['type']; ?></span>
					<code class="name"><?=$name; ?></code>
					<span class="parameter text">
						<?php echo $this->markdown->parse($t($this->docs->cleanup($data['text']), compact('scope'))); ?>
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
		<h3 class="h-beta"><?=$t('Returns', array('scope' => 'li3_docs')); ?></h3>
		<span class="type"><?=$object['return']['type']; ?></span>
		<span class="return">
			<?php echo $this->markdown->parse($t($this->docs->cleanup($object['return']['text']), compact('scope'))); ?>
		</span>
	</section>
</div>
<?php } ?>

<?php // Method filtering info ?>
<?php if (isset($object['tags']['filter'])) { ?>
<div id="filter" class="section">
	<section>
		<span class="flag">
			<?php echo $this->markdown->parse($t('This method can be filtered.', array('scope' => 'li3_docs'))); ?>
		</span>
	</section>
</div>
<?php } ?>