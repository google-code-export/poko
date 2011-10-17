<?php

class poko_form_elements_Input extends poko_form_FormElement {
	public function __construct($name, $label, $value, $required, $validators, $attributes) {
		if( !php_Boot::$skip_constructor ) {
		if($attributes === null) {
			$attributes = "";
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
		$this->attributes = $attributes;
		$this->password = false;
		$this->showLabelAsDefaultValue = false;
		$this->useSizeValues = false;
		$this->printRequired = false;
		$this->width = 180;
		;
	}}
	public $password;
	public $width;
	public $showLabelAsDefaultValue;
	public $useSizeValues;
	public $printRequired;
	public $formatter;
	public function render() {
		$n = ($this->form->name . "_") . $this->name;
		$tType = poko_form_elements_Input_0($this, $n);
		if($this->showLabelAsDefaultValue && _hx_equal($this->value, $this->label)) {
			$this->addValidator(new poko_form_validators_BoolValidator(false, "Not valid"));
			;
		}
		if((_hx_field($this, "value") === null || _hx_equal($this->value, "")) && $this->showLabelAsDefaultValue) {
			$this->value = $this->label;
			;
		}
		$style = poko_form_elements_Input_1($this, $n, $tType);
		return (((((((((((((("<input " . $style) . " class=\"") . $this->getClasses()) . "\" type=\"") . $tType) . "\" name=\"") . $n) . "\" id=\"") . $n) . "\" value=\"") . poko_form_elements_Input_2($this, $n, $style, $tType)) . "\"  ") . $this->attributes) . " />") . (poko_form_elements_Input_3($this, $n, $style, $tType));
		unset($tType,$style,$n);
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
;
function poko_form_elements_Input_0(&$»this, &$n) {
if($»this->password) {
	return "password";
	;
}
else {
	return "text";
	;
}
}
function poko_form_elements_Input_1(&$»this, &$n, &$tType) {
if($»this->useSizeValues) {
	return ("style=\"width:" . $»this->width) . "px\"";
	;
}
else {
	return "";
	;
}
}
function poko_form_elements_Input_2(&$»this, &$n, &$style, &$tType) {
{
	$s = $»this->value;
	if($s === null) {
		return "";
		;
	}
	else {
		return _hx_explode("\"", StringTools::htmlEscape(Std::string($s)))->join("&quot;");
		;
	}
	unset($s);
}
}
function poko_form_elements_Input_3(&$»this, &$n, &$style, &$tType) {
if($»this->required && $»this->form->isSubmitted() && $»this->printRequired) {
	return " required";
	;
}
}