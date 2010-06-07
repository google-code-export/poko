<?php

class site_cms_modules_base_UsersBase extends site_cms_templates_CmsTemplate {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public function main() {
		parent::main();
		$this->navigation->setSelected("Users");
		$this->setupLeftNav();
	}
	public function setupLeftNav() {
		$this->leftNavigation->addSection("Users", null);
		$this->leftNavigation->addLink("Users", "List Users", "cms.modules.base.Users&action=list", null, null);
		$this->leftNavigation->addLink("Users", "Add User", "cms.modules.base.User&action=add", null, null);
		if($this->application->user->isSuper()) {
			$this->leftNavigation->addSection("Groups", null);
			$this->leftNavigation->addLink("Groups", "List Groups", "cms.modules.base.Users_Groups&action=list", null, null);
			$this->leftNavigation->addLink("Groups", "Add Group", "cms.modules.base.Users_Group&action=add", null, null);
		}
	}
	function __toString() { return 'site.cms.modules.base.UsersBase'; }
}
