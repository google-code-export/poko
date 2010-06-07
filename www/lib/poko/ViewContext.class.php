<?php

class poko_ViewContext implements poko_TemploRenderable{
	public function __construct($template, $data) {
		if( !php_Boot::$skip_constructor ) {
		templo_Loader::$OPTIMIZED = true;
		templo_Loader::$TMP_DIR = "./tpl/";
		templo_Loader::$MACROS = null;
		$this->template_rendered = false;
		{
			$_g = 0; $_g1 = Reflect::fields($data);
			while($_g < $_g1->length) {
				$field = $_g1[$_g];
				++$_g;
				$this->{$field} = Reflect::field($data, $field);
				unset($field);
			}
		}
		$this->template_file = (($template !== null) ? $template : "");
	}}
	public $application;
	public $controller;
	public $template_file;
	public $template_data;
	public $template_rendered;
	public function render() {
		if($this->template_rendered) {
			return "";
		}
		$this->template_rendered = true;
		$this->template_prepareData();
		return $this->template_parseTemplate($this->template_file);
	}
	public function template_parseTemplate($template) {
		$type = strtoupper(_hx_substr($template, _hx_last_index_of($template, ".", null) + 1, null));
		if($type == "MTT") {
			$tpl = "./tpl/" . str_replace("/", "__", $template) . ".php";
			if(file_exists($tpl)) {
				$loader = new templo_Loader($template);
				return $loader->execute($this->template_data);
			}
			else {
				throw new HException("Template is missing: " . $template);
				return null;
			}
		}
		if($type == "PHP") {
			$tpl2 = "./tpl/" . str_replace("/", "__", $template);
			ob_start();
			extract((array)$this->template_data);
			include($tpl2);
			$out = ob_get_contents();
			ob_end_clean();
			return $out;
		}
		return null;
	}
	public function template_prepareData() {
		$this->template_data = _hx_anonymous(array());
		$k = "";
		$v = null;
		foreach($this->»dynamics as $k=> $v){ ;
		if(Std::is($v, _hx_qtype("poko.TemploRenderable"))) {
			$this->template_data->{$k} = $v->render();
		}
		else {
			$this->template_data->{$k} = $v;
		}
		}
		{
			$_g = 0; $_g1 = Reflect::fields($this);
			while($_g < $_g1->length) {
				$i = $_g1[$_g];
				++$_g;
				if(Std::is(Reflect::field($this, $i), _hx_qtype("poko.TemploRenderable"))) {
					$this->template_data->{$i} = Reflect::field($this, $i)->render();
				}
				else {
					$this->template_data->{$i} = Reflect::field($this, $i);
				}
				unset($i);
			}
		}
		$this->template_data->application = poko_Application::$instance;
		$this->template_data->controller = $this;
	}
	public function toString() {
		haxe_Log::trace("[TemplateObject " . $this->template_file . "]", _hx_anonymous(array("fileName" => "ViewContext.hx", "lineNumber" => 127, "className" => "poko.ViewContext", "methodName" => "toString")));
		return "";
	}
	private $»dynamics = array();
	public function &__get($n) {
		if(isset($this->»dynamics[$n]))
			return $this->»dynamics[$n];
	}
	public function __set($n, $v) {
		$this->»dynamics[$n] = $v;
	}
	public function __call($n, $a) {
		if(is_callable($this->»dynamics[$n]))
			return call_user_func_array($this->»dynamics[$n], $a);
		throw new HException("Unable to call «".$n."»");
	}
	static function parse($tpl, $data) {
		return _hx_deref(new poko_ViewContext($tpl, $data))->render();
	}
	function __toString() { return $this->toString(); }
}
