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
	<title>Docs > <?php echo $this->title(); ?></title>
	<?php echo $this->html->style(array('lithium', '/li3_docs/css/li3_docs')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="docs">
<div id="wrapper">
	<div id="cli">
		<div id="cli-display"></div>
		<div>
			<form id="cli-form" onSubmit="return false">
				<input type="text" id="cli-input" />
				<input id="cli-submit" type="submit" />
			</form>
		</div>
	</div>
	<div id="git-shortcuts">
		<span id="git-clone-path" class="clone fixed">git clone code@rad-dev.org:lithium.git</span>
		<a href="#" id="git-copy" class="copy" title="Copy the git clone shortcut to your clipboard">copy to clipboard</a>
	</div>
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
	<p class="copyright">Pretty much everything is &copy; 2009 and beyond, the Union of Rad</p>
</div>
<?php echo $this->html->script(array(
	'http://code.jquery.com/jquery-1.4.1.min.js',
	'li3',
	'cli',
//		'libraries/ZeroClipboard/ZeroClipboard.js',
	'showdown.min.js'
)); ?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function () {
		li3.setup({
			base : '<?php echo $this->_request->env('base');?>',
			testimonials: <?php echo !empty($testimonials) ? 'true' : 'false'; ?>
		});
		li3Cli.setup();
		var converter = new Showdown.converter("/");

		$(".markdown").each(function () {
			$(this).html(converter.makeHtml($.trim($(this).text())));
		});

		$('pre code').hide();

		$('.source-toggle').bind('click', function() {
			visible = $('pre code').is(':visible');
			$(this).text((visible ? 'Show' : 'Hide') + ' source');
			visible ? $('pre code').slideUp() : $('pre code').slideDown();
		});
	});
</script>
</body>
</html>