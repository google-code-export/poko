<?php

class site_cms_modules_base_formElements_KeyValueInput extends poko_form_FormElement {
	public function __construct($name, $label, $value, $properties, $validatorsKey, $validatorsValue, $attibutes) {
		if( !php_Boot::$skip_constructor ) {
		if($attibutes === null) {
			$attibutes = "";
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->attributes = $attibutes;
		$this->properties = $properties;
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.base.js.JsKeyValueInput");
	}}
	public $properties;
	public $jsBind;
	public $minRows;
	public $maxRows;
	public function render() {
		$n = $this->form->name . "_" . $this->name;
		$keyLabel = (_hx_equal($this->properties->keyLabel, "") ? "Key" : $this->properties->keyLabel);
		$valueLabel = (_hx_equal($this->properties->valueLabel, "") ? "Value" : $this->properties->valueLabel);
		$keyType = _hx_anonymous(array("isMultiline" => _hx_equal($this->properties->keyIsMultiline, "1"), "width" => Std::parseInt($this->properties->keyWidth), "height" => Std::parseInt($this->properties->keyHeight)));
		$valueType = _hx_anonymous(array("isMultiline" => _hx_equal($this->properties->valueIsMultiline, "1"), "width" => Std::parseInt($this->properties->valueWidth), "height" => Std::parseInt($this->properties->valueHeight)));
		$s = "<input type=\"hidden\" name=\"" . $n . "\" id=\"" . $n . "\" value=\"" . $this->value . "\" />";
		$s .= "<table id=\"" . $n . "_keyValueTable\">";
		$s .= "\x09<tr><td><label>" . $keyLabel . "</label></td><td><label>" . $valueLabel . "</label></td><td></td></tr>";
		$s .= "</table>";
		$s .= "<div><a href=\"#\" onclick=\"" . $this->jsBind->getCall("addKeyValueInput", new _hx_array(array($n))) . "; return(false);\"><img class=\"qTip\" src=\"./res/cms/add.png\" title=\"add row\" /></a></div>";
		$s .= "<script>\$(document).ready(function(){" . $this->jsBind->getCall("setupKeyValueInput", new _hx_array(array($n, $this->properties, $this->minRows, $this->maxRows))) . "});</script>";
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
