<?php

class poko_Poko {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		poko_Poko::$instance = $this;
		poko_utils_PhpTools::setupTrace();
		$this->config = new site_Config();
		if(php_Session::getName() != $this->config->sessionName) {
			php_Session::setName($this->config->sessionName);
		}
		$this->params = php_Web::getParams();
		$this->url = new poko_system_Url(php_Web::getURI());
		$controllerType = Type::resolveClass("site." . $this->findControllerClass());
		$is404 = false;
		if($controllerType !== null) {
			$this->controller = Type::createInstance($controllerType, new _hx_array(array()));
			if(Std::is($this->controller, _hx_qtype("poko.controllers.Controller"))) {
				$this->controller->handleRequest();
			}
			else {
				$is404 = true;
			}
		}
		else {
			$is404 = true;
		}
		if($is404) {
			php_Lib::hprint("<font color=\"red\"><b>404: Not a valid request</b></font>");
		}
	}}
	public $url;
	public $config;
	public $controller;
	public $params;
	public $db;
	public $__db;
	public function findControllerClass() {
		$c = (_hx_array_get($this->url->getSegments(), 0) != "" ? _hx_array_get($this->url->getSegments(), 0) : (($this->params->get("request") !== null ? $this->params->get("request") : $this->config->defaultController)));
		if(_hx_last_index_of($c, ".", null) !== -1) {
			$c = _hx_substr($c, 0, _hx_last_index_of($c, ".", null) + 1) . strtoupper(_hx_substr($c, _hx_last_index_of($c, ".", null) + 1, 1)) . _hx_substr($c, _hx_last_index_of($c, ".", null) + 2, null);
		}
		else {
			$c = strtoupper(_hx_substr($c, 0, 1)) . _hx_substr($c, 1, null);
		}
		return $c;
	}
	public function getDb() {
		if($this->__db === null) {
			$this->db = new poko_system_Db();
		}
		$this->db->connect($this->config->database_host, $this->config->database_database, $this->config->database_user, $this->config->database_password, null, $this->config->database_port);
		return $this->db;
	}
	public function redirect($url) {
		$this->controller->onFinal();
		php_Web::redirect($url);
		php_Sys::hexit(1);
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	static $instance;
	static function main() {
		new poko_Poko();
	}
	function __toString() { return 'poko.Poko'; }
}
