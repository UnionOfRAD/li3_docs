<?php $defaults = array(
	'library' => 'li3_docs', 'controller' => 'api_browser', 'action' => 'view'
); ?>

<div class="nav">
	<h2><?=$this->title($t('Libraries', array('scope' => 'li3_docs'))); ?></h2>
	<nav>
		<ul class="libraries">
			<?php foreach ($libraries as $lib => $config) { ?>
				<li>
					<div class="title">
						<?=$this->html->link($lib, compact('lib') + $defaults); ?>
					</div>
				</li>
			<?php } ?>
		</ul>
	</nav>
</div>

<div class="section">
	<section>
		<h3><?=$this->title($t('Available APIs', array('scope' => 'li3_docs'))); ?></h3>
		<?php foreach ($libraries as $lib => $config) { ?>
				<h4 class="title">
					<?=$this->html->link($config['title'], compact('lib') + $defaults); ?>
				</h4>
				<?php if (isset($config['description'])) { ?>
					<div class="library-description markdown">
						<pre>
<?=$config['description']; ?>
						</pre>
					</div>
				<?php } ?>
		<?php } ?>
	</section>
</div>