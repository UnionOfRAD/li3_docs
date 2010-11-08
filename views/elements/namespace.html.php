<div class="nav">
	<nav>
	<?php if ($object['children']) { ?>
		<h2><?=$t('Packages', array('scope' => 'li3_docs')); ?></h2>
		<ul class="children">
			<?php foreach ($object['children'] as $class => $type) { ?>
				<?php
					$parts = explode('\\', $class);
					$url = $this->docs->identifierUrl($class);
				?>
				<li class="<?=$type; ?>"><?php echo $this->html->link(end($parts), $url); ?></li>
			<?php } ?>
		</ul>
	<?php } ?>
	</nav>
</div>

<div class="section">
	<section>
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
	</section>
</div>