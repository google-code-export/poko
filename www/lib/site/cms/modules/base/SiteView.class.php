<?php

class site_cms_modules_base_SiteView extends site_cms_modules_base_DatasetBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $jsBind;
	public function pre() {
		$this->head->js->add("js/cms/jquery-ui-1.7.2.custom.min.js");
		$this->head->css->add("css/cms/ui-lightness/jquery-ui-1.7.2.custom.css");
		$this->siteMode = true;
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.base.js.JsSiteView");
		parent::pre();
	}
	public function main() {
		parent::main();
		if(!$this->application->params->get("manage") || !$this->application->user->isAdmin()) {
			$str = "";
			if(poko_Application::$instance->user->isAdmin() || poko_Application::$instance->user->isSuper()) {
				$str .= poko_ViewContext::parse("site/cms/modules/base/blocks/siteView.mtt", null);
			}
			$this->setContentOutput($str);
		}
		else {
			if(_hx_equal($this->application->params->get("action"), "saveSiteView")) {
				$d = $this->application->params->get("siteViewData");
				$this->application->db->update("_settings", _hx_anonymous(array("value" => $d)), "`key` ='siteView'");
				$this->application->messages->addMessage("Menu updated.");
			}
		}
		$this->setupLeftNav();
		return;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.base.SiteView'; }
}
