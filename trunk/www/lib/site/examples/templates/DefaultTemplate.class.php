<?php

class site_examples_templates_DefaultTemplate extends poko_controllers_HtmlController {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $navigation;
	public function init() {
		parent::init();
		$this->head->title = "haXe poko examples site";
		$this->head->css->add("css/reset.css");
		$this->head->css->add("css/fonts.css");
		$this->head->css->add("css/normal.css");
		$this->head->css->add("css/cms/cms.css");
		$this->navigation = new site_examples_components_Navigation();
		$this->navigation->addLink("Test Page", "examples.TestPage", null);
		$this->navigation->addLink("Pages", "examples.Pages", null);
		$this->navigation->addLink("Basic data", "examples.Basic", null);
		$this->navigation->addLink("Forms", "examples.Forms", null);
		$this->navigation->addLink("Image Processing", "examples.ImageProcessing", null);
		$this->navigation->addLink("Complex Data", "examples.ComplexData", null);
		$this->navigation->setSelectedByRequest($this->app->params->get("request"));
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.examples.templates.DefaultTemplate'; }
}
