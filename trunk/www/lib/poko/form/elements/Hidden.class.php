<?php

class poko_form_elements_Hidden extends poko_form_FormElement {
	public function __construct($name, $value, $required, $display, $attributes) {
		if( !php_Boot::$skip_constructor ) {
		if($attributes === null) {
			$attributes = "";
		}
		if($display === null) {
			$display = false;
		}
		if($required === null) {
			$required = false;
		}
		parent::__construct();
		$this->name = $name;
		$this->value = $value;
		$this->required = $required;
		$this->display = $display;
		$this->attributes = $attributes;
	}}
	public $display;
	public function render() {
		$n = $this->form->name . "_" . $this->name;
		return "<input type=\"hidden\" name=\"" . $n . "\" id=\"" . $n . "\" value=\"" . $this->value . "\"/>";
	}
	public function getPreview() {
		return $this->render();
	}
	public function toString() {
		return $this->render();
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return $this->toString(); }
}
