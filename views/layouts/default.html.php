<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Union of Rad, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\g11n\Locale;
use lithium\core\Environment;
use lithium\core\Libraries;

$config = Libraries::get('li3_docs');
$searchBase = $config['url'];
if (strpos($searchBase, '/') === 0) {
	$searchBase = substr($searchBase, 1);
}
if ($searchBase === false) {
	$searchBase = "";
}
if ($searchBase !== "") {
	$searchBase .= "/";
}

?>
<!doctype html>
<html lang="<?= str_replace('_', '-', Environment::get('locale')); ?>">
<head>
	<?=$this->html->charset(); ?>
	<title>
		#li3 > <?=$t('Lithium Documentation', array('scope' => 'li3_docs')) . ' > ' . $this->title(); ?>
	</title>
	<?=$this->html->style(array('lithium', '/li3_docs/css/li3_docs', '/li3_docs/css/highlight')); ?>
	<?php if (file_exists(dirname(dirname(__DIR__)) . '/webroot/css/u1m.css')) { ?>
		<?=$this->html->style('u1m'); ?>
	<?php } ?>
	<?=$this->html->link('Icon', null, array('type' => 'icon')); ?>
	<?=$this->html->script(array(
		'/li3_docs/js/jquery-1.7.1.min.js',
		'/li3_docs/js/jquery-ui-custom.min.js',
		'/li3_docs/js/showdown.min.js',
		'/li3_docs/js/highlight.pack.js',
		'/li3_docs/js/search.js',
		'/li3_docs/js/rad.cli.js'
	)); ?>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function () {
			var converter = new Showdown.converter("/");

			$(".markdown").each(function () {
				$(this).html(converter.makeHtml($.trim($(this).text())));
			});

			hljs.initHighlighting();
		});
	</script>
</head>

<body class="docs">
	<div id="header">
		<header>
			<?=$this->html->link(
				$t('<span class="home"></span>', array('scope' => 'li3_docs')),
				array('controller' => 'li3_docs.ApiBrowser', 'action' => 'index'),
				array('escape' => false, 'title' => 'Return to Lithium Docs home')
			); ?>
			<div id="search" data-webroot="<?= $this->url('/'); ?>" data-base="<?= $searchBase; ?>" ><?= $this->form->text('query') ?></div>
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
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function () {
			RadCli.setup({
				setupGitCopy: false,
				commandBase: 'http://lithify.me/<?= Locale::language(Environment::get('locale')); ?>/cmd'
			});
			$('#header').css({ borderTop: '40px solid black' });
		});
	</script>
</body>
</html>
