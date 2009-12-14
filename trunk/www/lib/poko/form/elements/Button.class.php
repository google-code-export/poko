<?php

class poko_form_elements_Button extends poko_form_FormElement {
	public function __construct($name, $label, $value, $type) {
		if( !php_Boot::$skip_constructor ) {
		if($value === null) {
			$value = "Submit";
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->type = (($type === null) ? poko_form_elements_ButtonType::$SUBMIT : $type);
	}}
	public $type;
	public function isValid() {
		return true;
	}
	public function render() {
		return "<button type=\"" . $this->type . "\" name=\"" . $this->form->name . "_" . $this->name . "\" id=\"" . $this->form->name . "_" . $this->name . "\" value=\"" . $this->value . "\" " . $this->attributes . " >" . $this->label . "</button>";
	}
	public function toString() {
		return $this->render();
	}
	public function getLabel() {
		$n = $this->form->name . "_" . $this->name;
		return "<label for=\"" . $n . "\" ></label>";
	}
	public function getPreview() {
		return "<tr><td></td><td>" . $this->render() . "<td></tr>";
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
