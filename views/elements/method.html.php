<section id="method">
<?php if ($object['description']) { ?>
	<section class="description">
		<?php echo $this->markdown->parse($this->docs->cleanup($object['description'])); ?>
	</section>
<?php } ?>
</section>

<?php if ($object['text']) { ?>
<section class="text">
	<?php echo $this->markdown->parse($this->docs->cleanup($object['text'])); ?>
</section>
<?php } ?>

<?php if (array_key_exists('deprecated', $object['tags'])): ?>
<section class="deprecated">
	<?php if ($object['tags']['deprecated']): ?>
		<?= $object['tags']['deprecated'] ?>
	<?php else: ?>
		This method is deprecated.
	<?php endif ?>
</section>
<?php endif ?>

<?php // Method parameters ?>
<?php if (isset($object['tags']['params'])) { ?>
<section id="params">
	<h3 class="h-beta">Parameters</h3>
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
<?php } ?>

<?php // Method return value ?>
<?php if (isset($object['return'])) { ?>
<section id="return">
	<h3 class="h-beta">Returns</h3>
	<span class="type"><?=$object['return']['type']; ?></span>
	<span class="return">
		<?php echo $this->markdown->parse($this->docs->cleanup($object['return']['text'])); ?>
	</span>
</section>
<?php } ?>

<?php // Method filtering info ?>
<?php if (isset($object['tags']['filter'])) { ?>
<section id="filter" class="flag">
	<h3 class="h-beta">
		Filter
		<?= $this->html->link(
			'see how to use filters',
			'http://li3.me/docs/manual/common-tasks/basic-filters.md'
		) ?>
	</h3>
	<p>
		<?= $object['tags']['filter'] ?: 'This method can be filtered.' ?>
	</p>
</section>
<?php } ?>