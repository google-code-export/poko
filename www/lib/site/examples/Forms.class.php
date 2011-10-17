<?php

class site_examples_Forms extends site_examples_templates_DefaultTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $form1;
	public $form2;
	public function main() {
		$this->scripts->addExternal(poko_utils_html_ScriptType::$js, "js/cms/wymeditor/jquery.wymeditor.pack.js", null, null, null);
		$this->scripts->addExternal(poko_utils_html_ScriptType::$js, "js/cms/wym_editor_browse.js", null, null, null);
		$this->form1 = new poko_form_Form("form1", null, null);
		$this->form1->addElement(new poko_form_elements_Input("name", "Your Name", null, null, null, null), null);
		$this->form1->addElement(new poko_form_elements_Selectbox("gender", "Gender", null, null, null, null, null), null);
		$gender = $this->form1->getElementTyped("gender", _hx_qtype("poko.form.elements.Selectbox"));
		$gender->data->add(_hx_anonymous(array("key" => "M", "value" => "Male")));
		$gender->data->add(_hx_anonymous(array("key" => "F", "value" => "Female")));
		$checkbox = new poko_form_elements_Checkbox("newsletter", "Sign up for newsletter?", false, null, null);
		$this->form1->addElement($checkbox, null);
		$radioGroup = new poko_form_elements_RadioGroup("age", "Age", null, null, null, null, null);
		$radioGroup->addOption("15-18", "15 - 18 years");
		$radioGroup->addOption("18-25", "18 - 25 years");
		$radioGroup->addOption("25-35", "25 - 35 years");
		$radioGroup->addOption("45+", "over 45 years");
		$this->form1->addElement($radioGroup, null);
		$textarea = new poko_form_elements_TextArea("specialRequests", "Special Requests", null, null, null, null);
		$textarea->height = 60;
		$textarea->useSizeValues = true;
		$this->form1->addElement($textarea, null);
		$editor = new poko_form_elements_RichtextWym("editor", "Editor", null, null, null);
		$editor->width = 400;
		$editor->height = 200;
		$this->form1->addElement($editor, null);
		$location = new poko_form_elements_LocationSelector("location", "Location (Lat/Long)", "", null, null);
		$location->popupWidth = 500;
		$location->popupHeight = 500;
		$location->searchAddress = true;
		$this->form1->addElement($location, null);
		$this->form1->setSubmitButton($this->form1->addElement(new poko_form_elements_Button("submit", "Submit", null, null), null));
		$this->form1->populateElements(null);
		$this->form2 = new poko_form_Form("form2", null, null);
		$el = new poko_form_elements_Input("name", "Your Name", null, true, null, null);
		$el->addValidator(new poko_form_validators_StringValidator(3, 10, null, null, null, null));
		$this->form2->addElement($el, null);
		$this->form2->addElement(new poko_form_elements_Selectbox("doStuff", "Do Stuff?", null, null, null, null, null), null);
		$this->form2->setSubmitButton($this->form2->addElement(new poko_form_elements_Button("submit", "Submit", null, null), null));
		$doStuff = $this->form2->getElementTyped("doStuff", _hx_qtype("poko.form.elements.Selectbox"));
		$doStuff->data->add(_hx_anonymous(array("key" => "1", "value" => "Yes")));
		$doStuff->data->add(_hx_anonymous(array("key" => "0", "value" => "No")));
		$this->form2->populateElements(null);
		unset($textarea,$radioGroup,$location,$gender,$el,$editor,$doStuff,$checkbox);
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
	function __toString() { return 'site.examples.Forms'; }
}
