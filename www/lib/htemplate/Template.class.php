<?php

class htemplate_Template {
	public function __construct($template) {
		if( !php_Boot::$skip_constructor ) {
		$this->template = $template;
		;
	}}
	public $template;
	public $variables;
	public function execute($content) {
		$buffer = new StringBuf();
		$parsedBlocks = _hx_deref(new htemplate_Parser())->parse($this->template);
		$script = _hx_deref(new htemplate_ScriptBuilder("__b__"))->build($parsedBlocks);
		$parser = new hscript_Parser();
		$program = $parser->parseString($script);
		$interp = new hscript_Interp();
		$this->variables = $interp->variables;
		$bufferStack = new _hx_array(array());
		$this->setInterpreterVars($interp, $content);
		$interp->variables->set("__b__", $buffer);
		$interp->variables->set("__string_buf__", array(new _hx_lambda(array(&$buffer, &$bufferStack, &$content, &$interp, &$parsedBlocks, &$parser, &$program, &$script), "htemplate_Template_0"), 'execute'));
		$interp->variables->set("__restore_buf__", array(new _hx_lambda(array(&$buffer, &$bufferStack, &$content, &$interp, &$parsedBlocks, &$parser, &$program, &$script), "htemplate_Template_1"), 'execute'));
		$interp->execute($program);
		return $buffer->b;
		unset($script,$program,$parser,$parsedBlocks,$interp,$bufferStack,$buffer);
	}
	public function setInterpreterVars($interp, $content) {
		if(Std::is($content, _hx_qtype("Hash"))) {
			$hash = $content;
			if(null == $hash) throw new HException('null iterable');
			$»it = $hash->keys();
			while($»it->hasNext()) {
			$field = $»it->next();
			{
				$interp->variables->set($field, $hash->get($field));
				;
			}
			}
			unset($hash);
		}
		else {
			{
				$_g = 0; $_g1 = Reflect::fields($content);
				while($_g < $_g1->length) {
					$field = $_g1[$_g];
					++$_g;
					$interp->variables->set($field, Reflect::field($content, $field));
					unset($field);
				}
				unset($_g1,$_g);
			}
			;
		}
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
	function __toString() { return 'htemplate.Template'; }
}
;
function htemplate_Template_0(&$buffer, &$bufferStack, &$content, &$interp, &$parsedBlocks, &$parser, &$program, &$script, $current) {
{
	$bufferStack->push($current);
	return new StringBuf();
	;
}
}
function htemplate_Template_1(&$buffer, &$bufferStack, &$content, &$interp, &$parsedBlocks, &$parser, &$program, &$script) {
{
	return $bufferStack->pop();
	;
}
}