<?php

class site_cms_modules_base_DefinitionElement extends site_cms_modules_base_DefinitionsBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $action;
	public $table;
	public $elements;
	public $fieldsets;
	public $form;
	public $metaData;
	public $form2;
	public $definition;
	public $meta;
	public $typeSelector;
	public $jsBind;
	public function pre() {
		parent::pre();
		$this->head->js->add("js/cms/jquery-ui-1.7.2.custom.min.js");
		$this->head->css->add("css/cms/ui-lightness/jquery-ui-1.7.2.custom.css");
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.base.js.JsDefinitionElement");
		$this->remoting->addObject("api", _hx_anonymous(array("getListData" => isset($this->getListData) ? $this->getListData: array($this, "getListData"))), null);
		$this->remoting->addObject("api", _hx_anonymous(array("getListData2" => isset($this->getListData2) ? $this->getListData2: array($this, "getListData2"))), null);
	}
	public function getListData($table) {
		$result = $this->application->db->request("SHOW FIELDS FROM `" . $table . "`");
		$arr = new _hx_array(array());
		$»it = $result->iterator();
		while($»it->hasNext()) {
		$item = $»it->next();
		$arr->push($item->Field);
		}
		return $arr;
	}
	public function getListData2($table, $table2) {
		$out = new _hx_array(array());
		$result = $this->application->db->request("SHOW FIELDS FROM `" . $table . "`");
		$arr = new _hx_array(array());
		$»it = $result->iterator();
		while($»it->hasNext()) {
		$item = $»it->next();
		$arr->push($item->Field);
		}
		$out->push($arr);
		$result = $this->application->db->request("SHOW FIELDS FROM `" . $table2 . "`");
		$arr = new _hx_array(array());
		$»it2 = $result->iterator();
		while($»it2->hasNext()) {
		$item2 = $»it2->next();
		$arr->push($item2->Field);
		}
		$out->push($arr);
		return $out;
	}
	public function main() {
		parent::main();
		$this->definition = new site_cms_common_Definition($this->application->params->get("id"));
		$this->meta = $this->definition->getElement($this->application->params->get("definition"));
		$this->setupForm();
		if($this->form->isSubmitted()) {
			$this->update();
			if($this->pagesMode) {
				php_Web::redirect("?request=cms.modules.base.Definition&id=" . $this->application->params->get("id") . "&pagesMode=true");
			}
			else {
				php_Web::redirect("?request=cms.modules.base.Definition&id=" . $this->application->params->get("id"));
			}
		}
		$this->setupForm();
		$this->setupLeftNav();
	}
	public function update() {
		$this->meta->properties = $this->getFormData();
		$this->meta->name = $this->meta->properties->name;
		$this->meta->type = $this->meta->properties->type;
		$this->meta->label = $this->meta->properties->label;
		$this->meta->showInList = $this->meta->properties->showInList;
		$this->meta->showInFiltering = $this->meta->properties->showInFiltering;
		$this->meta->showInOrdering = $this->meta->properties->showInOrdering;
		$this->definition->save();
	}
	public function setupForm() {
		$this->form = new poko_form_Form("form", null, null);
		$data = $this->meta->properties;
		$datatypes = poko_utils_ListData::flatArraytoList(new _hx_array(array("text", "number", "bool", "image-file", "richtext-tinymce", "richtext-wym", "date", "association", "keyvalue", "read-only", "order", "link-to", "link-value", "hidden", "post-sql-value")));
		$yesno = new HList();
		$yesno->add(_hx_anonymous(array("key" => "Yes", "value" => "1")));
		$yesno->add(_hx_anonymous(array("key" => "No", "value" => "0")));
		$truefalse = new HList();
		$truefalse->add(_hx_anonymous(array("key" => "True", "value" => "1")));
		$truefalse->add(_hx_anonymous(array("key" => "False", "value" => "0")));
		$imagefile = new HList();
		$imagefile->add(_hx_anonymous(array("key" => "Image", "value" => "1")));
		$imagefile->add(_hx_anonymous(array("key" => "File", "value" => "0")));
		$dataType = new HList();
		$dataType->add(_hx_anonymous(array("key" => "VARCHAR", "value" => "varchar")));
		$dataType->add(_hx_anonymous(array("key" => "TEXT", "value" => "text")));
		$dataType->add(_hx_anonymous(array("key" => "BOOL", "value" => "bool")));
		$dataType->add(_hx_anonymous(array("key" => "INT", "value" => "int")));
		$dataType->add(_hx_anonymous(array("key" => "FLOAT", "value" => "float")));
		$dataType->add(_hx_anonymous(array("key" => "DOUBLE", "value" => "double")));
		$tableList = site_cms_common_Tools::getDBTables();
		$tableList = Lambda::map($tableList, array(new _hx_lambda(array("data" => &$data, "dataType" => &$dataType, "datatypes" => &$datatypes, "imagefile" => &$imagefile, "tableList" => &$tableList, "truefalse" => &$truefalse, "yesno" => &$yesno), null, array('table'), "{
			return _hx_anonymous(array(\"key\" => \$table, \"value\" => \$table));
		}"), 'execute1'));
		$selectedAssocTable = $data->table;
		$assocFields = null;
		if($selectedAssocTable !== null) {
			try {
				$assocFields = $this->application->db->request("SHOW FIELDS FROM `" . $selectedAssocTable . "`");
				$assocFields = Lambda::map($assocFields, array(new _hx_lambda(array("assocFields" => &$assocFields, "data" => &$data, "dataType" => &$dataType, "datatypes" => &$datatypes, "imagefile" => &$imagefile, "selectedAssocTable" => &$selectedAssocTable, "tableList" => &$tableList, "truefalse" => &$truefalse, "yesno" => &$yesno), null, array('field'), "{
					return _hx_anonymous(array(\"key\" => \$field->Field, \"value\" => \$field->Field));
				}"), 'execute1'));
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e = $_ex_;
			{
				$assocFields = null;
			}}}
		}
		$localFields = null;
		try {
			$localFields = $this->application->db->request("SHOW FIELDS FROM `" . $this->definition->table . "`");
			$localFields = Lambda::map($localFields, array(new _hx_lambda(array("_ex_" => &$_ex_, "assocFields" => &$assocFields, "data" => &$data, "dataType" => &$dataType, "datatypes" => &$datatypes, "e" => &$e, "imagefile" => &$imagefile, "localFields" => &$localFields, "selectedAssocTable" => &$selectedAssocTable, "tableList" => &$tableList, "truefalse" => &$truefalse, "yesno" => &$yesno, "»e" => &$»e), null, array('field'), "{
				return _hx_anonymous(array(\"key\" => \$field->Field, \"value\" => \$field->Field));
			}"), 'execute1'));
		}catch(Exception $»e2) {
		$_ex_2 = ($»e2 instanceof HException) ? $»e2->e : $»e2;
		;
		{ $e2 = $_ex_2;
		{
			$localFields = null;
		}}}
		$selectedMultiTable = $data->table;
		$multiFields = null;
		if($selectedMultiTable !== null) {
			try {
				$multiFields = $this->application->db->request("SHOW FIELDS FROM `" . $selectedMultiTable . "`");
				$multiFields = Lambda::map($multiFields, array(new _hx_lambda(array("_ex_" => &$_ex_, "_ex_2" => &$_ex_2, "assocFields" => &$assocFields, "data" => &$data, "dataType" => &$dataType, "datatypes" => &$datatypes, "e" => &$e, "e2" => &$e2, "imagefile" => &$imagefile, "localFields" => &$localFields, "multiFields" => &$multiFields, "selectedAssocTable" => &$selectedAssocTable, "selectedMultiTable" => &$selectedMultiTable, "tableList" => &$tableList, "truefalse" => &$truefalse, "yesno" => &$yesno, "»e" => &$»e, "»e2" => &$»e2), null, array('field'), "{
					return _hx_anonymous(array(\"key\" => \$field->Field, \"value\" => \$field->Field));
				}"), 'execute1'));
			}catch(Exception $»e3) {
			$_ex_3 = ($»e3 instanceof HException) ? $»e3->e : $»e3;
			;
			{ $e3 = $_ex_3;
			{
				$multiFields = null;
			}}}
		}
		$selectedMultilinkTable = $data->link;
		$multiLinkFields = null;
		if($selectedMultilinkTable !== null) {
			try {
				$multiLinkFields = $this->application->db->request("SHOW FIELDS FROM `" . $selectedMultilinkTable . "`");
				$multiLinkFields = Lambda::map($multiLinkFields, array(new _hx_lambda(array("_ex_" => &$_ex_, "_ex_2" => &$_ex_2, "_ex_3" => &$_ex_3, "assocFields" => &$assocFields, "data" => &$data, "dataType" => &$dataType, "datatypes" => &$datatypes, "e" => &$e, "e2" => &$e2, "e3" => &$e3, "imagefile" => &$imagefile, "localFields" => &$localFields, "multiFields" => &$multiFields, "multiLinkFields" => &$multiLinkFields, "selectedAssocTable" => &$selectedAssocTable, "selectedMultiTable" => &$selectedMultiTable, "selectedMultilinkTable" => &$selectedMultilinkTable, "tableList" => &$tableList, "truefalse" => &$truefalse, "yesno" => &$yesno, "»e" => &$»e, "»e2" => &$»e2, "»e3" => &$»e3), null, array('field'), "{
					return _hx_anonymous(array(\"key\" => \$field->Field, \"value\" => \$field->Field));
				}"), 'execute1'));
			}catch(Exception $»e4) {
			$_ex_4 = ($»e4 instanceof HException) ? $»e4->e : $»e4;
			;
			{ $e4 = $_ex_4;
			{
				$multiLinkFields = null;
			}}}
		}
		$charsModeOptions = new HList();
		$charsModeOptions->add(_hx_anonymous(array("key" => "Allow", "value" => "ALLOW")));
		$charsModeOptions->add(_hx_anonymous(array("key" => "Deny", "value" => "DENY")));
		$numberType = new HList();
		$numberType->add(_hx_anonymous(array("key" => "Float", "value" => "0")));
		$numberType->add(_hx_anonymous(array("key" => "Int", "value" => "1")));
		$this->elements = new HList();
		$this->form->addElement(new poko_form_elements_Readonly("att_name", "Field", $this->meta->name, null, null, null), null);
		if($this->meta->type == "linkdisplay" || $this->meta->type == "multilink") {
			$this->form->addElement(new poko_form_elements_Input("att_label", "Label", $data->label, null, null, null), null);
			$this->typeSelector = new poko_form_elements_Readonly("type", "Type", $this->meta->type, null, null, null);
			$this->form->addElement($this->typeSelector, null);
		}
		else {
			$this->form->addElement(new poko_form_elements_Input("att_label", "Label", $data->label, null, null, null), null);
			$this->form->addElement(new poko_form_elements_TextArea("att_description", "Description", $data->description, false, null, "class=\"resizable\" style=\"width: 200px; height: 3em;\""), null);
			$this->typeSelector = new poko_form_elements_Selectbox("type", "Type", $datatypes, $this->meta->type, true, "");
			$this->form->addElement($this->typeSelector, null);
			$this->form->addElement(new poko_form_elements_RadioGroup("att_showInList", "Show in List?", $yesno, ($this->meta->showInList ? "1" : "0"), "0", false, null), null);
			$this->form->addElement(new poko_form_elements_RadioGroup("att_showInFiltering", "Show in filter?", $yesno, ($this->meta->showInFiltering ? "1" : "0"), "0", false, null), null);
			$this->form->addElement(new poko_form_elements_RadioGroup("att_showInOrdering", "Enable ordering?", $yesno, ($this->meta->showInOrdering ? "1" : "0"), "0", false, null), null);
		}
		$this->form->addFieldset("properties", new poko_form_FieldSet("propertiesFieldset", "Properties", true));
		$this->form->addElement(new poko_form_elements_RadioGroup("def_text_isMultiline", "Multiline?", $yesno, $data->isMultiline, "0", false, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_text_width", "Width", $data->width, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_text_height", "Height", $data->height, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_text_minChars", "Min Chars", $data->minChars, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_text_maxChars", "Max Chars", $data->maxChars, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_text_charsList", "Chars List", $data->charsList, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_text_mode", "Chars List Mode", $charsModeOptions, $data->mode, "ALLOW", false, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_text_regex", "Regex", $data->regex, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_text_regexError", "Regex Error message", $data->regexError, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_text_regexDescription", "Regex Description", $data->regexDescription, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_text_regexCaseInsensitive", "Regex Case Insensitive", $yesno, $data->regexCaseInsensitive, "0", false, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_text_required", "Required", $yesno, $data->required, "0", false, null), "properties");
		$input = new poko_form_elements_Input("def_text_formatter", "Formatter Class", $data->formatter, false, null, null);
		$input->width = 400;
		$input->useSizeValues = true;
		$this->form->addElement($input, "properties");
		$this->form->addElement(new poko_form_elements_Input("def_number_min", "Min", $data->min, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_number_max", "Max", $data->max, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_number_isInt", "isInt", $numberType, $data->isInt, "0", false, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_number_required", "Required", $yesno, $data->required, "0", false, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_bool_labelTrue", "Label 'true'", $data->labelTrue, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_bool_labelFalse", "Label 'false'", $data->labelFalse, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_bool_showHideFields", "Hide Field(s)", $data->showHideFields, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_bool_showHideValue", "Hide on...", $truefalse, $data->showHideValue, "0", false, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_image-file_required", "Required", $yesno, $data->required, "0", false, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_image-file_isImage", "Type", $imagefile, $data->isImage, "0", false, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_image-file_extList", "Ext (jpg,gif,png)", $data->extList, false, null, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_image-file_extMode", "Ext Mode", $charsModeOptions, $data->extMode, "ALLOW", false, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_image-file_minSize", "Min size (Kb)", $data->minSize, false, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_image-file_maxSize", "Max size (Kb)", $data->maxSize, false, null, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_date_restrictMin", "Restrict Min", $yesno, $data->restrictMin, "0", false, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_date_minOffset", "Min Offset (-months)", $data->minOffset, false, null, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_date_restrictMax", "Restrict Max", $yesno, $data->restrictMax, "0", false, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_date_maxOffset", "Min Offset (+months)", $data->maxOffset, false, null, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_date_required", "Required", $yesno, $data->required, "0", false, null), "properties");
		$rtf = new HList();
		$rtf->add(_hx_anonymous(array("key" => "Simple", "value" => "SIMPLE")));
		$rtf->add(_hx_anonymous(array("key" => "Simple w/ Format", "value" => "FORMAT")));
		$rtf->add(_hx_anonymous(array("key" => "Simple w/ tables", "value" => "SIMPLE_TABLES")));
		$rtf->add(_hx_anonymous(array("key" => "Advanced", "value" => "ADVANCED")));
		$this->form->addElement(new poko_form_elements_Selectbox("def_richtext-tinymce_mode", "Mode", $rtf, $data->mode, false, ""), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_richtext-tinymce_width", "Width", $data->width, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_richtext-tinymce_height", "Height", $data->height, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_richtext-tinymce_content_css", "CSS file", $data->content_css, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_richtext-tinymce_required", "Required", $yesno, $data->required, "0", false, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_richtext-wym_width", "Width", $data->width, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_richtext-wym_height", "Height", $data->height, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_richtext-wym_required", "Required", $yesno, $data->required, "0", false, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_minRows", "Min Rows", $data->minRows, null, null, null), "properties");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_maxRows", "Max Rows", $data->maxRows, null, null, null), "properties");
		$this->form->addFieldset("key", new poko_form_FieldSet("def_keyvalue_keyFieldset", "Key", null));
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_keyLabel", "Label", $data->keyLabel, null, null, null), "key");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_keyvalue_keyIsMultiline", "Multiline?", $yesno, $data->keyIsMultiline, "0", false, null), "key");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_keyWidth", "Width", $data->keyWidth, null, null, null), "key");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_keyHeight", "Height", $data->keyHeight, null, null, null), "key");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_keyMinChars", "MinChars", $data->keyMinChars, null, null, null), "key");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_keyMaxChars", "MaxChars", $data->keyMaxChars, null, null, null), "key");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_keyCharsList", "Chars List", $data->keyCharsList, null, null, null), "key");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_keyvalue_keyNode", "Chars List Mode", $charsModeOptions, $data->keyMode, "ALLOW", false, null), "key");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_keyRegex", "Regex", $data->keyRegex, null, null, null), "key");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_keyRegexError", "Regex Error message", $data->keyRegexError, null, null, null), "key");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_keyDescription", "Regex Description", $data->keyDescription, null, null, null), "key");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_keyvalue_keyRegexCaseInsensitive", "Regex Case Insensitive", $yesno, $data->keyRegexCaseInsensitive, "0", false, null), "key");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_keyvalue_keyRequired", "Required", $yesno, $data->keyRequired, "0", false, null), "key");
		$this->form->addFieldset("value", new poko_form_FieldSet("def_keyvalue_valueFieldset", "Value", null));
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_valueLabel", "Label", $data->valueLabel, null, null, null), "value");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_keyvalue_valueIsMultiline", "Multiline?", $yesno, $data->valueIsMultiline, "0", false, null), "value");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_valueWidth", "Width", $data->valueWidth, null, null, null), "value");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_valueHeight", "Height", $data->valueHeight, null, null, null), "value");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_valueMinChars", "MinChars", $data->valueMinChars, null, null, null), "value");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_valueMaxChars", "MaxChars", $data->valueMaxChars, null, null, null), "value");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_valueCharsList", "Chars List", $data->valueCharsList, null, null, null), "value");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_keyvalue_valueMode", "Chars List Mode", $charsModeOptions, $data->valueMode, "ALLOW", false, null), "value");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_valueRegex", "Regex", $data->valueRegex, null, null, null), "value");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_valueRegexError", "Regex Error message", $data->valueRegexError, null, null, null), "value");
		$this->form->addElement(new poko_form_elements_Input("def_keyvalue_valueDescription", "Regex Description", $data->valueDescription, null, null, null), "value");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_keyvalue_valueRegexCaseInsensitive", "Regex Case Insensitive", $yesno, $data->valueRegexCaseInsensitive, "0", false, null), "value");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_keyvalue_valueRequired", "Required", $yesno, $data->valueRequired, "0", false, null), "value");
		$assoc1 = new poko_form_elements_Selectbox("def_association_table", "Table", $tableList, $data->table, null, null);
		$assoc1->onChange = $this->jsBind->getRawCall("onChangeSelectbox(this)");
		$this->form->addElement($assoc1, "properties");
		$assoc2 = new poko_form_elements_Selectbox("def_association_field", "Field", $assocFields, $data->field, null, null);
		$this->form->addElement($assoc2, "properties");
		$assoc3 = new poko_form_elements_Selectbox("def_association_fieldLabel", "Label", $assocFields, $data->fieldLabel, null, null);
		$this->form->addElement($assoc3, "properties");
		$assoc4 = new poko_form_elements_Input("def_association_fieldSql", "Label SQL", $data->fieldSql, null, null, null);
		$assoc4->useSizeValues = true;
		$assoc4->width = 400;
		$this->form->addElement($assoc4, "properties");
		$this->form->addElement(new poko_form_elements_RadioGroup("def_association_showAsLabel", "Show as Label?", $yesno, $data->showAsLabel, "0", false, null), "properties");
		$multiTable = new poko_form_elements_Selectbox("def_multilink_table", "With Table", $tableList, $data->table, null, null);
		$multiTable->onChange = $this->jsBind->getRawCall("onChangeSelectbox(this)");
		$this->form->addElement($multiTable, "properties");
		$multiField = new poko_form_elements_Selectbox("def_multilink_field", "With Field", $multiFields, $data->field, null, null);
		$this->form->addElement($multiField, "properties");
		$multiLabel = new poko_form_elements_Selectbox("def_multilink_fieldLabel", "With Label", $multiFields, $data->fieldLabel, null, null);
		$this->form->addElement($multiLabel, "properties");
		$multilinkTable = new poko_form_elements_Selectbox("def_multilink_link", "Link Table", $tableList, $data->link, null, null);
		$multilinkTable->onChange = $this->jsBind->getRawCall("onChangeSelectbox(this)");
		$this->form->addElement($multilinkTable, "properties");
		$multiLinkField1 = new poko_form_elements_Selectbox("def_multilink_linkField1", "Link Field 1", $multiLinkFields, $data->linkField1, null, null);
		$this->form->addElement($multiLinkField1, "properties");
		$multiLinkField2 = new poko_form_elements_Selectbox("def_multilink_linkField2", "Link Field 2", $multiLinkFields, $data->linkField2, null, null);
		$this->form->addElement($multiLinkField2, "properties");
		$assoc = new poko_form_elements_Selectbox("def_linkdisplay_table", "link Table", $tableList, $data->table, null, null);
		$this->form->addElement($assoc, "properties");
		$this->form->addElement(new poko_form_elements_Hidden("def_post-sql-value_updateKeyTable", $this->definition->table, null, null, null), "properties");
		$postSqlValue1 = new poko_form_elements_Selectbox("def_post-sql-value_table", "With Table", $tableList, $data->table, null, null);
		$postSqlValue1->onChange = $this->jsBind->getRawCall("onChangeSelectbox(this)");
		$this->form->addElement($postSqlValue1, "properties");
		$postSqlValue2 = new poko_form_elements_Selectbox("def_post-sql-value_updateTo", "Update to", $assocFields, $data->updateTo, null, null);
		$this->form->addElement($postSqlValue2, "properties");
		$postSqlValue3 = new poko_form_elements_Selectbox("def_post-sql-value_updateKey", "Local Key", $localFields, $data->updateKey, null, null);
		$this->form->addElement($postSqlValue3, "properties");
		$this->form->addFieldset("submit", new poko_form_FieldSet("__submit", "__submit", false));
		$this->form->setSubmitButton($this->form->addElement(new poko_form_elements_Button("submit", "Submit", null, null), "submit"));
		$this->form->populateElements();
		$this->fieldsets = $this->form->getFieldsets();
	}
	public function getFormData() {
		$data = _hx_anonymous(array());
		$type = $this->typeSelector->value;
		$data->type = $type;
		$»it = $this->form->getElements()->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		{
			if($element->name == "att_name") {
				$data->name = $element->value;
			}
			if($element->name == "att_label") {
				$data->label = $element->value;
			}
			if($element->name == "att_description") {
				$data->description = $element->value;
			}
			if($element->name == "att_showInList") {
				$data->showInList = ((_hx_equal($element->value, "1")) ? 1 : 0);
			}
			if($element->name == "att_showInFiltering") {
				$data->showInFiltering = ((_hx_equal($element->value, "1")) ? 1 : 0);
			}
			if($element->name == "att_showInOrdering") {
				$data->showInOrdering = ((_hx_equal($element->value, "1")) ? 1 : 0);
			}
			if(_hx_index_of($element->name, "def_" . $type, null) !== -1) {
				$attr = _hx_explode("_", $element->name)->pop();
				$data->{$attr} = $element->value;
			}
			unset($attr);
		}
		}
		return $data;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.base.DefinitionElement'; }
}
