<?php

if (!isset($object['tags']['link'])) {
	return;
}

?>

<section id="links">
	<h3 class="h-beta">Links</h3>
	<ul class="links">
	<?php foreach ((array) $object['tags']['link'] as $url) { ?>
		<?php
			$title = $url;

			if (strpos($url, ' ')) {
				list($url, $title) = array_map('trim', explode(' ', $url, 2));
			}
		?>
		<li><?=$this->html->link($title, $url); ?></li>
	<?php } ?>
	</ul>
</section>