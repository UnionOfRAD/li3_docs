<?php

$this->title(implode(' – ', [
	$symbol->title(),
	$index->title() . ' v' . $index->version,
	'Documentation'
]));

$docblock = $symbol->docblock();

?>
<article class="apis-view has-aside-right">

	<?php echo $this->_view->render(
		['element' => 'aside_symbol'],
		compact('symbol', 'index', 'docblock'),
		['library' => 'li3_docs']
	) ?>

	<div class="body">
	<?php if ($symbol->type === 'namespace' && ($page = $symbol->page())): ?>
		<section>
			<?php echo $this->markdown->parse($page->content) ?>
		</section>
	<?php else: ?>
		<h1 class="h-alpha">
		<?php
			$segments = $symbol->segments();
			while (list($segment, $title) = each($segments)) {
				if (!is_string($segment)) {
					echo $title;
					continue;
				}
				if (key($segments) === null) {
					echo $title;
				} else {
					echo $this->html->link($title, [
						'library' => 'li3_docs',
						'action' => 'view',
						'name' => $index->name,
						'version' => $index->version,
						'symbol' => $segment
					], ['class' => 'symbol-segment']);
				}
			}
		?>
		</h1>

		<section class="under">
			<?php if ($symbol->type === 'class' && ($extends = $symbol->extends_())): ?>
				<div class="extends">
					<span class="extends__title">Extends</span>
					<?php if ($extends->isExternal()): ?>
						<?= $extends->title() ?>
					<?php else: ?>
						<?= $this->html->link($extends->title(), [
							'library' => 'li3_docs',
							'action' => 'view',
							'name' => $index->name,
							'version' => $index->version,
							'symbol' => $symbol->extends
						], ['class' => 'extends__symbol']) ?>
					<?php endif ?>
				</div>
			<?php endif ?>
			<?php if ($symbol->type === 'class' && ($extends = $symbol->implements_()) && $extends->count()): ?>
				<?php foreach ($extends as $extend): ?>
				<div class="extends">
					<span class="extends__title">Implements</span>
					<?php if ($extend->isExternal()): ?>
						<?= $extend->title() ?>
					<?php else: ?>
						<?= $this->html->link($extend->title(), [
							'library' => 'li3_docs',
							'action' => 'view',
							'name' => $index->name,
							'version' => $index->version,
							'symbol' => $extend->name,
						], ['class' => 'extends__symbol']) ?>
					<?php endif ?>
				</div>
				<?php endforeach ?>
			<?php endif ?>
			<?php if (in_array($symbol->type, ['method', 'property']) && ($over = $symbol->overrides())): ?>
				<div class="extends">
					<span class="extends__title">
						<?php if ($over->isAbstract()): ?>
							Implements
						<?php else: ?>
							Overrides
						<?php endif ?>
					</span>
					<?= $this->html->link($over->title(), [
						'library' => 'li3_docs',
						'action' => 'view',
						'name' => $index->name,
						'version' => $index->version,
						'symbol' => $over->name
					], ['class' => 'extends__symbol']) ?>
				</div>
			<?php endif ?>
			<div class="tags">
				<?php if ($symbol->isAbstract()): ?>
					<span class="tag">abstract</span>
				<?php endif ?>
				<?php if ($symbol->visibility): ?>
					<span class="tag <?= $symbol->visibility ?>"><?= $symbol->visibility ?></span>
				<?php endif ?>
				<?php if ($symbol->isStatic()): ?>
					<span class="tag">static</span>
				<?php endif ?>
				<span class="tag"><?= $symbol->type ?></span>
			</div>
		</section>

		<?php if ($docblock): ?>
			<?php if ($tag = $docblock->tag('deprecated')): ?>
			<section class="deprecated">
				<?php if ($tag['description']): ?>
					<?= $tag['description'] ?>
				<?php else: ?>
					<?php if ($symbol->type === 'class'): ?>
						This class, its method, properties and constants are deprecated.
					<?php else: ?>
						This <?= $symbol->type ?> is deprecated.
					<?php endif ?>
				<?php endif ?>
			</section>
			<?php endif ?>

			<section>
				<?php if ($text = $docblock->summary()): ?>
					<div class="summary">
						<?php echo $this->markdown->parse($text) ?>
					</div>
				<?php endif ?>

				<?php if ($text  = $docblock->description()): ?>
					<div class="description">
						<?php echo $this->markdown->parse($text) ?>
					</div>
				<?php endif ?>
			</section>

			<?php if ($tags = $docblock->tags('param')): ?>
			<section id="params">
				<h3 class="h-beta">Parameters</h3>
				<ul class="parameters">
					<?php foreach ($tags as $tag): ?>
						<li>
							<span class="type"><?= $tag['type'] ?></span>
							<code class="name"><?= $tag['name'] ?></code>
							<?php if ($tag['description']): ?>
								<span class="parameter">
									<?php echo $this->markdown->parse($tag['description']) ?>
								</span>
							<?php endif ?>
						</li>
					<?php endforeach ?>
				</ul>
			</section>
			<?php endif ?>

			<?php if ($tag = $docblock->tag('return')): ?>
			<section id="return">
				<h3 class="h-beta">Returns</h3>
				<span class="type"><?= $tag['type'] ?></span>
				<?php if ($tag['description']): ?>
					<span class="return">
						<?php echo $this->markdown->parse($tag['description']) ?>
					</span>
				<?php endif ?>
			</section>
			<?php endif ?>

			<?php if ($tag = $docblock->tag('filter')): ?>
			<section id="filter" class="flag">
				<h3 class="h-beta">
					Filter
					<nav class="headline-nav">
						<?= $this->html->link(
							'see how to use filters',
							'http://li3.me/docs/book/manual/1.x/common-tasks/filters'
						) ?>
					</nav>
				</h3>
				<p>
					<?= $tag['description'] ?: 'This method can be filtered.' ?>
				</p>
			</section>
			<?php endif ?>

			<?php if ($tags = $docblock->tags('see')): ?>
			<section id="related">
				<h3 class="h-beta">Related</h3>
				<ul class="related">
				<?php foreach ($tags as $tag): ?>
					<li><?=$this->html->link($tag['description'] ?: $tag['symbol'], [
					'library' => 'li3_docs',
					'action' => 'view',
					'name' => $index->name,
					'version' => $index->version,
					'symbol' => str_replace('\\', '/', $tag['symbol'])
					]) ?>
				<?php endforeach ?>
				</ul>
			</section>
			<?php endif ?>

			<?php if ($tags = $docblock->tags('link')): ?>
			<section id="links">
				<h3 class="h-beta">Links</h3>
				<ul class="links">
				<?php foreach ($tags as $tag): ?>
					<li><?=$this->html->link($tag['description'] ?: $tag['url'], $tag['url'], ['target' => 'new']) ?>
				<?php endforeach ?>
				</ul>
			</section>
			<?php endif ?>

			<?php if (($symbols = $symbol->subclasses()) && $symbols->count()): ?>
			<section id="subclasses" class="section">
				<h3 class="h-beta">Subclasses</h3>
				<ul class="subclasses">
					<?php foreach ($symbols as $s): ?>
						<li><?php echo $this->html->link($s->title(), [
								'library' => 'li3_docs',
								'action' => 'view',
								'name' => $index->name,
								'version' => $index->version,
								'symbol' => $s->name
							]) ?>
					<?php endforeach ?>
				</ul>
			</section>
			<?php endif ?>

			<?php if (!empty($symbol->source)): ?>
			<section id="source" class="section">
				<h3 class="h-beta">Source</h3>
				<div id="source" class="source-display">
					<div class="source-wrapper">
						<pre class="source-code"><code class="language-php"><?= $symbol->source ?></code></pre>
					</div>
				</div>
			</section>
			<?php endif ?>
		<?php endif ?>
	<?php endif ?>
	</div>
</article>