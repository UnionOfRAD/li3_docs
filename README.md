Li3_docs is a **Lithium plugin**, NOT a Lithium app. Furthermore, by itself it is a VIEWER ONLY and contains no actual documentation other than its own.

Once installed in your existing application, however, it generates documentation from your app's docblocks in real-time, which is all accessible from http://yourapp.tld/docs/. Not only that, but it will generate documentation for your plugins, too. Including itself; so it is self-replicating in a way. In this vein, it becomes part of a series of plugins required in order to obtain various documentation volumes of interest.

such as:

 * https://github.com/UnionOfRAD/manual
 * https://github.com/UnionOfRAD/lithium
 * https://github.com/UnionOfRAD/framework

So the Lithium documentation plugin (Li3 Docs) is a tool for creating automatically browse-able documentation of your application's codebase. In addition to simple descriptions and tables of contents, Li3 Docs allows application and code to be embedded with metadata and testable code examples to provide richer and more comprehensive documentation.

### Documentation structure

For generating documentation, Li3 Docs relies on PHP documentation blocks, or _docblocks_. These docblocks can appear above classes, methods, properties, etc., and contain three things: a short description, a longer description (often including usage examples), and docblock _tags_, which are denoted by an `@` symbol, followed by a keyword. A typical docblock might look something like this:

	/**
	 * Contains an instance of the `Request` object with all the details of the HTTP request that
	 * was dispatched to the controller object. Any parameters captured in routing, such as
	 * controller or action name are accessible as properties of this object, i.e.
	 * `$this->request->controller` or `$this->request->action`.
	 *
	 * @see lithium\action\Request
	 * @var object
	 */
	public $request = null;

This docblock documents a class property, and contains a short description and two docblock tags. The tags to be used in a docblock vary by what is being documented. A property docblock should contain a `@var` tag that describes what type of value the property holds, while methods have a series of `@param` tags and a `@return` tag.

There are also general tags which can be added to any docblock. These include the `@see` tag, which allows you to link to another class, method or property, and the `@link` tag, which allows you to link to an arbitrary URL.

### Markdown syntax

Docblock descriptions are written in Markdown format, with a few conventions. Namely, any code references or identifiers should be enclosed in backticks. This includes namespaces, classes, variable names, and keywords like `true`, `false` and `null`. Extended code examples should be written enclosed in three sets of curly braces, i.e.: {&zwj;{{ // Code goes here }}}.

### Code embedding

In order to improve the testability of documented code examples, and to help ensure that code and documentation stay in sync, Li3 Docs supports a special syntax that allows code from class methods to be embedded inline inside Markdown text. Consider the following:

	{{{ embed:lithium\tests\cases\core\EnvironmentTest::testSetAndGetCurrentEnvironment(1-3) }‍}}

This will embed code from the `testSetAndGetCurrentEnvironment()` method of the `Environment` test case, from line 1 through line 3 as an inline code example in the Markdown text. This way, whenever the code changes, the tests change to match it, and the documentation stays up-to-date with what's in the test.

Finally, since explanations and descriptions of code can fall out of sync with examples presented, Li3 Docs can be configured with a storage backend which retains hash values which represent the embedded code examples. When the underlying code changes, the hash values will fall out-of-sync, and corresponding documentation can be reviewed for accuracy.

### Browsing

Once loaded into your application the plugin will enable browsing for all added libraries. In some cases it may be desired to disable browsing for certain libraries. In order to do so pass the `'index'` option in the second parameter when adding Li3 Docs:

	Libraries::add('li3_docs', array(
		'index' => array('lithium', 'li3_bot')
	));

By default, the url to view the docs is `'/docs'`. This can be customized with the `'url'` option in the second paramter when adding Li3 Docs:

	Libraries::add('li3_docs', array(
		'url' => '/documentation'
	));

Or if you wanted to have an app who's sole purpose is displaying docs, you could add all of your libraries and plugins to that app with `'bootstrap' => false` in the second parameter when adding the libraries with `Libraries::add()`. Then point the Li3 Docs url to the root of the app:

	Libraries::add('li3_docs', array(
		'url' => '/'
	));


### Searching

The plugin now features a symbol-based live search. Search for classes, methods and properties using the search bar near the top of the page. By default, any term entered into the box will trigger a search across all symbol types. You can refine your search by entering in specially formed queries:

* If the first letter in the query is upper-case, you will only get _classes_ in the results.
* If the query contains a $, only _properties_ will be shown in the results.
* If the query ends with or contains a parenthesis, you'll only be searching _methods_.

To update the search database, run the symbol harvesting task:

	$ li3 harvest
