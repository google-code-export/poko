<?php

class site_examples_TestPage extends site_examples_templates_DefaultTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $page;
	public $heading;
	public $content;
	public $image;
	public function main() {
		$this->page = site_cms_common_PageData::getPageByName("Test Page");
		$this->heading = $this->page->data->heading;
		$this->content = $this->page->data->content;
		$this->image = $this->page->data->image;
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
	function __toString() { return 'site.examples.TestPage'; }
}
