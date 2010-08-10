<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of Rad, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\g11n\Locale;
use lithium\core\Environment;

?>
<!doctype html>
<html lang="<?= str_replace('_', '-', Environment::get('locale')); ?>">
<head>
	<?=$this->html->charset(); ?>
	<title><?=$t('Lithium API', array('scope' => 'li3_docs')) . ' > ' . $this->title(); ?></title>
	<?=$this->html->style(array('lithium', '/li3_docs/css/li3_docs', '/li3_docs/css/highlight')); ?>
	<?php if (file_exists(dirname(dirname(__DIR__)) . '/webroot/css/u1m.css')) { ?>
		<?=$this->html->style('u1m'); ?>
	<?php } ?>
	<?=$this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>

<body class="docs">
<div id="wrapper">
	<?php //$this->_view->render(array('element' => 'locale_navigation')); ?>
	<div id="container">
		<div id="header">
			<h1><?=$this->html->link($t('Lithium API', array('scope' => 'li3_docs')), array(
				'library' => 'li3_docs', 'controller' => 'api_browser', 'action' => 'index'
			)); ?></h1>
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
			<?=$this->content; ?>
		</div>
	</div>
	<div id="footer-spacer"></div>
</div>

<div id="footer">
	<p class="copyright">
		<?=$t('Pretty much everything is © {:year} and beyond, the Union of RAD', array(
			'year' => date('Y'),
			'scope' => 'li3_docs'
		)); ?>
	</p>
</div>
<?=$this->html->script('http://code.jquery.com/jquery-1.4.1.min.js'); ?>
<?=$this->html->script(array('/li3_docs/js/showdown.min.js', '/li3_docs/js/highlight.pack.js')); ?>
<?php if (file_exists(dirname(dirname(__DIR__)) . '/webroot/js/rad.cli.js')) { ?>
	<?=$this->html->script('rad.cli.js'); ?>
<?php } ?>

<script type="text/javascript" charset="utf-8">
	var codeSelector = '.source-wrapper';
	var cmdUrl = 'http://lithify.me/<?= Locale::language(Environment::get('locale')); ?>/cmd';

	$(document).ready(function () {
		$(codeSelector).hide();

		if (typeof RadCli != 'undefined') {
			RadCli.setup({ commandBase: cmdUrl });
		}
		var converter = new Showdown.converter("/");

		$(".markdown").each(function () {
			$(this).html(converter.makeHtml($.trim($(this).text())));
		});

		$('.source-toggle').bind('click', function() {
			if ($(codeSelector).is(':visible')) {
				text = '<?=$t('Show source', array('scope' => 'li3_docs')); ?>';
			} else {
				text = '<?=$t('Hide source', array('scope' => 'li3_docs')); ?>';
			}
			$button = $(this);
			$(codeSelector).slideToggle(400, function() { $button.text(text); });
		});
		hljs.initHighlighting();
	});
</script>
</body>
</html>