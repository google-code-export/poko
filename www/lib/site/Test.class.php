<?php

class site_Test extends site_layouts_TestLayout {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $test;
	public $comp;
	public $poo;
	public function init() {
		$this->poo = "sss";
		$this->test = "mmm";
		$this->comp = new site_components_TestComponent("test comp");
	}
	public function post() {
		;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.Test'; }
}
