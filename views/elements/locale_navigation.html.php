<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of Rad, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\core\Environment;

?>
<div id="locale-navigation">
	<ul>
		<?php foreach (Environment::get('locales') as $locale => $name): ?>
			<li><?=$this->html->link($name, compact('locale') + $this->_request->params); ?></li>
		<?php endforeach; ?>
	</ul>
</div>