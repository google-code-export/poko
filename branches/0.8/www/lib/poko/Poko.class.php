<?php

class poko_Poko {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		poko_Poko::$instance = $this;
		poko_utils_PhpTools::setupTrace();
		$this->config = new site_Config();
		$this->config->init();
		$v = "";
		if(php_Session::getName() !== $this->config->sessionName) {
			$v = "YAY";
			php_Session::setName($this->config->sessionName);
			;
		}
		php_Session::start();
		if(!php_Session::exists("pokodata")) {
			php_Session::set("pokodata", _hx_anonymous(array()));
			;
		}
		$this->session = php_Session::get("pokodata");
		$this->params = php_Web::getParams();
		$this->url = new poko_system_Url(php_Web::getURI());
		$useURLRewrite = false;
		$this->controllerId = poko_Poko_0($this, $useURLRewrite, $v);
		$controllerClass = Type::resolveClass("site." . $this->controllerId);
		$is404 = false;
		if($controllerClass !== null) {
			$this->controller = Type::createInstance($controllerClass, new _hx_array(array()));
			if(Std::is($this->controller, _hx_qtype("poko.controllers.Controller"))) {
				$this->controller->handleRequest();
				;
			}
			else {
				$is404 = true;
				;
			}
			;
		}
		else {
			$is404 = true;
			;
		}
		if($is404) {
			php_Lib::hprint("<font color=\"red\"><b>404: Not a valid request</b></font>");
			;
		}
		php_Session::set("pokodata", $this->session);
		unset($v,$useURLRewrite,$is404,$controllerClass);
	}}
	public $url;
	public $config;
	public $controllerId;
	public $controller;
	public $params;
	public $db;
	public $__db;
	public $session;
	public function findControllerClassByRewrite() {
		$request = $this->params->get("request");
		$path = $this->params->get("path");
		haxe_Log::trace($request, _hx_anonymous(array("fileName" => "Poko.hx", "lineNumber" => 128, "className" => "poko.Poko", "methodName" => "findControllerClassByRewrite")));
		haxe_Log::trace($path, _hx_anonymous(array("fileName" => "Poko.hx", "lineNumber" => 129, "className" => "poko.Poko", "methodName" => "findControllerClassByRewrite")));
		return "Index";
		unset($request,$path);
	}
	public function findControllerClass() {
		$c = poko_Poko_1($this);
		if(_hx_last_index_of($c, ".", null) !== -1) {
			$c = (_hx_substr($c, 0, _hx_last_index_of($c, ".", null) + 1) . strtoupper(_hx_substr($c, _hx_last_index_of($c, ".", null) + 1, 1))) . _hx_substr($c, _hx_last_index_of($c, ".", null) + 2, null);
			;
		}
		else {
			$c = strtoupper(_hx_substr($c, 0, 1)) . _hx_substr($c, 1, null);
			;
		}
		return $c;
		unset($c);
	}
	public function getDb() {
		if($this->__db === null) {
			$this->__db = new poko_system_Db();
			;
		}
		$this->__db->connect($this->config->database_host, $this->config->database_database, $this->config->database_user, $this->config->database_password, null, $this->config->database_port);
		return $this->__db;
		;
	}
	public function redirect($url) {
		$this->controller->onFinal();
		php_Web::redirect($url);
		php_Sys::hexit(1);
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
	static $instance;
	static function main() {
		new poko_Poko();
		;
	}
	function __toString() { return 'poko.Poko'; }
}
;
function poko_Poko_0(&$»this, &$useURLRewrite, &$v) {
if($useURLRewrite) {
	return $»this->findControllerClassByRewrite();
	;
}
else {
	return $»this->findControllerClass();
	;
}
}
function poko_Poko_1(&$»this) {
if(_hx_array_get($»this->url->getSegments(), 0) !== "") {
	return _hx_array_get($»this->url->getSegments(), 0);
	;
}
else {
	if($»this->params->get("request") !== null) {
		return $»this->params->get("request");
		;
	}
	else {
		return $»this->config->getDefaultController();
		;
	}
	;
}
}