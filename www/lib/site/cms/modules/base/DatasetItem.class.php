<?php

class site_cms_modules_base_DatasetItem extends site_cms_modules_base_DatasetBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $id;
	public $dataset;
	public $table;
	public $definition;
	public $label;
	public $form;
	public $page;
	public $data;
	public $isOrderingEnabled;
	public $orderField;
	public $jsBind;
	public $autoFilterValue;
	public $autoFilterByAssocValue;
	public $singleInstanceEdit;
	public function init() {
		parent::init();
		$this->head->js->add("js/cms/jquery-ui-1.7.2.custom.min.js");
		$this->head->css->add("css/cms/ui-lightness/jquery-ui-1.7.2.custom.css");
		$this->head->js->add("js/cms/jquery.qtip.min.js");
		$this->head->js->add("js/cms/tiny_mce/tiny_mce.js");
		$this->head->js->add("js/cms/tiny_mce_browse.js");
		$this->head->js->add("js/cms/wymeditor/jquery.wymeditor.pack.js");
		$this->head->js->add("js/cms/wym_editor_browse.js");
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.base.js.JsDatasetItem");
		$this->remoting->addObject("api", _hx_anonymous(array("deleteFile" => isset($this->deleteFile) ? $this->deleteFile: array($this, "deleteFile"))), null);
		$this->singleInstanceEdit = $this->app->params->get("singleInstanceEdit");
		if($this->linkMode) {
			$this->head->css->add("css/cms/miniView.css");
			$this->view->template = "cms/templates/CmsTemplate_mini.mtt";
		}
	}
	public function main() {
		$this->data = _hx_anonymous(array());
		$this->id = Std::parseInt($this->app->params->get("id"));
		$this->dataset = Std::parseInt($this->app->params->get("dataset"));
		$this->isOrderingEnabled = false;
		if(!$this->pagesMode) {
			$this->definition = new site_cms_common_Definition($this->dataset);
			$this->label = $this->definition->name;
			$this->table = $this->definition->table;
			$this->data = $this->app->getDb()->requestSingle("SELECT * FROM `" . $this->table . "` WHERE `id`=" . $this->app->getDb()->cnx->quote(Std::string($this->id)));
			$this->orderField = $this->getOrderField();
			$this->isOrderingEnabled = $this->orderField !== null;
			$this->autoFilterValue = (!_hx_equal($this->app->params->get("autofilterBy"), "") ? $this->app->params->get("autofilterBy") : null);
			$this->autoFilterByAssocValue = (!_hx_equal($this->app->params->get("autofilterByAssoc"), "") ? $this->app->params->get("autofilterByAssoc") : null);
		}
		else {
			$result = $this->app->getDb()->requestSingle("SELECT * FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id AND p.id=" . $this->app->getDb()->cnx->quote(Std::string($this->id)));
			$this->label = $this->page = $result->name;
			$this->data = ($result->data != "" ? haxe_Unserializer::run($result->data) : _hx_anonymous(array()));
			$this->definition = new site_cms_common_Definition($result->definitionId);
		}
		$this->navigation->pageHeading .= " (" . $this->label . ")";
		$this->setupForm();
		if($this->form->isSubmitted() && $this->form->isValid()) {
			$this->processForm();
		}
		$this->setupForm();
		$this->setupLeftNav();
	}
	public function deleteFile($filename, $display) {
		$this->id = Std::parseInt($this->app->params->get("id"));
		$this->dataset = Std::parseInt($this->app->params->get("dataset"));
		$this->definition = new site_cms_common_Definition($this->dataset);
		$this->table = $this->definition->table;
		try {
			$d = new Hash();
			$d->set(_hx_substr($display, 18, null), "");
			$result = $this->app->getDb()->update($this->table, $d, "id=" . $this->id);
			return _hx_anonymous(array("success" => @unlink(site_cms_PokoCms::$uploadFolder . "/" . $filename) && $result, "display" => $display, "error" => null));
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			return _hx_anonymous(array("success" => false, "display" => $display, "error" => $e));
		}}}
	}
	public function processForm() {
		$data = $this->form->getData();
		$uploadData = $this->uploadFiles();
		$»it = $uploadData->keys();
		while($»it->hasNext()) {
		$k = $»it->next();
		{
			$data->{$k} = $uploadData->get($k);
			;
		}
		}
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
		switch($this->form->getElement("__action")->value) {
		case "add":{
			if($this->isOrderingEnabled) {
				$result = $this->app->getDb()->requestSingle("SELECT MAX(`" . $this->orderField . "`) as 'order' FROM `" . $this->table . "`");
				$data->{$this->orderField} = Std::string($result->order + 1);
			}
			$doPost = true;
			try {
				$this->app->getDb()->insert($this->table, $data);
				$this->id = $this->app->getDb()->cnx->lastInsertId();
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e = $_ex_;
			{
				$doPost = false;
				$this->messages->addError("Update failed. Not running post commands or procedures.");
			}}}
			if($doPost) {
				$primaryKey = $this->app->getDb()->getPrimaryKey($this->table);
				if($primaryKey !== null) {
					$data->{$primaryKey} = $this->id;
					if($this->definition->postCreateSql !== null && $this->definition->postCreateSql != "") {
						$tSql = $this->definition->postCreateSql;
						{
							$_g = 0; $_g1 = Reflect::fields($data);
							while($_g < $_g1->length) {
								$tField = $_g1[$_g];
								++$_g;
								$tSql = str_replace("#" . $tField . "#", Reflect::field($data, $tField), $tSql);
								unset($tField);
							}
						}
						try {
							$this->app->getDb()->query($tSql);
						}catch(Exception $»e2) {
						$_ex_2 = ($»e2 instanceof HException) ? $»e2->e : $»e2;
						;
						{ $e2 = $_ex_2;
						{
							$this->messages->addError("Post-create SQL had problems: " . $tSql);
						}}}
					}
				}
				else {
					$this->messages->addError("Could not get the primary key from newly created record. Post-SQL not run.");
				}
				if($postProcedure !== null) {
					$postProcedure->postCreate($this->table, $data, $this->id);
				}
			}
		}break;
		case "edit":{
			if($this->pagesMode) {
				$sdata = haxe_Serializer::run($data);
				$this->app->getDb()->update("_pages", _hx_anonymous(array("data" => $sdata)), "`id`=" . $this->app->getDb()->cnx->quote(Std::string($this->id)));
			}
			else {
				$oldData = $this->app->getDb()->requestSingle("SELECT * FROM `" . $this->table . "` WHERE `id`=" . $this->app->getDb()->cnx->quote(Std::string($this->id)));
				$doPost2 = true;
				try {
					$this->app->getDb()->update($this->table, $data, "`id`=" . $this->app->getDb()->cnx->quote(Std::string($this->id)));
				}catch(Exception $»e3) {
				$_ex_3 = ($»e3 instanceof HException) ? $»e3->e : $»e3;
				;
				{ $e3 = $_ex_3;
				{
					$doPost2 = false;
					$this->messages->addError("Update failed. Not running post commands or procedures.");
				}}}
				if($doPost2) {
					if($this->definition->postEditSql !== null && $this->definition->postEditSql != "") {
						$tSql2 = $this->definition->postEditSql;
						{
							$_g2 = 0; $_g12 = Reflect::fields($oldData);
							while($_g2 < $_g12->length) {
								$tField2 = $_g12[$_g2];
								++$_g2;
								$tSql2 = str_replace("#" . $tField2 . "#", Reflect::field($oldData, $tField2), $tSql2);
								unset($tField2);
							}
						}
						{
							$_g3 = 0; $_g13 = Reflect::fields($data);
							while($_g3 < $_g13->length) {
								$tField3 = $_g13[$_g3];
								++$_g3;
								$tSql2 = str_replace("*" . $tField3 . "*", Reflect::field($data, $tField3), $tSql2);
								unset($tField3);
							}
						}
						try {
							$this->app->getDb()->query($tSql2);
						}catch(Exception $»e4) {
						$_ex_4 = ($»e4 instanceof HException) ? $»e4->e : $»e4;
						;
						{ $e4 = $_ex_4;
						{
							$this->messages->addError("Post-delete SQL had problems: " . $tSql2);
						}}}
					}
					if($postProcedure !== null) {
						$postProcedure->postUpdate($this->table, $oldData, $data);
					}
				}
			}
		}break;
		}
		$elements = ($this->pagesMode ? Lambda::hlist($this->definition->elements) : $this->getElementMatches());
		$»it2 = $elements->iterator();
		while($»it2->hasNext()) {
		$element = $»it2->next();
		{
			if($element->type == "multilink") {
				$this->app->getDb()->delete($element->properties->link, "`" . $element->properties->linkField1 . "`=" . $this->app->getDb()->cnx->quote(Std::string($this->id)));
				{
					$_g4 = 0; $_g14 = eval("if(isset(\$this)) \$»this =& \$this;\$tmp = Reflect::field(\$data, \$element->name);
						\$»r = (Std::is(\$tmp, _hx_qtype(\"Array\")) ? \$tmp : eval(\"if(isset(\\\$this)) \\\$»this =& \\\$this;throw new HException(\\\"Class cast error\\\");
							return \\\$»r2;
						\"));
						return \$»r;
					");
					while($_g4 < $_g14->length) {
						$check = $_g14[$_g4];
						++$_g4;
						$d = _hx_anonymous(array());
						$d->{$element->properties->linkField1} = $this->id;
						$d->{$element->properties->linkField2} = $check;
						$this->app->getDb()->insert($element->properties->link, $d);
						unset($d,$check);
					}
				}
			}
			if($element->type == "post-sql-value") {
				$updateKeyValue = Reflect::field($data, $element->properties->updateKey);
				$primaryKey2 = $this->app->getDb()->getPrimaryKey($this->table);
				if($primaryKey2 !== null && $updateKeyValue !== null) {
					$result2 = $this->app->getDb()->requestSingle("SELECT `" . $element->properties->updateTo . "` AS `__v` FROM `" . $element->properties->table . "` WHERE `" . $primaryKey2 . "`='" . $updateKeyValue . "'");
					$tData = _hx_anonymous(array());
					try {
						$tData->{$element->name} = $result2->__v;
						$this->app->getDb()->update($this->table, $tData, "`id`=" . $this->app->getDb()->cnx->quote(Std::string($this->id)));
					}catch(Exception $»e5) {
					$_ex_5 = ($»e5 instanceof HException) ? $»e5->e : $»e5;
					;
					{ $e5 = $_ex_5;
					{
						$this->messages->addError("There is an error in your 'post-sql-value' setup for field: " . $element->name);
					}}}
				}
				else {
					$this->messages->addWarning("There was a problem updating your post SQL field because there was no primary key for the target table.");
				}
			}
			if($element->type == "date") {
				;
			}
			unset($»r2,$»r,$»e5,$updateKeyValue,$tmp,$tData,$result2,$primaryKey2,$e5,$d,$check,$_g4,$_g14,$_ex_5);
		}
		}
		$this->messages->addMessage((($this->pagesMode ? "Page" : "Record")) . " " . ((_hx_equal($this->form->getElement("__action")->value, "add") ? "added." : "updated.")));
		if(!$this->pagesMode && !$this->singleInstanceEdit) {
			$url = "?request=cms.modules.base.Dataset";
			$url .= "&dataset=" . $this->dataset;
			$url .= "&linkMode=" . (($this->linkMode ? "true" : "false"));
			$url .= "&linkToField=" . $this->app->params->get("linkToField");
			$url .= "&linkTo=" . $this->app->params->get("linkTo");
			$url .= "&linkValueField=" . $this->app->params->get("linkValueField");
			$url .= "&linkValue=" . $this->app->params->get("linkValue");
			$url .= "&autofilterByAssoc=" . $this->app->params->get("autofilterByAssoc");
			$url .= "&autofilterBy=" . $this->app->params->get("autofilterBy");
			if($this->siteMode) {
				$url .= "&siteMode=true";
			}
			$this->app->redirect($url);
		}
		else {
			if($this->pagesMode) {
				$url2 = "?request=cms.modules.base.DatasetItem";
				$url2 .= "&pagesMode=true&action=edit&id=" . $this->id;
				if($this->siteMode) {
					$url2 .= "&siteMode=true";
				}
				$this->app->redirect($url2);
			}
			else {
				$url3 = "?request=cms.modules.base.DatasetItem";
				$url3 .= "&dataset=" . $this->dataset;
				$url3 .= "&id=" . $this->id;
				$url3 .= "&autofilterByAssoc=" . $this->app->params->get("autofilterByAssoc");
				$url3 .= "&autofilterBy=" . $this->app->params->get("autofilterBy");
				$url3 .= "&siteMode=true";
				$url3 .= "&singleInstanceEdit=true";
			}
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
	public function getElementMatches() {
		$fields = $this->app->getDb()->request("SHOW FIELDS FROM `" . $this->table . "`");
		$fields = Lambda::map($fields, array(new _hx_lambda(array("fields" => &$fields), null, array('row'), "{
			return \$row->Field;
		}"), 'execute1'));
		$elements = Lambda::hlist($this->definition->elements);
		$elements = Lambda::filter($elements, array(new _hx_lambda(array("elements" => &$elements, "fields" => &$fields), null, array('element'), "{
			return (Lambda::has(\$fields, \$element->name, null) && \$element->type != \"hidden\") || \$element->type == \"multilink\" || \$element->type == \"linkdisplay\";
		}"), 'execute1'));
		return $elements;
	}
	public function uploadFiles() {
		$filesToDelete = new HList();
		$fieldsToWipe = new HList();
		$safeId = $this->app->getDb()->cnx->quote(Std::string($this->id));
		$nFilesReplaced = 0;
		$nFilesAdded = 0;
		$nFilesDeleted = 0;
		$f = php_Web::getParams()->get("form1__filesToDelete");
		$h = new Hash();
		try {
			$h = haxe_Unserializer::run($f);
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			;
		}}}
		$»it = $h->keys();
		while($»it->hasNext()) {
		$k = $»it->next();
		{
			$filesToDelete->add($k);
			$fieldsToWipe->add($h->get($k));
			;
		}
		}
		$files = poko_utils_PhpTools::getFilesInfo();
		$data = new Hash();
		$»it2 = $files->keys();
		while($»it2->hasNext()) {
		$file = $»it2->next();
		{
			$info = $files->get($file);
			$name = _hx_substr($file, strlen($this->form->name) + 1, null);
			$filename = $info->get("name");
			$randomString = haxe_Md5::encode(Date::now()->toString() . Math::random());
			$libraryItemValue = $this->app->params->get($this->form->name . "_" . $name . "_libraryItemValue");
			if(_hx_equal($this->app->params->get($this->form->name . "_" . $name . "_operation"), site_cms_modules_base_formElements_FileUpload::$OPERATION_LIBRARY) && $libraryItemValue !== null && $libraryItemValue != "") {
				$imgRoot = "./res/media/galleries";
				if(file_exists($imgRoot . "/" . $libraryItemValue)) {
					$copyToName = $randomString . _hx_substr($libraryItemValue, _hx_last_index_of($libraryItemValue, "/", null) + 1, null);
					php_io_File::copy($imgRoot . "/" . $libraryItemValue, site_cms_PokoCms::$uploadFolder . "/" . $copyToName);
					$data->set($name, $copyToName);
					$nFilesAdded++;
				}
			}
			else {
				if(_hx_equal($info->get("error"), 0)) {
					poko_utils_PhpTools::moveFile($info->get("tmp_name"), site_cms_PokoCms::$uploadFolder . "/" . $randomString . $filename);
					$data->set($name, $randomString . $filename);
					$nFilesAdded++;
				}
			}
			unset($randomString,$name,$libraryItemValue,$info,$imgRoot,$filename,$copyToName);
		}
		}
		if(!$this->pagesMode) {
			$sql = "SELECT ";
			$c = 0;
			$»it3 = $data->keys();
			while($»it3->hasNext()) {
			$k2 = $»it3->next();
			{
				if(!Lambda::has($fieldsToWipe, $k2, null)) {
					$sql .= "`" . $k2 . "`,";
					$c++;
				}
				;
			}
			}
			$sql = _hx_substr($sql, 0, strlen($sql) - 1);
			$sql .= " FROM " . $this->table . " WHERE id=" . $safeId;
			if($c > 0) {
				$result = $this->app->getDb()->requestSingle($sql);
				{
					$_g = 0; $_g1 = Reflect::fields($result);
					while($_g < $_g1->length) {
						$i = $_g1[$_g];
						++$_g;
						$filesToDelete->add(Reflect::field($result, $i));
						$nFilesAdded--;
						$nFilesReplaced++;
						unset($i);
					}
				}
			}
		}
		else {
			$r = $this->app->getDb()->requestSingle("SELECT data FROM _pages WHERE `id`=" . $safeId);
			try {
				$d = haxe_Unserializer::run($r->data);
				$»it4 = $data->keys();
				while($»it4->hasNext()) {
				$k3 = $»it4->next();
				{
					if(!Lambda::has($fieldsToWipe, $k3, null)) {
						$filesToDelete->add(Reflect::field($d, $k3));
						$nFilesAdded--;
						$nFilesReplaced++;
					}
					;
				}
				}
			}catch(Exception $»e2) {
			$_ex_2 = ($»e2 instanceof HException) ? $»e2->e : $»e2;
			;
			{ $e2 = $_ex_2;
			{
				$this->messages->addError("There may have been a problem updating your page.");
			}}}
		}
		$»it5 = $filesToDelete->iterator();
		while($»it5->hasNext()) {
		$f1 = $»it5->next();
		{
			try {
				@unlink(site_cms_PokoCms::$uploadFolder . "/" . $f1);
			}catch(Exception $»e3) {
			$_ex_3 = ($»e3 instanceof HException) ? $»e3->e : $»e3;
			;
			{ $e3 = $_ex_3;
			{
				;
			}}}
			unset($»e3,$e3,$_ex_3);
		}
		}
		$»it6 = $fieldsToWipe->iterator();
		while($»it6->hasNext()) {
		$f12 = $»it6->next();
		{
			if($data->get($f12) === null) {
				$data->set($f12, "");
				$nFilesDeleted++;
			}
			else {
				$nFilesReplaced++;
				$nFilesAdded--;
			}
			;
		}
		}
		if($nFilesAdded > 0 || $nFilesReplaced > 0 || $nFilesDeleted > 0) {
			$this->messages->addMessage("Files: " . $nFilesAdded . " added, " . $nFilesReplaced . " replaced and " . $nFilesDeleted . " deleted.");
		}
		return $data;
	}
	public function stringCompare($a, $b) {
		return _hx_equal(_hx_string_call($a, "toString", array()), _hx_string_call($b, "toString", array()));
	}
	public function setupForm() {
		$this->form = new poko_form_Form("form1", null, null);
		$this->form->addElement(new poko_form_elements_Hidden("__action", $this->app->params->get("action"), null, null, null), null);
		$elements = ($this->pagesMode ? Lambda::hlist($this->definition->elements) : $this->getElementMatches());
		$element = null;
		$»it = $elements->iterator();
		while($»it->hasNext()) {
		$element1 = $»it->next();
		{
			$value = Reflect::field($this->data, $element1->name);
			$label = (!_hx_equal($element1->properties->label, "") && _hx_field($element1->properties, "label") !== null ? $element1->properties->label : $element1->name);
			switch($element1->type) {
			case "text":{
				$el = null;
				if($element1->properties->isMultiline) {
					$el = new poko_form_elements_TextArea($element1->name, $label, $value, $element1->properties->required, null, null);
					$el->height = $element1->properties->height;
					if($el->height > 0 && $el->width > 0) {
						$el->useSizeValues = true;
					}
				}
				else {
					$el = new poko_form_elements_Input($element1->name, $label, $value, $element1->properties->required, null, null);
					if($el->width > 0) {
						$el->useSizeValues = true;
					}
				}
				$el->width = $element1->properties->width;
				$el->addValidator(new poko_form_validators_StringValidator((!_hx_equal($element1->properties->minChars, "") ? $element1->properties->minChars : null), (!_hx_equal($element1->properties->maxChars, "") ? $element1->properties->maxChars : null), $element1->properties->charsList, (_hx_equal($element1->properties->mode, "ALLOW") ? poko_form_validators_StringValidatorMode::$ALLOW : poko_form_validators_StringValidatorMode::$DENY), null, null));
				if(strlen(Std::string($element1->properties->regex)) > 0) {
					$reg = new EReg($element1->properties->regex, ((_hx_equal($element1->properties->regexCaseInsensitive, "1") ? "i" : "")));
					$el->addValidator(new poko_form_validators_RegexValidator($reg, $element1->properties->regexError));
				}
				if(_hx_field($element1->properties, "formatter") !== null && !_hx_equal($element1->properties->formatter, "")) {
					$el->formatter = Type::createInstance(Type::resolveClass($element1->properties->formatter), new _hx_array(array()));
				}
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
			}break;
			case "number":{
				$el2 = new poko_form_elements_Input($element1->name, $label, $value, _hx_equal($element1->properties->required, "1"), null, null);
				$el2->addValidator(new poko_form_validators_NumberValidator((!_hx_equal($element1->properties->min, "") ? $element1->properties->min : null), (!_hx_equal($element1->properties->max, "") ? $element1->properties->max : null), _hx_equal($element1->properties->isInt, "1")));
				$el2->description = $element1->properties->description;
				$this->form->addElement($el2, null);
			}break;
			case "image-file":{
				$el3 = new site_cms_modules_base_formElements_FileUpload($element1->name, $label, $value, $element1->properties->required);
				if($element1->properties->description) {
					$el3->description = $element1->properties->description . "<br />";
				}
				if($element1->properties->isImage) {
					$el3->description .= "Images Only<br />";
				}
				if($element1->properties->extList) {
					$el3->description .= "File Types";
					$el3->description .= ((_hx_equal($element1->properties->extMode, "ALLOW")) ? " Allowed: " : " Denied: ");
					$a = _hx_string_call($element1->properties->extList, "split", array(","));
					{
						$_g = 0;
						while($_g < $a->length) {
							$i = $a[$_g];
							++$_g;
							$el3->description .= $i . ", ";
							unset($i);
						}
					}
					$el3->description = _hx_substr($el3->description, 0, strlen($el3->description) - 2);
					$el3->description .= "<br />";
				}
				if($element1->properties->minSize && $element1->properties->maxSize) {
					$el3->description .= "Size: " . $element1->properties->minSize . "Kb - " . $element1->properties->maxSize . "Kb";
				}
				else {
					if($element1->properties->minSize) {
						$el3->description .= "Min Size: " . $element1->properties->minSize . "Kb";
					}
					if($element1->properties->maxSize) {
						$el3->description .= "Max Size: " . $element1->properties->maxSize . "Kb";
					}
				}
				$el3->showUpload = (_hx_equal($element1->properties->uploadType, "0") || _hx_equal($element1->properties->uploadType, "1"));
				$el3->showLibrary = (_hx_equal($element1->properties->uploadType, "0") || _hx_equal($element1->properties->uploadType, "2"));
				$el3->libraryViewThumb = (_hx_equal($element1->properties->libraryView, "0") || _hx_equal($element1->properties->libraryView, "1"));
				$el3->libraryViewList = (_hx_equal($element1->properties->libraryView, "0") || _hx_equal($element1->properties->libraryView, "2"));
				$t = trim($element1->properties->showOnlyLibraries);
				if($t != "") {
					$el3->showOnlyLibraries = _hx_explode(":", $t);
				}
				$this->form->addElement($el3, null);
			}break;
			case "date":{
				$d = (($value != "" && $value !== null) ? $value : Date::now());
				if(_hx_equal($element1->properties->currentOnAdd, "1") && (_hx_equal($this->form->getElement("__action")->value, "add") || _hx_equal($this->app->params->get("action"), "add"))) {
					$d = Date::now();
				}
				$el4 = new poko_form_elements_DateSelector($element1->name, $label, $d, $element1->properties->required, null, null);
				if(_hx_equal($element1->properties->restrictMin, "1")) {
					$el4->minOffset = $element1->properties->minOffset;
				}
				if(_hx_equal($element1->properties->restrictMax, "1")) {
					$el4->maxOffset = $element1->properties->maxOffset;
				}
				$el4->addValidator(new poko_form_validators_DateValidator(null, null));
				$el4->description = $element1->properties->description;
				$this->form->addElement($el4, null);
			}break;
			case "richtext-tinymce":{
				$el5 = new poko_form_elements_Richtext($element1->name, $label, $value, $element1->properties->required, null);
				if($element1->properties->mode) {
					$el5->mode = Type::createEnum(Type::resolveEnum("poko.form.elements.RichtextMode"), $element1->properties->mode, null);
				}
				if(!_hx_equal($element1->properties->width, "")) {
					$el5->width = Std::parseInt($element1->properties->width);
				}
				if(!_hx_equal($element1->properties->height, "")) {
					$el5->height = Std::parseInt($element1->properties->height);
				}
				if(!_hx_equal($element1->properties->content_css, "") && _hx_field($element1->properties, "content_css") !== null) {
					$el5->content_css = $element1->properties->content_css;
				}
				$el5->description = $element1->properties->description;
				$this->form->addElement($el5, null);
			}break;
			case "richtext-wym":{
				$el6 = new poko_form_elements_RichtextWym($element1->name, $label, $value, $element1->properties->required, null);
				if(!_hx_equal($element1->properties->width, "")) {
					$el6->width = Std::parseInt($element1->properties->width);
				}
				if(!_hx_equal($element1->properties->height, "")) {
					$el6->height = Std::parseInt($element1->properties->height);
				}
				if(!_hx_equal($element1->properties->allowImages, "")) {
					$el6->allowImages = $element1->properties->allowImages;
				}
				if(!_hx_equal($element1->properties->allowTables, "")) {
					$el6->allowTables = $element1->properties->allowTables;
				}
				if(!_hx_equal($element1->properties->editorStyles, "")) {
					$el6->editorStyles = $element1->properties->editorStyles;
				}
				$el6->containersItems = ((!_hx_equal($element1->properties->containersItems, "") && _hx_field($element1->properties, "containersItems") !== null) ? $element1->properties->containersItems : "{'name': 'P', 'title': 'Paragraph', 'css': 'wym_containers_p'}");
				$el6->classesItems = ((!_hx_equal($element1->properties->classesItems, "") && _hx_field($element1->properties, "classesItems") !== null) ? $element1->properties->classesItems : "");
				$el6->description = $element1->properties->description;
				$this->form->addElement($el6, null);
			}break;
			case "read-only":{
				$el7 = new poko_form_elements_Readonly($element1->name, $label, $value, $element1->properties->required, null, null);
				$el7->description = $element1->properties->description;
				$this->form->addElement($el7, null);
			}break;
			case "bool":{
				$options = new HList();
				$trueLable = (!_hx_equal($element1->properties->labelTrue, "") ? $element1->properties->labelTrue : "true");
				$falseLable = (!_hx_equal($element1->properties->labelFalse, "") ? $element1->properties->labelFalse : "false");
				$options->add(_hx_anonymous(array("key" => $trueLable, "value" => "1")));
				$options->add(_hx_anonymous(array("key" => $falseLable, "value" => "0")));
				$el8 = new poko_form_elements_RadioGroup($element1->name, $label, $options, $value, "1", false, null);
				$el8->description = $element1->properties->description;
				$this->form->addElement($el8, null);
				$this->jsBind->queueCall("setupShowHideElements", new _hx_array(array($el8->name, $element1->properties->showHideFields, $value, $element1->properties->showHideValue)), null);
			}break;
			case "association":{
				$fieldLabelSelect = $element1->properties->fieldLabel;
				if(_hx_field($element1->properties, "fieldSql") !== null && !_hx_equal($element1->properties->fieldSql, "")) {
					$fieldLabelSelect = "(" . $element1->properties->fieldSql . ")";
				}
				$assocData = $this->app->getDb()->request("SELECT `" . $element1->properties->field . "` as value, " . $fieldLabelSelect . " as label FROM `" . $element1->properties->table . "`");
				$assocData = Lambda::map($assocData, array(new _hx_lambda(array("_g" => &$_g, "a" => &$a, "assocData" => &$assocData, "d" => &$d, "el" => &$el, "el2" => &$el2, "el3" => &$el3, "el4" => &$el4, "el5" => &$el5, "el6" => &$el6, "el7" => &$el7, "el8" => &$el8, "element" => &$element, "element1" => &$element1, "elements" => &$elements, "falseLable" => &$falseLable, "fieldLabelSelect" => &$fieldLabelSelect, "i" => &$i, "label" => &$label, "options" => &$options, "reg" => &$reg, "t" => &$t, "trueLable" => &$trueLable, "value" => &$value, "»it" => &$»it), null, array('value1'), "{
					return _hx_anonymous(array(\"key\" => \$value1->label, \"value\" => \$value1->value));
				}"), 'execute1'));
				if($this->autoFilterValue == $element1->name) {
					$value = $this->autoFilterByAssocValue;
				}
				$el9 = new poko_form_elements_Selectbox($element1->name, $label, $assocData, $value, $element1->properties->required, null);
				$el9->description = $element1->properties->description;
				$this->form->addElement($el9, null);
			}break;
			case "multilink":{
				$sql = "";
				$sql .= "SELECT `" . $element1->properties->field . "` as 'key', \x09\x09";
				$sql .= "       `" . $element1->properties->fieldLabel . "` as 'value' \x09";
				$sql .= "  FROM `" . $element1->properties->table . "`\x09\x09\x09\x09\x09";
				$linkData = $this->app->getDb()->request($sql);
				$selectedData = new _hx_array(array());
				if(!_hx_equal($this->app->params->get("action"), "add")) {
					$sql1 = "";
					$sql1 .= "SELECT `" . $element1->properties->linkField2 . "` as 'link' \x09";
					$sql1 .= "  FROM `" . $element1->properties->link . "`";
					$sql1 .= " WHERE `" . $element1->properties->linkField1 . "`=" . $this->app->getDb()->cnx->quote(Std::string($this->id));
					$result = $this->app->getDb()->request($sql1);
					$»it2 = $result->iterator();
					while($»it2->hasNext()) {
					$row = $»it2->next();
					$selectedData->push(Std::string($row->link));
					}
				}
				$el10 = new poko_form_elements_CheckboxGroup($element1->name, $label, $linkData, $selectedData, null, null);
				if(_hx_field($element1->properties, "formatter") !== null && !_hx_equal($element1->properties->formatter, "")) {
					$el10->formatter = Type::createInstance(Type::resolveClass($element1->properties->formatter), new _hx_array(array()));
				}
				$el10->description = $element1->properties->description;
				$this->form->addElement($el10, null);
			}break;
			case "keyvalue":{
				$el11 = new site_cms_modules_base_formElements_KeyValueInput($element1->name, $label, $value, $element1->properties, null, null, null);
				$el11->minRows = $element1->properties->minRows;
				$el11->maxRows = $element1->properties->maxRows;
				$el11->description = $element1->properties->description;
				if($el11->minRows > 0 || $el11->maxRows > 0) {
					$el11->description .= " <br />";
				}
				if($el11->minRows > 0) {
					$el11->description .= " <br /><b>Minimum Rows</b>: " . $el11->minRows;
				}
				if($el11->maxRows > 0) {
					$el11->description .= " <br /><b>Maximum Rows</b>: " . $el11->maxRows;
				}
				$this->form->addElement($el11, null);
			}break;
			case "linkdisplay":{
				$el12 = new site_cms_modules_base_formElements_LinkTable($element1->name, $label, $element1->properties->table, $this->table, $this->id, null, null, "class=\"resizableFrame\"");
				$el12->description = $element1->properties->description;
				$this->form->addElement($el12, null);
			}break;
			case "post-add-current-time":{
				$el13 = new poko_form_elements_Readonly($element1->name, $label, $value, $element1->properties->required, null, null);
				$el13->description = $element1->properties->description;
				$this->form->addElement($el13, null);
			}break;
			}
			unset($»it2,$value,$trueLable,$t,$sql1,$sql,$selectedData,$row,$result,$reg,$options,$linkData,$label,$i,$fieldLabelSelect,$falseLable,$el9,$el8,$el7,$el6,$el5,$el4,$el3,$el2,$el13,$el12,$el11,$el10,$el,$d,$assocData,$a,$_g);
		}
		}
		if($this->linkMode) {
			$this->form->addElement(new poko_form_elements_Hidden($this->app->params->get("linkToField"), $this->app->params->get("linkTo"), null, null, null), null);
			$this->form->addElement(new poko_form_elements_Hidden($this->app->params->get("linkValueField"), $this->app->params->get("linkValue"), null, null, null), null);
		}
		if(_hx_equal($this->app->params->get("siteMode"), "true")) {
			$this->form->addElement(new poko_form_elements_Hidden("siteMode", "true", null, null, null), null);
		}
		$aF = $this->app->params->get("autofilterBy");
		if($aF !== null && !_hx_equal($aF, "")) {
			$this->form->addElement(new poko_form_elements_Hidden("autofilterBy", $aF, null, null, null), null);
		}
		$aFA = $this->app->params->get("autofilterByAssoc");
		if($aFA !== null && !_hx_equal($aFA, "")) {
			$this->form->addElement(new poko_form_elements_Hidden("autofilterByAssoc", $aFA, null, null, null), null);
		}
		$submitButton = new poko_form_elements_Button("__submit", (_hx_equal($this->app->params->get("action"), "add") ? "Add" : "Update"), null, poko_form_elements_ButtonType::$SUBMIT);
		$keyValJsBinding = $this->jsBindings->get("site.cms.modules.base.js.JsKeyValueInput");
		if($keyValJsBinding !== null) {
			$submitButton->attributes = "onClick=\"" . $this->jsBind->getCall("flushWymEditors", new _hx_array(array())) . "; return(" . $keyValJsBinding->getCall("flushKeyValueInputs", new _hx_array(array())) . ");\"";
		}
		else {
			$submitButton->attributes = "onClick=\"return(" . $this->jsBind->getCall("flushWymEditors", new _hx_array(array())) . ");\"";
		}
		$this->form->addElement($submitButton, null);
		if(_hx_equal($this->app->params->get("action"), "add") && $this->linkMode) {
			$cancelButton = new poko_form_elements_Button("__cancel", "Cancel", "Cancel", poko_form_elements_ButtonType::$BUTTON);
			$this->form->addElement($cancelButton, null);
		}
		$this->form->populateElements();
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.base.DatasetItem'; }
}
