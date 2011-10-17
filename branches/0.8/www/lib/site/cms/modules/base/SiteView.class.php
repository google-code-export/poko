<?php

class site_cms_modules_base_SiteView extends site_cms_modules_base_DatasetBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $jsBind;
	public function init() {
		parent::init();
		$this->head->js->add("js/cms/jquery-ui-1.7.2.custom.min.js");
		$this->head->css->add("css/cms/ui-lightness/jquery-ui-1.7.2.custom.css");
		$this->siteMode = true;
		$this->navigation->setSelected("SiteView");
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.base.js.JsSiteView");
		;
	}
	public function main() {
		parent::main();
		if(!$this->app->params->get("manage") || !$this->user->isAdmin()) {
			$str = "";
			if($this->user->isAdmin() || $this->user->isSuper()) {
				$str .= poko_views_renderers_Templo::parse("cms/modules/base/blocks/siteView.mtt", null);
				;
			}
			$this->setContentOutput($str);
			unset($str);
		}
		else {
			if(_hx_equal($this->app->params->get("action"), "saveSiteView")) {
				$d = $this->app->params->get("siteViewData");
				$this->app->getDb()->update("_settings", _hx_anonymous(array("value" => $d)), "`key` ='siteView'");
				$this->messages->addMessage("Menu updated.");
				unset($d);
			}
			;
		}
		$this->setupLeftNav();
		return;
		;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else if('toString' == $m)
			return $this->__toString();
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.base.SiteView'; }
}
