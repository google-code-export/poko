<?php

class poko_form_elements_TextArea extends poko_form_elements_Input {
	public function __construct($name, $label, $value, $required, $validators, $attributes) {
		if( !php_Boot::$skip_constructor ) {
		if($required === null) {
			$required = false;
		}
		parent::__construct($name,$label,$value,$required,$validators,$attributes);
		$this->width = 300;
		$this->height = 50;
	}}
	public $height;
	public function render() {
		$n = $this->form->name . "_" . $this->name;
		if($this->showLabelAsDefaultValue && _hx_equal($this->value, $this->label)) {
			$this->addValidator(new poko_form_validators_BoolValidator(false, "Not valid"));
		}
		if((_hx_field($this, "value") === null || _hx_equal($this->value, "")) && $this->showLabelAsDefaultValue) {
			$this->value = $this->label;
		}
		$s = "";
		if($this->required && $this->form->isSubmitted() && $this->printRequired) {
			$s .= "required<br />";
		}
		$style = ($this->useSizeValues ? "style=\"width:" . $this->width . "px; height:" . $this->height . "px;\"" : "");
		$s .= "<textarea " . $style . " name=\"" . $n . "\" id=\"" . $n . "\" " . $this->attributes . " >" . $this->value . "</textarea>";
		return $s;
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
