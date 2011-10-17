<?php

class poko_form_elements_Readonly extends poko_form_FormElement {
	public function __construct($name, $label, $value, $required, $display, $attributes) {
		if( !php_Boot::$skip_constructor ) {
		if($attributes === null) {
			$attributes = "";
			;
		}
		if($display === null) {
			$display = false;
			;
		}
		if($required === null) {
			$required = false;
			;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->required = $required;
		$this->display = $display;
		$this->attributes = $attributes;
		;
	}}
	public $display;
	public function render() {
		$n = ($this->form->name . "_") . $this->name;
		$str = new StringBuf();
		$str->b .= ((((("<input type=\"hidden\" name=\"" . $n) . "\" id=\"") . $n) . "\" value=\"") . $this->value) . "\"/>";
		$str->b .= $this->value;
		return $str->b;
		unset($str,$n);
	}
	public function toString() {
		return $this->render();
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
