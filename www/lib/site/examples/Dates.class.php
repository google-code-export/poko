<?php

class site_examples_Dates extends site_examples_templates_DefaultTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $form;
	public function main() {
		$this->scripts->addExternal(poko_utils_html_ScriptType::$css, "css/cms/ui-lightness/jquery-ui-1.7.2.custom.css", null, null, null);
		$this->scripts->addExternal(poko_utils_html_ScriptType::$js, "js/cms/jquery-ui-1.7.2.custom.min.js", null, null, null);
		$this->form = new poko_form_Form("form", null, null);
		$d = new poko_form_elements_DateSelector("date", "Date", Date::now(), null, null, null);
		$d->mode = site_cms_common_DateTimeMode::$dateTime;
		$this->form->addElement($d, null);
		$d2 = new poko_form_elements_DateInput("date2", "Date 2", Date::now(), null, null, null, null, null);
		$this->form->addElement($d2, null);
		$this->form->setSubmitButton($this->form->addElement(new poko_form_elements_Button("submit", "Submit", null, null), null));
		$this->form->populateElements(null);
		if($this->form->isSubmitted() && $this->form->isValid()) {
			$data = $this->form->getData();
			unset($data);
		}
		unset($d2,$d);
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
	function __toString() { return 'site.examples.Dates'; }
}
