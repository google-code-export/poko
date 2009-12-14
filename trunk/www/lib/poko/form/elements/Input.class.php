<?php

class poko_form_elements_Input extends poko_form_FormElement {
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
		$this->password = false;
		$this->showLabelAsDefaultValue = false;
		$this->useSizeValues = false;
		$this->printRequired = true;
		$this->width = 180;
	}}
	public $password;
	public $width;
	public $showLabelAsDefaultValue;
	public $useSizeValues;
	public $printRequired;
	public $formatter;
	public function render() {
		$n = $this->form->name . "_" . $this->name;
		$tType = ($this->password ? "password" : "text");
		if($this->showLabelAsDefaultValue && _hx_equal($this->value, $this->label)) {
			$this->addValidator(new poko_form_validators_BoolValidator(false, "Not valid"));
		}
		if((_hx_field($this, "value") === null || _hx_equal($this->value, "")) && $this->showLabelAsDefaultValue) {
			$this->value = $this->label;
		}
		$style = ($this->useSizeValues ? "style=\"width:" . $this->width . "px\"" : "");
		return "<input " . $style . " type=\"" . $tType . "\" name=\"" . $n . "\" id=\"" . $n . "\" value=\"" . $this->value . "\" />" . (($this->required && $this->form->isSubmitted() && $this->printRequired ? " required" : null));
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
