<?php

class site_cms_templates_CmsPopup extends site_cms_CmsController {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->authenticate = true;
		;
	}}
	public function init() {
		parent::init();
		$this->head->css->add("css/fixes/reset.css");
		$this->head->css->add("css/fixes/fonts.css");
		$this->head->css->add("css/cms/cms.css");
		$this->head->css->add("?request=cms.services.CmsCss");
		$this->head->css->add("css/cms/miniView.css");
		$this->head->js->add("js/cms/jquery-1.3.2.min.js");
		$this->head->js->add("js/cms/jquery.domec.js");
		$this->head->js->add("js/main.js");
		;
	}
	public function post() {
		$this->messages->clearAll();
		;
	}
	function __toString() { return 'site.cms.templates.CmsPopup'; }
}
