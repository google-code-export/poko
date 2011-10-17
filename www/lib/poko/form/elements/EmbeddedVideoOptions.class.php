<?php

class poko_form_elements_EmbeddedVideoOptions extends poko_form_FormElement {
	public function __construct($name, $label, $service) {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->service = $service;
		;
	}}
	public $service;
	public $vimeo;
	public function render() {
		$n = ($this->form->name . "_") . $this->name;
		if($this->service == poko_form_elements_EmbeddedVideoService::$vimeo) {
			$color = new poko_form_elements_Input($n . "Color", "Color", "Blue", null, null, null);
			unset($color);
		}
		return null;
		unset($n);
	}
	public function toString() {
		return $this->render();
		;
	}
	public function populate() {
		;
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
