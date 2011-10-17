<?php

class poko_form_elements_LocationSelector extends poko_form_FormElement {
	public function __construct($name, $label, $value, $required, $attibutes) {
		if( !php_Boot::$skip_constructor ) {
		if($attibutes === null) {
			$attibutes = "";
			;
		}
		if($required === null) {
			$required = false;
			;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->required = $required;
		$this->attributes = $attibutes;
		$tmp = poko_Poko::$instance->getDb()->requestSingle("SELECT * FROM _settings WHERE `key`='googleMapsApiKey'");
		if($tmp !== null) {
			$this->googleMapsKey = $tmp->value;
			;
		}
		unset($tmp);
	}}
	public $defaultLocation;
	public $popupWidth;
	public $popupHeight;
	public $searchAddress;
	public $googleMapsKey;
	public function render() {
		$n = ($this->form->name . "_") . $this->name;
		$s = new StringBuf();
		$location = poko_form_elements_LocationSelector_0($this, $n, $s);
		if($location === null || _hx_index_of($location, ",", null) === -1 || $location === "") {
			$location = "0,0";
			;
		}
		$popupUrl = (((((((((("tpl/php/cms/components/LocationSelector.php?eName=" . $n) . "&location=") . rawurlencode($location)) . "&popupWidth=") . $this->popupWidth) . "&popupHeight=") . $this->popupHeight) . "&searchAddress=") . $this->searchAddress) . "&key=") . $this->googleMapsKey;
		$popupFeatures = ((("width=" . $this->popupWidth) . ",height=") . $this->popupHeight) . ",resizable=0,width=620,height=450,toolbar=0,location=0,status=0";
		$s->b .= ((((("<input type=\"text\" name=\"" . $n) . "\" id=\"") . $n) . "\" value=\"") . $this->value) . "\" size=\"50\" /> \x0A";
		$s->b .= (("<a id=\"" . $n) . "_edit") . "\" href=\"#\">Edit Location</a>\x0A";
		$s->b .= "<script type=\"text/javascript\">\x09\x09\x09\x0A";
		$s->b .= "\x09\x09\$(function() {\x09\x09\x09\x09\x09\x09\x09\x0A";
		$s->b .= ("\x09\x09\x09\$('#" . $n) . "_edit').click( function() {\x09\x0A";
		$s->b .= ((("\x09\x09\x09\x09var mapWindow = open('" . $popupUrl) . "','locationSelector','") . $popupFeatures) . "'); \x0A";
		$s->b .= "\x09\x09\x09\x09if ( mapWindow.opener == null ) mapWindow.opener = self; \x0A";
		$s->b .= "\x09\x09\x09});\x09\x09\x09\x09\x09\x09\x09\x09\x09\x0A";
		$s->b .= "\x09\x09});\x09\x09\x09\x09\x09\x09\x09\x09\x09\x09\x0A";
		$s->b .= "</script> \x09\x09\x09\x09\x09\x09\x09\x09\x09\x0A";
		return $s->b;
		unset($s,$popupUrl,$popupFeatures,$n,$location);
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
function poko_form_elements_LocationSelector_0(&$»this, &$n, &$s) {
if(_hx_field($»this, "value") === null || _hx_equal($»this->value, "")) {
	return $»this->defaultLocation;
	;
}
else {
	return $»this->value;
	;
}
}