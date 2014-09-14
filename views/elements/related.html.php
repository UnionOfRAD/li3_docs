<?php if (isset($object['tags']['see'])) { ?>
	<section id="related">
		<h3 class="h-beta">Related</h3>
		<ul class="related">
			<?php foreach ((array) $object['tags']['see'] as $name) { ?>
				<li><?=$this->html->link($name, $this->docs->identifierUrl($name)); ?></li>
			<?php } ?>
		</ul>
	</section>
<?php } ?>