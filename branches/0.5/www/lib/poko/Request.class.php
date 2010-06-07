<?php

class poko_Request extends poko_ViewContext {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct(null,null);
		$this->js = new HList();
		$this->css = new HList();
		$this->cssPrint = new HList();
		$this->head = new poko_HtmlHeader();
		$this->head->title = "haxe poko";
		$this->jsBindings = new Hash();
		$this->jsCalls = new HList();
		if(Type::getSuperClass(Type::getClass($this)) == _hx_qtype("poko.Request") || Type::getSuperClass(Type::getClass($this)) == _hx_qtype("poko.Service")) {
			$this->template_file = $this->findTemplate(Type::getClassName(Type::getClass($this)));
		}
		else {
			$c = Type::getClass($this);
			while(Type::getSuperClass($c) != _hx_qtype("poko.Request")) $c = Type::getSuperClass($c);
			$this->template_file = $this->findTemplate(Type::getClassName($c));
			$this->request_content_file = $this->findTemplate(Type::getClassName(Type::getClass($this)));
		}
		$this->authenticate = false;
	}}
	public $authenticate;
	public $authenticationRequired;
	public $request_output;
	public $request_content;
	public $request_content_file;
	public $head;
	public $js;
	public $css;
	public $cssPrint;
	public $remoting;
	public $jsBindings;
	public $jsCalls;
	public function findTemplate($name) {
		$n = str_replace(".", "__", $name);
		if(file_exists("tpl/" . $n . ".mtt.php")) {
			return $n . ".mtt";
		}
		if(file_exists("tpl/" . $n . ".php")) {
			return $n . ".php";
		}
		return "";
	}
	public function setContentTemplate($file) {
		$this->request_content_file = poko_Application::$instance->sitePackage . "/" . $file;
	}
	public function setRequestTemplate($file) {
		$this->template_file = poko_Application::$instance->sitePackage . "/" . $file;
	}
	public function setContentOutput($output) {
		$this->request_content = $output;
	}
	public function setOutput($output) {
		$this->request_output = $output;
	}
	public function init() {
		if(_hx_equal($this->application->params->get("logout"), "true")) {
			php_Session::set("authenticated", null);
			php_Web::redirect("?request=cms.Index");
		}
		$this->remoting = new haxe_remoting_Context();
	}
	public function pre() {
		;
	}
	public function auth() {
		if(!$this->application->user->authenticated) {
			return false;
		}
		if($this->authenticationRequired !== null && $this->authenticationRequired->length > 0) {
			{
				$_g = 0; $_g1 = $this->application->user->groups;
				while($_g < $_g1->length) {
					$s = $_g1[$_g];
					++$_g;
					if(Lambda::has($this->authenticationRequired, $s, null)) {
						return true;
					}
					unset($s);
				}
			}
			return false;
		}
		return true;
	}
	public function main() {
		;
	}
	public function preRender() {
		;
	}
	public function post() {
		;
	}
	public function render() {
		$this->init();
		if($this->application->isDbRequired) {
			if($this->application->db->cnx === null) {
				throw new HException("You have not setup a DB connection and you application states that one is required");
			}
		}
		$this->pre();
		$»it = $this->application->components->iterator();
		while($»it->hasNext()) {
		$component = $»it->next();
		$component->pre();
		}
		if(!$this->application->skipAuthentication && ($this->authenticate && !$this->auth())) {
			$this->application->redirect("?request=cms.Index");
		}
		else {
			if(!haxe_remoting_HttpConnection::handleRequest($this->remoting)) {
				$this->main();
				$»it2 = $this->application->components->iterator();
				while($»it2->hasNext()) {
				$component2 = $»it2->next();
				$component2->main();
				}
				if($this->request_output === null) {
					$this->preRender();
					$this->request_output = parent::render();
				}
			}
		}
		$this->post();
		return $this->request_output;
	}
	public function getRequestContent() {
		if($this->request_content !== null) {
			return $this->request_content;
		}
		else {
			return $this->template_parseTemplate($this->request_content_file);
		}
	}
	public function nl2br($input) {
		return nl2br($input);
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'poko.Request'; }
}
