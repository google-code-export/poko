<?php

class poko_form_elements_DateSelector extends poko_form_FormElement {
	public function __construct($name, $label, $value, $required, $validators, $attibutes) {
		if( !php_Boot::$skip_constructor ) {
		if($attibutes === null) {
			$attibutes = "";
		}
		if($required === null) {
			$required = false;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->required = $required;
		$this->attributes = $attibutes;
		$this->maxOffset = null;
		$this->minOffset = null;
	}}
	public $maxOffset;
	public $minOffset;
	public function render() {
		$n = $this->form->name . "_" . $this->name;
		$sb = new StringBuf();
		$s = new StringBuf();
		$s->b .= "<input type=\"text\" name=\"" . $n . "\" id=\"" . $n . "\" value=\"" . $this->value . "\" /> \x0A";
		$s->b .= "<script type=\"text/javascript\">\x09\x09\x09\x0A";
		$s->b .= "\x09\x09\$(function() {\x09\x09\x09\x09\x09\x09\x09\x0A";
		$maxOffsetStr = ($this->minOffset !== null ? ", minDate: '-" . $this->minOffset . "m'" : "");
		$minOffsetStr = ($this->maxOffset !== null ? ", maxDate: '+" . $this->maxOffset . "m'" : "");
		$s->b .= "\x09\x09\x09\$(\"#" . $n . "\").datepicker({ dateFormat: 'yy-mm-dd' " . $minOffsetStr . $maxOffsetStr . " });\x09\x09\x0A";
		$s->b .= "\x09\x09}); \x09\x09\x09\x09\x09\x09\x09\x09\x09\x0A";
		$s->b .= "</script> \x09\x09\x09\x09\x09\x09\x09\x09\x09\x0A";
		return $s->b;
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
