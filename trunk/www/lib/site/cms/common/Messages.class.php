<?php

class site_cms_common_Messages {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->messages = new HList();
	}}
	public $messages;
	public $instanceName;
	public function save() {
		php_Session::set("Messages_" . $this->instanceName, $this);
	}
	public function addMessage($text) {
		$this->messages->push(new site_cms_common_Message($text, site_cms_common_MessageType::$MESSAGE));
	}
	public function addWarning($text) {
		$this->messages->push(new site_cms_common_Message($text, site_cms_common_MessageType::$WARNING));
	}
	public function addError($text) {
		$this->messages->push(new site_cms_common_Message($text, site_cms_common_MessageType::$ERROR));
	}
	public function addDebug($text) {
		$this->messages->push(new site_cms_common_Message($text, site_cms_common_MessageType::$DEBUG));
	}
	public function getMessages() {
		return ($this->get(site_cms_common_MessageType::$MESSAGE));
	}
	public function getWarnings() {
		return ($this->get(site_cms_common_MessageType::$WARNING));
	}
	public function getDebugs() {
		return ($this->get(site_cms_common_MessageType::$DEBUG));
	}
	public function getAll() {
		return ($this->messages);
	}
	public function getErrors() {
		return ($this->get(site_cms_common_MessageType::$ERROR));
	}
	public function get($type) {
		$l = new HList();
		$m = null;
		$»it = $this->messages->iterator();
		while($»it->hasNext()) {
		$m1 = $»it->next();
		{
			if(Std::string($m1->type) == Std::string($type)) {
				$l->add($m1);
			}
			;
		}
		}
		return ($l);
	}
	public function clearAll() {
		$this->messages = new HList();
	}
	public function toString() {
		return (Std::string($this->messages));
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	static function load($instanceName) {
		$m = (php_Session::get("Messages_" . $instanceName) ? php_Session::get("Messages_" . $instanceName) : new site_cms_common_Messages());
		$m->instanceName = $instanceName;
		return $m;
	}
	function __toString() { return $this->toString(); }
}
