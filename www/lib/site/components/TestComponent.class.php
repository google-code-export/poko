<?php

class site_components_TestComponent extends poko_system_Component {
	public function __construct($var2) {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->var2 = $var2;
		;
	}}
	public $var2;
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
	function __toString() { return 'site.components.TestComponent'; }
}
