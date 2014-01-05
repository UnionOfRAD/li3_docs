/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of Rad (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

var RadCli = {
	options: {
		div: null,
		assetBase: 'http://li3.me',
		commandBase: 'http://li3.me/cmd'
	},
	html: {
		cli: null,
		git: null
	},

	/**
	 * Setup assigns options, creates the html and calls the appropriate css and javascript
	 *
	 * @var mixed options
	 * @return void
	 */
	setup: function(setupOptions) {
		if(setupOptions != null) {
			$.extend(this.options, setupOptions);
		}
		this.loadGoods();
		this.clearCli();

		$('#cli-form').bind('submit', function() {
			var command = $('#cli-input').val();
			if (command == "`") {
				command = 'clear';
			}
			RadCli.clearCli();
			if (command != 'clear') {
				$.getJSON(RadCli.options.commandBase + '/?jsoncallback=?', { cmd: command });
			}
		});
		$('#container').click(function() {
			RadCli.clearCli();
		});
		$("#cli").click(function() {
			$('#cli-input').focus();
		});
		$("#cli").dblclick(function() {
			$.getJSON(RadCli.options.commandBase + '/?jsoncallback=?', { cmd: 'help' });
			$('#cli-input').focus();
		});
	},

	loadGoods: function() {
		var html = RadCli.createCliHtml() + RadCli.createGitHtml();
		if (RadCli.options.div) {
			$(RadCli.options.div).append(html);
		} else {
			html = $('<div id="cli-wrapper" />').hide().html(html);
			$("body").prepend(html);
		}
		$("head").append(
			'<link id="rad-cli-css" rel="stylesheet" type="text/css" href="' +
			this.options.assetBase +
			'/css/rad.cli.css" />'
		);
		$(window).load(function() {
			$("#cli-wrapper").fadeIn();
		});
	},

	/**
	 * Generate, assign internally, and return string of html for Rad Cli bar
	 *
	 * @return string html
	 */
	createCliHtml: function() {
		var html = '';
		html += '<div id="cli">';
			html += '<div id="cli-display"></div>';
			html += '<div>';
				html += '<form id="cli-form" onSubmit="return false;">';
					html += '<input id="cli-input" type="text" />';
					html += '<input id="cli-submit" type="submit" />';
				html += '</form>';
			html += '</div>';
		html += '</div>';
		return this.html.cli = html;
	},

	/**
	 * Generate, assign internally, and return string of html for Rad Git path
	 *
	 * @return string html
	 */
	createGitHtml: function() {
		var html = '';
		html += '<div id="git-shortcuts">';
			html += '<span id="git-clone-path" class="clone fixed"><strong>git clone</strong>';
			html += ' git://github.com/UnionOfRAD/lithium.git</span>';
		html += '</div>';
		return this.html.git = html;
	},

	response: function(json) {
		if (json.status == 'success') {
			if (json.data.url) {
				return window.location.href = json.data.url;
			} else {
				$('#cli-display').html(json.data.text);
			}
		} else {
			// This is not my beautiful house... (How did I get here?)
			$('#cli-display').html(response+': temporarily out of order.');
		}
		$('#cli-display').animate({
			height: 'show',
			opacity: 'show'
		});
	},

	clearCli: function () {
		$('#cli-display').hide();
		$('#cli-input').val('');
		//$('#cli-input').focus();
	}
}
