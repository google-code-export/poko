<?php

class site_cms_modules_base_Pages extends site_cms_modules_base_PageBase {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public function pre() {
		;
	}
	public function main() {
		parent::main();
		if(!$this->application->params->get("manage")) {
			$str = "";
			if(poko_Application::$instance->user->isAdmin() || poko_Application::$instance->user->isSuper()) {
				$str .= poko_ViewContext::parse("site/cms/modules/base/blocks/pages.mtt", null);
			}
			$this->setContentOutput($str);
			$this->setupLeftNav();
			return;
		}
	}
	function __toString() { return 'site.cms.modules.base.Pages'; }
}
