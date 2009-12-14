<?php

class site_examples_Index extends site_examples_templates_DefaultTemplate {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public function main() {
		parent::main();
		haxe_Log::trace($this->layoutView->template, _hx_anonymous(array("fileName" => "Index.hx", "lineNumber" => 42, "className" => "site.examples.Index", "methodName" => "main")));
	}
	function __toString() { return 'site.examples.Index'; }
}
