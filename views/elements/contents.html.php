<div class="nav"><nav>
	<?php if ($object['children']) { ?>
		<h2><?=$t('{:scope} contents', array('scope' => isset($object['library'])? $object['library'] : 'file')); ?></h2>
		<ul class="children">
			<?php foreach ($object['children'] as $name => $type) { ?>
			<?php
			if (!isset($url)) {
				$url = $this->docs->identifierUrl($name);
				$parts = explode('\\', $name);
				$name = basename(end($parts));
			}
			else {
				$url = $this->docs->pageUrl($url);
			}
			?>
			<li class="<?=is_array($type)? 'namespace' : $type; ?>"><?=$this->html->link($name, $url); ?></li>
			<?php unset($url, $parts, $name); ?>
			<?php if (is_array($type) && count($type)>=1) { ?>
				<ul>
					<?php foreach ($type as $name2 => $type2) { ?>
						<?php
						if (!isset($url)) {
							$url = $this->docs->identifierUrl($name2);
							$parts = explode('\\', $name2);
							$name2 = basename(end($parts));
						} else {
							$url = $this->docs->pageUrl($url);
						}
						?>
						<li class="<?=is_array($type2)? 'namespace' : $type2; ?>"><?=$this->html->link($name2, $url); ?></li>
						<?php unset($url, $parts, $name2); ?>
						<?php if (is_array($type2) && count($type2)>=1) { ?>
							<ul>
								<?php foreach ($type2 as $name3 => $type3) { ?>
									<?php
									if (!isset($url)) {
										$url = $this->docs->identifierUrl($name3);
										$parts = explode('\\', $name3);
										$name3 = basename(end($parts));
									} else {
										$url = $this->docs->pageUrl($url);
									}
									?>
									<li class="<?=$type3?>"><?=$this->html->link($name3, $url); ?></li>
									<?php unset($url, $parts, $name3, $type3); ?>
								<?php } ?>
							</ul>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>
			<?php unset($url); ?>
		<?php } ?>
		</ul>
	<?php } ?>
</nav></div>