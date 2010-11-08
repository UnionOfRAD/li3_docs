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
	<title>
		<?=$t('Lithium Documentation', array('scope' => 'li3_docs')) . ' > ' . $this->title(); ?>
	</title>
	<?=$this->html->style(array('lithium', '/li3_docs/css/li3_docs', '/li3_docs/css/highlight')); ?>
	<?php if (file_exists(dirname(dirname(__DIR__)) . '/webroot/css/u1m.css')) { ?>
		<?=$this->html->style('u1m'); ?>
	<?php } ?>
	<?=$this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>

<body class="docs">
	<div id="header">
		<header>
			<h1>
				<?=$this->html->link($t('<span class="triangle"></span> Lithium Docs', array('scope' => 'li3_docs')), array(
					'library' => 'li3_docs', 'controller' => 'api_browser', 'action' => 'index'
				), array('escape' => false)); ?>
			</h1>
		</header>
	</div>

	<div class="nav">
		<nav>
			<?php echo $this->_view->render(
				array('element' => 'nav'), compact('object'), array('library' => 'li3_docs')
			); ?>
		</nav>
	</div>

	<div class="article">
		<article>
			<?=$this->content; ?>
		</article>
	</div>

<?=$this->html->script('http://code.jquery.com/jquery-1.4.1.min.js'); ?>
<?=$this->html->script(array('/li3_docs/js/showdown.min.js', '/li3_docs/js/highlight.pack.js')); ?>

<?php if (file_exists(dirname(dirname(__DIR__)) . '/webroot/js/rad.cli.js')) { ?>
	<?=$this->html->script('rad.cli.js'); ?>
<?php } ?>

<script type="text/javascript" charset="utf-8">
	var cmdUrl = 'http://lithify.me/<?= Locale::language(Environment::get('locale')); ?>/cmd';

	$(document).ready(function () {

		if (typeof RadCli != 'undefined') {
			RadCli.setup({ commandBase: cmdUrl });
		}
		var converter = new Showdown.converter("/");

		$(".markdown").each(function () {
			$(this).html(converter.makeHtml($.trim($(this).text())));
		});

		hljs.initHighlighting();
	});
</script>
</body>
</html>