<?php

class poko_form_FormElement {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->active = true;
		$this->errors = new HList();
		$this->validators = new HList();
		$this->inited = false;
		$this->internal = false;
		;
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
	public $inited;
	public $internal;
	public function isValid() {
		$this->errors->clear();
		if($this->active === false) {
			return true;
			;
		}
		if(_hx_equal($this->value, "") && $this->required) {
			$this->errors->add(("<span class=\"formErrorsField\">" . (poko_form_FormElement_0($this))) . "</span> required.");
			return false;
			;
		}
		else {
			if(!_hx_equal($this->value, "")) {
				if(!$this->validators->isEmpty()) {
					$pass = true;
					if(null == $this->validators) throw new HException('null iterable');
					$»it = $this->validators->iterator();
					while($»it->hasNext()) {
					$validator = $»it->next();
					{
						if(!$validator->isValid($this->value)) {
							$pass = false;
							;
						}
						;
					}
					}
					if(!$pass) {
						return false;
						;
					}
					unset($pass);
				}
				return true;
				;
			}
			;
		}
		return true;
		;
	}
	public function checkValid() {
		_hx_equal($this->value, "");
		;
	}
	public function init() {
		$this->inited = true;
		;
	}
	public function addValidator($validator) {
		$this->validators->add($validator);
		;
	}
	public function bindEvent($event, $method, $params, $isMethodGlobal) {
		if($isMethodGlobal === null) {
			$isMethodGlobal = false;
			;
		}
		;
	}
	public function populate() {
		if(!$this->inited) {
			$this->init();
			;
		}
		$n = ($this->form->name . "_") . $this->name;
		$v = poko_Poko::$instance->params->get($n);
		if($v !== null) {
			$this->value = $v;
			;
		}
		unset($v,$n);
	}
	public function getErrors() {
		$this->isValid();
		if(null == $this->validators) throw new HException('null iterable');
		$»it = $this->validators->iterator();
		while($»it->hasNext()) {
		$val = $»it->next();
		if(null == $val->errors) throw new HException('null iterable');
		$»it2 = $val->errors->iterator();
		while($»it2->hasNext()) {
		$err = $»it2->next();
		$this->errors->add((("<span class=\"formErrorsField\">" . $this->label) . "</span> : ") . $err);
		}
		}
		return $this->errors;
		;
	}
	public function render() {
		if(!$this->inited) {
			$this->init();
			;
		}
		return $this->value;
		;
	}
	public function remove() {
		if($this->form !== null) {
			return $this->form->removeElement($this);
			;
		}
		return false;
		;
	}
	public function getPreview() {
		return ((("<tr><td>" . $this->getLabel()) . "</td><td>") . $this->render()) . "<td></tr>";
		;
	}
	public function getType() {
		return Std::string(Type::getClass($this));
		;
	}
	public function getLabelClasses() {
		$css = "";
		$requiredSet = false;
		if($this->required) {
			$css = $this->form->requiredClass;
			if($this->form->isSubmitted() && $this->required && _hx_equal($this->value, "")) {
				$css = $this->form->requiredErrorClass;
				$requiredSet = true;
				;
			}
			;
		}
		if(!$requiredSet && $this->form->isSubmitted() && !$this->isValid()) {
			$css = $this->form->invalidErrorClass;
			;
		}
		if($this->cssClass !== null) {
			$css .= poko_form_FormElement_1($this, $css, $requiredSet);
			;
		}
		return $css;
		unset($requiredSet,$css);
	}
	public function getLabel() {
		$n = ($this->form->name . "_") . $this->name;
		return (((((((("<label for=\"" . $n) . "\" class=\"") . $this->getLabelClasses()) . "\" id=\"") . $n) . "__Label\">") . $this->label) . (poko_form_FormElement_2($this, $n))) . "</label>";
		unset($n);
	}
	public function getClasses() {
		$css = poko_form_FormElement_3($this);
		if($this->required && $this->form->isSubmitted()) {
			if(_hx_equal($this->value, "")) {
				$css .= " " . $this->form->requiredErrorClass;
				;
			}
			if(!$this->isValid()) {
				$css .= " " . $this->form->invalidErrorClass;
				;
			}
			;
		}
		return trim($css);
		unset($css);
	}
	public function safeString($s) {
		return poko_form_FormElement_4($this, $s);
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
	function __toString() { return 'poko.form.FormElement'; }
}
;
function poko_form_FormElement_0(&$»this) {
if($»this->label !== null && $»this->label !== "") {
	return $»this->label;
	;
}
else {
	return $»this->name;
	;
}
}
function poko_form_FormElement_1(&$»this, &$css, &$requiredSet) {
if($css === "") {
	return $»this->cssClass;
	;
}
else {
	return " " . $»this->cssClass;
	;
}
}
function poko_form_FormElement_2(&$»this, &$n) {
if($»this->required) {
	return $»this->form->labelRequiredIndicator;
	;
}
}
function poko_form_FormElement_3(&$»this) {
if($»this->cssClass !== null) {
	return $»this->cssClass;
	;
}
else {
	return $»this->form->defaultClass;
	;
}
}
function poko_form_FormElement_4(&$»this, &$s) {
if($s === null) {
	return "";
	;
}
else {
	return _hx_explode("\"", StringTools::htmlEscape(Std::string($s)))->join("&quot;");
	;
}
}