# li3_docs

**ATTENTION:** li3_docs is a Lithium plugin, NOT a Lithium app. Furthermore, by itself it is a VIEWER ONLY and contains no actual documentation other than its own.

Once installed in your existing application, however, it generates documentation from your app's docblocks in real-time, which is all accessible from http://yourapp.tld/docs/. Not only that, but it will generate documentation for your plugins, too. Including itself; so it is self-replicating in a way. In this vain, it becomes part of a series of plugins required in order to obtain various documentation volumes of interest.

such as:

 * https://github.com/UnionOfRAD/manual
 * https://github.com/UnionOfRAD/lithium
 * https://github.com/UnionOfRAD/framework

**KNOWN BUGS:**

 1. For reasons known only to _nateabele_, `$form->create()` on `/users/login` will fail when li3_docs and/or g11n are enabled. A temporary workaround is to add the following route if you need that page to work in your app:

```php
Router::connect('/users/login', array('controller' => 'users', 'action' => 'login', 'locale' => 'en_US'));
```

Please see README.wiki for all other details.
