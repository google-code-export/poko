<?php

class site_examples_Basic extends site_examples_templates_DefaultTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $products;
	public function main() {
		$this->products = $this->app->getDb()->request("SELECT * FROM `example_projects` WHERE `visible`=1");
	}
	public function trim($value, $length) {
		if(strlen($value) > $length) {
			return _hx_substr($value, 0, $length - 3) . "...";
		}
		else {
			return $value;
		}
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.examples.Basic'; }
}
