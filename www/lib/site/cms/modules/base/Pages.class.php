<?php

class site_cms_modules_base_Pages extends site_cms_modules_base_PageBase {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public function main() {
		parent::main();
		if(!$this->app->params->get("manage")) {
			$str = "";
			if($this->user->isAdmin() || $this->user->isSuper()) {
				$str .= poko_views_renderers_Templo::parse("cms/modules/base/blocks/pages.mtt", null);
			}
			$this->setContentOutput($str);
			$this->setupLeftNav();
			return;
		}
	}
	function __toString() { return 'site.cms.modules.base.Pages'; }
}
