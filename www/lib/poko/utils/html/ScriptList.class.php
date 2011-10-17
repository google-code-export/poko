<?php

class poko_utils_html_ScriptList {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->scripts = new _hx_array(array());
		;
	}}
	public $scripts;
	public function addExternal($type, $url, $condition, $media, $priority) {
		if($priority === null) {
			$priority = 0;
			;
		}
		$this->scripts->push(_hx_anonymous(array("type" => $type, "isExternal" => true, "value" => $url, "condition" => $condition, "media" => $media, "priority" => $priority)));
		;
	}
	public function addInline($type, $source, $condition, $media, $priority) {
		if($priority === null) {
			$priority = 0;
			;
		}
		$this->scripts->push(_hx_anonymous(array("type" => $type, "isExternal" => false, "value" => $source, "condition" => $condition, "media" => $media, "priority" => $priority)));
		;
	}
	public function getScripts() {
		$output = "";
		$missing = new _hx_array(array());
		{
			$_g = 0; $_g1 = $this->scripts;
			while($_g < $_g1->length) {
				$script = $_g1[$_g];
				++$_g;
				if($script->isExternal) {
					if(!file_exists($script->value)) {
						$missing->push($script->value);
						;
					}
					;
				}
				$output .= poko_utils_html_ScriptList_0($this, $_g, $_g1, $missing, $output, $script);
				unset($script);
			}
			unset($_g1,$_g);
		}
		if(Std::is(poko_Poko::$instance->controller, _hx_qtype("poko.controllers.HtmlController"))) {
			$jsBindings = _hx_cast(poko_Poko::$instance->controller, _hx_qtype("poko.controllers.HtmlController"))->jsBindings;
			if(null == $jsBindings) throw new HException('null iterable');
			$»it = $jsBindings->keys();
			while($»it->hasNext()) {
			$jsBinding = $»it->next();
			$output .= ("<script> poko.js.JsPoko.instance.addRequest(\"" . $jsBinding) . "\") </script> \x0A";
			}
			unset($jsBindings);
		}
		if($missing->length > 0) {
			$error = ("Warning: (" . $missing->length) . ") external script(s) could not be found:\x0A";
			{
				$_g = 0;
				while($_g < $missing->length) {
					$s = $missing[$_g];
					++$_g;
					$error .= ("\x09" . $s) . "\x0A";
					unset($s);
				}
				unset($_g);
			}
			throw new HException($error);
			unset($error);
		}
		return $output;
		unset($output,$missing);
	}
	public function sortScripts($a, $b) {
		return ($b->priority - $a->priority);
		;
	}
	public function formatInlineScript($script) {
		$output = "";
		switch($script->type) {
		case poko_utils_html_ScriptType::$css:{
			$output = poko_utils_html_ScriptList_1($this, $output, $script);
			;
		}break;
		case poko_utils_html_ScriptType::$js:{
			$output = ("<script>" . $script->value) . "</script>";
			;
		}break;
		}
		if($script->condition !== null) {
			return $this->formatCondition($output, $script->condition);
			;
		}
		return $output;
		unset($output);
	}
	public function formatExternalScript($script) {
		$output = "";
		switch($script->type) {
		case poko_utils_html_ScriptType::$css:{
			$output = poko_utils_html_ScriptList_2($this, $output, $script);
			;
		}break;
		case poko_utils_html_ScriptType::$js:{
			$output = ("<script type=\"text/javascript\" src=\"" . $script->value) . "\"></script>";
			;
		}break;
		}
		if($script->condition !== null) {
			return $this->formatCondition($output, $script->condition);
			;
		}
		return $output;
		unset($output);
	}
	public function formatCondition($value, $condition) {
		return ((("<!--[" . $condition) . "]>") . $value) . "<![endif]-->";
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
	function __toString() { return 'poko.utils.html.ScriptList'; }
}
;
function poko_utils_html_ScriptList_0(&$»this, &$_g, &$_g1, &$missing, &$output, &$script) {
if($script->isExternal) {
	return $»this->formatExternalScript($script) . "\x0A";
	;
}
else {
	return $»this->formatInlineScript($script) . "\x0A";
	;
}
}
function poko_utils_html_ScriptList_1(&$»this, &$output, &$script) {
if($script->media === null) {
	return ("<style type=\"text/css\">" . $script->value) . "</style>";
	;
}
else {
	return ((("<style type=\"text/css\" media=\"" . $script->media) . "\">") . $script->value) . "</style>";
	;
}
}
function poko_utils_html_ScriptList_2(&$»this, &$output, &$script) {
if($script->media === null) {
	return ("<link href=\"" . $script->value) . "\" rel=\"stylesheet\" type=\"text/css\" />";
	;
}
else {
	return ((("<link href=\"" . $script->value) . "\" rel=\"stylesheet\" type=\"text/css\" media=\"") . $script->media) . "\" />";
	;
}
}