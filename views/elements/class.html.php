<?php // Parent object ?>
<div class="nav">
	<nav>
		<?php if ($object['properties'] || ($object['methods'] && $object['methods']->count())) { ?>
		<div class="contents">
			<?php // Object properties ?>
			<?php if ($object['properties']) { ?>
				<h2><?=$t('Class Properties', array('scope' => 'li3_docs')); ?></h2>
				<ul class="properties">
					<?php foreach ($object['properties'] as $property) { ?>
						<?php $url = $this->docs->identifierUrl("{$namespace}::\${$property['name']}"); ?>
						<li><?=$this->html->link($property['name'], $url); ?></li>
					<?php } ?>
				</ul>
			<?php } ?>

			<?php // Object methods ?>
			<?php if ($object['methods'] && $object['methods']->count()) { ?>
				<h2><?=$t('Class Methods', array('scope' => 'li3_docs')); ?></h2>
				<ul class="methods">
					<?php foreach ($object['methods'] as $method) { ?>
						<?php $url = $this->docs->identifierUrl("{$namespace}::{$method->name}()"); ?>
						<li><?php echo $this->html->link($method->name, $url); ?></li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
		<?php } ?>
	</nav>
</div>


<div id="parent" class="section">
	<section>
		<?php if ($object['parent']) { ?>
			<?php $parent = $object['parent']; ?>
			<h2 class="parent"><?=$t('Extends', array('scope' => 'li3_docs')); ?></h2>
			<?=$this->html->link(
				$parent, $this->docs->identifierUrl($parent), array('class' => 'parent')
			); ?>
		<?php } ?>
	</section>
</div>
<div class="section">
	<section>
		<?php if ($object['description']) { ?>
			<div class="description markdown">
				<pre>
<?=$t($this->docs->cleanup($object['description']), compact('scope')); ?>
				</pre>
			</div>
		<?php } ?>
		<?php if ($object['text']) { ?>
			<div class="text markdown">
				<pre>
<?=$t($this->docs->cleanup($object['text']), compact('scope')); ?>
				</pre>
			</div>
		<?php } ?>
	</section>
</div>