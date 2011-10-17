<?php

class site_cms_common_DefinitionElementMeta {
	public function __construct($name) {
		if( !php_Boot::$skip_constructor ) {
		$this->properties = _hx_anonymous(array());
		$this->name = $name;
		$this->showInList = true;
		$this->showInFiltering = false;
		$this->showInOrdering = false;
		;
	}}
	public $name;
	public $type;
	public $dbtype;
	public $label;
	public $properties;
	public $order;
	public $showInList;
	public $showInFiltering;
	public $showInOrdering;
	public function toString() {
		return ("DefinitionElementMeta: " . $this->name) . (", ShowInList: " . (site_cms_common_DefinitionElementMeta_0($this)));
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
	function __toString() { return $this->toString(); }
}
;
function site_cms_common_DefinitionElementMeta_0(&$»this) {
if($»this->showInList) {
	return "true";
	;
}
else {
	return "false";
	;
}
}