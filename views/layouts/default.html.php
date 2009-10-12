<html>
<head>
	<title><?=$this->title(); ?></title>
	<?=@$this->html->script('http://thechaw.com/js/jquery-1.3.1.min.js'); ?>
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
</head>
<body>
	<h1>Lithium API Browser</h1>
	<?=@$this->content(); ?>
</body>
</html>
