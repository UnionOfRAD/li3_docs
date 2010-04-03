<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of Rad, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use \lithium\core\Environment;
use \lithium\g11n\Locale;

?>
<!doctype html>
<html lang="<?= str_replace('_', '-', Environment::get('locale')); ?>">
<head>
	<?php echo $this->html->charset(); ?>
	<title><?=$t('Lithium API', array('scope' => 'li3_docs')) . ' > ' . $this->title(); ?></title>
	<?php echo $this->html->style(array('lithium', 'u1m', '/li3_docs/css/li3_docs')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="docs">
<div id="wrapper">
	<?=$this->_view->render(array('element' => 'locale_navigation')); ?>
	<div id="container">
		<div id="header">
			<h1><?php echo $this->html->link($t('Lithium API', array('scope' => 'li3_docs')), array(
				'plugin' => 'li3_docs', 'controller' => 'browser', 'action' => 'index'
			));?></h1>
			<ul class="crumbs">
			<?php foreach (isset($crumbs) ? $crumbs : array() as $crumb): ?>
				<li class="<?= $crumb['class'];?>">
					<?php
						if ($crumb['url']) {
							echo $this->html->link($crumb['title'], $crumb['url']);
							continue;
						}
					?>
					<span><?=$crumb['title']; ?></span>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
		<div id="content">
			<?php echo $this->content; ?>
		</div>
	</div>
	<div id="footer-spacer"></div>
</div>
<div id="footer">
	<p class="copyright">
		<?=$t('Pretty much everything is © {:year} and beyond, the Union of Rad', array(
			'year' => date('Y'),
			'scope' => 'li3_docs'
		)); ?>
	</p>
</div>
<?php echo $this->html->script(array(
	'http://code.jquery.com/jquery-1.4.1.min.js',
	'rad.cli.js',
	'showdown.min.js',
	'highlight.pack.js'
)); ?>
<script type="text/javascript" charset="utf-8">
	var codeSelector = '.source-wrapper';

	$(document).ready(function () {
		$(codeSelector).hide();

		RadCli.setup({
			commandBase: 'http://lithify.me/<?= Locale::language(Environment::get('locale')); ?>/cmd'
		});

		var converter = new Showdown.converter("/");

		$(".markdown").each(function () {
			$(this).html(converter.makeHtml($.trim($(this).text())));
		});

		$('.source-toggle').bind('click', function() {
			visible = $(codeSelector).is(':visible');

			if (visible) {
				text = '<?=$t('Show source', array('scope' => 'li3_docs')); ?>';
			} else {
				text = '<?=$t('Hide source', array('scope' => 'li3_docs')); ?>';
			}
			$(this).text((text));

			visible ? $(codeSelector).slideUp() : $(codeSelector).slideDown();
		});
		hljs.initHighlightingOnLoad();
	});
</script>
</body>
</html>