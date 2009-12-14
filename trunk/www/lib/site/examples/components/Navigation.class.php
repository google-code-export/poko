<?php

class site_examples_components_Navigation extends poko_system_Component {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->selected = "";
		$this->navItems = new HList();
	}}
	public $selected;
	public $navItems;
	public function main() {
		;
	}
	public function setSelectedByRequest($req) {
		$»it = $this->navItems->iterator();
		while($»it->hasNext()) {
		$item = $»it->next();
		{
			if(_hx_equal($item->request, $req)) {
				$this->selected = $item->label;
				break;
			}
			;
		}
		}
	}
	public function addLink($label, $request, $params) {
		if($params === null) {
			$params = _hx_anonymous(array());
		}
		$url = "?request=" . $request;
		{
			$_g = 0; $_g1 = Reflect::fields($params);
			while($_g < $_g1->length) {
				$param = $_g1[$_g];
				++$_g;
				$url .= "&" . $param . "=" . Reflect::field($params, $param);
				unset($param);
			}
		}
		$this->navItems->add(_hx_anonymous(array("label" => $label, "request" => $request, "url" => $url)));
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.examples.components.Navigation'; }
}
