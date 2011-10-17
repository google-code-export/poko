<?php

class site_Index extends poko_controllers_HtmlController {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	function __toString() { return 'site.Index'; }
}
