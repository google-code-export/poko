<?php

class site_examples_Forms extends site_examples_templates_DefaultTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $form1;
	public $form2;
	public function main() {
		$this->form1 = new poko_form_Form("form1", null, null);
		$this->form1->addElement(new poko_form_elements_Input("name", "You Name", null, null, null, null), null);
		$this->form1->addElement(new poko_form_elements_Selectbox("gender", "Gender", null, null, null, null), null);
		$this->form1->setSubmitButton($this->form1->addElement(new poko_form_elements_Button("submit", "Submit", null, null), null));
		$this->form1->populateElements();
		$gender = $this->form1->getElementTyped("gender", _hx_qtype("poko.form.elements.Selectbox"));
		$gender->addOption(_hx_anonymous(array("key" => "male", "value" => "male")));
		$gender->addOption(_hx_anonymous(array("key" => "female", "value" => "female")));
		$this->form2 = new poko_form_Form("form2", null, null);
		$el = new poko_form_elements_Input("name", "You Name", null, true, null, null);
		$el->addValidator(new poko_form_validators_StringValidator(3, 10, "abcdefg", null, null, null));
		$this->form2->addElement($el, null);
		$this->form2->addElement(new poko_form_elements_Selectbox("gender", "Gender", null, null, null, null), null);
		$this->form2->setSubmitButton($this->form2->addElement(new poko_form_elements_Button("submit", "Submit", null, null), null));
		$this->form2->populateElements();
		$gender1 = $this->form2->getElementTyped("gender", _hx_qtype("poko.form.elements.Selectbox"));
		$gender1->addOption(_hx_anonymous(array("key" => "male", "value" => "male")));
		$gender1->addOption(_hx_anonymous(array("key" => "female", "value" => "female")));
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.examples.Forms'; }
}
