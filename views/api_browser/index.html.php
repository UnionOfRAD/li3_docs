<?php

use lithium\util\Inflector;

$categories = array_values(array_unique(
	array_map(function($lib) { return $lib['category']; }, $libraries)
));
$defaults = array('controller' => 'li3_docs.ApiBrowser', 'action' => 'view');

?>

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

<?php foreach ($categories as $cat): ?>

<div class="section">
	<section>
		<h3><?=$this->title($t(Inflector::humanize($cat), array('scope' => 'li3_docs'))); ?></h3>
		<?php foreach ($libraries as $lib => $config) { ?>
			<?php if ($config['category'] != $cat): ?>
				<?php continue; ?>
			<?php endif ?>
			<h4 class="title">
				<?=$this->html->link($config['title'], compact('lib') + $defaults); ?>
			</h4>
			<?php if (isset($config['description'])) { ?>
				<div class="library-description markdown">
					<pre><?=$config['description']; ?></pre>
				</div>
			<?php } ?>
		<?php } ?>
	</section>
</div>

<?php endforeach ?>
