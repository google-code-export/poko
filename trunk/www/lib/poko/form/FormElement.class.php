<?php

class poko_form_FormElement {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->active = true;
		$this->errors = new HList();
		$this->validators = new HList();
	}}
	public $form;
	public $name;
	public $label;
	public $description;
	public $value;
	public $required;
	public $errors;
	public $attributes;
	public $active;
	public $validators;
	public $cssClass;
	public function isValid() {
		$this->errors->clear();
		if(_hx_equal($this->value, "") && $this->required) {
			$this->errors->add("please fill in: '" . $this->name . "'");
			return false;
		}
		else {
			if(!_hx_equal($this->value, "")) {
				if(!$this->validators->isEmpty()) {
					$pass = true;
					$»it = $this->validators->iterator();
					while($»it->hasNext()) {
					$validator = $»it->next();
					{
						if(!$validator->isValid($this->value)) {
							$pass = false;
						}
						;
					}
					}
					if(!$pass) {
						return false;
					}
				}
				return true;
			}
		}
		return true;
	}
	public function addValidator($validator) {
		$this->validators->add($validator);
	}
	public function bindEvent($event, $method, $params, $isMethodGlobal) {
		if($isMethodGlobal === null) {
			$isMethodGlobal = false;
		}
	}
	public function populate() {
		$n = $this->form->name . "_" . $this->name;
		$v = poko_Poko::$instance->params->get($n);
		if($v !== null) {
			$this->value = $v;
		}
	}
	public function getErrors() {
		$this->isValid();
		$»it = $this->validators->iterator();
		while($»it->hasNext()) {
		$val = $»it->next();
		$»it2 = $val->errors->iterator();
		while($»it2->hasNext()) {
		$err = $»it2->next();
		$this->errors->add($this->label . " : " . $err);
		}
		}
		return $this->errors;
	}
	public function render() {
		return $this->value;
	}
	public function getPreview() {
		return "<tr><td>" . $this->getLabel() . "</td><td>" . $this->render() . "<td></tr>";
	}
	public function getType() {
		return Std::string(Type::getClass($this));
	}
	public function getLabel() {
		$n = $this->form->name . "_" . $this->name;
		return "<label for=\"" . $n . "\" >" . $this->label . (($this->required ? "*" : null)) . "</label>";
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'poko.form.FormElement'; }
}
