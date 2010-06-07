<?php

class site_cms_modules_base_Dataset extends site_cms_modules_base_DatasetBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $dataset;
	public $label;
	public $table;
	public $data;
	public $fields;
	public $fieldLabels;
	public $definition;
	public $isOrderingEnabled;
	public $orderField;
	public $optionsForm;
	public $showOrderBy;
	public $showFiltering;
	public $linkToField;
	public $linkTo;
	public $linkValueField;
	public $linkValue;
	public $associateExtras;
	public $jsBind;
	public $currentFilterSettings;
	public $autoFilterValue;
	public $autoFilterByAssocValue;
	public function pre() {
		parent::pre();
		$this->head->js->add("js/cms/jquery.qtip.min.js");
		$this->dataset = Std::parseInt($this->application->params->get("dataset"));
		$this->definition = new site_cms_common_Definition($this->dataset);
		$this->table = $this->definition->table;
		$this->label = $this->definition->name;
		$this->navigation->pageHeading .= " (" . $this->label . ")";
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.base.js.JsDataset");
		$this->remoting->addObject("api", _hx_anonymous(array("getFilterInfo" => isset($this->getFilterInfo) ? $this->getFilterInfo: array($this, "getFilterInfo"))), null);
		if($this->linkMode) {
			$this->head->css->add("css/cms/miniView.css");
			$this->template_file = "site/cms/templates/CmsTemplate_mini.mtt";
		}
	}
	public function getFilterInfo($field) {
		$response = _hx_anonymous(array());
		$response->type = $this->definition->getElement($field)->type;
		$response->ass = $this->definition->getElement($field)->properties->table;
		$response->data = $this->getAssocFields($field);
		return $response;
	}
	public function getAssocFields($field) {
		$this->getAssociationExtras();
		return $this->associateExtras->get($field);
	}
	public function main() {
		site_cms_modules_base_FilterSettings::$lastDataset = $this->table;
		$this->orderField = $this->getOrderField();
		$this->isOrderingEnabled = $this->orderField !== null;
		$this->fields = $this->getFieldMatches();
		$primaryData = $this->application->db->request("SHOW COLUMNS FROM `" . $this->table . "` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
		if($primaryData->length < 1) {
			$this->application->messages->addError("<b>'" . $this->table . "'</b> does not have a field set as both: <b>auto_increment</b> AND <b>primary key</b>");
			$this->setupLeftNav();
			$this->setContentOutput("cannot display dataset");
			return;
		}
		else {
			$field = $primaryData->pop()->Field;
			$this->definition->primaryKey = $field;
		}
		if($this->application->params->get("action")) {
			$this->process();
		}
		$this->getAssociationExtras();
		$this->setupOptionsForm();
		$ths = $this;
		$this->fieldLabels = Lambda::map($this->fields, array(new _hx_lambda(array("field" => &$field, "primaryData" => &$primaryData, "ths" => &$ths), null, array('row'), "{
			\$el = \$ths->definition->getElement(\$row);
			return (\$el->label != \"\" ? \$el->label : \$el->name);
		}"), 'execute1'));
		$sql = "SELECT *, `" . $this->definition->primaryKey . "` as 'cms_primaryKey' ";
		if($this->orderField !== null) {
			$sql .= ", `" . $this->orderField . "` as 'dataset__orderField' ";
		}
		$sql .= "FROM `" . $this->table . "` ";
		$hasWhere = false;
		$this->currentFilterSettings = site_cms_modules_base_FilterSettings::get($this->table);
		if(_hx_equal($this->application->params->get("resetState"), "true") || $this->optionsForm->isSubmitted()) {
			$this->currentFilterSettings->clear();
		}
		$filterByValue = $this->optionsForm->getElement("filterBy")->value;
		$filterByAssocValue = $this->optionsForm->getElement("filterByAssoc")->value;
		$filterByOperatorValue = $this->optionsForm->getElement("filterByOperator")->value;
		$filterByValueValue = $this->optionsForm->getElement("filterByValue")->value;
		$this->autoFilterValue = (!_hx_equal($this->application->params->get("autofilterBy"), "") ? $this->application->params->get("autofilterBy") : null);
		$this->autoFilterByAssocValue = (!_hx_equal($this->application->params->get("autofilterByAssoc"), "") ? $this->application->params->get("autofilterByAssoc") : null);
		if($this->autoFilterValue !== null && $this->autoFilterByAssocValue !== null && !$this->optionsForm->isSubmitted()) {
			$this->currentFilterSettings->enabled = false;
			$sql .= "WHERE `" . $this->autoFilterValue . "`='" . $this->autoFilterByAssocValue . "' ";
			$hasWhere = true;
		}
		if($this->currentFilterSettings->enabled) {
			$filterByValue = $this->currentFilterSettings->filterBy;
			$filterByAssocValue = $this->currentFilterSettings->filterByAssoc;
			$filterByOperatorValue = $this->currentFilterSettings->filterByOperator;
			$filterByValueValue = $this->currentFilterSettings->filterByValue;
			$this->optionsForm->getElement("filterBy")->value = $filterByValue;
			$this->optionsForm->getElement("filterByAssoc")->value = $filterByAssocValue;
			$this->optionsForm->getElement("filterByOperator")->value = $filterByOperatorValue;
			$this->optionsForm->getElement("filterByValue")->value = $filterByValueValue;
		}
		if(($this->currentFilterSettings->enabled || $this->optionsForm->isSubmitted()) && $filterByValue !== null && $filterByValue != "") {
			if($this->definition->getElement($filterByValue)->type == "enum" || $this->definition->getElement($filterByValue)->type == "association" || $this->definition->getElement($filterByValue)->type == "bool") {
				if($filterByAssocValue != "") {
					$sql .= "WHERE `" . $filterByValue . "`='" . $filterByAssocValue . "' ";
				}
				$hasWhere = true;
			}
			else {
				if($filterByOperatorValue != "" && $filterByValueValue != "") {
					$op = ($filterByOperatorValue == "~" ? "LIKE" : $filterByOperatorValue);
					$val = ($filterByOperatorValue == "~" ? "%" . $filterByValueValue . "%" : $filterByValueValue);
					$sql .= "WHERE `" . $filterByValue . "` " . $op . " '" . $val . "' ";
					$hasWhere = true;
				}
			}
		}
		if($this->linkMode) {
			$this->linkToField = $this->application->params->get("linkToField");
			$this->linkTo = $this->application->params->get("linkTo");
			$this->linkValueField = $this->application->params->get("linkValueField");
			$this->linkValue = Std::parseInt($this->application->params->get("linkValue"));
			if(!$hasWhere) {
				$sql .= " WHERE ";
			}
			else {
				$sql .= " AND ";
			}
			$sql .= "`" . $this->linkToField . "`=\"" . $this->linkTo . "\" ";
			$sql .= "AND `" . $this->linkValueField . "`=\"" . $this->linkValue . "\" ";
		}
		$orderByValue = $this->optionsForm->getElement("orderBy")->value;
		$orderByDirectionValue = $this->optionsForm->getElement("orderByDirection")->value;
		if($this->currentFilterSettings->enabled) {
			$orderByValue = $this->currentFilterSettings->orderBy;
			$orderByDirectionValue = $this->currentFilterSettings->orderByDirection;
			$this->optionsForm->getElement("orderBy")->value = $orderByValue;
			$this->optionsForm->getElement("orderByDirection")->value = $orderByDirectionValue;
		}
		if($this->isOrderingEnabled && $this->orderField !== null && (!($this->optionsForm->isSubmitted() || $this->currentFilterSettings->enabled) || $orderByValue === null)) {
			$sql .= "ORDER BY `dataset__orderField`";
		}
		else {
			if(($this->optionsForm->isSubmitted() || $this->currentFilterSettings->enabled) && $orderByValue !== null && $orderByValue != "") {
				$sql .= "ORDER BY `" . $orderByValue . "` " . $orderByDirectionValue;
			}
			else {
				$sql .= "ORDER BY `" . $this->definition->primaryKey . "`";
			}
		}
		if($this->optionsForm->isSubmitted() && !_hx_equal($this->optionsForm->getElement("reset")->value, "true")) {
			$this->currentFilterSettings->enabled = true;
			$this->currentFilterSettings->filterBy = $filterByValue;
			$this->currentFilterSettings->filterByAssoc = $filterByAssocValue;
			$this->currentFilterSettings->filterByOperator = $filterByOperatorValue;
			$this->currentFilterSettings->filterByValue = $filterByValueValue;
			$this->currentFilterSettings->orderBy = $orderByValue;
			$this->currentFilterSettings->orderByDirection = $orderByDirectionValue;
			$this->currentFilterSettings->save();
		}
		$this->data = $this->application->db->request($sql);
		$this->setupLeftNav();
	}
	public function process() {
		if(_hx_equal($this->application->params->get("action"), "duplicate")) {
			$id = $this->application->params->get("id");
			if($id === null || _hx_equal($id, "")) {
				return;
			}
			$data = $this->application->db->requestSingle("SELECT * FROM `" . $this->table . "` WHERE " . $this->definition->primaryKey . "=" . $this->application->db->cnx->quote(Std::string($id)));
			{
				$_g = 0; $_g1 = $this->definition->elements;
				while($_g < $_g1->length) {
					$element = $_g1[$_g];
					++$_g;
					switch($element->type) {
					case "image-file":{
						$value = Reflect::field($data, $element->name);
						$prefix = haxe_Md5::encode(Date::now()->toString());
						$data->{$element->name} = $prefix . _hx_substr($value, 32, null);
						php_io_File::copy("res/uploads/" . $value, "res/uploads/" . $prefix . _hx_substr($value, 32, null));
					}break;
					}
					unset($value,$prefix,$element);
				}
			}
			$tableInfo = $this->application->db->request("SHOW FIELDS FROM `" . $this->table . "`");
			$pField = _hx_anonymous(array());
			$»it = $tableInfo->iterator();
			while($»it->hasNext()) {
			$f = $»it->next();
			{
				if($f->Field == $this->definition->primaryKey) {
					$pField = $f;
					break;
				}
				;
			}
			}
			if(_hx_equal(_hx_string_call($pField->Type, "indexOf", array("int")), 0) && _hx_equal($pField->Extra, "auto_increment")) {
				Reflect::deleteField($data, $this->definition->primaryKey);
			}
			else {
				$this->application->messages->addError("Duplicate only works on datasets with primary keys that are auto-increment ints.");
				return;
			}
			$this->application->db->insert($this->table, $data);
			$insertedId = $this->application->db->cnx->lastInsertId();
			if($this->application->db->lastAffectedRows > 0) {
				$element2 = null;
				{
					$_g2 = 0; $_g12 = $this->definition->elements;
					while($_g2 < $_g12->length) {
						$element1 = $_g12[$_g2];
						++$_g2;
						switch($element1->type) {
						case "multilink":{
							$p = $element1->properties;
							$result = $this->application->db->request("SELECT `" . $p->linkField1 . "`, `" . $p->linkField2 . "` FROM `" . $p->link . "` WHERE `" . $p->linkField1 . "`=" . $this->application->db->cnx->quote(Std::string($id)));
							$»it2 = $result->iterator();
							while($»it2->hasNext()) {
							$o = $»it2->next();
							{
								$o->{$p->linkField1} = $insertedId;
								$this->application->db->insert($p->link, $o);
								;
							}
							}
						}break;
						}
						unset($»it2,$result,$p,$o,$element1);
					}
				}
				$url = "?request=cms.modules.base.DatasetItem&action=edit";
				$url .= "&dataset=" . $this->dataset;
				$url .= "&id=" . $insertedId;
				$url .= "&linkMode=" . (($this->linkMode ? "true" : "false"));
				$url .= "&linkToField=" . $this->application->params->get("linkToField");
				$url .= "&linkTo=" . $this->application->params->get("linkTo");
				$url .= "&linkValueField=" . $this->application->params->get("linkValueField");
				$url .= "&linkValue=" . $this->application->params->get("linkValue");
				$url .= "&autofilterByAssoc=" . $this->application->params->get("autofilterByAssoc");
				$url .= "&autofilterBy=" . $this->application->params->get("autofilterBy");
				if($this->siteMode) {
					$url .= "&siteMode=true";
				}
				$this->application->redirect($url);
			}
			else {
				$this->application->messages->addError("Error duplicating item.");
			}
		}
		else {
			if(php_Web::getParamValues("delete") !== null) {
				$runSqlPerRow = false;
				if(_hx_index_of($this->definition->postDeleteSql, "#", null) !== -1) {
					$runSqlPerRow = true;
					$this->application->messages->addDebug("Post-delete SQL running per row");
				}
				else {
					if($this->definition->postDeleteSql !== null && $this->definition->postDeleteSql != "") {
						$this->application->db->request($this->definition->postDeleteSql);
					}
				}
				$numDeleted = 0;
				{
					$_g3 = 0; $_g13 = php_Web::getParamValues("delete");
					while($_g3 < $_g13->length) {
						$delId = $_g13[$_g3];
						++$_g3;
						$postProcedure = null;
						if($this->definition->postProcedure !== null && $this->definition->postProcedure != "") {
							$c = Type::resolveClass($this->definition->postProcedure);
							if($c !== null) {
								$postProcedure = Type::createInstance($c, new _hx_array(array()));
								if(!Std::is($postProcedure, _hx_qtype("site.cms.common.Procedure"))) {
									$postProcedure = null;
								}
							}
						}
						$tData = new HList();
						if($runSqlPerRow || $postProcedure !== null) {
							$tData = $this->application->db->requestSingle("SELECT * FROM `" . $this->table . "` WHERE `" . $this->definition->primaryKey . "`='" . $delId . "'");
						}
						try {
							$this->application->db->delete($this->table, "`" . $this->definition->primaryKey . "`='" . $delId . "'");
							$numDeleted++;
						}catch(Exception $»e) {
						$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
						;
						{ $e = $_ex_;
						{
							$this->application->messages->addError("There was a problem deleting your data.");
							if($runSqlPerRow) {
								$this->application->messages->addWarning("Post-delete SQL not run as delete run completed.");
							}
							$runSqlPerRow = false;
						}}}
						if($runSqlPerRow) {
							$tSql = $this->definition->postDeleteSql;
							{
								$_g22 = 0; $_g32 = Reflect::fields($tData);
								while($_g22 < $_g32->length) {
									$tField = $_g32[$_g22];
									++$_g22;
									$tSql = str_replace("#" . $tField . "#", Reflect::field($tData, $tField), $tSql);
									unset($tField);
								}
							}
							try {
								$this->application->db->request($tSql);
							}catch(Exception $»e2) {
							$_ex_2 = ($»e2 instanceof HException) ? $»e2->e : $»e2;
							;
							{ $e2 = $_ex_2;
							{
								$this->application->messages->addError("Post-delete SQL had problems: " . $tSql);
							}}}
						}
						if($postProcedure !== null) {
							$postProcedure->postDelete($this->table, $tData);
						}
						unset($»e2,$»e,$tSql,$tField,$tData,$postProcedure,$e2,$e,$delId,$c,$_g32,$_g22,$_ex_2,$_ex_);
					}
				}
				$this->application->messages->addMessage($numDeleted . " record(s) deleted.");
			}
			if($this->isOrderingEnabled) {
				$c2 = 0;
				{
					$_g4 = 0; $_g14 = php_Web::getParamValues("order");
					while($_g4 < $_g14->length) {
						$orderId = $_g14[$_g4];
						++$_g4;
						if($orderId !== null) {
							$d = _hx_anonymous(array());
							$d->{$this->orderField} = $orderId;
							$this->application->db->update($this->table, $d, "`" . $this->definition->primaryKey . "`='" . $c2 . "'");
						}
						$c2++;
						unset($orderId,$d);
					}
				}
				$c2 = 0;
				$res = $this->application->db->request("SELECT `" . $this->definition->primaryKey . "` as 'id' from " . $this->table . " ORDER BY `" . $this->orderField . "`");
				$»it3 = $res->iterator();
				while($»it3->hasNext()) {
				$item = $»it3->next();
				{
					$d2 = _hx_anonymous(array());
					$d2->{$this->orderField} = ++$c2;
					$this->application->db->update($this->table, $d2, "`" . $this->definition->primaryKey . "`='" . $item->id . "'");
					unset($d2);
				}
				}
			}
		}
	}
	public function getAssociationExtras() {
		$this->associateExtras = new Hash();
		$element = null;
		{
			$_g = 0; $_g1 = $this->definition->elements;
			while($_g < $_g1->length) {
				$element1 = $_g1[$_g];
				++$_g;
				if(_hx_equal($element1->properties->type, "association") && _hx_equal($element1->properties->showAsLabel, "1")) {
					$sql = "SELECT " . $element1->properties->field . " AS id, " . $element1->properties->fieldLabel . " AS label FROM " . $element1->properties->table;
					$result = $this->application->db->request($sql);
					$h = new Hash();
					$»it = $result->iterator();
					while($»it->hasNext()) {
					$e = $»it->next();
					$h->set(Std::string($e->id), $e->label);
					}
					$this->associateExtras->set($element1->properties->name, $h);
				}
				if(_hx_equal($element1->properties->type, "bool")) {
					$h2 = new Hash();
					if(!_hx_equal($element1->properties->labelTrue, "") && !_hx_equal($element1->properties->labelFalse, "")) {
						$h2->set("1", $element1->properties->labelTrue);
						$h2->set("0", $element1->properties->labelFalse);
					}
					else {
						$h2->set("1", "true");
						$h2->set("0", "false");
					}
					$this->associateExtras->set($element1->properties->name, $h2);
				}
				if(_hx_equal($element1->properties->type, "enum")) {
					$h3 = new Hash();
					$types = $this->application->db->requestSingle("SHOW COLUMNS FROM `" . $this->table . "` LIKE \"" . $element1->properties->name . "\"");
					$s = Reflect::field($types, "Type");
					$items = _hx_explode("','", _hx_substr($s, 6, strlen($s) - 8));
					{
						$_g2 = 0;
						while($_g2 < $items->length) {
							$item = $items[$_g2];
							++$_g2;
							$h3->set($item, $item);
							unset($item);
						}
					}
					$this->associateExtras->set($element1->properties->name, $h3);
				}
				unset($»it,$types,$sql,$s,$result,$items,$item,$h3,$h2,$h,$element1,$e,$_g2);
			}
		}
	}
	public function setupOptionsForm() {
		$this->showOrderBy = $this->definition->showOrdering;
		$this->showFiltering = $this->definition->showFiltering;
		$this->optionsForm = new poko_form_Form("options", null, null);
		$this->optionsForm->addElement(new poko_form_elements_Selectbox("filterBy", "filterSBy", null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Selectbox("filterByOperator", "filterOperator", null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Input("filterByValue", "filterByValue", null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Selectbox("filterByAssoc", "filterByAssoc", null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Selectbox("orderBy", "orderBy", null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Selectbox("orderByDirection", "direction", null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Hidden("reset", "false", null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Button("updateButton", "Update", null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Button("resetButton", "Reset", "", poko_form_elements_ButtonType::$BUTTON), null);
		$this->optionsForm->populateElements();
		$this->poplateOptionsFormData();
	}
	public function poplateOptionsFormData() {
		if($this->showFiltering) {
			$filterBySelector = $this->optionsForm->getElementTyped("filterBy", _hx_qtype("poko.form.elements.Selectbox"));
			$»it = $this->fields->iterator();
			while($»it->hasNext()) {
			$field = $»it->next();
			if($this->definition->getElement($field)->showInFiltering) {
				$label = ($this->definition->getElement($field)->label != "" ? $this->definition->getElement($field)->label : $field);
				$filterBySelector->addOption(_hx_anonymous(array("key" => $label, "value" => $field)));
			}
			}
			$filterAssocSelector = $this->optionsForm->getElementTyped("filterByAssoc", _hx_qtype("poko.form.elements.Selectbox"));
			$currentFilter = site_cms_modules_base_FilterSettings::getLast();
			$filterByValue = (($this->optionsForm->isSubmitted()) ? $filterBySelector->value : $currentFilter->filterBy);
			$data = $this->associateExtras->get($filterByValue);
			if($data !== null) {
				$»it2 = $data->keys();
				while($»it2->hasNext()) {
				$d = $»it2->next();
				$filterAssocSelector->addOption(_hx_anonymous(array("key" => $data->get($d), "value" => $d)));
				}
			}
			$filterOperatorSelector = $this->optionsForm->getElementTyped("filterByOperator", _hx_qtype("poko.form.elements.Selectbox"));
			$filterOperatorSelector->addOption(_hx_anonymous(array("key" => "=", "value" => "=")));
			$filterOperatorSelector->addOption(_hx_anonymous(array("key" => "~", "value" => "~")));
			$filterOperatorSelector->addOption(_hx_anonymous(array("key" => ">", "value" => ">")));
			$filterOperatorSelector->addOption(_hx_anonymous(array("key" => "<", "value" => "<")));
		}
		if($this->showOrderBy) {
			$orderBySelector = $this->optionsForm->getElementTyped("orderBy", _hx_qtype("poko.form.elements.Selectbox"));
			$»it3 = $this->fields->iterator();
			while($»it3->hasNext()) {
			$field2 = $»it3->next();
			if($this->definition->getElement($field2)->showInOrdering) {
				$orderBySelector->addOption(_hx_anonymous(array("key" => $field2, "value" => $field2)));
			}
			}
			$orderByDirectionSelector = $this->optionsForm->getElementTyped("orderByDirection", _hx_qtype("poko.form.elements.Selectbox"));
			$orderByDirectionSelector->addOption(_hx_anonymous(array("key" => "ASC", "value" => "ASC")));
			$orderByDirectionSelector->addOption(_hx_anonymous(array("key" => "DESC", "value" => "DESC")));
		}
	}
	public function getOrderField() {
		{
			$_g = 0; $_g1 = $this->definition->elements;
			while($_g < $_g1->length) {
				$element = $_g1[$_g];
				++$_g;
				if($element->type == "order") {
					return $element->name;
				}
				unset($element);
			}
		}
		return null;
	}
	public function getFieldMatches() {
		$definitionFields = new _hx_array(array());
		{
			$_g = 0; $_g1 = $this->definition->elements;
			while($_g < $_g1->length) {
				$element = $_g1[$_g];
				++$_g;
				$definitionFields->push($element->name);
				unset($element);
			}
		}
		$ths = $this;
		$fields = $this->application->db->request("SHOW FIELDS FROM `" . $this->table . "`");
		$fields = Lambda::map($fields, array(new _hx_lambda(array("_g" => &$_g, "_g1" => &$_g1, "definitionFields" => &$definitionFields, "element" => &$element, "fields" => &$fields, "ths" => &$ths), null, array('row'), "{
			return \$row->Field;
		}"), 'execute1'));
		$fields = Lambda::filter($fields, array(new _hx_lambda(array("_g" => &$_g, "_g1" => &$_g1, "definitionFields" => &$definitionFields, "element" => &$element, "fields" => &$fields, "ths" => &$ths), null, array('row'), "{
			\$match = Lambda::has(\$definitionFields, \$row, null) && ((\$ths->definition->getElement(\$row)->type != \"hidden\" && \$ths->definition->getElement(\$row)->type != \"order\" && \$ths->definition->getElement(\$row)->showInList));
			return \$match || \$row == \$ths->definition->primaryKey;
		}"), 'execute1'));
		return $fields;
	}
	public function orderItems() {
		$c = 0;
		{
			$_g = 0; $_g1 = php_Web::getParamValues("orderNum");
			while($_g < $_g1->length) {
				$val = $_g1[$_g];
				++$_g;
				if($val !== null) {
					$this->application->db->update("news", _hx_anonymous(array("order" => $val)), "`id`=" . $c);
				}
				$c++;
				unset($val);
			}
		}
	}
	public function preview($row, $field) {
		$data = Reflect::field($row, $field);
		$properties = $this->definition->getElement($field)->properties;
		if($properties->formatter !== null && $properties->formatter != "") {
			$f = Type::createInstance(Type::resolveClass($properties->formatter), new _hx_array(array()));
			return $f->format($data);
		}
		else {
			return eval("if(isset(\$this)) \$»this =& \$this;switch(\$properties->type) {
				case \"text\":{
					\$»r = _hx_string_call((\$data), \"substr\", array(0, 50)) . ((_hx_len(\$data) > 50 ? \"...\" : \"\"));
				}break;
				case \"richtext-tinymce\":{
					\$»r = StringTools::htmlEscape(_hx_string_call(\$data, \"substr\", array(0, 50))) . (((_hx_len(\$data) > 50) ? \"...\" : \"\"));
				}break;
				case \"richtext-wym\":{
					\$»r = StringTools::htmlEscape(_hx_string_call(\$data, \"substr\", array(0, 50))) . (((_hx_len(\$data) > 50) ? \"...\" : \"\"));
				}break;
				case \"image-file\":{
					\$»r = (\$properties->isImage == \"1\" ? \"<a target=\\\"_blank\\\" href=\\\"?request=cms.services.Image&src=\" . \$data . \"\\\"><img src=\\\"?request=cms.services.Image&preset=tiny&src=\" . \$data . \"\\\" /></a> <br/>\" : (\$data ? \"<a target=\\\"_blank\\\" href=\\\"./res/uploads/\" . \$data . \"\\\" />file</a>\" : \"empty\"));
				}break;
				case \"bool\":{
					\$»r = \$»this->formatBool(\$data, \$properties);
				}break;
				case \"date\":{
					\$»r = \$»this->formatDate(\$data);
				}break;
				case \"keyvalue\":{
					\$»r = \"list of values\";
				}break;
				case \"association\":{
					\$»r = (\$properties->showAsLabel == \"1\" ? \$»this->associateExtras->get(\$field)->get(\$data) : \$data);
				}break;
				default:{
					\$»r = \$data;
				}break;
				}
				return \$»r;
			");
		}
	}
	public function formatBool($data, $properties) {
		if(_hx_equal($properties->labelTrue, "") || _hx_equal($properties->labelFalse, "")) {
			return ($data ? "&#x2714;" : "&#x02610;");
		}
		else {
			return ($data ? $properties->labelTrue : $properties->labelFalse);
		}
	}
	public function formatDate($d) {
		if(!Std::is($d, _hx_qtype("Date"))) {
			return null;
		}
		$months = Lambda::harray(poko_utils_ListData::getMonths(null));
		return $d->getDate() . " " . _hx_array_get($months, $d->getMonth())->key . " " . $d->getFullYear();
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.base.Dataset'; }
}
