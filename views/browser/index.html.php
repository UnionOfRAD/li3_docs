<h2><?=$this->title($t('Libraries', array('scope' => 'li3_docs'))); ?></h2>

<ul class="libraries">
	<?php foreach ($libraries as $lib => $config) { ?>
		<?php $display = ucwords(str_replace('_', ' ', $lib)); ?>
		<li><?=$this->html->link($display, compact('lib') + array(
			'library' => 'li3_docs', 'controller' => 'browser', 'action' => 'view'
		)); ?></li>
	<?php } ?>
</ul>
