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
	<?=@$this->html->charset(); ?>
	<title>Lithium <?=@$this->title(); ?></title>
	<?=@$this->html->link('Icon', 'http://li3.rad-dev.org/favicon.png', array('type' => 'icon')); ?>
	<?=@$this->html->style(array('http://li3.rad-dev.org/css/base.css', 'http://li3.rad-dev.org/css/li3.css', 'docs')); ?>
	<?=@$this->html->script('http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js'); ?>
	<?=@$this->html->script('http://thechaw.com/js/gshowdown.min.js'); ?>
	<?=@$this->scripts(); ?>
	<script type="text/javascript">
		var converter = new Showdown.converter("/");

		$(document).ready(function(){
			$(".wiki-text").each(function () {
				$(this).html(converter.makeHtml(jQuery.trim($(this).text())));
			});
		});
	</script>
	<?=@$this->scripts(); ?>
</head>
<body <?php echo (!empty($apiNavigation)) ? 'class="side-navigation"' : null ; ?>>
	<header id="site-header">
		<aside id="cli">
			<nav>
				<div id="cli-display"></div>
				<div>
					<form id="cli-form" onSubmit="return false">
						<input type="text" id="cli-input" />
						<input id="cli-submit" type="submit" />
					</form>
				</div>
			</nav>
		</aside>
		<aside id="git-shortcuts">
			<span class="clone">git clone code@rad-dev.org:lithium.git</span>
		</aside>
		<div <?php echo !empty($constrained) ? 'class="width-constraint"' : null; ?>>
			<h1><?=@$this->html->link('Lithium', '/'); ?></h1>
		</div>
	</header>

	<div class="width-constraint">
		<aside id="site-navigation">
			<nav>
				<?php echo (!empty($apiNavigation)) ? @$apiNavigation : null ; ?>
			</nav>
		</aside>
		<article>
			<h1>API</h1>
			<?=@$this->content();?>
		</article>
	</div>

	<footer id="site-footer">
		<p class="copyright">Pretty much everything is © 2009 and beyond, the Union of Rad</p>
	</footer>
	<?=@$this->html->script('http://li3.rad-dev.org/js/li3.js'); ?>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function () {
			li3.setup({
				base : '<?php echo $this->_request->env('base');?>',
				testimonials: <?php echo !empty($testimonials) ? 'true' : 'false'; ?>
			});
		});
	</script>
</body>
</html>
