<?php if ($object['children']) { ?>
	<div class="contents">
		<h4><?=$t('Package contents', array('scope' => 'li3_docs')); ?></h4>
		<ul class="children">
			<?php foreach ($object['children'] as $class => $type) { ?>
				<?php
					$parts = explode('\\', $class);
					$url = $this->docs->identifierUrl($class);
				?>
				<li class="<?=$type; ?>"><?php echo $this->html->link(end($parts), $url); ?></li>
			<?php } ?>
		</ul>
	</div>
<?php } ?>

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
