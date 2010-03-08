<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of Rad, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset(); ?>
	<title>Lithium API <?php echo $this->title(); ?></title>
	<?php echo $this->html->style(array('lithium', '/li3_docs/css/li3_docs')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="docs">
	<div id="container">
		<div id="header">
			<h1><?php echo $this->html->link('Docs', '/docs');?></h1>
			<h2>Powered by <?php echo $this->html->link('Lithium', 'http://lithify.me');?>.</h2>
			<ul class="crumbs">
			<?php foreach (isset($crumbs) ? $crumbs : array() as $crumb): ?>
				<li class="<?= $crumb['class'];?>">
				<?php
					if ($crumb['url']) {
						echo $this->html->link($crumb['title'], $crumb['url']);
					} else {
						echo "<span>{$h($crumb['title'])}</span>";
					}
				?>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
		<div id="content">
			<?php echo $this->content; ?>
		</div>
	</div>
<?php
	$jQuery = 'http://code.jquery.com/jquery-1.4.1.min.js';
	if (\lithium\core\Environment::is('development')) {
		$jQuery = '/li3_docs/js/jquery-1.4.1.min';
	}
 	echo $this->html->script(array(
		$jQuery, '/li3_docs/js/showdown.min'
	));
?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function () {
		var converter = new Showdown.converter("/");

		$(".markdown").each(function () {
			$(this).html(converter.makeHtml($.trim($(this).text())));
		});

		$('pre.source-code').hide();

		$('.source-toggle').bind('click', function() {
			visible = $('pre.source-code').is(':visible');
			$(this).text((visible ? 'Show' : 'Hide') + ' source');
			visible ? $('pre.source-code').slideUp() : $('pre.source-code').slideDown();
		});
	});
</script>
</body>
</html>