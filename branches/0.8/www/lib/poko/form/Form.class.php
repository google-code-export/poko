<?php

class poko_form_Form {
	public function __construct($name, $action, $method) {
		if( !php_Boot::$skip_constructor ) {
		$this->requiredClass = "formRequired";
		$this->requiredErrorClass = "formRequiredError";
		$this->invalidErrorClass = "formInvalidError";
		$this->labelRequiredIndicator = " *";
		$this->forcePopulate = false;
		$this->id = $this->name = $name;
		$this->action = $action;
		$this->method = poko_form_Form_0($this, $action, $method, $name);
		$this->elements = new HList();
		$this->extraErrors = new HList();
		$this->fieldsets = new Hash();
		$this->addFieldset("__default", new poko_form_FieldSet("__default", "Default", false));
		$this->wymEditorCount = 0;
		$this->submittedButtonName = null;
		;
	}}
	public $id;
	public $name;
	public $action;
	public $method;
	public $elements;
	public $fieldsets;
	public $forcePopulate;
	public $submitButton;
	public $extraErrors;
	public $requiredClass;
	public $requiredErrorClass;
	public $invalidErrorClass;
	public $labelRequiredIndicator;
	public $defaultClass;
	public $submittedButtonName;
	public $wymEditorCount;
	public function addElement($element, $fieldSetKey) {
		if($fieldSetKey === null) {
			$fieldSetKey = "__default";
			;
		}
		$element->form = $this;
		$this->elements->add($element);
		if($fieldSetKey !== null) {
			if(!$this->fieldsets->exists($fieldSetKey)) {
				throw new HException(((("No fieldset '" . $fieldSetKey) . "' exists in '") . $this->name) . "' form.");
				;
			}
			$this->fieldsets->get($fieldSetKey)->elements->add($element);
			;
		}
		if(Std::is($element, _hx_qtype("poko.form.elements.RichtextWym"))) {
			$this->wymEditorCount++;
			;
		}
		return $element;
		;
	}
	public function removeElement($element) {
		if($this->elements->remove($element)) {
			$element->form = null;
			if(null == $this->fieldsets) throw new HException('null iterable');
			$»it = $this->fieldsets->iterator();
			while($»it->hasNext()) {
			$fs = $»it->next();
			{
				$fs->elements->remove($element);
				;
			}
			}
			if(Std::is($element, _hx_qtype("poko.form.elements.RichtextWym"))) {
				$this->wymEditorCount--;
				;
			}
			return true;
			;
		}
		return false;
		;
	}
	public function setSubmitButton($el) {
		$this->submitButton = $el;
		$this->submitButton->form = $this;
		return $el;
		;
	}
	public function addFieldset($fieldSetKey, $fieldSet) {
		$fieldSet->form = $this;
		$this->fieldsets->set($fieldSetKey, $fieldSet);
		;
	}
	public function getFieldsets() {
		return $this->fieldsets;
		;
	}
	public function getLabel($elementName) {
		return $this->getElement($elementName)->getLabel();
		;
	}
	public function getElement($name) {
		if(null == $this->elements) throw new HException('null iterable');
		$»it = $this->elements->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		{
			if($element->name === $name) {
				return $element;
				;
			}
			;
		}
		}
		throw new HException(("Cannot access Form Element: '" . $name) . "'");
		return null;
		;
	}
	public function getValueOf($elementName) {
		return $this->getElement($elementName)->value;
		;
	}
	public function getElementTyped($name, $type) {
		$o = $this->getElement($name);
		return $o;
		unset($o);
	}
	public function getData() {
		$data = _hx_anonymous(array());
		if(null == $this->getElements()) throw new HException('null iterable');
		$»it = $this->getElements()->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		{
			$data->{$element->name} = $element->value;
			if(Std::is($element, _hx_qtype("poko.form.elements.DateSelector"))) {
				$ds = _hx_cast($element, _hx_qtype("poko.form.elements.DateSelector"));
				unset($ds);
			}
			;
		}
		}
		return $data;
		unset($data);
	}
	public function populateElements($custom) {
		if($custom !== null) {
			if(null == $this->getElements()) throw new HException('null iterable');
			$»it = $this->getElements()->iterator();
			while($»it->hasNext()) {
			$element = $»it->next();
			{
				$n = $element->name;
				$v = Reflect::field($custom, $n);
				if($v !== null) {
					$element->value = $v;
					;
				}
				unset($v,$n);
			}
			}
			;
		}
		else {
			$element = null;
			if(null == $this->getElements()) throw new HException('null iterable');
			$»it = $this->getElements()->iterator();
			while($»it->hasNext()) {
			$element1 = $»it->next();
			{
				$element1->populate();
				;
			}
			}
			unset($element);
		}
		;
	}
	public function clearData() {
		$element = null;
		if(null == $this->getElements()) throw new HException('null iterable');
		$»it = $this->getElements()->iterator();
		while($»it->hasNext()) {
		$element1 = $»it->next();
		{
			$element1->value = null;
			;
		}
		}
		unset($element);
	}
	public function getOpenTag() {
		return ((((((("<form id=\"" . $this->id) . "\" name=\"") . $this->name) . "\" method=\"") . $this->method) . "\" action=\"") . $this->action) . "\" enctype=\"multipart/form-data\" >";
		;
	}
	public function getCloseTag() {
		$s = new StringBuf();
		$s->b .= "<div style=\"clear:both; height:0px;\">&nbsp;</div>";
		$s->b .= ("<input type=\"hidden\" name=\"" . $this->name) . "_formSubmitted\" value=\"true\" /></form>";
		if($this->wymEditorCount > 0) {
			$s->b .= ("<script>\$(function(){ \$(\"#" . $this->id) . "\").submit(function(){";
			$s->b .= "var i = 0; while ( jQuery != null ) { var wym = jQuery.wymeditors(i); if ( wym != null ) {\x09wym.update(); i++; } else {\x09break; } }";
			$s->b .= "}); });</script>";
			;
		}
		return $s->b;
		unset($s);
	}
	public function isValid() {
		$valid = true;
		if(null == $this->getElements()) throw new HException('null iterable');
		$»it = $this->getElements()->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		if(!$element->isValid()) {
			$valid = false;
			;
		}
		}
		if($this->extraErrors->length > 0) {
			$valid = false;
			;
		}
		return $valid;
		unset($valid);
	}
	public function addError($error) {
		$this->extraErrors->add($error);
		;
	}
	public function getErrorsList() {
		$this->isValid();
		$errors = new HList();
		if(null == $this->extraErrors) throw new HException('null iterable');
		$»it = $this->extraErrors->iterator();
		while($»it->hasNext()) {
		$e = $»it->next();
		$errors->add($e);
		}
		if(null == $this->getElements()) throw new HException('null iterable');
		$»it = $this->getElements()->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		if(null == $element->getErrors()) throw new HException('null iterable');
		$»it2 = $element->getErrors()->iterator();
		while($»it2->hasNext()) {
		$error = $»it2->next();
		$errors->add($error);
		}
		}
		return $errors;
		unset($errors);
	}
	public function getElements() {
		return $this->elements;
		;
	}
	public function isSubmitted() {
		return php_Web::getParams()->get($this->name . "_formSubmitted") === "true";
		;
	}
	public function getSubmittedValue() {
		return php_Web::getParams()->get($this->name . "_formSubmitted");
		;
	}
	public function getErrors() {
		if(!$this->isSubmitted()) {
			return "";
			;
		}
		$s = new StringBuf();
		$errors = $this->getErrorsList();
		if($errors->length > 0) {
			$s->b .= "<ul class=\"formErrors\" >";
			if(null == $errors) throw new HException('null iterable');
			$»it = $errors->iterator();
			while($»it->hasNext()) {
			$error = $»it->next();
			{
				$s->b .= ("<li>" . $error) . "</li>";
				;
			}
			}
			$s->b .= "</ul>";
			;
		}
		return $s->b;
		unset($s,$errors);
	}
	public function getPreview() {
		$s = new StringBuf();
		$s->b .= $this->getOpenTag();
		if($this->isSubmitted()) {
			$s->b .= $this->getErrors();
			;
		}
		$s->b .= "<table cellspacing=\"0\" cellspacing=\"0\" border=\"0\" >\x0A";
		if(null == $this->getElements()) throw new HException('null iterable');
		$»it = $this->getElements()->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		if($element !== $this->submitButton && $element->internal === false) {
			$s->b .= ("\x09" . $element->getPreview()) . "\x0A";
			;
		}
		}
		if($this->submitButton !== null) {
			$this->submitButton->form = $this;
			$s->b .= $this->submitButton->getPreview();
			;
		}
		$s->b .= "</table>\x0A";
		$s->b .= $this->getCloseTag();
		return $s->b;
		unset($s);
	}
	public function toString() {
		return $this->getPreview();
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
function poko_form_Form_0(&$»this, &$action, &$method, &$name) {
if($method === null) {
	return poko_form_FormMethod::$POST;
	;
}
else {
	return $method;
	;
}
}