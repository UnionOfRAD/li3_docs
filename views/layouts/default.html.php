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
	<?php echo $this->html->link('Icon', 'http://li3.rad-dev.org/favicon.png', array('type' => 'icon')); ?>
	<?php echo $this->html->style(array(
		'http://li3.rad-dev.org/css/li3.css',
		'http://li3.rad-dev.org/css/li3.docs.css'
	)); ?>
	<?php echo $this->scripts(); ?>
</head>
<body <?php echo (!empty($apiNavigation)) ? 'class="side-navigation"' : null ; ?>>
<div id="container">
	<div class="header" id="site-header">
		<div class="aside" id="cli">
			<div class="nav">
				<div id="cli-display"></div>
				<div>
					<form id="cli-form" onSubmit="return false">
						<input type="text" id="cli-input" />
						<input id="cli-submit" type="submit" />
					</form>
				</div>
			</div>
		</div>
		<div class="aside" id="git-shortcuts">
			<span id="git-clone-path" class="clone">git clone code@rad-dev.org:lithium.git</span>
			<div class="nav">
				<?php /*<a href="#" class="download" title="Download Lithium">download</a> */ ?>
				<a href="#" id="git-copy" class="copy" title="Copy the git clone shortcut to your clipboard">
					copy to clipboard
				</a>
			</div>
		</div>
		<div <?php echo !empty($constrained) ? 'class="width-constraint"' : null; ?>>
			<h1><?php echo $this->html->link('Lithium', '/'); ?></h1>
		</div>
	</div>

	<div class="width-constraint">
		<div class="aside" id="site-navigation">
			<div class="nav">
				<?php echo (!empty($apiNavigation)) ? @$apiNavigation : null ; ?>
			</div>
		</div>
		<div class="article">
			<h1><?php echo $this->html->link('API', '/docs');?></h1>
			<?php echo $this->content();?>
		</div>
	</div>
	<div id="footer-spacer"></div>
</div>
<div class="footer" id="site-footer">
	<p class="copyright">
		<?=$t('Pretty much everything is &copy; 2009 and beyond, the Union of Rad'); ?>
	</p>
</div>
<?php echo $this->html->script(array(
	'http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js',
	'http://li3.rad-dev.org/js/li3.js',
	'http://li3.rad-dev.org/js/cli.js',
	'http://li3.rad-dev.org/libraries/ZeroClipboard/ZeroClipboard.js',
	'http://li3.rad-dev.org/js/showdown.min.js'
)); ?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function () {
		li3.setup({
			base : '<?php echo $this->_request->env('base');?>',
			testimonials: <?php echo !empty($testimonials) ? 'true' : 'false'; ?>
		});
		li3Cli.setup();
		var converter = new Showdown.converter("/");
		$(".wiki-text").each(function () {
			$(this).html(converter.makeHtml($.trim($(this).text())));
		});
	});
</script>
</body>
</html>