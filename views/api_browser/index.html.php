<?php

use lithium\util\Inflector;

$defaults = array('controller' => 'li3_docs.ApiBrowser', 'action' => 'view');
?>
<article>
	<h1 class="alpha">Documentation</h1>
<?php foreach ($categories as $cat): ?>
	<section>
		<h1 class="beta"><?=$this->title($t(Inflector::humanize($cat), array('scope' => 'li3_docs'))); ?></h1>

		<?php foreach ($libraries as $lib => $config) { ?>
			<article>
				<?php if ($config['category'] != $cat) { continue; } ?>
				<h1 class="gamma title">
					<?=$this->html->link($config['title'], compact('lib') + $defaults); ?>
				</h1>
				<?php if (isset($config['description'])): ?>
					<div class="library-description markdown">
						<pre><?=$config['description']; ?></pre>
					</div>
				<?php else: ?>
					<p></p>
				<?php endif ?>
			</article>
		<?php } ?>
	</section>
<?php endforeach ?>
</article>