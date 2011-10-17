<?php

class htemplate_ScriptBuilder {
	public function __construct($context) {
		if( !php_Boot::$skip_constructor ) {
		$this->context = $context;
		;
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
			unset($_g);
		}
		return $buffer->b;
		unset($buffer);
	}
	public function blockToString($block) {
		$output = $this->context . ".add(";
		$»t = ($block);
		switch($»t->index) {
		case 0:
		$s = $»t->params[0];
		{
			return (($this->context . ".add('") . str_replace("'", "\\'", $s)) . "');\x0A";
			;
		}break;
		case 1:
		$s = $»t->params[0];
		{
			return ("if(" . $s) . ") {\x0A";
			;
		}break;
		case 2:
		$s = $»t->params[0];
		{
			return ("} else if(" . $s) . ") {\x0A";
			;
		}break;
		case 3:
		{
			return "} else {\x0A";
			;
		}break;
		case 8:
		{
			return "}\x0A";
			;
		}break;
		case 4:
		$s = $»t->params[0];
		{
			return ("for(" . $s) . ") {\x0A";
			;
		}break;
		case 5:
		$s = $»t->params[0];
		{
			return ("while(" . $s) . ") {\x0A";
			;
		}break;
		case 9:
		$s = $»t->params[0];
		{
			return $s . "\x0A";
			;
		}break;
		case 10:
		$s = $»t->params[0];
		{
			return (($this->context . ".add(") . $s) . ");\x0A";
			;
		}break;
		case 6:
		{
			return (($this->context . " = __string_buf__(") . $this->context) . ");\x0A";
			;
		}break;
		case 7:
		$v = $»t->params[0];
		{
			return (((($v . " = ") . $this->context) . ".toString();\x0A") . $this->context) . " = __restore_buf__();\x0A";
			;
		}break;
		}
		unset($output);
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
	function __toString() { return 'htemplate.ScriptBuilder'; }
}
