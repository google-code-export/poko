<?php

class site_cms_modules_base_helper_MenuDef {
	public function __construct($headings, $items) {
		if( !php_Boot::$skip_constructor ) {
		$this->headings = ($headings !== null ? $headings : new _hx_array(array()));
		$this->items = ($items !== null ? $items : new _hx_array(array()));
		$this->numberOfSeperators = 0;
	}}
	public $headings;
	public $items;
	public $numberOfSeperators;
	public function addHeading($name) {
		$this->headings->push(_hx_anonymous(array("name" => $name, "isSeperator" => false)));
	}
	public function addSeperator() {
		$this->numberOfSeperators++;
		$this->headings->push(_hx_anonymous(array("name" => "__sep" . $this->numberOfSeperators . "__", "isSeperator" => true)));
	}
	public function addItem($id, $type, $name, $heading, $indent, $listChildren, $linkChild) {
		if($indent === null) {
			$indent = 0;
		}
		$this->items->push(_hx_anonymous(array("id" => $id, "type" => $type, "name" => $name, "heading" => $heading, "indent" => $indent, "listChildren" => $listChildren, "linkChild" => $linkChild)));
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.base.helper.MenuDef'; }
}
