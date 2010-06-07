<?php

class poko_views_View implements poko_views_Renderable{
	public function __construct($scope, $type, $template, $data) {
		if( !php_Boot::$skip_constructor ) {
		if($type === null) {
			$type = poko_views_ViewType::$TEMPLO;
		}
		$this->type = $type;
		$this->template = $template;
		$this->data = ($data !== null ? $data : _hx_anonymous(array()));
		$this->scope = ($scope !== null ? $scope : poko_Poko::$instance->controller);
		$this->rendered = false;
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
		}
		$this->rendered = true;
		$this->renderer = eval("if(isset(\$this)) \$�this =& \$this;switch(\$�this->type) {
			case poko_views_ViewType::\$PHP:{
				\$�r = new poko_views_renderers_Php(\$�this->template);
			}break;
			case poko_views_ViewType::\$TEMPLO:{
				\$�r = new poko_views_renderers_Templo(\$�this->template);
			}break;
			case poko_views_ViewType::\$HTEMPLATE:{
				\$�r = new poko_views_renderers_HTemplate(\$�this->template);
			}break;
			default:{
				\$�r = null;
			}break;
			}
			return \$�r;
		");
		{
			$_g = 0; $_g1 = Reflect::fields($this->scope);
			while($_g < $_g1->length) {
				$i = $_g1[$_g];
				++$_g;
				$d = Reflect::field($this->scope, $i);
				if(!_hx_equal($d, poko_Poko::$instance) && !_hx_equal($d, poko_Poko::$instance->controller)) {
					$this->data->{$i} = $d;
				}
				unset($i,$d);
			}
		}
		{
			$_g2 = 0; $_g12 = Reflect::fields($this->data);
			while($_g2 < $_g12->length) {
				$i2 = $_g12[$_g2];
				++$_g2;
				$d2 = Reflect::field($this->data, $i2);
				if(Std::is($d2, _hx_qtype("poko.views.Renderable"))) {
					$d2 = _hx_deref(eval("if(isset(\$this)) \$�this =& \$this;\$tmp = \$d2;
						\$�r2 = (Std::is(\$tmp, _hx_qtype(\"poko.views.Renderable\")) ? \$tmp : eval(\"if(isset(\\\$this)) \\\$�this =& \\\$this;throw new HException(\\\"Class cast error\\\");
							return \\\$�r3;
						\"));
						return \$�r2;
					"))->render();
				}
				$this->renderer->assign($i2, $d2);
				unset($�r3,$�r2,$tmp,$i2,$d2);
			}
		}
		$this->renderer->assign("application", poko_Poko::$instance);
		$this->renderer->assign("controller", poko_Poko::$instance->controller);
		$this->renderer->assign("resolveClass", isset(Type::$resolveClass) ? Type::$resolveClass: array("Type", "resolveClass"));
		return $this->renderCache = $this->renderer->render();
	}
	public function findTemplate($controller, $skipTopLevel) {
		if($skipTopLevel === null) {
			$skipTopLevel = false;
		}
		$file = "";
		$c = ($skipTopLevel ? Type::getSuperClass(Type::getClass($controller)) : Type::getClass($controller));
		while($c !== null) {
			$file = Std::string($c);
			if(StringTools::startsWith($file, "site.")) {
				$file = str_replace(".", "/", _hx_substr($file, strlen("site."), null));
				$checkTemplo = "./tpl/mtt/" . str_replace("/", "__", $file) . ".mtt.php";
				$checkPhp = "./tpl/php/" . $file . ".php";
				$checkHTemplate = "./tpl/ht/" . $file . ".ht";
				if(file_exists($checkTemplo)) {
					$this->template = $file . ".mtt";
					$this->type = poko_views_ViewType::$TEMPLO;
					return;
				}
				if(file_exists($checkPhp)) {
					$this->template = $file . ".php";
					$this->type = poko_views_ViewType::$PHP;
					return;
				}
				if(file_exists($checkHTemplate)) {
					$this->template = $file . ".ht";
					$this->type = poko_views_ViewType::$HTEMPLATE;
					return;
				}
			}
			$c = Type::getSuperClass($c);
			unset($checkTemplo,$checkPhp,$checkHTemplate);
		}
		$this->template = null;
	}
	public function getExt() {
		return eval("if(isset(\$this)) \$�this =& \$this;switch(\$�this->type) {
			case poko_views_ViewType::\$HTEMPLATE:{
				\$�r = \"ht\";
			}break;
			case poko_views_ViewType::\$TEMPLO:{
				\$�r = \"mtt\";
			}break;
			case poko_views_ViewType::\$PHP:{
				\$�r = \"php\";
			}break;
			default:{
				\$�r = null;
			}break;
			}
			return \$�r;
		");
	}
	public function setOutput($s) {
		$this->renderCache = $s;
		$this->rendered = true;
	}
	public function toString() {
		return "[View " . $this->template . "]";
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->�dynamics[$m]) && is_callable($this->�dynamics[$m]))
			return call_user_func_array($this->�dynamics[$m], $a);
		else
			throw new HException('Unable to call �'.$m.'�');
	}
	function __toString() { return $this->toString(); }
}
