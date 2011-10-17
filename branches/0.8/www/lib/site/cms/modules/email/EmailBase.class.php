<?php

class site_cms_modules_email_EmailBase extends site_cms_templates_CmsTemplate {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public function setupLeftNav() {
		$this->leftNavigation->addSection("Email", null);
		$this->leftNavigation->addLink("Email", "Create & Send", "cms.modules.email.Index", null, null, null);
		if($this->user->isAdmin()) {
			$this->leftNavigation->addLink("Email", "Settings", "cms.modules.email.Settings", null, null, null);
			;
		}
		;
	}
	public function loadSettings() {
		$data = null;
		if($this->settings->exists("emailSettings")) {
			$data = $this->settings->get("emailSettings");
			;
		}
		else {
			$data = "";
			$this->app->getDb()->insert("_settings", _hx_anonymous(array("key" => "emailSettings", "value" => $data)));
			;
		}
		if($data !== null && !_hx_equal($data, "")) {
			return haxe_Unserializer::run($data);
			;
		}
		return _hx_anonymous(array("userTable" => null, "emailField" => null, "nameField" => null, "idField" => null));
		unset($data);
	}
	public function saveSettings($s) {
		$data = haxe_Serializer::run($s);
		$this->app->getDb()->update("_settings", _hx_anonymous(array("value" => $data)), "`key` = 'emailSettings'");
		unset($data);
	}
	function __toString() { return 'site.cms.modules.email.EmailBase'; }
}
