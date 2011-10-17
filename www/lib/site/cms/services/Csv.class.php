<?php

class site_cms_services_Csv extends poko_controllers_Controller {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $definition;
	public $fieldLabels;
	public $fields;
	public $data;
	public $associateExtras;
	public function main() {
		$now = DateTools::format(Date::now(), "%a, %d %b %Y %H:%M:%S") . " GMT";
		$dataset = Std::parseInt($this->app->params->get("dataset"));
		$this->definition = new site_cms_common_Definition($dataset);
		header(("Last-Modified" . ": ") . $now);
		header(("Expires" . ": ") . $now);
		header(("Content-disposition" . ": ") . (("attachment; filename=" . $this->definition->table) . ".csv"));
		header(("content-type" . ": ") . "text/csv");
		$this->fillData();
		$ths = $this;
		$this->fieldLabels = Lambda::map($this->fields, array(new _hx_lambda(array(&$dataset, &$now, &$ths), "site_cms_services_Csv_0"), 'execute'));
		$str = new StringBuf();
		if(null == $this->fieldLabels) throw new HException('null iterable');
		$»it = $this->fieldLabels->iterator();
		while($»it->hasNext()) {
		$f = $»it->next();
		$str->b .= (("\"" . str_replace("\"", "\"\"", $f)) . "\"") . ",";
		}
		$str->b .= "\x0A";
		if(null == $this->data) throw new HException('null iterable');
		$»it = $this->data->iterator();
		while($»it->hasNext()) {
		$r = $»it->next();
		{
			if(null == $this->fields) throw new HException('null iterable');
			$»it2 = $this->fields->iterator();
			while($»it2->hasNext()) {
			$f = $»it2->next();
			{
				$str->b .= (("\"" . str_replace("\"", "\"\"", $this->getVal($r, $f))) . "\"") . ",";
				;
			}
			}
			$str->b .= "\x0A";
			;
		}
		}
		php_Lib::hprint($str);
		unset($ths,$str,$now,$dataset);
	}
	public function getVal($row, $field) {
		$data = Reflect::field($row, $field);
		$properties = $this->definition->getElement($field)->properties;
		return site_cms_services_Csv_1($this, $data, $field, $properties, $row);
		unset($properties,$data);
	}
	public function formatBool($data, $properties) {
		if(_hx_equal($properties->labelTrue, "") || _hx_equal($properties->labelFalse, "")) {
			return site_cms_services_Csv_2($this, $data, $properties);
			;
		}
		else {
			return site_cms_services_Csv_3($this, $data, $properties);
			;
		}
		;
	}
	public function formatDate($d) {
		if(!Std::is($d, _hx_qtype("Date"))) {
			return null;
			;
		}
		$months = Lambda::harray(poko_utils_ListData::arrayToList(poko_utils_ListData::$months, 1));
		return ((($d->getDate() . " ") . _hx_array_get($months, $d->getMonth())->key) . " ") . $d->getFullYear();
		unset($months);
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
					$types = $this->app->getDb()->requestSingle(((("SHOW COLUMNS FROM `" . $this->definition->table) . "` LIKE \"") . $element1->properties->name) . "\"");
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
	public function fillData() {
		$orderField = $this->getOrderField();
		$isOrderingEnabled = $orderField !== null;
		$this->fields = $this->getFieldMatches();
		$primaryData = $this->app->getDb()->request(("SHOW COLUMNS FROM `" . $this->definition->table) . "` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
		if($primaryData->length < 1) {
			php_Lib::hprint(("\"" . $this->definition->table) . "\" does not have a field set as both: \"auto_increment\" AND \"primary key\"");
			php_Sys::hexit(1);
			;
		}
		else {
			$field = $primaryData->pop()->Field;
			$this->definition->primaryKey = $field;
			unset($field);
		}
		$this->getAssociationExtras();
		$sql = ("SELECT *, `" . $this->definition->primaryKey) . "` as 'cms_primaryKey' ";
		if($orderField !== null) {
			$sql .= (", `" . $orderField) . "` as 'dataset__orderField' ";
			;
		}
		$sql .= ("FROM `" . $this->definition->table) . "` ";
		$hasWhere = false;
		$currentFilterSettings = site_cms_modules_base_helper_FilterSettings::get($this->definition->table);
		$filterByValue = null;
		$filterByAssocValue = null;
		$filterByOperatorValue = null;
		$filterByValueValue = null;
		$autoFilterValue = site_cms_services_Csv_4($this, $currentFilterSettings, $filterByAssocValue, $filterByOperatorValue, $filterByValue, $filterByValueValue, $hasWhere, $isOrderingEnabled, $orderField, $primaryData, $sql);
		$autoFilterByAssocValue = site_cms_services_Csv_5($this, $autoFilterValue, $currentFilterSettings, $filterByAssocValue, $filterByOperatorValue, $filterByValue, $filterByValueValue, $hasWhere, $isOrderingEnabled, $orderField, $primaryData, $sql);
		if($currentFilterSettings->enabled) {
			$filterByValue = $currentFilterSettings->filterBy;
			$filterByAssocValue = $currentFilterSettings->filterByAssoc;
			$filterByOperatorValue = $currentFilterSettings->filterByOperator;
			$filterByValueValue = $currentFilterSettings->filterByValue;
			;
		}
		if($currentFilterSettings->enabled && $filterByValue !== null && $filterByValue !== "") {
			if($this->definition->getElement($filterByValue)->type === "enum" || $this->definition->getElement($filterByValue)->type === "association" || $this->definition->getElement($filterByValue)->type === "bool") {
				if($filterByAssocValue !== "") {
					$sql .= ((("WHERE `" . $filterByValue) . "`='") . $filterByAssocValue) . "' ";
					;
				}
				$hasWhere = true;
				;
			}
			else {
				if($filterByOperatorValue !== "" && $filterByValueValue !== "") {
					$op = site_cms_services_Csv_6($this, $autoFilterByAssocValue, $autoFilterValue, $currentFilterSettings, $filterByAssocValue, $filterByOperatorValue, $filterByValue, $filterByValueValue, $hasWhere, $isOrderingEnabled, $orderField, $primaryData, $sql);
					$val = site_cms_services_Csv_7($this, $autoFilterByAssocValue, $autoFilterValue, $currentFilterSettings, $filterByAssocValue, $filterByOperatorValue, $filterByValue, $filterByValueValue, $hasWhere, $isOrderingEnabled, $op, $orderField, $primaryData, $sql);
					$sql .= ((((("WHERE `" . $filterByValue) . "` ") . $op) . " '") . $val) . "' ";
					$hasWhere = true;
					unset($val,$op);
				}
				;
			}
			;
		}
		$orderByValue = null;
		$orderByDirectionValue = null;
		if($currentFilterSettings->enabled) {
			$orderByValue = $currentFilterSettings->orderBy;
			$orderByDirectionValue = $currentFilterSettings->orderByDirection;
			;
		}
		if($isOrderingEnabled && $orderField !== null && (!($currentFilterSettings->enabled) || $orderByValue === null)) {
			$sql .= "ORDER BY `dataset__orderField`";
			;
		}
		else {
			if($currentFilterSettings->enabled && $orderByValue !== null && $orderByValue !== "") {
				$sql .= (("ORDER BY `" . $orderByValue) . "` ") . $orderByDirectionValue;
				;
			}
			else {
				if($this->definition->autoOrderingField !== "" && $this->definition->autoOrderingField !== null) {
					$sql .= (("ORDER BY `" . $this->definition->autoOrderingField) . "` ") . $this->definition->autoOrderingOrder;
					;
				}
				else {
					$sql .= ("ORDER BY `" . $this->definition->primaryKey) . "`";
					;
				}
				;
			}
			;
		}
		$this->data = $this->app->getDb()->request($sql);
		unset($sql,$primaryData,$orderField,$orderByValue,$orderByDirectionValue,$isOrderingEnabled,$hasWhere,$filterByValueValue,$filterByValue,$filterByOperatorValue,$filterByAssocValue,$currentFilterSettings,$autoFilterValue,$autoFilterByAssocValue);
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
		$fields = $this->app->getDb()->request(("SHOW FIELDS FROM `" . $this->definition->table) . "`");
		$fields = Lambda::map($fields, array(new _hx_lambda(array(&$definitionFields, &$fields, &$ths), "site_cms_services_Csv_8"), 'execute'));
		$fields = Lambda::filter($fields, array(new _hx_lambda(array(&$definitionFields, &$fields, &$ths), "site_cms_services_Csv_9"), 'execute'));
		return $fields;
		unset($ths,$fields,$definitionFields);
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
	static function csvefy($s) {
		return ("\"" . str_replace("\"", "\"\"", $s)) . "\"";
		;
	}
	function __toString() { return 'site.cms.services.Csv'; }
}
;
function site_cms_services_Csv_0(&$dataset, &$now, &$ths, $row) {
{
	$el = $ths->definition->getElement($row);
	return site_cms_services_Csv_10($dataset, $el, $now, $row, $ths);
	unset($el);
}
}
function site_cms_services_Csv_1(&$»this, &$data, &$field, &$properties, &$row) {
switch($properties->type) {
case "text":{
	return $data;
	;
}break;
case "richtext-tinymce":{
	return $data;
	;
}break;
case "richtext-wym":{
	return $data;
	;
}break;
case "image-file":{
	return $data;
	;
}break;
case "bool":{
	return $»this->formatBool($data, $properties);
	;
}break;
case "date":{
	return $»this->formatDate($data);
	;
}break;
case "keyvalue":{
	return $data;
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
function site_cms_services_Csv_2(&$»this, &$data, &$properties) {
if($data) {
	return "&#x2714;";
	;
}
else {
	return "&#x02610;";
	;
}
}
function site_cms_services_Csv_3(&$»this, &$data, &$properties) {
if($data) {
	return $properties->labelTrue;
	;
}
else {
	return $properties->labelFalse;
	;
}
}
function site_cms_services_Csv_4(&$»this, &$currentFilterSettings, &$filterByAssocValue, &$filterByOperatorValue, &$filterByValue, &$filterByValueValue, &$hasWhere, &$isOrderingEnabled, &$orderField, &$primaryData, &$sql) {
if(!_hx_equal($»this->app->params->get("autofilterBy"), "")) {
	return $»this->app->params->get("autofilterBy");
	;
}
}
function site_cms_services_Csv_5(&$»this, &$autoFilterValue, &$currentFilterSettings, &$filterByAssocValue, &$filterByOperatorValue, &$filterByValue, &$filterByValueValue, &$hasWhere, &$isOrderingEnabled, &$orderField, &$primaryData, &$sql) {
if(!_hx_equal($»this->app->params->get("autofilterByAssoc"), "")) {
	return $»this->app->params->get("autofilterByAssoc");
	;
}
}
function site_cms_services_Csv_6(&$»this, &$autoFilterByAssocValue, &$autoFilterValue, &$currentFilterSettings, &$filterByAssocValue, &$filterByOperatorValue, &$filterByValue, &$filterByValueValue, &$hasWhere, &$isOrderingEnabled, &$orderField, &$primaryData, &$sql) {
if($filterByOperatorValue === "~") {
	return "LIKE";
	;
}
else {
	return $filterByOperatorValue;
	;
}
}
function site_cms_services_Csv_7(&$»this, &$autoFilterByAssocValue, &$autoFilterValue, &$currentFilterSettings, &$filterByAssocValue, &$filterByOperatorValue, &$filterByValue, &$filterByValueValue, &$hasWhere, &$isOrderingEnabled, &$op, &$orderField, &$primaryData, &$sql) {
if($filterByOperatorValue === "~") {
	return ("%" . $filterByValueValue) . "%";
	;
}
else {
	return $filterByValueValue;
	;
}
}
function site_cms_services_Csv_8(&$definitionFields, &$fields, &$ths, $row) {
{
	return $row->Field;
	;
}
}
function site_cms_services_Csv_9(&$definitionFields, &$fields, &$ths, $row) {
{
	$match = Lambda::has($definitionFields, $row, null) && ($ths->definition->getElement($row)->type !== "hidden" && $ths->definition->getElement($row)->type !== "order" && $ths->definition->getElement($row)->showInList);
	return $match || $row === $ths->definition->primaryKey;
	unset($match);
}
}
function site_cms_services_Csv_10(&$dataset, &$el, &$now, &$row, &$ths) {
if($el->label !== "") {
	return $el->label;
	;
}
else {
	return $el->name;
	;
}
}