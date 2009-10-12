<ul>
	<?php foreach ($libraries as $name => $config) { ?>
		<?php $display = ucwords(str_replace('_', ' ', $name)); ?>
		<li><a href="<?php echo $name; ?>"><?php echo $display; ?></a></li>
	<?php } ?>
</ul>
