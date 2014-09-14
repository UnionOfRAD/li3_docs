<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of Rad, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
	<?=$this->html->charset(); ?>
	<title><?php echo ($title = $this->title()) ? "{$title} < " : null ?> Documentation $lt; #li3</title>
	<?=$this->html->style(array('lithified', '/li3_docs/css/li3_docs', '/li3_docs/css/highlight')); ?>
	<?=$this->html->link('Icon', null, array('type' => 'icon')); ?>
	<?=$this->html->script(array(
		'//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js',
		'//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js',
		'//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.0/highlight.min.js',
		'//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.0/languages/php.min.js'
	)); ?>
	<script>
		$(document).ready(function () {
			hljs.initHighlighting();
		});
	</script>
</head>
<body class="li3 li3-docs">
	<div id="container">
		<header class="header-main">
			<div class="logo">&#10177;</div>
			<h1 class="title">li₃ docs</h1>
			<?php echo $this->_view->render(
				array('element' => 'crumbs'), compact('object'), array('library' => 'li3_docs')
			); ?>
		</header>

		<div id="content">
			<?php echo $this->content(); ?>
		</div>
		<footer class="footer-main">
			&copy; Union Of RAD <?php echo date('Y') ?>
		</footer>
	</div>
</body>
</html>