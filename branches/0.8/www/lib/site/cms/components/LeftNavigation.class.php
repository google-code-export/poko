<?php

class site_cms_components_LeftNavigation extends poko_system_Component {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->sections = new Hash();
		$this->sectionsIsSeperator = new Hash();
		;
	}}
	public $header;
	public $content;
	public $footer;
	public $sections;
	public $sectionsIsSeperator;
	public function init() {
		;
		;
	}
	public function main() {
		;
		;
	}
	public function addSection($name, $isSeperator) {
		if($isSeperator === null) {
			$isSeperator = false;
			;
		}
		$this->sections->set($name, new HList());
		$this->sectionsIsSeperator->set($name, $isSeperator);
		;
	}
	public function addLink($section, $title, $link, $indents, $external, $extra) {
		if($external === null) {
			$external = false;
			;
		}
		if($indents === null) {
			$indents = 0;
			;
		}
		$indentsData = new _hx_array(array());
		$indentsData[0] = "";
		$indentsData[1] = "<img src=\"./res/cms/tree_kink.png\" />";
		$indentsData[2] = "&nbsp;<img src=\"./res/cms/tree_kink.png\" />";
		$indentsData[3] = "&nbsp;&nbsp;<img src=\"./res/cms/tree_kink.png\" />";
		$indentsData[4] = "&nbsp;&nbsp;&nbsp;<img src=\"./res/cms/tree_kink.png\" />";
		$ind = $indentsData[$indents];
		$this->sections->get($section)->add(_hx_anonymous(array("title" => $title, "link" => $link, "external" => $external, "indents" => $ind, "extra" => $extra)));
		unset($indentsData,$ind);
	}
	public function editTag($request) {
		return ("<a class=\"leftNavEdit\" href=\"?request=" . $request) . "\">[e]</a>";
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
	function __toString() { return 'site.cms.components.LeftNavigation'; }
}
