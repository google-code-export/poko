<?php

class poko_Component extends poko_ViewContext {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct(null,null);
		$this->template_file = $this->findTemplate(Type::getClassName(Type::getClass($this)));
		$this->application = poko_Application::$instance;
		$this->application->components->add($this);
	}}
	public $output;
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
	public function setTemplate($file) {
		$this->template_file = poko_Application::$instance->packageRoot . "/" . $file;
	}
	public function pre() {
		;
	}
	public function main() {
		;
	}
	public function setOutput($value) {
		$this->output = Std::string($value);
	}
	public function render() {
		return ($this->output !== null ? $this->output : parent::render());
	}
	public function toString() {
		return "NAAAV";
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
