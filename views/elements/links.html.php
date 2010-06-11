<?php if (isset($object['tags']['link'])) { ?>
	<h4><?=$t('Links', array('scope' => 'li3_docs')); ?></h4>
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
<?php } ?>
