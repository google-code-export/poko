<?php

class htemplate_ScriptBuilder {
	public function __construct($context) {
		if( !php_Boot::$skip_constructor ) {
		$this->context = $context;
	}}
	public $context;
	public function build($blocks) {
		$buffer = new StringBuf();
		{
			$_g = 0;
			while($_g < $blocks->length) {
				$block = $blocks[$_g];
				++$_g;
				$buffer->b .= $this->blockToString($block);
				unset($block);
			}
		}
		return $buffer->b;
	}
	public function blockToString($block) {
		$output = $this->context . ".add(";
		$»t = ($block);
		switch($»t->index) {
		case 0:
		$s = $»t->params[0];
		{
			return $this->context . ".add('" . str_replace("'", "\\'", $s) . "');\x0A";
		}break;
		case 1:
		$s2 = $»t->params[0];
		{
			return "if(" . $s2 . ") {\x0A";
		}break;
		case 2:
		$s3 = $»t->params[0];
		{
			return "} else if(" . $s3 . ") {\x0A";
		}break;
		case 3:
		{
			return "} else {\x0A";
		}break;
		case 8:
		{
			return "}\x0A";
		}break;
		case 4:
		$s4 = $»t->params[0];
		{
			return "for(" . $s4 . ") {\x0A";
		}break;
		case 5:
		$s5 = $»t->params[0];
		{
			return "while(" . $s5 . ") {\x0A";
		}break;
		case 9:
		$s6 = $»t->params[0];
		{
			return $s6 . "\x0A";
		}break;
		case 10:
		$s7 = $»t->params[0];
		{
			return $this->context . ".add(" . $s7 . ");\x0A";
		}break;
		case 6:
		{
			return $this->context . " = __string_buf__(" . $this->context . ");\x0A";
		}break;
		case 7:
		$v = $»t->params[0];
		{
			return $v . " = " . $this->context . ".toString();\x0A" . $this->context . " = __restore_buf__();\x0A";
		}break;
		}
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'htemplate.ScriptBuilder'; }
}
