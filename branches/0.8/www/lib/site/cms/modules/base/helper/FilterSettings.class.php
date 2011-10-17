<?php

class site_cms_modules_base_helper_FilterSettings {
	public function __construct($dataset) {
		if( !php_Boot::$skip_constructor ) {
		$this->dataset = $dataset;
		site_cms_modules_base_helper_FilterSettings::$lastDataset = $dataset;
		$this->clear();
		;
	}}
	public $enabled;
	public $dataset;
	public $filterBy;
	public $filterByOperator;
	public $filterByAssoc;
	public $filterByValue;
	public $orderBy;
	public $orderByDirection;
	public function clear() {
		$this->enabled = false;
		$this->filterBy = "";
		$this->filterByOperator = "";
		$this->filterByAssoc = "";
		$this->filterByValue = "";
		$this->orderBy = "";
		$this->orderByDirection = "";
		$this->save();
		;
	}
	public function save() {
		php_Session::set("datasetFilterSettings-" . $this->dataset, $this);
		;
	}
	public function toString() {
		return (((site_cms_modules_base_helper_FilterSettings_0($this)) . " dataset:") . $this->dataset);
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
	static $lastDataset;
	static function get($dataset) {
		if(php_Session::get("datasetFilterSettings-" . $dataset)) {
			return php_Session::get("datasetFilterSettings-" . $dataset);
			;
		}
		else {
			return new site_cms_modules_base_helper_FilterSettings($dataset);
			;
		}
		;
	}
	static function getLast() {
		return site_cms_modules_base_helper_FilterSettings::get(site_cms_modules_base_helper_FilterSettings::$lastDataset);
		;
	}
	function __toString() { return $this->toString(); }
}
;
function site_cms_modules_base_helper_FilterSettings_0(&$»this) {
if($»this->enabled) {
	return "ON";
	;
}
else {
	return "OFF";
	;
}
}