<?php

class site_examples_Locations extends site_examples_templates_DefaultTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $form;
	public function main() {
		$this->scripts->addExternal(poko_utils_html_ScriptType::$css, "css/formatCode.css", null, null, null);
		$this->scripts->addExternal(poko_utils_html_ScriptType::$js, "js/jquery.formatCode.js", null, null, null);
		$this->form = new poko_form_Form("form", null, null);
		$location = new poko_form_elements_LocationSelector("location", "Simple Location", null, null, null);
		$this->form->addElement($location, null);
		$location2 = new poko_form_elements_LocationSelector("location2", "Location with default", null, null, null);
		$location2->defaultLocation = "-37.797871, 144.986099";
		$this->form->addElement($location2, null);
		$location3 = new poko_form_elements_LocationSelector("location3", "Location with search", null, null, null);
		$location3->searchAddress = true;
		$this->form->addElement($location3, null);
		$location4 = new poko_form_elements_LocationSelector("location4", "Advanced Location", null, null, null);
		$location4->searchAddress = true;
		$location4->defaultLocation = "-25.641526373065755, 133.41796875";
		$location4->googleMapsKey = "ABQIAAAAPEZwP3fTiAxipcxtf7x-gxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxRPwWSQQtyYryiI5S6KBZMsOwuCsw";
		$location4->popupWidth = 800;
		$location4->popupHeight = 600;
		$this->form->addElement($location4, null);
		$this->form->populateElements(null);
		unset($location4,$location3,$location2,$location);
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
	function __toString() { return 'site.examples.Locations'; }
}
