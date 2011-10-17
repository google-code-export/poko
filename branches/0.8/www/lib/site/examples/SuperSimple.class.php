<?php

class site_examples_SuperSimple extends poko_controllers_Controller {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->myString = "Hello World";
		;
	}}
	public $myString;
	public function myStringBold() {
		return ("<b>" . $this->myString) . "</b>";
		;
	}
	public function init() {
		parent::init();
		haxe_Log::trace("SuperSimple: init", _hx_anonymous(array("fileName" => "SuperSimple.hx", "lineNumber" => 28, "className" => "site.examples.SuperSimple", "methodName" => "init")));
		;
	}
	public function main() {
		parent::main();
		haxe_Log::trace("SuperSimple: main", _hx_anonymous(array("fileName" => "SuperSimple.hx", "lineNumber" => 35, "className" => "site.examples.SuperSimple", "methodName" => "main")));
		;
	}
	public function post() {
		parent::post();
		haxe_Log::trace("SuperSimple: post", _hx_anonymous(array("fileName" => "SuperSimple.hx", "lineNumber" => 42, "className" => "site.examples.SuperSimple", "methodName" => "post")));
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
	function __toString() { return 'site.examples.SuperSimple'; }
}
