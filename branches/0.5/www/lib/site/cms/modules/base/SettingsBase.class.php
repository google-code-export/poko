<?php

class site_cms_modules_base_SettingsBase extends site_cms_templates_CmsTemplate {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public function pre() {
		$this->navigation->setSelected("Settings");
		$this->setupLeftNav();
	}
	public function setupLeftNav() {
		parent::main();
		$this->leftNavigation->addSection("Settings", null);
		$this->leftNavigation->addLink("Settings", "Main", "cms.modules.base.Settings&section=main", null, null);
		$this->leftNavigation->addLink("Settings", "Theme", "cms.modules.base.SettingsTheme", null, null);
	}
	function __toString() { return 'site.cms.modules.base.SettingsBase'; }
}
