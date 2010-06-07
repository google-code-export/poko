<?php

class htemplate_Parser {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->blocksStack = new _hx_array(array());
	}}
	public $blocksStack;
	public $codeBuf;
	public function parseScript($scriptPart) {
		$braceStack = 0;
		$insideSingleQuote = false;
		$insideDoubleQuote = false;
		$buffer = new StringBuf();
		$i = -1;
		while(++$i < strlen($scriptPart)) {
			$char = substr($scriptPart, $i, 1);
			if(!$insideDoubleQuote && !$insideSingleQuote) {
				switch($char) {
				case "{":{
					++$braceStack;
				}break;
				case "}":{
					if($braceStack === 0) {
						return _hx_substr($scriptPart, 0, $i);
					}
					else {
						--$braceStack;
					}
				}break;
				case "\"":{
					$insideDoubleQuote = true;
				}break;
				case "'":{
					$insideSingleQuote = true;
				}break;
				}
			}
			else {
				if($insideDoubleQuote && $char == "\"" && substr($scriptPart, $i - 1, 1) != "\\") {
					$insideDoubleQuote = false;
				}
				else {
					if($insideSingleQuote && $char == "'" && substr($scriptPart, $i - 1, 1) != "\\") {
						$insideSingleQuote = false;
					}
				}
			}
			unset($char);
		}
		throw new HException("Failed to find a closing delimiter for the script block: " . _hx_substr($scriptPart, 0, 100) . " ...");
	}
	public function cleanCondition($s) {
		$s = trim($s);
		if(_hx_substr($s, 0, 1) == "(") {
			$s = _hx_substr($s, 1, strlen($s) - 2);
			$s = trim($s);
		}
		return $s;
	}
	public function parseBlock($blockType, $template) {
		switch($blockType) {
		case ":":{
			$script = $this->parseScript($template);
			return _hx_anonymous(array("block" => htemplate_TBlock::printBlock(trim($script)), "length" => strlen($script) + 1));
		}break;
		case "?":{
			$script2 = $this->parseScript($template);
			return _hx_anonymous(array("block" => htemplate_TBlock::codeBlock(trim($script2)), "length" => strlen($script2) + 1));
		}break;
		case "if":case "for":case "while":{
			$script3 = $this->parseScript($template);
			$block = Type::createEnum(_hx_qtype("htemplate.TBlock"), $blockType . "Block", ($blockType == "else" ? new _hx_array(array()) : new _hx_array(array($this->cleanCondition($script3)))));
			$this->blocksStack->push($block);
			return _hx_anonymous(array("block" => $block, "length" => strlen($script3) + 1));
		}break;
		case "else if":{
			$script4 = $this->parseScript($template);
			$block2 = htemplate_TBlock::elseifBlock($this->cleanCondition($script4));
			return _hx_anonymous(array("block" => $block2, "length" => strlen($script4) + 1));
		}break;
		case "else":{
			$script5 = $this->parseScript($template);
			$block3 = htemplate_TBlock::$elseBlock;
			return _hx_anonymous(array("block" => $block3, "length" => strlen($script5) + 1));
		}break;
		case "set":{
			$variable = $this->parseScript($template);
			$block4 = htemplate_TBlock::captureBlock(trim($variable));
			$this->blocksStack->push($block4);
			return _hx_anonymous(array("block" => $block4, "length" => strlen($variable) + 1));
		}break;
		case "eval":{
			$variable2 = $this->parseScript($template);
			$block5 = htemplate_TBlock::codeBlock(null);
			$this->blocksStack->push($block5);
			$this->codeBuf = new StringBuf();
			return _hx_anonymous(array("block" => null, "length" => strlen($variable2) + 1));
		}break;
		case "end":{
			$block6 = $this->blocksStack->pop();
			if(null === $block6) {
				throw new HException("unbalanced block ends");
			}
			$»t = ($block6);
			switch($»t->index) {
			case 1:
			case 4:
			case 5:
			{
				return _hx_anonymous(array("block" => htemplate_TBlock::$closeBlock, "length" => 1));
			}break;
			case 6:
			$n = $»t->params[0];
			{
				return _hx_anonymous(array("block" => htemplate_TBlock::captureCloseBlock($n), "length" => 1));
			}break;
			case 9:
			{
				$block1 = htemplate_TBlock::codeBlock(trim($this->codeBuf->b));
				$this->codeBuf = null;
				return _hx_anonymous(array("block" => $block1, "length" => 1));
			}break;
			default:{
				throw new HException("invalid block type in stack: " . $block6);
			}break;
			}
		}break;
		default:{
			throw new HException("invalid blockType: " . $blockType);
		}break;
		}
	}
	public function parse($template) {
		$output = new _hx_array(array());
		while(htemplate_Parser::$validBlock->match($template)) {
			$left = htemplate_Parser::$validBlock->matchedLeft();
			if(null !== $left && "" != $left) {
				if(null !== $this->codeBuf) {
					$this->codeBuf->b .= $left;
				}
				else {
					$output->push(htemplate_TBlock::literal($left));
				}
			}
			$block = $this->parseBlock(htemplate_Parser::$validBlock->matched(1), htemplate_Parser::$validBlock->matchedRight());
			if(null !== $block->block) {
				$output->push($block->block);
			}
			$template = _hx_substr(htemplate_Parser::$validBlock->matchedRight(), _hx_len($block), null);
			unset($left,$block);
		}
		if($this->blocksStack->length > 0) {
			throw new HException("some blocks have not been correctly closed");
		}
		if("" != $template) {
			$output->push(htemplate_TBlock::literal($template));
		}
		return $output;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	static $validBlock;
	function __toString() { return 'htemplate.Parser'; }
}
htemplate_Parser::$validBlock = new EReg("\\{([:?]|if|else if|else|for|while|set|eval|end)", "");
