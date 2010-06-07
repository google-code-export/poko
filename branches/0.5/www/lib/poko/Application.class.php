<?php

class poko_Application {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->time1 = haxe_Timer::stamp();
		$this->sessionName = "poko";
		$this->defaultRequest = "Index";
		poko_Application::$instance = $this;
		$this->debug = false;
		$this->sitePath = "";
		$this->siteRoot = ".";
		$this->packageRoot = $this->siteRoot;
		$this->sitePackage = "site";
		$this->uploadFolder = "uploads";
		$this->errorContactEmail = "contact@touchmypixel.com";
		$this->useGip = false;
		$this->skipAuthentication = false;
		$this->db = new poko_Db();
		$this->isDbRequired = true;
		$this->components = new HList();
	}}
	public $request;
	public $isDbRequired;
	public $db;
	public $defaultRequest;
	public $siteRoot;
	public $sitePath;
	public $packageRoot;
	public $sitePackage;
	public $uploadFolder;
	public $useGip;
	public $html;
	public $skipAuthentication;
	public $params;
	public $debug;
	public $sessionName;
	public $messages;
	public $user;
	public $errorContactEmail;
	public $components;
	public $time1;
	public function setupRequest() {
		$this->setupSessionData();
		$this->params = php_Web::getParams();
		$req = $this->params->get("request");
		if($req === null) {
			$req = $this->defaultRequest;
		}
		$pack = ($this->sitePackage != "" ? $this->sitePackage . "." : "");
		$c = Type::resolveClass($pack . $req);
		if($c === null) {
			php_Lib::hprint("<h3>404: Failed to load request</h3>Please check that the request is valid and it's class is imported.");
			php_Sys::hexit(1);
		}
		else {
			$this->request = Type::createInstance($c, new _hx_array(array()));
			$this->request->application = $this;
		}
	}
	public function execute() {
		if($this->request === null) {
			$this->setupRequest();
		}
		$content = $this->request->render();
		if($content !== null) {
			php_Lib::hprint($content);
		}
		$this->finalizeSessionData();
		$time = haxe_Timer::stamp() - $this->time1;
		php_Lib::hprint($time);
	}
	public function setupSessionData() {
		if(php_Session::getName() != $this->sessionName) {
			php_Session::setName($this->sessionName);
		}
		$this->messages = (php_Session::get("messages") ? php_Session::get("messages") : new poko_utils_Messages());
		$this->user = (php_Session::get("user") ? php_Session::get("user") : new poko_User());
		$this->user->update();
	}
	public function finalizeSessionData() {
		php_Session::set("messages", $this->messages);
		php_Session::set("user", $this->user);
	}
	public function redirect($url) {
		$this->finalizeSessionData();
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
	function __toString() { return 'poko.Application'; }
}
