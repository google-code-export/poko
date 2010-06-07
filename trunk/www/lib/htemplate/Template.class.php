<?php

class htemplate_Template {
	public function __construct($template) {
		if( !php_Boot::$skip_constructor ) {
		$this->template = $template;
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
		$interp->variables->set("__string_buf__", array(new _hx_lambda(array("buffer" => &$buffer, "bufferStack" => &$bufferStack, "content" => &$content, "interp" => &$interp, "parsedBlocks" => &$parsedBlocks, "parser" => &$parser, "program" => &$program, "script" => &$script), null, array('current'), "{
			\$bufferStack->push(\$current);
			return new StringBuf();
		}"), 'execute1'));
		$interp->variables->set("__restore_buf__", array(new _hx_lambda(array("buffer" => &$buffer, "bufferStack" => &$bufferStack, "content" => &$content, "interp" => &$interp, "parsedBlocks" => &$parsedBlocks, "parser" => &$parser, "program" => &$program, "script" => &$script), null, array(), "{
			return \$bufferStack->pop();
		}"), 'execute0'));
		$interp->execute($program);
		return $buffer->b;
	}
	public function setInterpreterVars($interp, $content) {
		if(Std::is($content, _hx_qtype("Hash"))) {
			$hash = $content;
			$»it = $hash->keys();
			while($»it->hasNext()) {
			$field = $»it->next();
			{
				$interp->variables->set($field, $hash->get($field));
				;
			}
			}
		}
		else {
			{
				$_g = 0; $_g1 = Reflect::fields($content);
				while($_g < $_g1->length) {
					$field2 = $_g1[$_g];
					++$_g;
					$interp->variables->set($field2, Reflect::field($content, $field2));
					unset($field2);
				}
			}
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
	function __toString() { return 'htemplate.Template'; }
}
