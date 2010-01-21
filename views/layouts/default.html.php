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
	<?php echo $this->html->style(array('base', 'li3_docs')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="docs">
	<div id="container">
		<div id="header">
			<h1>Lithium</h1>
			<h2><?php echo $this->html->link('API', '/docs');?></h2>
			<ul class="crumbs">
			<?php foreach (isset($crumbs) ? $crumbs : array() as $crumb): ?>
				<li class="<?= $crumb['class'];?>">
				<?php
					if ($crumb['url']) {
						echo $this->html->link($crumb['title'], $crumb['url']);
					} else {
						echo $this->html->tag('span', $crumb['title']);
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
<?php echo $this->html->script(array(
	'http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js',
	'showdown.min.js'
)); ?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function () {
		var converter = new Showdown.converter("/");
		$(".markdown").each(function () {
			$(this).html(converter.makeHtml($.trim($(this).text())));
		});
	});
</script>
</body>
</html>