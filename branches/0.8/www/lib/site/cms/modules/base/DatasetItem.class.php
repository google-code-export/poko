<?php

class site_cms_modules_base_DatasetItem extends site_cms_modules_base_DatasetBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
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
		$this->remoting->addObject("api", _hx_anonymous(array("deleteFile" => (isset($this->deleteFile) ? $this->deleteFile: array($this, "deleteFile")))), null);
		$this->singleInstanceEdit = $this->app->params->get("singleInstanceEdit");
		if($this->linkMode) {
			$this->head->css->add("css/cms/miniView.css");
			$this->layoutView->template = "cms/templates/CmsTemplate_mini.mtt";
			;
		}
		;
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
			$this->data = $this->app->getDb()->requestSingle((("SELECT * FROM `" . $this->table) . "` WHERE `id`=") . $this->app->getDb()->quote(Std::string($this->id)));
			$this->orderField = $this->getOrderField();
			$this->isOrderingEnabled = $this->orderField !== null;
			$this->autoFilterValue = site_cms_modules_base_DatasetItem_0($this);
			$this->autoFilterByAssocValue = site_cms_modules_base_DatasetItem_1($this);
			;
		}
		else {
			$result = $this->app->getDb()->requestSingle("SELECT * FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id AND p.id=" . $this->app->getDb()->quote(Std::string($this->id)));
			$this->label = $this->page = $result->name;
			$this->data = site_cms_modules_base_DatasetItem_2($this, $result);
			$this->definition = new site_cms_common_Definition($result->definitionId);
			unset($result);
		}
		$this->navigation->pageHeading .= (" (" . $this->label) . ")";
		$this->setupForm();
		if($this->form->isSubmitted() && $this->form->isValid()) {
			$this->processForm();
			;
		}
		$this->setupForm();
		$this->setupLeftNav();
		;
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
			return _hx_anonymous(array("success" => @unlink((site_cms_PokoCms::$uploadFolder . "/") . $filename) && $result, "display" => $display, "error" => null));
			unset($result,$d);
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			return _hx_anonymous(array("success" => false, "display" => $display, "error" => $e));
			;
		}}}
		unset($e);
	}
	public function processForm() {
		$data = $this->form->getData();
		$uploadData = $this->uploadFiles();
		if(null == $uploadData) throw new HException('null iterable');
		$»it = $uploadData->keys();
		while($»it->hasNext()) {
		$k = $»it->next();
		{
			$data->{$k} = $uploadData->get($k);
			;
		}
		}
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
		switch($this->form->getElement("__action")->value) {
		case "add":{
			if($this->isOrderingEnabled) {
				$result = $this->app->getDb()->requestSingle(((("SELECT MAX(`" . $this->orderField) . "`) as 'order' FROM `") . $this->table) . "`");
				$data->{$this->orderField} = Std::string($result->order + 1);
				unset($result);
			}
			$doPost = true;
			try {
				$this->app->getDb()->insert($this->table, $data);
				$this->id = $this->app->getDb()->lastInsertId;
				;
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e = $_ex_;
			{
				$doPost = false;
				$this->messages->addError("Update failed. Not running post commands or procedures.");
				;
			}}}
			if($doPost) {
				$primaryKey = $this->app->getDb()->getPrimaryKey($this->table);
				if($primaryKey !== null) {
					$data->{$primaryKey} = $this->id;
					if($this->definition->postCreateSql !== null && $this->definition->postCreateSql !== "") {
						$tSql = $this->definition->postCreateSql;
						{
							$_g = 0; $_g1 = Reflect::fields($data);
							while($_g < $_g1->length) {
								$tField = $_g1[$_g];
								++$_g;
								$tSql = str_replace(("#" . $tField) . "#", Reflect::field($data, $tField), $tSql);
								unset($tField);
							}
							unset($_g1,$_g);
						}
						try {
							$this->app->getDb()->query($tSql);
							;
						}catch(Exception $»e) {
						$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
						;
						{ $e2 = $_ex_;
						{
							$this->messages->addError("Post-create SQL had problems: " . $tSql);
							;
						}}}
						unset($tSql,$e2);
					}
					;
				}
				else {
					$this->messages->addError("Could not get the primary key from newly created record. Post-SQL not run.");
					;
				}
				if($postProcedure !== null) {
					$postProcedure->postCreate($this->table, $data, $this->id);
					;
				}
				unset($primaryKey);
			}
			unset($e,$doPost);
		}break;
		case "edit":{
			if($this->pagesMode) {
				$sdata = haxe_Serializer::run($data);
				$this->app->getDb()->update("_pages", _hx_anonymous(array("data" => $sdata)), "`id`=" . $this->app->getDb()->quote(Std::string($this->id)));
				unset($sdata);
			}
			else {
				$oldData = $this->app->getDb()->requestSingle((("SELECT * FROM `" . $this->table) . "` WHERE `id`=") . $this->app->getDb()->quote(Std::string($this->id)));
				$doPost = true;
				try {
					$this->app->getDb()->update($this->table, $data, "`id`=" . $this->app->getDb()->quote(Std::string($this->id)));
					;
				}catch(Exception $»e) {
				$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
				;
				{ $e = $_ex_;
				{
					$doPost = false;
					$this->messages->addError("Update failed. Not running post commands or procedures.");
					;
				}}}
				if($doPost) {
					if($this->definition->postEditSql !== null && $this->definition->postEditSql !== "") {
						$tSql = $this->definition->postEditSql;
						{
							$_g = 0; $_g1 = Reflect::fields($oldData);
							while($_g < $_g1->length) {
								$tField = $_g1[$_g];
								++$_g;
								$tSql = str_replace(("#" . $tField) . "#", Reflect::field($oldData, $tField), $tSql);
								unset($tField);
							}
							unset($_g1,$_g);
						}
						{
							$_g = 0; $_g1 = Reflect::fields($data);
							while($_g < $_g1->length) {
								$tField = $_g1[$_g];
								++$_g;
								$tSql = str_replace(("*" . $tField) . "*", Reflect::field($data, $tField), $tSql);
								unset($tField);
							}
							unset($_g1,$_g);
						}
						try {
							$this->app->getDb()->query($tSql);
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
						$postProcedure->postUpdate($this->table, $oldData, $data);
						;
					}
					;
				}
				unset($oldData,$e,$doPost);
			}
			;
		}break;
		}
		$elements = site_cms_modules_base_DatasetItem_3($this, $data, $postProcedure, $uploadData);
		if(null == $elements) throw new HException('null iterable');
		$»it = $elements->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		{
			if($element->type === "multilink") {
				$this->app->getDb()->delete($element->properties->link, (("`" . $element->properties->linkField1) . "`=") . $this->app->getDb()->quote(Std::string($this->id)));
				{
					$_g = 0; $_g1 = _hx_cast(Reflect::field($data, $element->name), _hx_qtype("Array"));
					while($_g < $_g1->length) {
						$check = $_g1[$_g];
						++$_g;
						$d = _hx_anonymous(array());
						$d->{$element->properties->linkField1} = $this->id;
						$d->{$element->properties->linkField2} = $check;
						$this->app->getDb()->insert($element->properties->link, $d);
						unset($d,$check);
					}
					unset($_g1,$_g);
				}
				;
			}
			if($element->type === "post-sql-value") {
				$updateKeyValue = Reflect::field($data, $element->properties->updateKey);
				$primaryKey = $this->app->getDb()->getPrimaryKey($this->table);
				if($primaryKey !== null && $updateKeyValue !== null) {
					$result = $this->app->getDb()->requestSingle(((((((("SELECT `" . $element->properties->updateTo) . "` AS `__v` FROM `") . $element->properties->table) . "` WHERE `") . $primaryKey) . "`='") . $updateKeyValue) . "'");
					$tData = _hx_anonymous(array());
					try {
						$tData->{$element->name} = $result->__v;
						$this->app->getDb()->update($this->table, $tData, "`id`=" . $this->app->getDb()->quote(Std::string($this->id)));
						;
					}catch(Exception $»e) {
					$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
					;
					{ $e = $_ex_;
					{
						$this->messages->addError("There is an error in your 'post-sql-value' setup for field: " . $element->name);
						;
					}}}
					unset($tData,$result,$e);
				}
				else {
					$this->messages->addWarning("There was a problem updating your post SQL field because there was no primary key for the target table.");
					;
				}
				unset($updateKeyValue,$primaryKey);
			}
			if($element->type === "date") {
				;
				;
			}
			;
		}
		}
		$this->messages->addMessage(((site_cms_modules_base_DatasetItem_4($this, $data, $elements, $postProcedure, $uploadData)) . " ") . (site_cms_modules_base_DatasetItem_5($this, $data, $elements, $postProcedure, $uploadData)));
		if(!$this->pagesMode && !$this->singleInstanceEdit) {
			$url = "?request=cms.modules.base.Dataset";
			$url .= "&dataset=" . $this->dataset;
			$url .= "&linkMode=" . (site_cms_modules_base_DatasetItem_6($this, $data, $elements, $postProcedure, $uploadData, $url));
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
			$this->app->redirect($url);
			unset($url);
		}
		else {
			if($this->pagesMode) {
				$url = "?request=cms.modules.base.DatasetItem";
				$url .= "&pagesMode=true&action=edit&id=" . $this->id;
				if($this->siteMode) {
					$url .= "&siteMode=true";
					;
				}
				$this->app->redirect($url);
				unset($url);
			}
			else {
				$url = "?request=cms.modules.base.DatasetItem";
				$url .= "&dataset=" . $this->dataset;
				$url .= "&id=" . $this->id;
				$url .= "&autofilterByAssoc=" . $this->app->params->get("autofilterByAssoc");
				$url .= "&autofilterBy=" . $this->app->params->get("autofilterBy");
				$url .= "&siteMode=true";
				$url .= "&singleInstanceEdit=true";
				unset($url);
			}
			;
		}
		unset($uploadData,$postProcedure,$elements,$data);
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
	public function getElementMatches() {
		$fields = $this->app->getDb()->request(("SHOW FIELDS FROM `" . $this->table) . "`");
		$fields = Lambda::map($fields, array(new _hx_lambda(array(&$fields), "site_cms_modules_base_DatasetItem_7"), 'execute'));
		$elements = Lambda::hlist($this->definition->elements);
		$elements = Lambda::filter($elements, array(new _hx_lambda(array(&$elements, &$fields), "site_cms_modules_base_DatasetItem_8"), 'execute'));
		return $elements;
		unset($fields,$elements);
	}
	public function uploadFiles() {
		$filesToDelete = new HList();
		$fieldsToWipe = new HList();
		$safeId = $this->app->getDb()->quote(Std::string($this->id));
		$nFilesReplaced = 0;
		$nFilesAdded = 0;
		$nFilesDeleted = 0;
		$f = php_Web::getParams()->get("form1__filesToDelete");
		$h = new Hash();
		try {
			$h = haxe_Unserializer::run($f);
			;
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			;
			;
		}}}
		if(null == $h) throw new HException('null iterable');
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
		if(null == $files) throw new HException('null iterable');
		$»it = $files->keys();
		while($»it->hasNext()) {
		$file = $»it->next();
		{
			$info = $files->get($file);
			$name = _hx_substr($file, strlen($this->form->name) + 1, null);
			$filename = $info->get("name");
			$randomString = haxe_Md5::encode(Date::now()->toString() . Math::random());
			$libraryItemValue = $this->app->params->get((($this->form->name . "_") . $name) . "_libraryItemValue");
			if(_hx_equal($this->app->params->get((($this->form->name . "_") . $name) . "_operation"), site_cms_modules_base_formElements_FileUpload::$OPERATION_LIBRARY) && $libraryItemValue !== null && $libraryItemValue !== "") {
				$imgRoot = "./res/media/galleries/";
				if(file_exists($imgRoot . $libraryItemValue)) {
					$copyToName = $randomString . _hx_substr($libraryItemValue, _hx_last_index_of($libraryItemValue, "/", null) + 1, null);
					php_io_File::copy($imgRoot . $libraryItemValue, site_cms_PokoCms::$uploadFolder . $copyToName);
					$data->set($name, $copyToName);
					$nFilesAdded++;
					unset($copyToName);
				}
				unset($imgRoot);
			}
			else {
				if(_hx_equal($info->get("error"), 0)) {
					poko_utils_PhpTools::moveFile($info->get("tmp_name"), (site_cms_PokoCms::$uploadFolder . $randomString) . $filename);
					$data->set($name, $randomString . $filename);
					$nFilesAdded++;
					;
				}
				;
			}
			unset($randomString,$name,$libraryItemValue,$info,$filename);
		}
		}
		if(!$this->pagesMode) {
			$sql = "SELECT ";
			$c = 0;
			if(null == $data) throw new HException('null iterable');
			$»it = $data->keys();
			while($»it->hasNext()) {
			$k = $»it->next();
			{
				if(!Lambda::has($fieldsToWipe, $k, null)) {
					$sql .= ("`" . $k) . "`,";
					$c++;
					;
				}
				;
			}
			}
			$sql = _hx_substr($sql, 0, strlen($sql) - 1);
			$sql .= ((" FROM " . $this->table) . " WHERE id=") . $safeId;
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
					unset($_g1,$_g);
				}
				unset($result);
			}
			unset($sql,$c);
		}
		else {
			$r = $this->app->getDb()->requestSingle("SELECT data FROM _pages WHERE `id`=" . $safeId);
			try {
				$d = site_cms_modules_base_DatasetItem_9($this, $data, $e, $f, $fieldsToWipe, $files, $filesToDelete, $h, $nFilesAdded, $nFilesDeleted, $nFilesReplaced, $r, $safeId);
				if(null == $data) throw new HException('null iterable');
				$»it = $data->keys();
				while($»it->hasNext()) {
				$k = $»it->next();
				{
					if($d !== null && !Lambda::has($fieldsToWipe, $k, null)) {
						$filesToDelete->add(Reflect::field($d, $k));
						$nFilesAdded--;
						$nFilesReplaced++;
						;
					}
					;
				}
				}
				unset($d);
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e2 = $_ex_;
			{
				$this->messages->addError("There may have been a problem updating your page." . $e2);
				;
			}}}
			unset($r,$e2);
		}
		if(null == $filesToDelete) throw new HException('null iterable');
		$»it = $filesToDelete->iterator();
		while($»it->hasNext()) {
		$f1 = $»it->next();
		{
			try {
				@unlink(site_cms_PokoCms::$uploadFolder . $f1);
				;
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e2 = $_ex_;
			{
				;
				;
			}}}
			unset($e2);
		}
		}
		if(null == $fieldsToWipe) throw new HException('null iterable');
		$»it = $fieldsToWipe->iterator();
		while($»it->hasNext()) {
		$f1 = $»it->next();
		{
			if($data->get($f1) === null) {
				$data->set($f1, "");
				$nFilesDeleted++;
				;
			}
			else {
				$nFilesReplaced++;
				$nFilesAdded--;
				;
			}
			;
		}
		}
		if($nFilesAdded > 0 || $nFilesReplaced > 0 || $nFilesDeleted > 0) {
			$this->messages->addMessage(((((("Files: " . $nFilesAdded) . " added, ") . $nFilesReplaced) . " replaced and ") . $nFilesDeleted) . " deleted.");
			;
		}
		return $data;
		unset($safeId,$nFilesReplaced,$nFilesDeleted,$nFilesAdded,$h,$filesToDelete,$files,$fieldsToWipe,$f,$e,$data);
	}
	public function stringCompare($a, $b) {
		return _hx_equal(_hx_string_call($a, "toString", array()), _hx_string_call($b, "toString", array()));
		;
	}
	public function setupForm() {
		$this->form = new poko_form_Form("form1", null, null);
		$this->form->addElement(new poko_form_elements_Hidden("__action", $this->app->params->get("action"), null, null, null), null);
		$elements = site_cms_modules_base_DatasetItem_10($this);
		$element = null;
		if(null == $elements) throw new HException('null iterable');
		$»it = $elements->iterator();
		while($»it->hasNext()) {
		$element1 = $»it->next();
		{
			$value = Reflect::field($this->data, $element1->name);
			$label = site_cms_modules_base_DatasetItem_11($this, $element, $element1, $elements, $value);
			switch($element1->type) {
			case "text":{
				$el = null;
				if($element1->properties->isMultiline) {
					$el = new poko_form_elements_TextArea($element1->name, $label, $value, $element1->properties->required, null, null);
					$el->height = $element1->properties->height;
					if($el->height > 0 && $el->width > 0) {
						$el->useSizeValues = true;
						;
					}
					;
				}
				else {
					$el = new poko_form_elements_Input($element1->name, $label, $value, $element1->properties->required, null, null);
					if($el->width > 0) {
						$el->useSizeValues = true;
						;
					}
					;
				}
				$el->width = $element1->properties->width;
				$el->addValidator(new poko_form_validators_StringValidator(site_cms_modules_base_DatasetItem_12($this, $el, $element, $element1, $elements, $label, $value), site_cms_modules_base_DatasetItem_13($this, $el, $element, $element1, $elements, $label, $value), $element1->properties->charsList, site_cms_modules_base_DatasetItem_14($this, $el, $element, $element1, $elements, $label, $value), null, null));
				if(strlen(Std::string($element1->properties->regex)) > 0) {
					$reg = new EReg($element1->properties->regex, (site_cms_modules_base_DatasetItem_15($this, $el, $element, $element1, $elements, $label, $value)));
					$el->addValidator(new poko_form_validators_RegexValidator($reg, $element1->properties->regexError));
					unset($reg);
				}
				if(_hx_field($element1->properties, "formatter") !== null && !_hx_equal($element1->properties->formatter, "")) {
					$el->formatter = Type::createInstance(Type::resolveClass($element1->properties->formatter), new _hx_array(array()));
					;
				}
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				unset($el);
			}break;
			case "number":{
				$el = new poko_form_elements_Input($element1->name, $label, $value, _hx_equal($element1->properties->required, "1"), null, null);
				$el->addValidator(new poko_form_validators_NumberValidator(site_cms_modules_base_DatasetItem_16($this, $el, $element, $element1, $elements, $label, $value), site_cms_modules_base_DatasetItem_17($this, $el, $element, $element1, $elements, $label, $value), _hx_equal($element1->properties->isInt, "1")));
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				unset($el);
			}break;
			case "image-file":{
				$el = new site_cms_modules_base_formElements_FileUpload($element1->name, $label, $value, $element1->properties->required);
				if($element1->properties->description) {
					$el->description = $element1->properties->description . "<br />";
					;
				}
				if($element1->properties->isImage) {
					$el->description .= "Images Only<br />";
					;
				}
				if($element1->properties->extList) {
					$el->description .= "File Types";
					$el->description .= site_cms_modules_base_DatasetItem_18($this, $el, $element, $element1, $elements, $label, $value);
					$a = _hx_string_call($element1->properties->extList, "split", array(","));
					{
						$_g = 0;
						while($_g < $a->length) {
							$i = $a[$_g];
							++$_g;
							$el->description .= $i . ", ";
							unset($i);
						}
						unset($_g);
					}
					$el->description = _hx_substr($el->description, 0, strlen($el->description) - 2);
					$el->description .= "<br />";
					unset($a);
				}
				if($element1->properties->minSize && $element1->properties->maxSize) {
					$el->description .= ((("Size: " . $element1->properties->minSize) . "Kb - ") . $element1->properties->maxSize) . "Kb";
					;
				}
				else {
					if($element1->properties->minSize) {
						$el->description .= ("Min Size: " . $element1->properties->minSize) . "Kb";
						;
					}
					if($element1->properties->maxSize) {
						$el->description .= ("Max Size: " . $element1->properties->maxSize) . "Kb";
						;
					}
					;
				}
				$el->showUpload = (_hx_equal($element1->properties->uploadType, "0") || _hx_equal($element1->properties->uploadType, "1"));
				$el->showLibrary = (_hx_equal($element1->properties->uploadType, "0") || _hx_equal($element1->properties->uploadType, "2"));
				$el->libraryViewThumb = (_hx_equal($element1->properties->libraryView, "0") || _hx_equal($element1->properties->libraryView, "1"));
				$el->libraryViewList = (_hx_equal($element1->properties->libraryView, "0") || _hx_equal($element1->properties->libraryView, "2"));
				$t = trim($element1->properties->showOnlyLibraries);
				if($t !== "") {
					$el->showOnlyLibraries = _hx_explode(":", $t);
					;
				}
				$this->form->addElement($el, null);
				unset($t,$el);
			}break;
			case "date":{
				$d = site_cms_modules_base_DatasetItem_19($this, $element, $element1, $elements, $label, $value);
				if(_hx_equal($element1->properties->currentOnAdd, "1") && (_hx_equal($this->form->getElement("__action")->value, "add") || _hx_equal($this->app->params->get("action"), "add"))) {
					$d = Date::now();
					;
				}
				$el = new poko_form_elements_DateSelector($element1->name, $label, $d, $element1->properties->required, null, null);
				if(_hx_equal($element1->properties->restrictMin, "1")) {
					$el->minOffset = $element1->properties->minOffset;
					;
				}
				if(_hx_equal($element1->properties->restrictMax, "1")) {
					$el->maxOffset = $element1->properties->maxOffset;
					;
				}
				$dateMode = site_cms_modules_base_DatasetItem_20($this, $d, $el, $element, $element1, $elements, $label, $value);
				$el->mode = $dateMode;
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				unset($el,$dateMode,$d);
			}break;
			case "location":{
				$el = new poko_form_elements_LocationSelector($element1->name, $label, $value, $element1->properties->required, null);
				$el->description = $element1->properties->description;
				$el->defaultLocation = $element1->properties->defaultLocation;
				$el->popupWidth = $element1->properties->popupWidth;
				$el->popupHeight = $element1->properties->popupHeight;
				$el->searchAddress = $element1->properties->searchAddress;
				$this->form->addElement($el, null);
				unset($el);
			}break;
			case "richtext-tinymce":{
				$el = new poko_form_elements_Richtext($element1->name, $label, $value, $element1->properties->required, null);
				if($element1->properties->mode) {
					$el->mode = Type::createEnum(Type::resolveEnum("poko.form.elements.RichtextMode"), $element1->properties->mode, null);
					;
				}
				if(!_hx_equal($element1->properties->width, "")) {
					$el->width = Std::parseInt($element1->properties->width);
					;
				}
				if(!_hx_equal($element1->properties->height, "")) {
					$el->height = Std::parseInt($element1->properties->height);
					;
				}
				if(!_hx_equal($element1->properties->content_css, "") && _hx_field($element1->properties, "content_css") !== null) {
					$el->content_css = $element1->properties->content_css;
					;
				}
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				unset($el);
			}break;
			case "richtext-wym":{
				$el = new poko_form_elements_RichtextWym($element1->name, $label, $value, $element1->properties->required, null);
				if(!_hx_equal($element1->properties->width, "")) {
					$el->width = Std::parseInt($element1->properties->width);
					;
				}
				if(!_hx_equal($element1->properties->height, "")) {
					$el->height = Std::parseInt($element1->properties->height);
					;
				}
				if(!_hx_equal($element1->properties->allowImages, "")) {
					$el->allowImages = $element1->properties->allowImages;
					;
				}
				if(!_hx_equal($element1->properties->allowTables, "")) {
					$el->allowTables = $element1->properties->allowTables;
					;
				}
				if(!_hx_equal($element1->properties->editorStyles, "")) {
					$el->editorStyles = $element1->properties->editorStyles;
					;
				}
				$el->containersItems = site_cms_modules_base_DatasetItem_21($this, $el, $element, $element1, $elements, $label, $value);
				$el->classesItems = site_cms_modules_base_DatasetItem_22($this, $el, $element, $element1, $elements, $label, $value);
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				unset($el);
			}break;
			case "read-only":{
				$el = new poko_form_elements_Readonly($element1->name, $label, $value, $element1->properties->required, null, null);
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				unset($el);
			}break;
			case "bool":{
				$options = new HList();
				$trueLable = site_cms_modules_base_DatasetItem_23($this, $element, $element1, $elements, $label, $options, $value);
				$falseLable = site_cms_modules_base_DatasetItem_24($this, $element, $element1, $elements, $label, $options, $trueLable, $value);
				$options->add(_hx_anonymous(array("key" => "1", "value" => $trueLable)));
				$options->add(_hx_anonymous(array("key" => "0", "value" => $falseLable)));
				$el = new poko_form_elements_RadioGroup($element1->name, $label, $options, $value, $element1->properties->defaultValue, false, null);
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				$this->jsBind->queueCall("setupShowHideElements", new _hx_array(array($el->name, $element1->properties->showHideFields, $value, $element1->properties->showHideValue)), null);
				unset($trueLable,$options,$falseLable,$el);
			}break;
			case "association":{
				$fieldLabelSelect = $element1->properties->fieldLabel;
				if(_hx_field($element1->properties, "fieldSql") !== null && !_hx_equal($element1->properties->fieldSql, "")) {
					$fieldLabelSelect = ("(" . $element1->properties->fieldSql) . ")";
					;
				}
				$assocData = $this->app->getDb()->request(((((("SELECT `" . $element1->properties->field) . "` as value, ") . $fieldLabelSelect) . " as label FROM `") . $element1->properties->table) . "`");
				$assocData = Lambda::map($assocData, array(new _hx_lambda(array(&$assocData, &$element, &$element1, &$elements, &$fieldLabelSelect, &$label, &$value), "site_cms_modules_base_DatasetItem_25"), 'execute'));
				if($this->autoFilterValue === $element1->name) {
					$value = $this->autoFilterByAssocValue;
					;
				}
				$el = new poko_form_elements_Selectbox($element1->name, $label, $assocData, $value, $element1->properties->required, null, null);
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				unset($fieldLabelSelect,$el,$assocData);
			}break;
			case "multilink":{
				$sql = "";
				$sql .= ("SELECT `" . $element1->properties->field) . "` as 'key', \x09\x09";
				$sql .= ("       `" . $element1->properties->fieldLabel) . "` as 'value' \x09";
				$sql .= ("  FROM `" . $element1->properties->table) . "`\x09\x09\x09\x09\x09";
				$linkData = $this->app->getDb()->request($sql);
				$selectedData = new _hx_array(array());
				if(!_hx_equal($this->app->params->get("action"), "add")) {
					$sql1 = "";
					$sql1 .= ("SELECT `" . $element1->properties->linkField2) . "` as 'link' \x09";
					$sql1 .= ("  FROM `" . $element1->properties->link) . "`";
					$sql1 .= ((" WHERE `" . $element1->properties->linkField1) . "`=") . $this->app->getDb()->quote(Std::string($this->id));
					$result = $this->app->getDb()->request($sql1);
					if(null == $result) throw new HException('null iterable');
					$»it2 = $result->iterator();
					while($»it2->hasNext()) {
					$row = $»it2->next();
					$selectedData->push(Std::string($row->link));
					}
					unset($sql1,$result);
				}
				$el = new poko_form_elements_CheckboxGroup($element1->name, $label, $linkData, $selectedData, null, null);
				if(_hx_field($element1->properties, "formatter") !== null && !_hx_equal($element1->properties->formatter, "")) {
					$el->formatter = Type::createInstance(Type::resolveClass($element1->properties->formatter), new _hx_array(array()));
					;
				}
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				unset($sql,$selectedData,$linkData,$el);
			}break;
			case "keyvalue":{
				$el = new site_cms_modules_base_formElements_KeyValueInput($element1->name, $label, $value, $element1->properties, null, null, null);
				$el->minRows = $element1->properties->minRows;
				$el->maxRows = $element1->properties->maxRows;
				$el->description = $element1->properties->description;
				if($el->minRows > 0 || $el->maxRows > 0) {
					$el->description .= " <br />";
					;
				}
				if($el->minRows > 0) {
					$el->description .= " <br /><b>Minimum Rows</b>: " . $el->minRows;
					;
				}
				if($el->maxRows > 0) {
					$el->description .= " <br /><b>Maximum Rows</b>: " . $el->maxRows;
					;
				}
				$this->form->addElement($el, null);
				unset($el);
			}break;
			case "linkdisplay":{
				$el = new site_cms_modules_base_formElements_LinkTable($element1->name, $label, $element1->properties->table, $this->table, $this->id, null, null, "class=\"resizableFrame\"");
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				unset($el);
			}break;
			case "post-add-current-time":{
				$el = new poko_form_elements_Readonly($element1->name, $label, $value, $element1->properties->required, null, null);
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				unset($el);
			}break;
			case "enum":{
				$types = $this->app->getDb()->requestSingle(((("SHOW COLUMNS FROM `" . $this->table) . "` LIKE \"") . $element1->properties->name) . "\"");
				$s = Reflect::field($types, "Type");
				$items = _hx_explode("','", _hx_substr($s, 6, strlen($s) - 8));
				$data = new HList();
				{
					$_g = 0;
					while($_g < $items->length) {
						$item = $items[$_g];
						++$_g;
						$data->add(_hx_anonymous(array("key" => $item, "value" => $item)));
						unset($item);
					}
					unset($_g);
				}
				if($value === null || $value === "") {
					$value = $items[0];
					;
				}
				$el = new poko_form_elements_RadioGroup($element1->name, $label, $data, $value, null, null, null);
				$el->description = $element1->properties->description;
				$this->form->addElement($el, null);
				unset($types,$s,$items,$el,$data);
			}break;
			}
			unset($value,$label);
		}
		}
		if($this->linkMode) {
			$this->form->addElement(new poko_form_elements_Hidden($this->app->params->get("linkToField"), $this->app->params->get("linkTo"), null, null, null), null);
			$this->form->addElement(new poko_form_elements_Hidden($this->app->params->get("linkValueField"), $this->app->params->get("linkValue"), null, null, null), null);
			;
		}
		if(_hx_equal($this->app->params->get("siteMode"), "true")) {
			$this->form->addElement(new poko_form_elements_Hidden("siteMode", "true", null, null, null), null);
			;
		}
		$aF = $this->app->params->get("autofilterBy");
		if($aF !== null && !_hx_equal($aF, "")) {
			$this->form->addElement(new poko_form_elements_Hidden("autofilterBy", $aF, null, null, null), null);
			;
		}
		$aFA = $this->app->params->get("autofilterByAssoc");
		if($aFA !== null && !_hx_equal($aFA, "")) {
			$this->form->addElement(new poko_form_elements_Hidden("autofilterByAssoc", $aFA, null, null, null), null);
			;
		}
		$submitButton = new poko_form_elements_Button("__submit", site_cms_modules_base_DatasetItem_26($this, $aF, $aFA, $element, $elements), null, poko_form_elements_ButtonType::$SUBMIT);
		$keyValJsBinding = $this->jsBindings->get("site.cms.modules.base.js.JsKeyValueInput");
		if($keyValJsBinding !== null) {
			$submitButton->attributes = ((("onClick=\"" . $this->jsBind->getCall("flushWymEditors", new _hx_array(array()))) . "; return(") . $keyValJsBinding->getCall("flushKeyValueInputs", new _hx_array(array()))) . ");\"";
			;
		}
		else {
			$submitButton->attributes = ("onClick=\"return(" . $this->jsBind->getCall("flushWymEditors", new _hx_array(array()))) . ");\"";
			;
		}
		$this->form->addElement($submitButton, null);
		if(_hx_equal($this->app->params->get("action"), "add") && $this->linkMode) {
			$cancelButton = new poko_form_elements_Button("__cancel", "Cancel", "Cancel", poko_form_elements_ButtonType::$BUTTON);
			$this->form->addElement($cancelButton, null);
			unset($cancelButton);
		}
		$this->form->populateElements(null);
		unset($submitButton,$keyValJsBinding,$elements,$element,$aFA,$aF);
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
	function __toString() { return 'site.cms.modules.base.DatasetItem'; }
}
;
function site_cms_modules_base_DatasetItem_0(&$»this) {
if(!_hx_equal($»this->app->params->get("autofilterBy"), "")) {
	return $»this->app->params->get("autofilterBy");
	;
}
}
function site_cms_modules_base_DatasetItem_1(&$»this) {
if(!_hx_equal($»this->app->params->get("autofilterByAssoc"), "")) {
	return $»this->app->params->get("autofilterByAssoc");
	;
}
}
function site_cms_modules_base_DatasetItem_2(&$»this, &$result) {
if($result->data !== "" && $result->data !== null) {
	return haxe_Unserializer::run($result->data);
	;
}
else {
	return _hx_anonymous(array());
	;
}
}
function site_cms_modules_base_DatasetItem_3(&$»this, &$data, &$postProcedure, &$uploadData) {
if($»this->pagesMode) {
	return Lambda::hlist($»this->definition->elements);
	;
}
else {
	return $»this->getElementMatches();
	;
}
}
function site_cms_modules_base_DatasetItem_4(&$»this, &$data, &$elements, &$postProcedure, &$uploadData) {
if($»this->pagesMode) {
	return "Page";
	;
}
else {
	return "Record";
	;
}
}
function site_cms_modules_base_DatasetItem_5(&$»this, &$data, &$elements, &$postProcedure, &$uploadData) {
if(_hx_equal($»this->form->getElement("__action")->value, "add")) {
	return "added.";
	;
}
else {
	return "updated.";
	;
}
}
function site_cms_modules_base_DatasetItem_6(&$»this, &$data, &$elements, &$postProcedure, &$uploadData, &$url) {
if($»this->linkMode) {
	return "true";
	;
}
else {
	return "false";
	;
}
}
function site_cms_modules_base_DatasetItem_7(&$fields, $row) {
{
	return $row->Field;
	;
}
}
function site_cms_modules_base_DatasetItem_8(&$elements, &$fields, $element) {
{
	return (Lambda::has($fields, $element->name, null) && $element->type !== "hidden") || $element->type === "multilink" || $element->type === "linkdisplay";
	;
}
}
function site_cms_modules_base_DatasetItem_9(&$»this, &$data, &$e, &$f, &$fieldsToWipe, &$files, &$filesToDelete, &$h, &$nFilesAdded, &$nFilesDeleted, &$nFilesReplaced, &$r, &$safeId) {
if($r->data !== "" && $r->data !== null) {
	return haxe_Unserializer::run($r->data);
	;
}
}
function site_cms_modules_base_DatasetItem_10(&$»this) {
if($»this->pagesMode) {
	return Lambda::hlist($»this->definition->elements);
	;
}
else {
	return $»this->getElementMatches();
	;
}
}
function site_cms_modules_base_DatasetItem_11(&$»this, &$element, &$element1, &$elements, &$value) {
if(!_hx_equal($element1->properties->label, "") && _hx_field($element1->properties, "label") !== null) {
	return $element1->properties->label;
	;
}
else {
	return $element1->name;
	;
}
}
function site_cms_modules_base_DatasetItem_12(&$»this, &$el, &$element, &$element1, &$elements, &$label, &$value) {
if(!_hx_equal($element1->properties->minChars, "")) {
	return $element1->properties->minChars;
	;
}
}
function site_cms_modules_base_DatasetItem_13(&$»this, &$el, &$element, &$element1, &$elements, &$label, &$value) {
if(!_hx_equal($element1->properties->maxChars, "")) {
	return $element1->properties->maxChars;
	;
}
}
function site_cms_modules_base_DatasetItem_14(&$»this, &$el, &$element, &$element1, &$elements, &$label, &$value) {
if(_hx_equal($element1->properties->mode, "ALLOW")) {
	return poko_form_validators_StringValidatorMode::$ALLOW;
	;
}
else {
	return poko_form_validators_StringValidatorMode::$DENY;
	;
}
}
function site_cms_modules_base_DatasetItem_15(&$»this, &$el, &$element, &$element1, &$elements, &$label, &$value) {
if(_hx_equal($element1->properties->regexCaseInsensitive, "1")) {
	return "i";
	;
}
else {
	return "";
	;
}
}
function site_cms_modules_base_DatasetItem_16(&$»this, &$el, &$element, &$element1, &$elements, &$label, &$value) {
if(!_hx_equal($element1->properties->min, "")) {
	return $element1->properties->min;
	;
}
}
function site_cms_modules_base_DatasetItem_17(&$»this, &$el, &$element, &$element1, &$elements, &$label, &$value) {
if(!_hx_equal($element1->properties->max, "")) {
	return $element1->properties->max;
	;
}
}
function site_cms_modules_base_DatasetItem_18(&$»this, &$el, &$element, &$element1, &$elements, &$label, &$value) {
if(_hx_equal($element1->properties->extMode, "ALLOW")) {
	return " Allowed: ";
	;
}
else {
	return " Denied: ";
	;
}
}
function site_cms_modules_base_DatasetItem_19(&$»this, &$element, &$element1, &$elements, &$label, &$value) {
if($value !== "" && $value !== null) {
	return $value;
	;
}
}
function site_cms_modules_base_DatasetItem_20(&$»this, &$d, &$el, &$element, &$element1, &$elements, &$label, &$value) {
switch($element1->properties->mode) {
case "DATE":{
	return site_cms_common_DateTimeMode::$date;
	;
}break;
case "TIME":{
	return site_cms_common_DateTimeMode::$time;
	;
}break;
default:{
	return site_cms_common_DateTimeMode::$dateTime;
	;
}break;
}
}
function site_cms_modules_base_DatasetItem_21(&$»this, &$el, &$element, &$element1, &$elements, &$label, &$value) {
if(!_hx_equal($element1->properties->containersItems, "") && _hx_field($element1->properties, "containersItems") !== null) {
	return $element1->properties->containersItems;
	;
}
else {
	return "{'name': 'P', 'title': 'Paragraph', 'css': 'wym_containers_p'}";
	;
}
}
function site_cms_modules_base_DatasetItem_22(&$»this, &$el, &$element, &$element1, &$elements, &$label, &$value) {
if(!_hx_equal($element1->properties->classesItems, "") && _hx_field($element1->properties, "classesItems") !== null) {
	return $element1->properties->classesItems;
	;
}
else {
	return "";
	;
}
}
function site_cms_modules_base_DatasetItem_23(&$»this, &$element, &$element1, &$elements, &$label, &$options, &$value) {
if(!_hx_equal($element1->properties->labelTrue, "")) {
	return $element1->properties->labelTrue;
	;
}
else {
	return "true";
	;
}
}
function site_cms_modules_base_DatasetItem_24(&$»this, &$element, &$element1, &$elements, &$label, &$options, &$trueLable, &$value) {
if(!_hx_equal($element1->properties->labelFalse, "")) {
	return $element1->properties->labelFalse;
	;
}
else {
	return "false";
	;
}
}
function site_cms_modules_base_DatasetItem_25(&$assocData, &$element, &$element1, &$elements, &$fieldLabelSelect, &$label, &$value, $value1) {
{
	return _hx_anonymous(array("key" => $value1->value, "value" => $value1->label));
	;
}
}
function site_cms_modules_base_DatasetItem_26(&$»this, &$aF, &$aFA, &$element, &$elements) {
if(_hx_equal($»this->app->params->get("action"), "add")) {
	return "Add";
	;
}
else {
	return "Update";
	;
}
}