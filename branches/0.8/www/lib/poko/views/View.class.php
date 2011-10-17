<?php

class poko_views_View implements poko_views_Renderable{
	public function __construct($scope, $type, $template, $data) {
		if( !php_Boot::$skip_constructor ) {
		if($type === null) {
			$type = poko_views_ViewType::$PHP;
			;
		}
		$this->type = $type;
		$this->template = $template;
		$this->data = poko_views_View_0($this, $data, $scope, $template, $type);
		$this->scope = poko_views_View_1($this, $data, $scope, $template, $type);
		$this->rendered = false;
		;
	}}
	public $type;
	public $rendered;
	public $template;
	public $data;
	public $renderCache;
	public $renderer;
	public $scope;
	public function render() {
		if($this->template === null || $this->rendered) {
			return $this->renderCache;
			;
		}
		$this->rendered = true;
		$this->renderer = poko_views_View_2($this);
		{
			$_g = 0; $_g1 = Reflect::fields($this->scope);
			while($_g < $_g1->length) {
				$i = $_g1[$_g];
				++$_g;
				$d = Reflect::field($this->scope, $i);
				if(!_hx_equal($d, poko_Poko::$instance) && !_hx_equal($d, poko_Poko::$instance->controller)) {
					$this->data->{$i} = $d;
					;
				}
				unset($i,$d);
			}
			unset($_g1,$_g);
		}
		{
			$_g = 0; $_g1 = Reflect::fields($this->data);
			while($_g < $_g1->length) {
				$i = $_g1[$_g];
				++$_g;
				$d = Reflect::field($this->data, $i);
				if(Std::is($d, _hx_qtype("poko.views.Renderable"))) {
					$d = _hx_cast($d, _hx_qtype("poko.views.Renderable"))->render();
					;
				}
				$this->renderer->assign($i, $d);
				unset($i,$d);
			}
			unset($_g1,$_g);
		}
		$this->renderer->assign("app", poko_Poko::$instance);
		$this->renderer->assign("controller", poko_Poko::$instance->controller);
		$this->renderer->assign("resolveClass", (isset(Type::$resolveClass) ? Type::$resolveClass: array("Type", "resolveClass")));
		return $this->renderCache = $this->renderer->render();
		;
	}
	public function findTemplate($controller, $skipTopLevel) {
		if($skipTopLevel === null) {
			$skipTopLevel = false;
			;
		}
		$file = "";
		$c = poko_views_View_3($this, $controller, $file, $skipTopLevel);
		while($c !== null) {
			$file = Std::string($c);
			if(StringTools::startsWith($file, "site.")) {
				$file = str_replace(".", "/", _hx_substr($file, strlen("site."), null));
				$checkTemplo = ("./tpl/mtt/" . str_replace("/", "__", $file)) . ".mtt.php";
				$checkPhp = ("./tpl/php/" . $file) . ".php";
				$checkHTemplate = ("./tpl/ht/" . $file) . ".ht";
				if(file_exists($checkTemplo)) {
					$this->template = $file . ".mtt";
					$this->type = poko_views_ViewType::$TEMPLO;
					return;
					;
				}
				if(file_exists($checkPhp)) {
					$this->template = $file . ".php";
					$this->type = poko_views_ViewType::$PHP;
					return;
					;
				}
				if(file_exists($checkHTemplate)) {
					$this->template = $file . ".ht";
					$this->type = poko_views_ViewType::$HTEMPLATE;
					return;
					;
				}
				unset($checkTemplo,$checkPhp,$checkHTemplate);
			}
			$c = Type::getSuperClass($c);
			;
		}
		$this->template = null;
		unset($file,$c);
	}
	public function getExt() {
		return poko_views_View_4($this);
		;
	}
	public function setOutput($s) {
		$this->renderCache = $s;
		$this->rendered = true;
		;
	}
	public function toString() {
		return ("[View " . $this->template) . "]";
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
	function __toString() { return $this->toString(); }
}
;
function poko_views_View_0(&$»this, &$data, &$scope, &$template, &$type) {
if($data !== null) {
	return $data;
	;
}
else {
	return _hx_anonymous(array());
	;
}
}
function poko_views_View_1(&$»this, &$data, &$scope, &$template, &$type) {
if($scope !== null) {
	return $scope;
	;
}
else {
	return poko_Poko::$instance->controller;
	;
}
}
function poko_views_View_2(&$»this) {
switch($»this->type) {
case poko_views_ViewType::$PHP:{
	return new poko_views_renderers_Php($»this->template);
	;
}break;
case poko_views_ViewType::$TEMPLO:{
	return new poko_views_renderers_Templo($»this->template);
	;
}break;
case poko_views_ViewType::$HTEMPLATE:{
	return new poko_views_renderers_HTemplate($»this->template);
	;
}break;
default:{
	return null;
	;
}break;
}
}
function poko_views_View_3(&$»this, &$controller, &$file, &$skipTopLevel) {
if($skipTopLevel) {
	return Type::getSuperClass(Type::getClass($controller));
	;
}
else {
	return Type::getClass($controller);
	;
}
}
function poko_views_View_4(&$»this) {
switch($»this->type) {
case poko_views_ViewType::$HTEMPLATE:{
	return "ht";
	;
}break;
case poko_views_ViewType::$TEMPLO:{
	return "mtt";
	;
}break;
case poko_views_ViewType::$PHP:{
	return "php";
	;
}break;
default:{
	return null;
	;
}break;
}
}