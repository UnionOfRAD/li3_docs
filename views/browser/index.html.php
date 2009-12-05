<h2>Available Libraries</h2>
<ul class="libraries">
	<?php foreach ($libraries as $name => $config) { ?>
		<?php $display = ucwords(str_replace('_', ' ', $name)); ?>
		<li><?php echo $this->html->link($display, "docs/{$name}"); ?></li>
	<?php } ?>
</ul>