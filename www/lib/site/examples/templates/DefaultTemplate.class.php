<?php

class site_examples_templates_DefaultTemplate extends poko_controllers_HtmlController {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $navigation;
	public $scripts;
	public function init() {
		parent::init();
		$this->head->title = "haXe poko examples site";
		$this->scripts = new poko_utils_html_ScriptList();
		$this->scripts->addExternal(poko_utils_html_ScriptType::$css, "css/normal.css", null, null, null);
		$this->scripts->addExternal(poko_utils_html_ScriptType::$css, "css/example-site.css", null, null, null);
		$this->scripts->addExternal(poko_utils_html_ScriptType::$css, "css/cms/cms.css", null, null, null);
		$this->scripts->addExternal(poko_utils_html_ScriptType::$js, "js/jquery.js", null, null, null);
		$this->navigation = new site_examples_components_Navigation();
		$this->navigation->addLink("Test Page", "examples.TestPage", null);
		$this->navigation->addLink("Super Simple", "examples.SuperSimple", null);
		$this->navigation->addLink("Pages", "examples.Pages", null);
		$this->navigation->addLink("Basic data", "examples.Basic", null);
		$this->navigation->addLink("Forms", "examples.Forms", null);
		$this->navigation->addLink("Dates", "examples.Dates", null);
		$this->navigation->addLink("Locations", "examples.Locations", null);
		$this->navigation->addLink("Image Processing", "examples.ImageProcessing", null);
		$this->navigation->addLink("Complex Data", "examples.ComplexData", null);
		$this->navigation->addLink("Using Poko CMS", "examples.UsingPokoCMS", null);
		$this->navigation->setSelectedByRequest($this->app->params->get("request"));
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
	function __toString() { return 'site.examples.templates.DefaultTemplate'; }
}
