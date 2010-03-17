<h2><?=$this->title($t('Libraries', array('scope' => 'li3_docs'))); ?></h2>
<ul class="libraries">
	<?php foreach ($libraries as $name => $config) { ?>
		<?php $display = ucwords(str_replace('_', ' ', $name)); ?>
		<li><?=$this->html->link($display, array(
			'plugin' => 'li3_docs',
			'controller' => 'browser', 'action' => 'view',
			'library' => $name
		)); ?></li>
	<?php } ?>
</ul>