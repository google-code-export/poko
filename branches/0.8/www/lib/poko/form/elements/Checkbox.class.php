<?php

class poko_form_elements_Checkbox extends poko_form_FormElement {
	public function __construct($name, $label, $checked, $required, $attibutes) { if( !php_Boot::$skip_constructor ) {
		if($attibutes === null) {
			$attibutes = "";
			;
		}
		if($required === null) {
			$required = false;
			;
		}
		if($checked === null) {
			$checked = false;
			;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->value = poko_form_elements_Checkbox_0($this, $attibutes, $checked, $label, $name, $required);
		$this->required = $required;
		$this->attributes = $attibutes;
		;
	}}
	public function render() {
		$n = ($this->form->name . "_") . $this->name;
		$checkedStr = poko_form_elements_Checkbox_1($this, $n);
		return ((((((((("<input type=\"checkbox\" id=\"" . $n) . "\" name=\"") . $n) . "\" class=\"") . $this->getClasses()) . "\" value=\"") . $this->value) . "\" ") . $checkedStr) . " />";
		unset($n,$checkedStr);
	}
	public function toString() {
		return $this->render();
		;
	}
	public function populate() {
		$n = ($this->form->name . "_") . $this->name;
		$v = poko_form_elements_Checkbox_2($this, $n);
		if($this->form->isSubmitted()) {
			if($v !== null) {
				$this->value = $v;
				;
			}
			;
		}
		unset($v,$n);
	}
	public function isValid() {
		$this->errors->clear();
		if($this->required && _hx_equal($this->value, "0")) {
			$this->errors->add(("Please check '" . (poko_form_elements_Checkbox_3($this))) . "'");
			return false;
			;
		}
		return true;
		;
	}
	function __toString() { return $this->toString(); }
}
;
function poko_form_elements_Checkbox_0(&$퍁his, &$attibutes, &$checked, &$label, &$name, &$required) {
if($checked) {
	return "1";
	;
}
else {
	return "0";
	;
}
}
function poko_form_elements_Checkbox_1(&$퍁his, &$n) {
if(_hx_equal($퍁his->value, "1")) {
	return "checked";
	;
}
else {
	return "";
	;
}
}
function poko_form_elements_Checkbox_2(&$퍁his, &$n) {
if(poko_Poko::$instance->params->exists($n)) {
	return "1";
	;
}
else {
	return "0";
	;
}
}
function poko_form_elements_Checkbox_3(&$퍁his) {
if($퍁his->label !== null && $퍁his->label !== "") {
	return $퍁his->label;
	;
}
else {
	return $퍁his->name;
	;
}
}