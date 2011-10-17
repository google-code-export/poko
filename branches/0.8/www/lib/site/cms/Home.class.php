<?php

class site_cms_Home extends site_cms_templates_CmsTemplate {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public function main() {
		$this->navigation->pageHeading = "Home";
		;
	}
	function __toString() { return 'site.cms.Home'; }
}
