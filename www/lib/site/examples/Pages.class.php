<?php

class site_examples_Pages extends site_examples_templates_DefaultTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $pages;
	public $pageNav;
	public $selectedPage;
	public function main() {
		$this->pages = site_cms_common_PageData::getPages();
		$this->pageNav = new site_examples_components_Navigation();
		$this->pageNav->selected = $this->app->params->get("page");
		if(null == $this->pages) throw new HException('null iterable');
		$»it = $this->pages->iterator();
		while($»it->hasNext()) {
		$page = $»it->next();
		{
			$this->pageNav->addLink($page->definition->name, $this->app->params->get("request"), _hx_anonymous(array("page" => $page->definition->name)));
			if(_hx_equal($page->name, $this->app->params->get("page"))) {
				$this->selectedPage = $page;
				;
			}
			;
		}
		}
		;
	}
	public function getData($element) {
		return Reflect::field($this->selectedPage->data, $element);
		;
	}
	public function trim($value, $length) {
		if(strlen($value) > $length) {
			return _hx_substr($value, 0, $length - 3) . "...";
			;
		}
		else {
			return $value;
			;
		}
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
	function __toString() { return 'site.examples.Pages'; }
}
