<?php

class site_cms_modules_base_DatasetsLink extends site_cms_modules_base_DatasetBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $tableName;
	public $form1;
	public function main() {
		$this->tableName = $this->application->params->get("tableName");
		$info = $this->application->db->requestSingle("SELECT t.*, d.`name` as 'definitionName' FROM `_datalink` t, `_definitions` d WHERE t.definitionId=d.id AND t.`tableName`=\"" . $this->tableName . "\"");
		$this->form1 = new poko_form_Form("form1", null, null);
		$this->form1->addElement(new poko_form_elements_Input("label", "Label", $info->label, null, null, null), null);
		$this->form1->addElement(new poko_form_elements_Selectbox("definitionId", "Definition", null, $info->definitionId, false, "- none -"), null);
		$this->form1->addElement(new poko_form_elements_Selectbox("indents", "Indents", null, $info->indents, false, "- none -"), null);
		$this->form1->addElement(new poko_form_elements_Button("submit", "Update", null, null), null);
		$this->form1->populateElements();
		if($this->form1->isSubmitted()) {
			$this->process();
		}
		$definitionsSelctor = $this->form1->getElementTyped("definitionId", _hx_qtype("poko.form.elements.Selectbox"));
		$definitionsSelctor->data = $this->application->db->request("SELECT `name` as 'key', `id` as 'value' FROM _definitions WHERE isPage='0'");
		$indentSelctor = $this->form1->getElementTyped("indents", _hx_qtype("poko.form.elements.Selectbox"));
		$indentSelctor->addOption(_hx_anonymous(array("key" => 1, "value" => 1)));
		$indentSelctor->addOption(_hx_anonymous(array("key" => 2, "value" => 2)));
		$indentSelctor->addOption(_hx_anonymous(array("key" => 3, "value" => 3)));
		$indentSelctor->addOption(_hx_anonymous(array("key" => 4, "value" => 4)));
		$this->setupLeftNav();
	}
	public function process() {
		$this->application->db->update("_datalink", $this->form1->getData(), "`tableName`='" . $this->tableName . "'");
		$this->application->messages->addMessage("DataLink '" . $this->tableName . "' has been updated");
		$this->application->redirect("?request=cms.modules.base.Datasets&manage=true");
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.base.DatasetsLink'; }
}
