<?php if ($object['description']) { ?>
	<p class="description markdown">
		<?=$t($this->docs->cleanup($object['description']), compact('scope')); ?>
	</p>
<?php } ?>

<?php if ($object['text']) { ?>
	<p class="text markdown">
		<?=$t($this->docs->cleanup($object['text']), compact('scope')); ?>
	</p>
<?php } ?>

<?php // Method parameters ?>
<?php if (isset($object['tags']['params'])) { ?>
	<h4><?=$t('Parameters', array('scope' => 'li3_docs')); ?></h4>
	<ul class="parameters">
		<?php foreach ((array) $object['tags']['params'] as $name => $data) { ?>
			<li>
				<span class="type"><?=$data['type']; ?></span>
				<?=$name; ?>
				<span class="parameter text markdown">
					<?=$t($this->docs->cleanup($data['text']), compact('scope')); ?>
				</span>
			</li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Method return value ?>
<?php if (isset($object['return'])) { ?>
	<h4><?=$t('Returns', array('scope' => 'li3_docs')); ?></h4>
	<span class="type"><?=$object['return']['type']; ?></span>
	<span class="return markdown">
		<?=$t($this->docs->cleanup($object['return']['text']), compact('scope')); ?>
	</span>
<?php } ?>

<?php // Method filtering info ?>
<?php if (isset($object['tags']['filter'])) { ?>
	<span class="flag markdown">
		<?=$t('This method can be filtered.', array('scope' => 'li3_docs')); ?>
	</span>
<?php } ?>
