<?php

class site_examples_Index extends site_examples_templates_DefaultTemplate {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public function main() {
		parent::main();
		;
	}
	function __toString() { return 'site.examples.Index'; }
}
