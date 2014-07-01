<?php if (isset($object['tags']['see'])) { ?>
<div id="related" class="section">
	<section>
		<h3 class="h-beta"><?=$t('Related', array('scope' => 'li3_docs')); ?></h3>
		<ul class="related">
			<?php foreach ((array) $object['tags']['see'] as $name) { ?>
				<li><?=$this->html->link($name, $this->docs->identifierUrl($name)); ?></li>
			<?php } ?>
		</ul>
	</section>
</div>
<?php } ?>