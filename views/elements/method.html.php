
<?php // Method parameters ?>
<?php if (isset($object['tags']['params'])) { ?>
	<h4><?=$t('Parameters', array('scope' => 'li3_docs')); ?></h4>
	<ul class="parameters">
		<?php foreach ($object['info']['tags']['params'] as $name => $data) { ?>
			<li>
				<span class="type"><?=$data['type']; ?></span>
				<?=$name; ?>
				<span class="description markdown">
					<?=$t($this->docs->cleanup($data['text']), compact('scope')); ?>
				</span>
			</li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Method return value ?>
<?php if (isset($object['return'])) { ?>
	<h4><?=$t('Returns', array('scope' => 'li3_docs')); ?></h4>
	<span class="type"><?=$object['info']['return']['type']; ?></span>
	<span class="return markdown">
		<?=$t($this->docs->cleanup($object['info']['return']['text']), compact('scope')); ?>
	</span>
<?php } ?>

<?php // Method filtering info ?>
<?php if (isset($object['tags']['filter'])) { ?>
	<span class="flag markdown">
		<?=$t('This method can be filtered.', array('scope' => 'li3_docs')); ?>
	</span>
<?php } ?>
