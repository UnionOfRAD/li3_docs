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
<html>
<head>
	<?=$this->html->charset(); ?>
	<title><?php echo ($title = $this->title()) ? "{$title} < " : null ?> Documentation $lt; #li3</title>
	<?=$this->html->style(array('lithified', '/li3_docs/css/li3_docs', '/li3_docs/css/highlight')); ?>
	<?=$this->html->link('Icon', null, array('type' => 'icon')); ?>
	<?=$this->html->script(array(
		'/li3_docs/js/jquery-1.7.1.min.js',
		'/li3_docs/js/jquery-ui-custom.min.js',
		'/li3_docs/js/showdown.js',
		'/li3_docs/js/highlight.pack.js',
		'/li3_docs/js/search.js',
	)); ?>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function () {
			var converter = new Showdown.converter({ extensions: []});

			$(".markdown").each(function () {
				$(this).html(converter.makeHtml($.trim($(this).text())));
				$(this).find('h1').addClass('h-alpha');
				$(this).find('h2').addClass('h-beta');
				$(this).find('h3').addClass('h-gamma');
				$(this).find('h4').addClass('h-delta');
			});

			hljs.initHighlighting();
			$('.aside').each(function(k, v) {
				$('#content').css('min-height', $(v).height() + 300);
			});

		});
	</script>
</head>
<body class="li3-docs">
	<div id="container">
		<div id="header">
			<header>
				<?=$this->html->link(
					$t('<span class="home"></span>', array('scope' => 'li3_docs')),
					array('controller' => 'li3_docs.ApiBrowser', 'action' => 'index'),
					array('escape' => false, 'title' => 'Return to Lithium Docs home')
				); ?>
			</header>
		</div>
		<?php if (isset($library) && $library['category'] == 'libraries'): ?>
			<div id="search" data-webroot="<?= $this->url('/'); ?>" data-base="<?= $searchBase; ?>" ><?= $this->form->text('query', array('placeholder' => 'Type to search…')) ?></div>
		<?php endif ?>

		<?php echo $this->_view->render(
			array('element' => 'crumbs'), compact('object'), array('library' => 'li3_docs')
		); ?>
		<div id="content">
			<?php echo $this->content() ?>
		</div>
	</div>
</body>
</html>