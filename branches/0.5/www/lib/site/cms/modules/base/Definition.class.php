<?php

class site_cms_modules_base_Definition extends site_cms_modules_base_DefinitionsBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $form1;
	public $id;
	public $elements;
	public $undefinedFields;
	public $definition;
	public $jsBind;
	public function pre() {
		parent::pre();
		$this->id = $this->application->params->get("id");
		$this->definition = new site_cms_common_Definition($this->id);
		$this->remoting->addObject("api", _hx_anonymous(array("toggleCheckbox" => isset($this->toggleCheckbox) ? $this->toggleCheckbox: array($this, "toggleCheckbox"))), null);
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.base.js.JsDefinition");
	}
	public function toggleCheckbox($field, $index, $type) {
		$element = $this->definition->getElement($field);
		$val = $element->{$type} = !Reflect::field($element, $type);
		$this->definition->save();
		$d = _hx_anonymous(array());
		$d->field = $field;
		$d->index = $index;
		$d->type = $type;
		$d->value = $val;
		return $d;
	}
	public function main() {
		parent::main();
		$this->setupForm1();
		if($this->form1->isSubmitted()) {
			$this->application->db->update("_definitions", $this->form1->getData(), "`id`=" . $this->id);
		}
		if($this->application->params->get("action")) {
			$this->process();
		}
		$this->setupForm1();
		$this->elements = new HList();
		if($this->pagesMode) {
			{
				$_g = 0; $_g1 = $this->definition->elements;
				while($_g < $_g1->length) {
					$el = $_g1[$_g];
					++$_g;
					$this->elements->add($el);
					unset($el);
				}
			}
		}
		else {
			$fields = $this->application->db->request("SHOW FIELDS FROM `" . $this->definition->table . "`");
			$defined = new HList();
			$»it = $fields->iterator();
			while($»it->hasNext()) {
			$field = $»it->next();
			{
				$def = $this->definition->getElement($field->Field);
				if($def !== null) {
					$def->dbtype = $field->Type;
					$this->elements->add($def);
					$defined->add($def->name);
				}
				unset($def);
			}
			}
			$fields = Lambda::filter($fields, array(new _hx_lambda(array("_g" => &$_g, "_g1" => &$_g1, "def" => &$def, "defined" => &$defined, "el" => &$el, "field" => &$field, "fields" => &$fields, "»it" => &$»it), null, array('field2'), "{
				return !Lambda::has(\$defined, \$field2->Field, null);
			}"), 'execute1'));
			$this->undefinedFields = $fields;
		}
		$this->setupLeftNav();
	}
	public function process() {
		switch($this->application->params->get("action")) {
		case "addElement":{
			$name = $this->application->params->get("elementName");
			$type = "hidden";
			if(_hx_equal($name, "")) {
				haxe_Log::trace("name missing", _hx_anonymous(array("fileName" => "Definition.hx", "lineNumber" => 148, "className" => "site.cms.modules.base.Definition", "methodName" => "process")));
				return;
			}
			$el = $this->definition->addElement($name);
			if($el !== null) {
				$el->type = "read-only";
				$el->showInList = true;
				$this->definition->save();
			}
			else {
				haxe_Log::trace("name missing", _hx_anonymous(array("fileName" => "Definition.hx", "lineNumber" => 159, "className" => "site.cms.modules.base.Definition", "methodName" => "process")));
			}
		}break;
		case "update":{
			if(php_Web::getParamValues("delete") !== null) {
				{
					$_g = 0; $_g1 = php_Web::getParamValues("delete");
					while($_g < $_g1->length) {
						$el2 = $_g1[$_g];
						++$_g;
						$this->definition->removeElement($el2);
						unset($el2);
					}
				}
			}
			$this->definition->save();
			$this->definition->reOrderElements(php_Web::getParamValues("order"));
		}break;
		case "define":{
			$define = $this->application->params->get("define");
			$el3 = $this->definition->addElement($define);
			$el3->type = "read-only";
			$el3->showInList = true;
			$this->definition->save();
			$this->application->redirect("?request=cms.modules.base.DefinitionElement&id=" . $this->id . "&definition=" . $define . "&pagesMode=false");
		}break;
		case "addExtra":{
			switch($this->application->params->get("extra")) {
			case "linkdisplay":{
				$lastlink = null;
				{
					$_g2 = 0; $_g12 = $this->definition->elements;
					while($_g2 < $_g12->length) {
						$el4 = $_g12[$_g2];
						++$_g2;
						if($el4->type == "linkdisplay") {
							$lastlink = $el4;
						}
						unset($el4);
					}
				}
				$linkname = "link_";
				if($lastlink === null) {
					$linkname .= "1";
				}
				else {
					$linkname .= Std::parseInt(_hx_array_get(_hx_explode("_", $lastlink->name), 1)) + 1;
				}
				$el5 = $this->definition->addElement($linkname);
				$el5->type = "linkdisplay";
				$this->definition->save();
			}break;
			case "multilink":{
				$lastlink2 = null;
				{
					$_g3 = 0; $_g13 = $this->definition->elements;
					while($_g3 < $_g13->length) {
						$el6 = $_g13[$_g3];
						++$_g3;
						if($el6->type == "multilink") {
							$lastlink2 = $el6;
						}
						unset($el6);
					}
				}
				$linkname2 = "multilink_";
				if($lastlink2 === null) {
					$linkname2 .= "1";
				}
				else {
					$linkname2 .= Std::parseInt(_hx_array_get(_hx_explode("_", $lastlink2->name), 1)) + 1;
				}
				$el7 = $this->definition->addElement($linkname2);
				$el7->type = "multilink";
				$this->definition->save();
			}break;
			}
		}break;
		}
	}
	public function setupForm1() {
		$generalInfo = $this->application->db->requestSingle("SELECT * FROM `_definitions` WHERE `id`=" . $this->id);
		$yesno = new HList();
		$yesno->add(_hx_anonymous(array("key" => "Yes", "value" => "1")));
		$yesno->add(_hx_anonymous(array("key" => "No", "value" => "0")));
		$this->form1 = new poko_form_Form("form1", null, null);
		if(!$this->pagesMode) {
			$this->form1->addElement(new poko_form_elements_Readonly("table", "Table", $generalInfo->table, null, null, null), null);
		}
		$this->form1->addElement(new poko_form_elements_Input("name", "Name", $generalInfo->name, false, null, null), null);
		$this->form1->addElement(new poko_form_elements_Input("description", "Description", $generalInfo->description, false, null, null), null);
		$this->form1->addElement(new poko_form_elements_RadioGroup("showFiltering", "Filtering?", $yesno, $generalInfo->showFiltering, "0", false, null), null);
		$this->form1->addElement(new poko_form_elements_RadioGroup("showOrdering", "Ordering?", $yesno, $generalInfo->showOrdering, "0", false, null), null);
		$this->form1->addElement(new poko_form_elements_RadioGroup("showInMenu", "In Menu?", $yesno, $generalInfo->showInMenu, "0", false, null), null);
		$this->form1->addElement(new poko_form_elements_Selectbox("indents", "Indents", null, $generalInfo->indents, false, "- none -"), null);
		$i = new poko_form_elements_Input("postCreateSql", "Post-create SQL", StringTools::htmlEscape($generalInfo->postCreateSql), false, null, null);
		$i->width = 400;
		$i->useSizeValues = true;
		$this->form1->addElement($i, null);
		$i1 = new poko_form_elements_Input("postEditSql", "Post-edit SQL", StringTools::htmlEscape($generalInfo->postEditSql), false, null, null);
		$i1->width = 400;
		$i1->useSizeValues = true;
		$this->form1->addElement($i1, null);
		$i2 = new poko_form_elements_Input("postDeleteSql", "Post-delete SQL", StringTools::htmlEscape($generalInfo->postDeleteSql), false, null, null);
		$i2->width = 400;
		$i2->useSizeValues = true;
		$this->form1->addElement($i2, null);
		$i3 = new poko_form_elements_Input("postDuplicateSql", "Post-dup SQL", StringTools::htmlEscape($generalInfo->postDeleteSql), false, null, null);
		$i3->width = 400;
		$i3->useSizeValues = true;
		$this->form1->addElement($i3, null);
		$i4 = new poko_form_elements_Input("postProcedure", "Post Procedure", $generalInfo->postProcedure, false, null, null);
		$i4->description = "The name of a class which extends Procedure to use when adding, updating or deleting a record.";
		$i4->width = 400;
		$i4->useSizeValues = true;
		$this->form1->addElement($i4, null);
		$this->form1->addElement(new poko_form_elements_Button("submit", "Update", "Update", null), null);
		$this->form1->submitButton = $this->form1->getElement("submit");
		$this->form1->populateElements();
		$indentSelector = $this->form1->getElementTyped("indents", _hx_qtype("poko.form.elements.Selectbox"));
		$indentSelector->addOption(_hx_anonymous(array("key" => 1, "value" => 1)));
		$indentSelector->addOption(_hx_anonymous(array("key" => 2, "value" => 2)));
		$indentSelector->addOption(_hx_anonymous(array("key" => 3, "value" => 3)));
		$indentSelector->addOption(_hx_anonymous(array("key" => 4, "value" => 4)));
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.base.Definition'; }
}
