<?php

class poko_form_Form {
	public function __construct($name, $action, $method) {
		if( !php_Boot::$skip_constructor ) {
		$this->forcePopulate = false;
		$this->id = $this->name = $name;
		$this->action = $action;
		$this->method = (($method === null) ? poko_form_FormMethod::$POST : $method);
		$this->elements = new HList();
		$this->fieldsets = new Hash();
		$this->addFieldset("__default", new poko_form_FieldSet("__default", "Default", false));
	}}
	public $id;
	public $name;
	public $action;
	public $method;
	public $elements;
	public $fieldsets;
	public $forcePopulate;
	public $submitButton;
	public function addElement($element, $fieldSetKey) {
		if($fieldSetKey === null) {
			$fieldSetKey = "__default";
		}
		$element->form = $this;
		$this->elements->add($element);
		if($fieldSetKey !== null && $this->fieldsets->exists($fieldSetKey)) {
			$this->fieldsets->get($fieldSetKey)->elements->add($element);
		}
		return $element;
	}
	public function setSubmitButton($el) {
		return $this->submitButton = $el;
	}
	public function addFieldset($fieldSetKey, $fieldSet) {
		$fieldSet->form = $this;
		$this->fieldsets->set($fieldSetKey, $fieldSet);
	}
	public function getFieldsets() {
		return $this->fieldsets;
	}
	public function getElement($name) {
		$»it = $this->elements->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		{
			if($element->name == $name) {
				return $element;
			}
			;
		}
		}
		throw new HException("Cannot access Form Element: '" . $name . "'");
		return null;
	}
	public function getElementTyped($name, $type) {
		$o = $this->getElement($name);
		return $o;
	}
	public function getData() {
		$data = _hx_anonymous(array());
		$»it = $this->getElements()->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		{
			$data->{$element->name} = $element->value;
			;
		}
		}
		return $data;
	}
	public function populateElements() {
		$element = null;
		$»it = $this->getElements()->iterator();
		while($»it->hasNext()) {
		$element1 = $»it->next();
		{
			$element1->populate();
			;
		}
		}
	}
	public function clearData() {
		$element = null;
		$»it = $this->getElements()->iterator();
		while($»it->hasNext()) {
		$element1 = $»it->next();
		{
			$element1->value = null;
			;
		}
		}
	}
	public function getOpenTag() {
		return "<form id=\"" . $this->id . "\" name=\"" . $this->name . "\" method=\"" . $this->method . "\" action=\"" . $this->action . "\" enctype=\"multipart/form-data\" >";
	}
	public function getCloseTag() {
		return "<input type=\"hidden\" name=\"" . $this->name . "_formSubmitted\" value=\"true\" /></form>";
	}
	public function isValid() {
		$valid = true;
		$»it = $this->getElements()->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		if(!$element->isValid()) {
			$valid = false;
		}
		}
		return $valid;
	}
	public function getErrorsList() {
		$this->isValid();
		$errors = new HList();
		$»it = $this->getElements()->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		$»it2 = $element->getErrors()->iterator();
		while($»it2->hasNext()) {
		$error = $»it2->next();
		$errors->add($error);
		}
		}
		return $errors;
	}
	public function getElements() {
		return $this->elements;
	}
	public function isSubmitted() {
		return php_Web::getParams()->get($this->name . "_formSubmitted") == "true";
	}
	public function getSubmittedValue() {
		return php_Web::getParams()->get($this->name . "_formSubmitted");
	}
	public function getErrors() {
		$s = new StringBuf();
		$errors = $this->getErrorsList();
		if($errors->length > 0) {
			$s->b .= "<ul>";
			$»it = $errors->iterator();
			while($»it->hasNext()) {
			$error = $»it->next();
			{
				$s->b .= "<li>" . $error . "</li>";
				;
			}
			}
			$s->b .= "</ul>";
		}
		return $s->b;
	}
	public function getPreview() {
		$s = new StringBuf();
		$s->b .= $this->getOpenTag();
		if($this->isSubmitted()) {
			$s->b .= $this->getErrors();
		}
		$s->b .= "<table>\x0A";
		$»it = $this->getElements()->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		$s->b .= "\x09" . $element->getPreview() . "\x0A";
		}
		$s->b .= "</table>\x0A";
		$s->b .= $this->getCloseTag();
		return $s->b;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'poko.form.Form'; }
}
