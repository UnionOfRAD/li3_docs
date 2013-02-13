(function() {

	var webroot, base, $el;

	// Listeners
	$(document).ready(function(event){
		$el = $('#search');
		webroot = $el.data('webroot');
		base = $el.data('base');
		$el.find('input').autocomplete({
			source:getResults,
			select:selectResult
		});
		$el.on('click', 'ui.autocomplete li a', function(event){
			event.preventDefault();
			selectResult(event, null);
		});
	});

	// Handler on select
	function selectResult(event, ui) {
		var destination = ui.item.value;
		var path = destination.replace(/\\/g, "/");
		window.location.href = webroot + base + path;
	}

	// jQuery UI Autocomplete source
	function getResults(term, callback) {
		var options = [];
		$.get(
			webroot + 'li3_docs/search/' + term.term,
			function(data, status, xhr) {
				if(typeof(data.results.error) != 'undefined') {
					alert(data.results.error);
					return;
				}
				for(var i in data.results) {
					options.push(createValue(
						data.results[i]['class'],
						data.results[i].type,
						data.results[i].name
					));
				}
				callback(options);
			},
			'json'
		);
	}

	// Transforms symbol results into displayable options
	function createValue(className, type, name) {
		var value = className;
		switch(type) {
			case 'method':
				value += '::' + name + '()';
				break;
			case 'property':
				value += '::$' + name;
				break;
			case 'class':
				break;
		}
		return value;
	}

})();

