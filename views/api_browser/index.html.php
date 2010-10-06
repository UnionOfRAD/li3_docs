<?php $defaults = array(
	'library' => 'li3_docs', 'controller' => 'api_browser', 'action' => 'view'
); ?>

<h2><?=$this->title($t('Library APIs', array('scope' => 'li3_docs'))); ?></h2>

<ul class="libraries">
	<?php foreach ($libraries as $lib => $config) { ?>
		<li>
			<div class="title">
				<?=$this->html->link($config['title'], compact('lib') + $defaults); ?>
			</div>
			<?php if (isset($config['description'])) { ?>
				<p class="markdown"><?=$config['description']; ?></p>
			<?php } ?>
		</li>
	<?php } ?>
</ul>