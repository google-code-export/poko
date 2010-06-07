<?php

class poko_utils_Messages {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->messages = new HList();
	}}
	public $messages;
	public function addMessage($text) {
		$this->messages->push(new poko_utils_Message($text, poko_utils_MessageType::$MESSAGE));
	}
	public function addWarning($text) {
		$this->messages->push(new poko_utils_Message($text, poko_utils_MessageType::$WARNING));
	}
	public function addError($text) {
		$this->messages->push(new poko_utils_Message($text, poko_utils_MessageType::$ERROR));
	}
	public function addDebug($text) {
		$this->messages->push(new poko_utils_Message($text, poko_utils_MessageType::$DEBUG));
	}
	public function getMessages() {
		return ($this->get(poko_utils_MessageType::$MESSAGE));
	}
	public function getWarnings() {
		return ($this->get(poko_utils_MessageType::$WARNING));
	}
	public function getDebugs() {
		return ($this->get(poko_utils_MessageType::$DEBUG));
	}
	public function getAll() {
		return ($this->messages);
	}
	public function getErrors() {
		return ($this->get(poko_utils_MessageType::$ERROR));
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
	function __toString() { return $this->toString(); }
}
