<?php

class site_cms_modules_base_Definition extends site_cms_modules_base_DefinitionsBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
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
		$this->remoting->addObject("api", _hx_anonymous(array("toggleCheckbox" => (isset($this->toggleCheckbox) ? $this->toggleCheckbox: array($this, "toggleCheckbox")))), null);
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.base.js.JsDefinition");
		$this->head->css->add("css/cms/ui-lightness/jquery-ui-1.7.2.custom.css");
		$this->head->js->add("js/cms/jquery.qtip.min.js");
		;
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
		unset($val,$element,$d);
	}
	public function main() {
		parent::main();
		$this->setupForm1();
		if($this->form1->isSubmitted()) {
			$d = $this->form1->getData();
			try {
				$d->autoOrdering = ($this->form1->getElement("orderByField")->value . "|") . $this->form1->getElement("orderByDirection")->value;
				$p = new site_cms_common_DefinitionParams();
				$p->usePaging = site_cms_modules_base_Definition_0($this, $d, $p);
				$p->perPage = $this->form1->getElement("perPage")->value;
				$p->pagingRange = $this->form1->getElement("pagingRange")->value;
				$p->useTabulation = site_cms_modules_base_Definition_1($this, $d, $p);
				$p->tabulationFields = $this->form1->getElement("tabulationFields")->value;
				$d->params = haxe_Serializer::run($p);
				unset($p);
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e = $_ex_;
			{
				$this->messages->addError(Std::string($e));
				;
			}}}
			$this->app->getDb()->update("_definitions", $d, "`id`=" . $this->id);
			unset($e,$d);
		}
		if($this->app->params->get("action")) {
			$this->process();
			;
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
				unset($_g1,$_g);
			}
			;
		}
		else {
			$fields = $this->app->getDb()->request(("SHOW FIELDS FROM `" . $this->definition->table) . "`");
			$defined = new HList();
			if(null == $fields) throw new HException('null iterable');
			$»it = $fields->iterator();
			while($»it->hasNext()) {
			$field = $»it->next();
			{
				$def = $this->definition->getElement($field->Field);
				if($def !== null) {
					$def->dbtype = $field->Type;
					$this->elements->add($def);
					$defined->add($def->name);
					;
				}
				unset($def);
			}
			}
			$fields = Lambda::filter($fields, array(new _hx_lambda(array(&$defined, &$fields), "site_cms_modules_base_Definition_2"), 'execute'));
			$this->undefinedFields = $fields;
			unset($fields,$defined);
		}
		$this->setupLeftNav();
		;
	}
	public function process() {
		switch($this->app->params->get("action")) {
		case "addElement":{
			$name = $this->app->params->get("elementName");
			$type = "hidden";
			if(_hx_equal($name, "")) {
				haxe_Log::trace("name missing", _hx_anonymous(array("fileName" => "Definition.hx", "lineNumber" => 168, "className" => "site.cms.modules.base.Definition", "methodName" => "process")));
				return;
				;
			}
			$el = $this->definition->addElement($name);
			if($el !== null) {
				$el->type = "read-only";
				$el->showInList = true;
				$this->definition->save();
				;
			}
			else {
				haxe_Log::trace("name missing", _hx_anonymous(array("fileName" => "Definition.hx", "lineNumber" => 179, "className" => "site.cms.modules.base.Definition", "methodName" => "process")));
				;
			}
			unset($type,$name,$el);
		}break;
		case "update":{
			if(php_Web::getParamValues("delete") !== null) {
				{
					$_g = 0; $_g1 = php_Web::getParamValues("delete");
					while($_g < $_g1->length) {
						$el = $_g1[$_g];
						++$_g;
						$this->definition->removeElement($el);
						unset($el);
					}
					unset($_g1,$_g);
				}
				;
			}
			$this->definition->save();
			$this->definition->reOrderElements(php_Web::getParamValues("order"));
			;
		}break;
		case "define":{
			$define = $this->app->params->get("define");
			$el = $this->definition->addElement($define);
			$el->type = "read-only";
			$el->showInList = true;
			$this->definition->save();
			$this->app->redirect(((("?request=cms.modules.base.DefinitionElement&id=" . $this->id) . "&definition=") . $define) . "&pagesMode=false");
			unset($el,$define);
		}break;
		case "addExtra":{
			switch($this->app->params->get("extra")) {
			case "linkdisplay":{
				$lastlink = null;
				{
					$_g = 0; $_g1 = $this->definition->elements;
					while($_g < $_g1->length) {
						$el = $_g1[$_g];
						++$_g;
						if($el->type === "linkdisplay") {
							$lastlink = $el;
							;
						}
						unset($el);
					}
					unset($_g1,$_g);
				}
				$linkname = "link_";
				if($lastlink === null) {
					$linkname .= "1";
					;
				}
				else {
					$linkname .= Std::parseInt(_hx_array_get(_hx_explode("_", $lastlink->name), 1)) + 1;
					;
				}
				$el = $this->definition->addElement($linkname);
				$el->type = "linkdisplay";
				$this->definition->save();
				unset($linkname,$lastlink,$el);
			}break;
			case "multilink":{
				$lastlink = null;
				{
					$_g = 0; $_g1 = $this->definition->elements;
					while($_g < $_g1->length) {
						$el = $_g1[$_g];
						++$_g;
						if($el->type === "multilink") {
							$lastlink = $el;
							;
						}
						unset($el);
					}
					unset($_g1,$_g);
				}
				$linkname = "multilink_";
				if($lastlink === null) {
					$linkname .= "1";
					;
				}
				else {
					$linkname .= Std::parseInt(_hx_array_get(_hx_explode("_", $lastlink->name), 1)) + 1;
					;
				}
				$el = $this->definition->addElement($linkname);
				$el->type = "multilink";
				$this->definition->save();
				unset($linkname,$lastlink,$el);
			}break;
			}
			;
		}break;
		}
		;
	}
	public function setupForm1() {
		$generalInfo = $this->app->getDb()->requestSingle("SELECT * FROM `_definitions` WHERE `id`=" . $this->id);
		$params = $this->definition->params;
		$tOrder = _hx_string_call($generalInfo->autoOrdering, "split", array("|"));
		$orderBy = "";
		$orderOrder = "ASC";
		if($tOrder->length === 2) {
			$orderBy = $tOrder[0];
			$orderOrder = $tOrder[1];
			;
		}
		$yesno = new HList();
		$yesno->add(_hx_anonymous(array("key" => "1", "value" => "Yes")));
		$yesno->add(_hx_anonymous(array("key" => "0", "value" => "No")));
		$this->form1 = new poko_form_Form("form1", null, null);
		$this->form1->addFieldset("simple", new poko_form_FieldSet("simple", "Simple", null));
		$this->form1->addFieldset("filt", new poko_form_FieldSet("filt", "Filtering, Ordering", null));
		$this->form1->addFieldset("page", new poko_form_FieldSet("page", "Paging", null));
		$this->form1->addFieldset("tab", new poko_form_FieldSet("tab", "Tabulation", null));
		$this->form1->addFieldset("func", new poko_form_FieldSet("func", "Functions", null));
		$this->form1->addFieldset("help", new poko_form_FieldSet("help", "Help", null));
		$this->form1->addFieldset("old", new poko_form_FieldSet("old", "Old (not really used anymore)", null));
		if(!$this->pagesMode) {
			$this->form1->addElement(new poko_form_elements_Readonly("table", "Table", $this->definition->table, null, null, null), "simple");
			;
		}
		$this->form1->addElement(new poko_form_elements_Input("name", "Name", $this->definition->name, false, null, null), "simple");
		$this->form1->addElement(new poko_form_elements_Input("description", "Description", $this->definition->description, false, null, null), "simple");
		$this->form1->addElement(new poko_form_elements_RadioGroup("showFiltering", "Filtering?", $yesno, $generalInfo->showFiltering, "0", false, null), "filt");
		$this->form1->addElement(new poko_form_elements_RadioGroup("showOrdering", "Ordering?", $yesno, $generalInfo->showOrdering, "0", false, null), "filt");
		$this->form1->addElement(new poko_form_elements_RadioGroup("allowCsv", "CSV Download?", $yesno, $generalInfo->allowCsv, "0", false, null), "filt");
		if(!$this->pagesMode) {
			$this->form1->addElement(new poko_form_elements_RadioGroup("usePaging", "Pagulation?", $yesno, site_cms_modules_base_Definition_3($this, $generalInfo, $orderBy, $orderOrder, $params, $tOrder, $yesno), "0", false, null), "page");
			$i = new poko_form_elements_Input("perPage", "Items Per Page", $this->definition->params->perPage, false, null, null);
			$i->description = "How many items to list per page.";
			$i->width = 30;
			$i->useSizeValues = true;
			$this->form1->addElement($i, "page");
			$i1 = new poko_form_elements_Input("pagingRange", "Paging Range", $this->definition->params->pagingRange, false, null, null);
			$i1->description = "How many pages to list before we get next/previous links.";
			$i1->width = 30;
			$i1->useSizeValues = true;
			$this->form1->addElement($i1, "page");
			$this->form1->addElement(new poko_form_elements_RadioGroup("useTabulation", "Tabulation?", $yesno, site_cms_modules_base_Definition_4($this, $generalInfo, $i, $i1, $orderBy, $orderOrder, $params, $tOrder, $yesno), "0", false, null), "tab");
			$p = _hx_anonymous(array("keyLabel" => "Tab Name", "valueLabel" => "Filter SQL", "valueIsMultiline" => true));
			$this->form1->addElement(new site_cms_modules_base_formElements_KeyValueInput("tabulationFields", "Tabulation Fields", $this->definition->params->tabulationFields, $p, null, null, null), "tab");
			unset($p,$i1,$i);
		}
		if(!$this->pagesMode) {
			$result = $this->app->getDb()->request(("SHOW FIELDS FROM `" . $this->definition->table) . "`");
			$fields = new HList();
			if(null == $result) throw new HException('null iterable');
			$»it = $result->iterator();
			while($»it->hasNext()) {
			$f = $»it->next();
			$fields->add(_hx_anonymous(array("key" => $f->Field, "value" => $f->Field)));
			}
			$s = new poko_form_elements_Selectbox("orderByField", "Order By", $fields, $orderBy, null, null, null);
			$this->form1->addElement($s, "filt");
			$l2 = new HList();
			$l2->add(_hx_anonymous(array("key" => "ASC", "value" => "ASC")));
			$l2->add(_hx_anonymous(array("key" => "DESC", "value" => "DESC")));
			$s2 = new poko_form_elements_Selectbox("orderByDirection", "Order Dir", $l2, $orderOrder, null, null, null);
			$s2->nullMessage = "";
			$this->form1->addElement($s2, "filt");
			$i = new poko_form_elements_TextArea("postCreateSql", "Post-create SQL", StringTools::htmlEscape($generalInfo->postCreateSql), false, null, null);
			$i->description = "An SQL statement that is run after a new record has been created. You can use any of the created records data by adding '#FIELD_NAME#'. ie UPDATE tbl SET name='Hello' WHERE id='#id#'.";
			$i->width = 400;
			$i->useSizeValues = true;
			$this->form1->addElement($i, "func");
			unset($s2,$s,$result,$l2,$i,$fields);
		}
		$i = new poko_form_elements_TextArea("postEditSql", "Post-edit SQL", StringTools::htmlEscape($generalInfo->postEditSql), false, null, null);
		$i->description = "An SQL statement that is run each time you save a record. You can use data both from the before and after states of your record. For data before the edits use '#id#', for after use '*id*'.";
		$i->width = 400;
		$i->useSizeValues = true;
		$this->form1->addElement($i, "func");
		if(!$this->pagesMode) {
			$i1 = new poko_form_elements_TextArea("postDeleteSql", "Post-delete SQL", StringTools::htmlEscape($generalInfo->postDeleteSql), false, null, null);
			$i1->description = "An SQL statement that is run after a record has been deleted. You can use any of the created records data by adding '#FIELD_NAME#'. ie UPDATE tbl SET name='Hello' WHERE id='#id#'.";
			$i1->width = 400;
			$i1->useSizeValues = true;
			$this->form1->addElement($i1, "func");
			$i2 = new poko_form_elements_TextArea("postDuplicateSql", "Post-dup SQL", StringTools::htmlEscape($generalInfo->postDuplicateSql), false, null, null);
			$i2->description = "WARNING: Currently not implemented. Should be implemented in Dataset.hx !";
			$i2->width = 400;
			$i2->useSizeValues = true;
			$this->form1->addElement($i2, "func");
			$i3 = new poko_form_elements_Input("postProcedure", "Post Procedure", $generalInfo->postProcedure, false, null, null);
			$i3->description = "The name of a class which extends Procedure to use when adding, updating or deleting a record.";
			$i3->width = 400;
			$i3->useSizeValues = true;
			$this->form1->addElement($i3, "func");
			$t = new poko_form_elements_TextArea("help_list", "Help (list)", $generalInfo->help_list, false, null, null);
			$t->width = 400;
			$t->useSizeValues = true;
			$this->form1->addElement($t, "help");
			unset($t,$i3,$i2,$i1);
		}
		$t = new poko_form_elements_TextArea("help", "Help (item)", $generalInfo->help, false, null, null);
		$t->width = 400;
		$t->useSizeValues = true;
		$this->form1->addElement($t, "help");
		$this->form1->addElement(new poko_form_elements_RadioGroup("showInMenu", "In Menu?", $yesno, $generalInfo->showInMenu, "0", false, null), "old");
		$this->form1->addElement(new poko_form_elements_Selectbox("indents", "Indents", null, $generalInfo->indents, false, "- none -", null), "old");
		$submitButton = new poko_form_elements_Button("__submit", "Save Settings", null, poko_form_elements_ButtonType::$SUBMIT);
		if(!$this->pagesMode) {
			$keyValJsBinding = $this->jsBindings->get("site.cms.modules.base.js.JsKeyValueInput");
			$submitButton->attributes = ("onClick=\"return(" . $keyValJsBinding->getCall("flushKeyValueInputs", new _hx_array(array()))) . ");\"";
			unset($keyValJsBinding);
		}
		$this->form1->setSubmitButton($submitButton);
		$this->form1->populateElements(null);
		$indentSelector = $this->form1->getElementTyped("indents", _hx_qtype("poko.form.elements.Selectbox"));
		$indentSelector->data->add(_hx_anonymous(array("key" => 1, "value" => 1)));
		$indentSelector->data->add(_hx_anonymous(array("key" => 2, "value" => 2)));
		$indentSelector->data->add(_hx_anonymous(array("key" => 3, "value" => 3)));
		$indentSelector->data->add(_hx_anonymous(array("key" => 4, "value" => 4)));
		unset($yesno,$tOrder,$t,$submitButton,$params,$orderOrder,$orderBy,$indentSelector,$i,$generalInfo);
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
	function __toString() { return 'site.cms.modules.base.Definition'; }
}
;
function site_cms_modules_base_Definition_0(&$»this, &$d, &$p) {
if(_hx_equal($»this->form1->getElement("usePaging")->value, "1")) {
	return true;
	;
}
else {
	return false;
	;
}
}
function site_cms_modules_base_Definition_1(&$»this, &$d, &$p) {
if(_hx_equal($»this->form1->getElement("useTabulation")->value, "1")) {
	return true;
	;
}
else {
	return false;
	;
}
}
function site_cms_modules_base_Definition_2(&$defined, &$fields, $field) {
{
	return !Lambda::has($defined, $field->Field, null);
	;
}
}
function site_cms_modules_base_Definition_3(&$»this, &$generalInfo, &$orderBy, &$orderOrder, &$params, &$tOrder, &$yesno) {
if($»this->definition->params->usePaging) {
	return "1";
	;
}
else {
	return "0";
	;
}
}
function site_cms_modules_base_Definition_4(&$»this, &$generalInfo, &$i, &$i1, &$orderBy, &$orderOrder, &$params, &$tOrder, &$yesno) {
if($»this->definition->params->useTabulation) {
	return "1";
	;
}
else {
	return "0";
	;
}
}