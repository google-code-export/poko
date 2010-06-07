<?php

class poko_Service extends poko_Request {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->data = _hx_anonymous(array());
		$this->allowedMethods = new HList();
		parent::__construct();
	}}
	public $method;
	public $data;
	public $allowedMethods;
	public function pre() {
		$this->method = $this->application->params->get("method");
	}
	public function main() {
		if($this->checkMethodAccess()) {
			Reflect::callMethod($this, $this->method, new _hx_array(array()));
		}
	}
	public function checkMethodAccess() {
		$passed = false;
		$»it = $this->allowedMethods->iterator();
		while($»it->hasNext()) {
		$allowed = $»it->next();
		if($allowed == $this->method) {
			$passed = true;
		}
		}
		if(!$passed) {
			$this->forbidden();
			return false;
		}
		else {
			return true;
		}
	}
	public function forbidden() {
		haxe_Log::trace("forbidden", _hx_anonymous(array("fileName" => "Service.hx", "lineNumber" => 76, "className" => "poko.Service", "methodName" => "forbidden")));
		header("content-type" . ": " . "text/html");
		php_Web::setReturnCode(403);
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'poko.Service'; }
}
