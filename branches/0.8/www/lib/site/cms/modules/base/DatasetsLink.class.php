<?php

class site_cms_modules_base_DatasetsLink extends site_cms_modules_base_DatasetBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $tableName;
	public $form1;
	public function main() {
		$this->tableName = $this->app->params->get("tableName");
		$info = $this->app->getDb()->requestSingle(("SELECT t.*, d.`name` as 'definitionName' FROM `_datalink` t, `_definitions` d WHERE t.definitionId=d.id AND t.`tableName`=\"" . $this->tableName) . "\"");
		$this->form1 = new poko_form_Form("form1", null, null);
		$this->form1->addElement(new poko_form_elements_Input("label", "Label", $info->label, null, null, null), null);
		$this->form1->addElement(new poko_form_elements_Selectbox("definitionId", "Definition", null, $info->definitionId, false, "- none -", null), null);
		$this->form1->addElement(new poko_form_elements_Selectbox("indents", "Indents", null, $info->indents, false, "- none -", null), null);
		$this->form1->addElement(new poko_form_elements_Button("submit", "Update", null, null), null);
		$this->form1->populateElements(null);
		if($this->form1->isSubmitted()) {
			$this->process();
			;
		}
		$definitionsSelctor = $this->form1->getElementTyped("definitionId", _hx_qtype("poko.form.elements.Selectbox"));
		$definitionsSelctor->data = $this->app->getDb()->request("SELECT `id` as 'key', `name` as 'value' FROM _definitions WHERE isPage='0'");
		$indentSelctor = $this->form1->getElementTyped("indents", _hx_qtype("poko.form.elements.Selectbox"));
		$indentSelctor->data->add(_hx_anonymous(array("key" => 1, "value" => 1)));
		$indentSelctor->data->add(_hx_anonymous(array("key" => 2, "value" => 2)));
		$indentSelctor->data->add(_hx_anonymous(array("key" => 3, "value" => 3)));
		$indentSelctor->data->add(_hx_anonymous(array("key" => 4, "value" => 4)));
		$this->setupLeftNav();
		unset($info,$indentSelctor,$definitionsSelctor);
	}
	public function process() {
		$this->app->getDb()->update("_datalink", $this->form1->getData(), ("`tableName`='" . $this->tableName) . "'");
		$this->messages->addMessage(("DataLink '" . $this->tableName) . "' has been updated");
		$this->app->redirect("?request=cms.modules.base.Datasets&manage=true");
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
	function __toString() { return 'site.cms.modules.base.DatasetsLink'; }
}
