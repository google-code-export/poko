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
	public function init() {
		parent::init();
		$this->id = $this->app->params->get("id");
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
			$d = $this->form1->getData();
			try {
				$d->autoOrdering = $this->form1->getElement("orderByField")->value . "|" . $this->form1->getElement("orderByDirection")->value;
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e = $_ex_;
			{
				;
			}}}
			$this->app->getDb()->update("_definitions", $d, "`id`=" . $this->id);
		}
		if($this->app->params->get("action")) {
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
			$fields = $this->app->getDb()->request("SHOW FIELDS FROM `" . $this->definition->table . "`");
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
			$fields = Lambda::filter($fields, array(new _hx_lambda(array("_ex_" => &$_ex_, "_g" => &$_g, "_g1" => &$_g1, "d" => &$d, "def" => &$def, "defined" => &$defined, "e" => &$e, "el" => &$el, "field" => &$field, "fields" => &$fields, "»e" => &$»e, "»it" => &$»it), null, array('field2'), "{
				return !Lambda::has(\$defined, \$field2->Field, null);
			}"), 'execute1'));
			$this->undefinedFields = $fields;
		}
		$this->setupLeftNav();
	}
	public function process() {
		switch($this->app->params->get("action")) {
		case "addElement":{
			$name = $this->app->params->get("elementName");
			$type = "hidden";
			if(_hx_equal($name, "")) {
				haxe_Log::trace("name missing", _hx_anonymous(array("fileName" => "Definition.hx", "lineNumber" => 153, "className" => "site.cms.modules.base.Definition", "methodName" => "process")));
				return;
			}
			$el = $this->definition->addElement($name);
			if($el !== null) {
				$el->type = "read-only";
				$el->showInList = true;
				$this->definition->save();
			}
			else {
				haxe_Log::trace("name missing", _hx_anonymous(array("fileName" => "Definition.hx", "lineNumber" => 164, "className" => "site.cms.modules.base.Definition", "methodName" => "process")));
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
			$define = $this->app->params->get("define");
			$el3 = $this->definition->addElement($define);
			$el3->type = "read-only";
			$el3->showInList = true;
			$this->definition->save();
			$this->app->redirect("?request=cms.modules.base.DefinitionElement&id=" . $this->id . "&definition=" . $define . "&pagesMode=false");
		}break;
		case "addExtra":{
			switch($this->app->params->get("extra")) {
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
		$generalInfo = $this->app->getDb()->requestSingle("SELECT * FROM `_definitions` WHERE `id`=" . $this->id);
		$tOrder = _hx_string_call($generalInfo->autoOrdering, "split", array("|"));
		$orderBy = "";
		$orderOrder = "ASC";
		if($tOrder->length === 2) {
			$orderBy = $tOrder[0];
			$orderOrder = $tOrder[1];
		}
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
		if(!$this->pagesMode) {
			$result = $this->app->getDb()->request("SHOW FIELDS FROM `" . $this->definition->table . "`");
			$fields = new HList();
			$»it = $result->iterator();
			while($»it->hasNext()) {
			$f = $»it->next();
			$fields->add(_hx_anonymous(array("key" => $f->Field, "value" => $f->Field)));
			}
			$s = new poko_form_elements_Selectbox("orderByField", "Order By", $fields, $orderBy, null, null);
			$this->form1->addElement($s, null);
			$l2 = new HList();
			$l2->add(_hx_anonymous(array("key" => "ASC", "value" => "ASC")));
			$l2->add(_hx_anonymous(array("key" => "DESC", "value" => "DESC")));
			$s2 = new poko_form_elements_Selectbox("orderByDirection", "Order Dir", $l2, $orderOrder, null, null);
			$s2->nullMessage = "";
			$this->form1->addElement($s2, null);
			$i = new poko_form_elements_Input("postCreateSql", "Post-create SQL", StringTools::htmlEscape($generalInfo->postCreateSql), false, null, null);
			$i->width = 400;
			$i->useSizeValues = true;
			$this->form1->addElement($i, null);
		}
		$i2 = new poko_form_elements_Input("postEditSql", "Post-edit SQL", StringTools::htmlEscape($generalInfo->postEditSql), false, null, null);
		$i2->width = 400;
		$i2->useSizeValues = true;
		$this->form1->addElement($i2, null);
		if(!$this->pagesMode) {
			$i1 = new poko_form_elements_Input("postDeleteSql", "Post-delete SQL", StringTools::htmlEscape($generalInfo->postDeleteSql), false, null, null);
			$i1->width = 400;
			$i1->useSizeValues = true;
			$this->form1->addElement($i1, null);
			$i22 = new poko_form_elements_Input("postDuplicateSql", "Post-dup SQL", StringTools::htmlEscape($generalInfo->postDuplicateSql), false, null, null);
			$i22->width = 400;
			$i22->useSizeValues = true;
			$this->form1->addElement($i22, null);
			$i3 = new poko_form_elements_Input("postProcedure", "Post Procedure", $generalInfo->postProcedure, false, null, null);
			$i3->description = "The name of a class which extends Procedure to use when adding, updating or deleting a record.";
			$i3->width = 400;
			$i3->useSizeValues = true;
			$this->form1->addElement($i3, null);
			$t = new poko_form_elements_TextArea("help_list", "Help (list)", $generalInfo->help_list, false, null, null);
			$t->width = 400;
			$t->useSizeValues = true;
			$this->form1->addElement($t, null);
		}
		$t2 = new poko_form_elements_TextArea("help", "Help (item)", $generalInfo->help, false, null, null);
		$t2->width = 400;
		$t2->useSizeValues = true;
		$this->form1->addElement($t2, null);
		$b = new poko_form_elements_Button("submit", "Update", "Update", null);
		$this->form1->submitButton = $b;
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
