<?php

class poko_form_elements_Selectbox extends poko_form_FormElement {
	public function __construct($name, $label, $data, $selected, $required, $nullMessage) {
		if( !php_Boot::$skip_constructor ) {
		if($nullMessage === null) {
			$nullMessage = "- select -";
		}
		if($required === null) {
			$required = false;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->data = ($data !== null ? $data : new HList());
		$this->value = $selected;
		$this->required = $required;
		$this->nullMessage = $nullMessage;
		$this->onChange = "";
	}}
	public $data;
	public $nullMessage;
	public $onChange;
	public function render() {
		$s = "";
		$n = $this->form->name . "_" . $this->name;
		$s .= "\x0A<select name=\"" . $n . "\" id=\"" . $n . "\" " . $this->attributes . " onChange=\"" . $this->onChange . "\" />";
		if($this->nullMessage != "") {
			$s .= "<option value=\"\" " . ((Std::string($this->value) == "" ? "selected" : "")) . ">" . $this->nullMessage . "</option>";
		}
		if($this->data !== null) {
			$»it = $this->data->iterator();
			while($»it->hasNext()) {
			$row = $»it->next();
			{
				$s .= "<option value=\"" . Std::string($row->value) . "\" " . ((Std::string($row->value) == Std::string($this->value) ? "selected" : "")) . ">" . Std::string($row->key) . "</option>";
				;
			}
			}
		}
		$s .= "</select>";
		return $s;
	}
	public function addOption($keyVal) {
		$this->data->add($keyVal);
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
