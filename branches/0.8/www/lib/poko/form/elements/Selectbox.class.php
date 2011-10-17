<?php

class poko_form_elements_Selectbox extends poko_form_FormElement {
	public function __construct($name, $label, $data, $selected, $required, $nullMessage, $attributes) {
		if( !php_Boot::$skip_constructor ) {
		if($attributes === null) {
			$attributes = "";
			;
		}
		if($nullMessage === null) {
			$nullMessage = "- select -";
			;
		}
		if($required === null) {
			$required = false;
			;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->data = poko_form_elements_Selectbox_0($this, $attributes, $data, $label, $name, $nullMessage, $required, $selected);
		$this->value = $selected;
		$this->required = $required;
		$this->nullMessage = $nullMessage;
		$this->attributes = $attributes;
		$this->size = 1;
		$this->multiple = false;
		$this->onChange = "";
		;
	}}
	public $data;
	public $nullMessage;
	public $onChange;
	public $size;
	public $multiple;
	public function render() {
		$s = "";
		$n = ($this->form->name . "_") . $this->name;
		$s .= ((((((((((((("\x0A<select name=\"" . $n) . "\" id=\"") . $n) . "\" ") . $this->attributes) . " class=\"") . $this->getClasses()) . "\" onChange=\"") . $this->onChange) . "\" size=\"") . $this->size) . "\" ") . (poko_form_elements_Selectbox_1($this, $n, $s))) . "/>";
		if($this->nullMessage !== "") {
			$s .= ((("<option value=\"\" " . (poko_form_elements_Selectbox_2($this, $n, $s))) . ">") . $this->nullMessage) . "</option>";
			;
		}
		if($this->data !== null) {
			if(null == $this->data) throw new HException('null iterable');
			$»it = $this->data->iterator();
			while($»it->hasNext()) {
			$row = $»it->next();
			{
				$s .= ((((("<option value=\"" . Std::string($row->key)) . "\" ") . (poko_form_elements_Selectbox_3($this, $n, $row, $s))) . ">") . Std::string($row->value)) . "</option>";
				;
			}
			}
			;
		}
		$s .= "</select>";
		return $s;
		unset($s,$n);
	}
	public function selectFirst() {
		$this->value = $this->data->first()->key;
		;
	}
	public function add($key, $value) {
		$this->data->add(_hx_anonymous(array("key" => $key, "value" => $value)));
		;
	}
	public function addOption($keyVal) {
		$this->data->add($keyVal);
		;
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
function poko_form_elements_Selectbox_0(&$»this, &$attributes, &$data, &$label, &$name, &$nullMessage, &$required, &$selected) {
if($data !== null) {
	return $data;
	;
}
else {
	return new HList();
	;
}
}
function poko_form_elements_Selectbox_1(&$»this, &$n, &$s) {
if($»this->multiple) {
	return "multiple";
	;
}
else {
	return "";
	;
}
}
function poko_form_elements_Selectbox_2(&$»this, &$n, &$s) {
if(Std::string($»this->value) === "") {
	return "selected";
	;
}
else {
	return "";
	;
}
}
function poko_form_elements_Selectbox_3(&$»this, &$n, &$row, &$s) {
if(Std::string($row->key) === Std::string($»this->value)) {
	return "selected";
	;
}
else {
	return "";
	;
}
}