<?php

class site_cms_modules_base_Dataset extends site_cms_modules_base_DatasetBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
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
	public $allowCsv;
	public $linkToField;
	public $linkTo;
	public $linkValueField;
	public $linkValue;
	public $associateExtras;
	public $jsBind;
	public $currentFilterSettings;
	public $autoFilterValue;
	public $autoFilterByAssocValue;
	public $tabFields;
	public $tabFilter;
	public $paginationLinks;
	public function init() {
		parent::init();
		$this->head->js->add("js/cms/jquery.qtip.min.js");
		$this->dataset = Std::parseInt($this->app->params->get("dataset"));
		$this->definition = new site_cms_common_Definition($this->dataset);
		$this->table = $this->definition->table;
		$this->label = $this->definition->name;
		$this->navigation->pageHeading .= (" (" . $this->label) . ")";
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.base.js.JsDataset");
		$this->remoting->addObject("api", _hx_anonymous(array("getFilterInfo" => (isset($this->getFilterInfo) ? $this->getFilterInfo: array($this, "getFilterInfo")))), null);
		if($this->linkMode) {
			$this->head->css->add("css/cms/miniView.css");
			$this->layoutView->template = "cms/templates/CmsTemplate_mini.mtt";
			;
		}
		;
	}
	public function getFilterInfo($field) {
		$response = _hx_anonymous(array());
		$response->type = $this->definition->getElement($field)->type;
		$response->ass = $this->definition->getElement($field)->properties->table;
		$response->data = $this->getAssocFields($field);
		return $response;
		unset($response);
	}
	public function getAssocFields($field) {
		$this->getAssociationExtras();
		return $this->associateExtras->get($field);
		;
	}
	public function main() {
		site_cms_modules_base_helper_FilterSettings::$lastDataset = $this->table;
		$this->orderField = $this->getOrderField();
		$this->isOrderingEnabled = $this->orderField !== null;
		$this->fields = $this->getFieldMatches();
		$primaryData = $this->app->getDb()->request(("SHOW COLUMNS FROM `" . $this->table) . "` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
		if($primaryData->length < 1) {
			$this->messages->addError(("<b>'" . $this->table) . "'</b> does not have a field set as both: <b>auto_increment</b> AND <b>primary key</b>");
			$this->setupLeftNav();
			$this->setContentOutput("cannot display dataset");
			return;
			;
		}
		else {
			$field = $primaryData->pop()->Field;
			$this->definition->primaryKey = $field;
			unset($field);
		}
		if($this->app->params->get("action")) {
			$this->process();
			;
		}
		$this->getAssociationExtras();
		$this->setupOptionsForm();
		$this->allowCsv = $this->definition->allowCsv;
		$ths = $this;
		$this->fieldLabels = Lambda::map($this->fields, array(new _hx_lambda(array(&$primaryData, &$ths), "site_cms_modules_base_Dataset_0"), 'execute'));
		$sql = ("SELECT *, `" . $this->definition->primaryKey) . "` as 'cms_primaryKey' ";
		if($this->orderField !== null) {
			$sql .= (", `" . $this->orderField) . "` as 'dataset__orderField' ";
			;
		}
		$sql .= ("FROM `" . $this->table) . "` ";
		$hasWhere = false;
		$this->currentFilterSettings = site_cms_modules_base_helper_FilterSettings::get($this->table);
		if(_hx_equal($this->app->params->get("resetState"), "true") || $this->optionsForm->isSubmitted()) {
			$this->currentFilterSettings->clear();
			;
		}
		if($this->definition->params->useTabulation) {
			$this->tabFields = haxe_Unserializer::run($this->definition->params->tabulationFields);
			$this->tabFilter = $this->app->params->get("tabFilter");
			if($this->tabFilter === null) {
				$this->tabFilter = "";
				;
			}
			;
		}
		$sqlWhere = "";
		$filterByValue = $this->optionsForm->getElement("filterBy")->value;
		$filterByAssocValue = $this->optionsForm->getElement("filterByAssoc")->value;
		$filterByOperatorValue = $this->optionsForm->getElement("filterByOperator")->value;
		$filterByValueValue = $this->optionsForm->getElement("filterByValue")->value;
		$this->autoFilterValue = site_cms_modules_base_Dataset_1($this, $filterByAssocValue, $filterByOperatorValue, $filterByValue, $filterByValueValue, $hasWhere, $primaryData, $sql, $sqlWhere, $ths);
		$this->autoFilterByAssocValue = site_cms_modules_base_Dataset_2($this, $filterByAssocValue, $filterByOperatorValue, $filterByValue, $filterByValueValue, $hasWhere, $primaryData, $sql, $sqlWhere, $ths);
		if($this->autoFilterValue !== null && $this->autoFilterByAssocValue !== null && $this->optionsForm->isSubmitted()) {
			$this->currentFilterSettings->enabled = false;
			$sqlWhere .= ((("WHERE `" . $this->autoFilterValue) . "`='") . $this->autoFilterByAssocValue) . "' ";
			$hasWhere = true;
			;
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
			;
		}
		if(($this->currentFilterSettings->enabled || $this->optionsForm->isSubmitted()) && $filterByValue !== null && $filterByValue !== "") {
			$elType = $this->definition->getElement($filterByValue)->type;
			if($elType === "enum" || $elType === "association" || $elType === "bool") {
				if($filterByAssocValue !== "") {
					$sqlWhere .= ((("WHERE `" . $filterByValue) . "`='") . $filterByAssocValue) . "' ";
					;
				}
				$hasWhere = true;
				;
			}
			else {
				if($filterByOperatorValue !== "" && $filterByValueValue !== "") {
					$op = site_cms_modules_base_Dataset_3($this, $elType, $filterByAssocValue, $filterByOperatorValue, $filterByValue, $filterByValueValue, $hasWhere, $primaryData, $sql, $sqlWhere, $ths);
					$val = site_cms_modules_base_Dataset_4($this, $elType, $filterByAssocValue, $filterByOperatorValue, $filterByValue, $filterByValueValue, $hasWhere, $op, $primaryData, $sql, $sqlWhere, $ths);
					if($elType === "date") {
						$sqlWhere .= ((((("WHERE `" . $filterByValue) . "` ") . $op) . " ") . $val) . " ";
						;
					}
					else {
						$sqlWhere .= ((((("WHERE `" . $filterByValue) . "` ") . $op) . " '") . $val) . "' ";
						;
					}
					$hasWhere = true;
					unset($val,$op);
				}
				;
			}
			unset($elType);
		}
		if($this->tabFilter !== null && $this->tabFilter !== "") {
			if(!$hasWhere) {
				$sqlWhere .= " WHERE ";
				;
			}
			$sqlWhere .= (" " . $this->tabFilter) . " ";
			;
		}
		if($this->linkMode) {
			$this->linkToField = $this->app->params->get("linkToField");
			$this->linkTo = $this->app->params->get("linkTo");
			$this->linkValueField = $this->app->params->get("linkValueField");
			$this->linkValue = Std::parseInt($this->app->params->get("linkValue"));
			if(!$hasWhere) {
				$sqlWhere .= " WHERE 1=1 ";
				;
			}
			if($this->linkToField !== null && $this->linkToField !== "") {
				$sqlWhere .= ((("AND `" . $this->linkToField) . "`=\"") . $this->linkTo) . "\" ";
				;
			}
			$sqlWhere .= ((("AND `" . $this->linkValueField) . "`=\"") . $this->linkValue) . "\" ";
			;
		}
		$orderByValue = $this->optionsForm->getElement("orderBy")->value;
		$orderByDirectionValue = $this->optionsForm->getElement("orderByDirection")->value;
		if($this->currentFilterSettings->enabled) {
			$orderByValue = $this->currentFilterSettings->orderBy;
			$orderByDirectionValue = $this->currentFilterSettings->orderByDirection;
			$this->optionsForm->getElement("orderBy")->value = $orderByValue;
			$this->optionsForm->getElement("orderByDirection")->value = $orderByDirectionValue;
			;
		}
		$sqlOrder = "";
		if($this->isOrderingEnabled && $this->orderField !== null && (!($this->optionsForm->isSubmitted() || $this->currentFilterSettings->enabled) || $orderByValue === null)) {
			$sqlOrder .= "ORDER BY `dataset__orderField`";
			;
		}
		else {
			if(($this->optionsForm->isSubmitted() || $this->currentFilterSettings->enabled) && $orderByValue !== null && $orderByValue !== "") {
				$sqlOrder .= (("ORDER BY `" . $orderByValue) . "` ") . $orderByDirectionValue;
				;
			}
			else {
				if($this->definition->autoOrderingField !== "" && $this->definition->autoOrderingField !== null) {
					$sqlOrder .= (("ORDER BY `" . $this->definition->autoOrderingField) . "` ") . $this->definition->autoOrderingOrder;
					;
				}
				else {
					$sqlOrder .= ("ORDER BY `" . $this->definition->primaryKey) . "`";
					;
				}
				;
			}
			;
		}
		$sqlLimit = " ";
		$this->paginationLinks = "";
		if($this->definition->params->usePaging) {
			$maxRecords = $this->app->getDb()->requestSingle((("SELECT COUNT(*) as count FROM `" . $this->table) . "` ") . $sqlWhere)->count;
			$perPage = $this->definition->params->perPage;
			$maxPage = Math::ceil($maxRecords / $perPage) - 1;
			$wantedPage = $this->app->params->get("page");
			if($wantedPage === null) {
				$wantedPage = 0;
				;
			}
			if($wantedPage > $maxPage) {
				$wantedPage = $maxPage;
				;
			}
			$startAt = $perPage * $wantedPage;
			if($startAt < 0) {
				$startAt = 0;
				;
			}
			$sqlLimit .= ((" LIMIT " . $startAt) . ", ") . $perPage;
			$this->paginationLinks = $this->pagination($maxPage + 1, $wantedPage, $this->definition->params->pagingRange);
			unset($wantedPage,$startAt,$perPage,$maxRecords,$maxPage);
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
			;
		}
		$sql = ((((($sql . " ") . $sqlWhere) . " ") . $sqlOrder) . " ") . $sqlLimit;
		$this->data = $this->app->getDb()->request($sql);
		$this->setupLeftNav();
		unset($ths,$sqlWhere,$sqlOrder,$sqlLimit,$sql,$primaryData,$orderByValue,$orderByDirectionValue,$hasWhere,$filterByValueValue,$filterByValue,$filterByOperatorValue,$filterByAssocValue);
	}
	public function pagination($numberOfPages, $currentPage, $range) {
		if($range === null) {
			$range = 4;
			;
		}
		if($currentPage === null) {
			$currentPage = 0;
			;
		}
		$showItems = ($range * 2) + 1;
		$currentPage++;
		$o = "";
		if($currentPage > 2 && $currentPage > $range + 1 && $showItems < $numberOfPages) {
			$o .= ("<a href='" . $this->getPageLink(1)) . "'>&laquo;</a>";
			;
		}
		if($currentPage > 1 && $showItems < $numberOfPages) {
			$o .= ("<a href='" . $this->getPageLink($currentPage - 1)) . "'>&lsaquo;</a>";
			;
		}
		{
			$_g1 = 1; $_g = $numberOfPages + 1;
			while($_g1 < $_g) {
				$i = $_g1++;
				if(1 !== $numberOfPages && (!($i >= ($currentPage + $range) + 1 || $i <= ($currentPage - $range) - 1) || $numberOfPages <= $showItems)) {
					$o .= site_cms_modules_base_Dataset_5($this, $_g, $_g1, $currentPage, $i, $numberOfPages, $o, $range, $showItems);
					;
				}
				unset($i);
			}
			unset($_g1,$_g);
		}
		if($currentPage < $numberOfPages && $showItems < $numberOfPages) {
			$o .= ("<a href=\"" . $this->getPageLink($currentPage + 1)) . "\">&rsaquo;</a>";
			;
		}
		if($currentPage < $numberOfPages - 1 && ($currentPage + $range) - 1 < $numberOfPages && $showItems < $numberOfPages) {
			$o .= ("<a href='" . $this->getPageLink($numberOfPages)) . "'>&raquo;</a>";
			;
		}
		$o .= ((("<span class=\"paginationOf\">Page " . $currentPage) . " of ") . $numberOfPages) . "</span>";
		return $o;
		unset($showItems,$o);
	}
	public function getPageLink($page) {
		$page = $page - 1;
		return (((((((((((((((((((("?request=cms.modules.base.Dataset&dataset=" . $this->dataset) . "&tabFilter=") . rawurlencode($this->tabFilter)) . "&page=") . Std::string($page)) . "&siteMode=") . (site_cms_modules_base_Dataset_6($this, $page))) . "&linkMode=") . $this->linkMode) . "&linkToField=") . $this->linkToField) . "&linkTo=") . $this->linkTo) . "&linkValueField=") . $this->linkValueField) . "&linkValue=") . $this->linkValue) . "&autofilterBy") . $this->autoFilterValue) . "&autofilterByAssoc") . $this->autoFilterByAssocValue;
		;
	}
	public function process() {
		if(_hx_equal($this->app->params->get("action"), "duplicate")) {
			$id = $this->app->params->get("id");
			if($id === null || _hx_equal($id, "")) {
				return;
				;
			}
			$data = $this->app->getDb()->requestSingle((((("SELECT * FROM `" . $this->table) . "` WHERE ") . $this->definition->primaryKey) . "=") . $this->app->getDb()->quote(Std::string($id)));
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
						php_io_File::copy("res/uploads/" . $value, ("res/uploads/" . $prefix) . _hx_substr($value, 32, null));
						unset($value,$prefix);
					}break;
					}
					unset($element);
				}
				unset($_g1,$_g);
			}
			$tableInfo = $this->app->getDb()->request(("SHOW FIELDS FROM `" . $this->table) . "`");
			$pField = _hx_anonymous(array());
			if(null == $tableInfo) throw new HException('null iterable');
			$»it = $tableInfo->iterator();
			while($»it->hasNext()) {
			$f = $»it->next();
			{
				if($f->Field === $this->definition->primaryKey) {
					$pField = $f;
					break;
					;
				}
				;
			}
			}
			if(_hx_equal(_hx_string_call($pField->Type, "indexOf", array("int")), 0) && _hx_equal($pField->Extra, "auto_increment")) {
				Reflect::deleteField($data, $this->definition->primaryKey);
				;
			}
			else {
				$this->messages->addError("Duplicate only works on datasets with primary keys that are auto-increment ints.");
				return;
				;
			}
			$this->app->getDb()->insert($this->table, $data);
			$insertedId = $this->app->getDb()->lastInsertId;
			if($this->app->getDb()->lastInsertId > 0) {
				$element = null;
				{
					$_g = 0; $_g1 = $this->definition->elements;
					while($_g < $_g1->length) {
						$element1 = $_g1[$_g];
						++$_g;
						switch($element1->type) {
						case "multilink":{
							$p = $element1->properties;
							$result = $this->app->getDb()->request((((((((("SELECT `" . $p->linkField1) . "`, `") . $p->linkField2) . "` FROM `") . $p->link) . "` WHERE `") . $p->linkField1) . "`=") . $this->app->getDb()->quote(Std::string($id)));
							if(null == $result) throw new HException('null iterable');
							$»it = $result->iterator();
							while($»it->hasNext()) {
							$o = $»it->next();
							{
								$o->{$p->linkField1} = $insertedId;
								$this->app->getDb()->insert($p->link, $o);
								;
							}
							}
							unset($result,$p);
						}break;
						}
						unset($element1);
					}
					unset($_g1,$_g);
				}
				$url = "?request=cms.modules.base.DatasetItem&action=edit";
				$url .= "&dataset=" . $this->dataset;
				$url .= "&id=" . $insertedId;
				$url .= "&linkMode=" . (site_cms_modules_base_Dataset_7($this, $data, $element, $id, $insertedId, $pField, $tableInfo, $url));
				$url .= "&linkToField=" . $this->app->params->get("linkToField");
				$url .= "&linkTo=" . $this->app->params->get("linkTo");
				$url .= "&linkValueField=" . $this->app->params->get("linkValueField");
				$url .= "&linkValue=" . $this->app->params->get("linkValue");
				$url .= "&autofilterByAssoc=" . $this->app->params->get("autofilterByAssoc");
				$url .= "&autofilterBy=" . $this->app->params->get("autofilterBy");
				if($this->siteMode) {
					$url .= "&siteMode=true";
					;
				}
				$this->messages->addMessage("Record duplicated.");
				$this->app->redirect($url);
				unset($url,$element);
			}
			else {
				$this->messages->addError("Error duplicating item.");
				;
			}
			unset($tableInfo,$pField,$insertedId,$id,$data);
		}
		else {
			if($this->app->params->get("submitted_delete") !== null) {
				if(php_Web::getParamValues("delete") !== null) {
					$runSqlPerRow = false;
					if(_hx_index_of($this->definition->postDeleteSql, "#", null) !== -1) {
						$runSqlPerRow = true;
						$this->messages->addDebug("Post-delete SQL running per row");
						;
					}
					else {
						if($this->definition->postDeleteSql !== null && $this->definition->postDeleteSql !== "") {
							$this->app->getDb()->request($this->definition->postDeleteSql);
							;
						}
						;
					}
					$numDeleted = 0;
					{
						$_g = 0; $_g1 = php_Web::getParamValues("delete");
						while($_g < $_g1->length) {
							$delId = $_g1[$_g];
							++$_g;
							$postProcedure = null;
							if($this->definition->postProcedure !== null && $this->definition->postProcedure !== "") {
								$c = Type::resolveClass($this->definition->postProcedure);
								if($c !== null) {
									$postProcedure = Type::createInstance($c, new _hx_array(array()));
									if(!Std::is($postProcedure, _hx_qtype("site.cms.common.Procedure"))) {
										$postProcedure = null;
										;
									}
									;
								}
								unset($c);
							}
							$tData = new HList();
							if($runSqlPerRow || $postProcedure !== null) {
								$tData = $this->app->getDb()->requestSingle(((((("SELECT * FROM `" . $this->table) . "` WHERE `") . $this->definition->primaryKey) . "`='") . $delId) . "'");
								;
							}
							try {
								$this->app->getDb()->delete($this->table, ((("`" . $this->definition->primaryKey) . "`='") . $delId) . "'");
								$numDeleted++;
								;
							}catch(Exception $»e) {
							$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
							;
							{ $e = $_ex_;
							{
								$this->messages->addError("There was a problem deleting your data.");
								if($runSqlPerRow) {
									$this->messages->addWarning("Post-delete SQL not run as delete run completed.");
									;
								}
								$runSqlPerRow = false;
								;
							}}}
							if($runSqlPerRow) {
								$tSql = $this->definition->postDeleteSql;
								{
									$_g2 = 0; $_g3 = Reflect::fields($tData);
									while($_g2 < $_g3->length) {
										$tField = $_g3[$_g2];
										++$_g2;
										$tSql = str_replace(("#" . $tField) . "#", Reflect::field($tData, $tField), $tSql);
										unset($tField);
									}
									unset($_g3,$_g2);
								}
								try {
									$this->app->getDb()->request($tSql);
									;
								}catch(Exception $»e) {
								$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
								;
								{ $e2 = $_ex_;
								{
									$this->messages->addError("Post-delete SQL had problems: " . $tSql);
									;
								}}}
								unset($tSql,$e2);
							}
							if($postProcedure !== null) {
								$postProcedure->postDelete($this->table, $tData);
								;
							}
							unset($tData,$postProcedure,$e,$delId);
						}
						unset($_g1,$_g);
					}
					$this->messages->addMessage($numDeleted . " record(s) deleted.");
					unset($runSqlPerRow,$numDeleted);
				}
				else {
					$this->messages->addWarning("Select records to delete.");
					;
				}
				;
			}
			else {
				if($this->app->params->get("submitted_order") !== null) {
					if($this->isOrderingEnabled) {
						$c = 0;
						{
							$_g = 0; $_g1 = php_Web::getParamValues("order");
							while($_g < $_g1->length) {
								$orderId = $_g1[$_g];
								++$_g;
								if($orderId !== null) {
									$d = _hx_anonymous(array());
									$d->{$this->orderField} = $orderId;
									$this->app->getDb()->update($this->table, $d, ((("`" . $this->definition->primaryKey) . "`='") . $c) . "'");
									unset($d);
								}
								$c++;
								unset($orderId);
							}
							unset($_g1,$_g);
						}
						$c = 0;
						$res = $this->app->getDb()->request(((((("SELECT `" . $this->definition->primaryKey) . "` as 'id' from ") . $this->table) . " ORDER BY `") . $this->orderField) . "`");
						$numSorted = 0;
						if(null == $res) throw new HException('null iterable');
						$»it = $res->iterator();
						while($»it->hasNext()) {
						$item = $»it->next();
						{
							$d = _hx_anonymous(array());
							$d->{$this->orderField} = ++$c;
							$this->app->getDb()->update($this->table, $d, ((("`" . $this->definition->primaryKey) . "`='") . $item->id) . "'");
							$numSorted++;
							unset($d);
						}
						}
						$this->messages->addMessage($numSorted . " record(s) sorted.");
						unset($res,$numSorted,$c);
					}
					else {
						$this->messages->addError("Tried to order records that are not allowed to be re-ordered!");
						;
					}
					;
				}
				;
			}
			;
		}
		;
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
					$sql = (((("SELECT " . $element1->properties->field) . " AS id, ") . $element1->properties->fieldLabel) . " AS label FROM ") . $element1->properties->table;
					$result = $this->app->getDb()->request($sql);
					$h = new Hash();
					if(null == $result) throw new HException('null iterable');
					$»it = $result->iterator();
					while($»it->hasNext()) {
					$e = $»it->next();
					$h->set(Std::string($e->id), $e->label);
					}
					$this->associateExtras->set($element1->properties->name, $h);
					unset($sql,$result,$h);
				}
				if(_hx_equal($element1->properties->type, "bool")) {
					$h = new Hash();
					if(!_hx_equal($element1->properties->labelTrue, "") && !_hx_equal($element1->properties->labelFalse, "")) {
						$h->set("1", $element1->properties->labelTrue);
						$h->set("0", $element1->properties->labelFalse);
						;
					}
					else {
						$h->set("1", "true");
						$h->set("0", "false");
						;
					}
					$this->associateExtras->set($element1->properties->name, $h);
					unset($h);
				}
				if(_hx_equal($element1->properties->type, "enum")) {
					$h = new Hash();
					$types = $this->app->getDb()->requestSingle(((("SHOW COLUMNS FROM `" . $this->table) . "` LIKE \"") . $element1->properties->name) . "\"");
					$s = Reflect::field($types, "Type");
					$items = _hx_explode("','", _hx_substr($s, 6, strlen($s) - 8));
					{
						$_g2 = 0;
						while($_g2 < $items->length) {
							$item = $items[$_g2];
							++$_g2;
							$h->set($item, $item);
							unset($item);
						}
						unset($_g2);
					}
					$this->associateExtras->set($element1->properties->name, $h);
					unset($types,$s,$items,$h);
				}
				unset($element1);
			}
			unset($_g1,$_g);
		}
		unset($element);
	}
	public function setupOptionsForm() {
		$this->showOrderBy = $this->definition->showOrdering;
		$this->showFiltering = $this->definition->showFiltering;
		$this->optionsForm = new poko_form_Form("options", null, null);
		$this->optionsForm->addElement(new poko_form_elements_Selectbox("filterBy", "filterSBy", null, null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Selectbox("filterByOperator", "filterOperator", null, null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Input("filterByValue", "filterByValue", null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Selectbox("filterByAssoc", "filterByAssoc", null, null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Selectbox("orderBy", "orderBy", null, null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Selectbox("orderByDirection", "direction", null, null, null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Hidden("reset", "false", null, null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Button("updateButton", "Update", null, null), null);
		$this->optionsForm->addElement(new poko_form_elements_Button("resetButton", "Reset", "", poko_form_elements_ButtonType::$BUTTON), null);
		$this->optionsForm->populateElements(null);
		$this->poplateOptionsFormData();
		;
	}
	public function poplateOptionsFormData() {
		if($this->showFiltering) {
			$filterBySelector = $this->optionsForm->getElementTyped("filterBy", _hx_qtype("poko.form.elements.Selectbox"));
			if(null == $this->fields) throw new HException('null iterable');
			$»it = $this->fields->iterator();
			while($»it->hasNext()) {
			$field = $»it->next();
			if($this->definition->getElement($field)->showInFiltering) {
				$label = site_cms_modules_base_Dataset_8($this, $field, $filterBySelector);
				$filterBySelector->data->add(_hx_anonymous(array("key" => $field, "value" => $label)));
				unset($label);
			}
			}
			$filterAssocSelector = $this->optionsForm->getElementTyped("filterByAssoc", _hx_qtype("poko.form.elements.Selectbox"));
			$currentFilter = site_cms_modules_base_helper_FilterSettings::getLast();
			$filterByValue = site_cms_modules_base_Dataset_9($this, $currentFilter, $filterAssocSelector, $filterBySelector);
			$data = $this->associateExtras->get($filterByValue);
			if($data !== null) {
				if(null == $data) throw new HException('null iterable');
				$»it = $data->keys();
				while($»it->hasNext()) {
				$d = $»it->next();
				$filterAssocSelector->data->add(_hx_anonymous(array("key" => $d, "value" => $data->get($d))));
				}
				;
			}
			$filterOperatorSelector = $this->optionsForm->getElementTyped("filterByOperator", _hx_qtype("poko.form.elements.Selectbox"));
			$filterOperatorSelector->data->add(_hx_anonymous(array("key" => "=", "value" => "=")));
			$filterOperatorSelector->data->add(_hx_anonymous(array("key" => "~", "value" => "~")));
			$filterOperatorSelector->data->add(_hx_anonymous(array("key" => ">", "value" => ">")));
			$filterOperatorSelector->data->add(_hx_anonymous(array("key" => "<", "value" => "<")));
			unset($filterOperatorSelector,$filterByValue,$filterBySelector,$filterAssocSelector,$data,$currentFilter);
		}
		if($this->showOrderBy) {
			$orderBySelector = $this->optionsForm->getElementTyped("orderBy", _hx_qtype("poko.form.elements.Selectbox"));
			if(null == $this->fields) throw new HException('null iterable');
			$»it = $this->fields->iterator();
			while($»it->hasNext()) {
			$field = $»it->next();
			if($this->definition->getElement($field)->showInOrdering) {
				$orderBySelector->data->add(_hx_anonymous(array("key" => $field, "value" => $field)));
				;
			}
			}
			$orderByDirectionSelector = $this->optionsForm->getElementTyped("orderByDirection", _hx_qtype("poko.form.elements.Selectbox"));
			$orderByDirectionSelector->data->add(_hx_anonymous(array("key" => "ASC", "value" => "ASC")));
			$orderByDirectionSelector->data->add(_hx_anonymous(array("key" => "DESC", "value" => "DESC")));
			unset($orderBySelector,$orderByDirectionSelector);
		}
		;
	}
	public function getOrderField() {
		{
			$_g = 0; $_g1 = $this->definition->elements;
			while($_g < $_g1->length) {
				$element = $_g1[$_g];
				++$_g;
				if($element->type === "order") {
					return $element->name;
					;
				}
				unset($element);
			}
			unset($_g1,$_g);
		}
		return null;
		;
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
			unset($_g1,$_g);
		}
		$ths = $this;
		$fields = $this->app->getDb()->request(("SHOW FIELDS FROM `" . $this->table) . "`");
		$fields = Lambda::map($fields, array(new _hx_lambda(array(&$definitionFields, &$fields, &$ths), "site_cms_modules_base_Dataset_10"), 'execute'));
		$fields = Lambda::filter($fields, array(new _hx_lambda(array(&$definitionFields, &$fields, &$ths), "site_cms_modules_base_Dataset_11"), 'execute'));
		return $fields;
		unset($ths,$fields,$definitionFields);
	}
	public function orderItems() {
		$c = 0;
		{
			$_g = 0; $_g1 = php_Web::getParamValues("orderNum");
			while($_g < $_g1->length) {
				$val = $_g1[$_g];
				++$_g;
				if($val !== null) {
					$this->app->getDb()->update("news", _hx_anonymous(array("order" => $val)), "`id`=" . $c);
					;
				}
				$c++;
				unset($val);
			}
			unset($_g1,$_g);
		}
		unset($c);
	}
	public function preview($row, $field) {
		$data = Reflect::field($row, $field);
		$properties = $this->definition->getElement($field)->properties;
		if($properties->formatter !== null && $properties->formatter !== "") {
			$f = Type::createInstance(Type::resolveClass($properties->formatter), new _hx_array(array()));
			return $f->format($data);
			unset($f);
		}
		else {
			return site_cms_modules_base_Dataset_12($this, $data, $field, $properties, $row);
			;
		}
		unset($properties,$data);
	}
	public function formatBool($data, $properties) {
		if(_hx_equal($properties->labelTrue, "") || _hx_equal($properties->labelFalse, "")) {
			return site_cms_modules_base_Dataset_13($this, $data, $properties);
			;
		}
		else {
			return site_cms_modules_base_Dataset_14($this, $data, $properties);
			;
		}
		;
	}
	public function formatDate($d, $mode) {
		if($mode === null) {
			$mode = "DATETIME";
			;
		}
		if(!Std::is($d, _hx_qtype("Date"))) {
			return null;
			;
		}
		$out = "";
		$months = Lambda::harray(poko_utils_ListData::arrayToList(poko_utils_ListData::$months, 1));
		if($mode === "DATE" || $mode === "DATETIME") {
			$out = ((($d->getDate() . " ") . _hx_array_get($months, $d->getMonth())->key) . " ") . $d->getFullYear();
			;
		}
		if($mode === "TIME" || $mode === "DATETIME") {
			$out .= ((((" " . str_pad(Std::string($d->getHours()), 2, "0", STR_PAD_LEFT)) . ":") . str_pad(Std::string($d->getMinutes()), 2, "0", STR_PAD_LEFT)) . ":") . str_pad(Std::string($d->getSeconds()), 2, "0", STR_PAD_LEFT);
			;
		}
		return $out;
		unset($out,$months);
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
	function __toString() { return 'site.cms.modules.base.Dataset'; }
}
;
function site_cms_modules_base_Dataset_0(&$primaryData, &$ths, $row) {
{
	$el = $ths->definition->getElement($row);
	return site_cms_modules_base_Dataset_15($»this, $el, $primaryData, $row, $ths);
	unset($el);
}
}
function site_cms_modules_base_Dataset_1(&$»this, &$filterByAssocValue, &$filterByOperatorValue, &$filterByValue, &$filterByValueValue, &$hasWhere, &$primaryData, &$sql, &$sqlWhere, &$ths) {
if(!_hx_equal($»this->app->params->get("autofilterBy"), "")) {
	return $»this->app->params->get("autofilterBy");
	;
}
}
function site_cms_modules_base_Dataset_2(&$»this, &$filterByAssocValue, &$filterByOperatorValue, &$filterByValue, &$filterByValueValue, &$hasWhere, &$primaryData, &$sql, &$sqlWhere, &$ths) {
if(!_hx_equal($»this->app->params->get("autofilterByAssoc"), "")) {
	return $»this->app->params->get("autofilterByAssoc");
	;
}
}
function site_cms_modules_base_Dataset_3(&$»this, &$elType, &$filterByAssocValue, &$filterByOperatorValue, &$filterByValue, &$filterByValueValue, &$hasWhere, &$primaryData, &$sql, &$sqlWhere, &$ths) {
if($filterByOperatorValue === "~") {
	return "LIKE";
	;
}
else {
	return $filterByOperatorValue;
	;
}
}
function site_cms_modules_base_Dataset_4(&$»this, &$elType, &$filterByAssocValue, &$filterByOperatorValue, &$filterByValue, &$filterByValueValue, &$hasWhere, &$op, &$primaryData, &$sql, &$sqlWhere, &$ths) {
if($filterByOperatorValue === "~") {
	return ("%" . $filterByValueValue) . "%";
	;
}
else {
	return $filterByValueValue;
	;
}
}
function site_cms_modules_base_Dataset_5(&$»this, &$_g, &$_g1, &$currentPage, &$i, &$numberOfPages, &$o, &$range, &$showItems) {
if($currentPage === $i) {
	return ("<span class=\"paginationCurrent\">" . $i) . "</span>";
	;
}
else {
	return ((("<a href='" . $»this->getPageLink($i)) . "' class=\"paginationInactive\">") . $i) . "</a>";
	;
}
}
function site_cms_modules_base_Dataset_6(&$»this, &$page) {
if($»this->siteMode) {
	return "true";
	;
}
else {
	return "false";
	;
}
}
function site_cms_modules_base_Dataset_7(&$»this, &$data, &$element, &$id, &$insertedId, &$pField, &$tableInfo, &$url) {
if($»this->linkMode) {
	return "true";
	;
}
else {
	return "false";
	;
}
}
function site_cms_modules_base_Dataset_8(&$»this, &$field, &$filterBySelector) {
if($»this->definition->getElement($field)->label !== "") {
	return $»this->definition->getElement($field)->label;
	;
}
else {
	return $field;
	;
}
}
function site_cms_modules_base_Dataset_9(&$»this, &$currentFilter, &$filterAssocSelector, &$filterBySelector) {
if($»this->optionsForm->isSubmitted()) {
	return $filterBySelector->value;
	;
}
else {
	return $currentFilter->filterBy;
	;
}
}
function site_cms_modules_base_Dataset_10(&$definitionFields, &$fields, &$ths, $row) {
{
	return $row->Field;
	;
}
}
function site_cms_modules_base_Dataset_11(&$definitionFields, &$fields, &$ths, $row) {
{
	$match = Lambda::has($definitionFields, $row, null) && ($ths->definition->getElement($row)->type !== "hidden" && $ths->definition->getElement($row)->type !== "order" && $ths->definition->getElement($row)->showInList);
	return $match || $row === $ths->definition->primaryKey;
	unset($match);
}
}
function site_cms_modules_base_Dataset_12(&$»this, &$data, &$field, &$properties, &$row) {
switch($properties->type) {
case "text":{
	return StringTools::htmlEscape(_hx_string_call(($data), "substr", array(0, 50))) . (site_cms_modules_base_Dataset_16($»this, $data, $field, $properties, $row));
	;
}break;
case "richtext-tinymce":{
	return StringTools::htmlEscape(_hx_string_call($data, "substr", array(0, 50))) . (site_cms_modules_base_Dataset_17($»this, $data, $field, $properties, $row));
	;
}break;
case "richtext-wym":{
	return StringTools::htmlEscape(_hx_string_call($data, "substr", array(0, 50))) . (site_cms_modules_base_Dataset_18($»this, $data, $field, $properties, $row));
	;
}break;
case "image-file":{
	if($properties->isImage === "1") {
		return ((("<a target=\"_blank\" href=\"?request=cms.services.Image&src=" . $data) . "\"><img src=\"?request=cms.services.Image&preset=tiny&src=") . $data) . "\" /></a> <br/>";
		;
	}
	else {
		if($data) {
			return ("<a target=\"_blank\" href=\"./res/uploads/" . $data) . "\" />file</a>";
			;
		}
		else {
			return "empty";
			;
		}
		;
	}
	;
}break;
case "bool":{
	return $»this->formatBool($data, $properties);
	;
}break;
case "date":{
	return $»this->formatDate($data, $properties->mode);
	;
}break;
case "keyvalue":{
	return "list of values";
	;
}break;
case "association":{
	if($properties->showAsLabel === "1") {
		return $»this->associateExtras->get($field)->get($data);
		;
	}
	else {
		return $data;
		;
	}
	;
}break;
default:{
	return $data;
	;
}break;
}
}
function site_cms_modules_base_Dataset_13(&$»this, &$data, &$properties) {
if($data) {
	return "&#x2714;";
	;
}
else {
	return "&#x02610;";
	;
}
}
function site_cms_modules_base_Dataset_14(&$»this, &$data, &$properties) {
if($data) {
	return $properties->labelTrue;
	;
}
else {
	return $properties->labelFalse;
	;
}
}
function site_cms_modules_base_Dataset_15(&$»this, &$el, &$primaryData, &$row, &$ths) {
if($el->label !== "") {
	return $el->label;
	;
}
else {
	return $el->name;
	;
}
}
function site_cms_modules_base_Dataset_16(&$»this, &$data, &$field, &$properties, &$row) {
if(_hx_len($data) > 50) {
	return "...";
	;
}
else {
	return "";
	;
}
}
function site_cms_modules_base_Dataset_17(&$»this, &$data, &$field, &$properties, &$row) {
if(_hx_len($data) > 50) {
	return "...";
	;
}
else {
	return "";
	;
}
}
function site_cms_modules_base_Dataset_18(&$»this, &$data, &$field, &$properties, &$row) {
if(_hx_len($data) > 50) {
	return "...";
	;
}
else {
	return "";
	;
}
}