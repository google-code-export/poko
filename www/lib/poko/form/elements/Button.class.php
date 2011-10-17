<?php

class poko_form_elements_Button extends poko_form_FormElement {
	public function __construct($name, $label, $value, $type) {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->type = poko_form_elements_Button_0($this, $label, $name, $type, $value);
		;
	}}
	public $type;
	public function isValid() {
		return true;
		;
	}
	public function render() {
		$s = ((((((((((((((((("<button type=\"" . $this->type) . "\" class=\"") . $this->getClasses()) . "\" value=\"") . $this->value) . "\" ") . $this->attributes) . " name=\"") . $this->form->name) . "_") . $this->name) . "\" id=\"") . $this->form->name) . "_") . $this->name) . "\" >") . $this->label) . "</button>";
		return $s;
		unset($s);
	}
	public function toString() {
		return $this->render();
		;
	}
	public function getLabel() {
		$n = ($this->form->name . "_") . $this->name;
		return ("<label for=\"" . $n) . "\" ></label>";
		unset($n);
	}
	public function getPreview() {
		return ("<tr><td></td><td>" . $this->render()) . "<td></tr>";
		;
	}
	public function populate() {
		parent::populate();
		$n = ($this->form->name . "_") . $this->name;
		if(poko_Poko::$instance->params->exists($n)) {
			$this->form->submittedButtonName = $this->name;
			;
		}
		unset($n);
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
function poko_form_elements_Button_0(&$»this, &$label, &$name, &$type, &$value) {
if($type === null) {
	return poko_form_elements_ButtonType::$SUBMIT;
	;
}
else {
	return $type;
	;
}
}