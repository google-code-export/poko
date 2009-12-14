<?php

class site_cms_templates_CmsTemplate extends site_cms_CmsController {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $navigation;
	public $leftNavigation;
	public function init() {
		parent::init();
		$this->navigation = new site_cms_components_Navigation();
		$this->leftNavigation = new site_cms_components_LeftNavigation();
		$this->authenticate = true;
		$this->settings = new Hash();
		$this->head->css->add("css/fixes/reset.css");
		$this->head->css->add("css/fixes/fonts.css");
		$this->head->css->add("css/cms/cms.css");
		$this->head->css->add("?request=cms.services.CmsCss");
		$this->head->js->add("js/cms/jquery-1.3.2.min.js");
		$this->head->js->add("js/cms/jquery.domec.js");
		$this->head->js->add("js/main.js");
		$request = $this->app->getDb()->request("SELECT * FROM _settings");
		$»it = $request->iterator();
		while($»it->hasNext()) {
		$i = $»it->next();
		{
			$this->settings->set($i->key, $i->value);
			;
		}
		}
		$this->head->title = $this->settings->get("cmsTitle");
		$this->userName = $this->user->name;
	}
	public function post() {
		parent::post();
		$this->messages->clearAll();
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.templates.CmsTemplate'; }
}
