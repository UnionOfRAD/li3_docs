<?php if (isset($object['tags']['see'])) { ?>
	<h4><?=$t('Related', array('scope' => 'li3_docs')); ?></h4>
	<ul class="related">
		<?php foreach ((array) $object['tags']['see'] as $name) { ?>
			<li><?=$this->html->link($name, $this->docs->identifierUrl($name)); ?></li>
		<?php } ?>
	</ul>
<?php } ?>
