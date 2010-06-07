<?php

class hscript_Parser {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->line = 1;
		$this->opChars = "+*/-=!><&|^%~";
		$this->identChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_";
		$this->opPriority = new _hx_array(array("...", "=", "||", "&&", "==", "!=", ">", "<", ">=", "<=", "|", "&", "^", "<<", ">>", ">>>", "+", "-", "*", "/", "%"));
		$this->unopsPrefix = new _hx_array(array("!", "++", "--", "-", "~"));
		$this->unopsSuffix = new _hx_array(array("++", "--"));
	}}
	public $line;
	public $opChars;
	public $identChars;
	public $opPriority;
	public $unopsPrefix;
	public $unopsSuffix;
	public $char;
	public $ops;
	public $idents;
	public $tokens;
	public function parseString($s) {
		$this->line = 1;
		return $this->parse(new haxe_io_StringInput($s));
	}
	public function parse($s) {
		$this->char = null;
		$this->ops = new _hx_array(array());
		$this->idents = new _hx_array(array());
		$this->tokens = new haxe_FastList();
		{
			$_g1 = 0; $_g = strlen($this->opChars);
			while($_g1 < $_g) {
				$i = $_g1++;
				$this->ops[_hx_char_code_at($this->opChars, $i)] = true;
				unset($i);
			}
		}
		{
			$_g12 = 0; $_g2 = strlen($this->identChars);
			while($_g12 < $_g2) {
				$i2 = $_g12++;
				$this->idents[_hx_char_code_at($this->identChars, $i2)] = true;
				unset($i2);
			}
		}
		$a = new _hx_array(array());
		while(true) {
			$tk = $this->token($s);
			if($tk === hscript_Token::$TEof) {
				break;
			}
			{
				$_g3 = $this->tokens;
				$_g3->head = new haxe_FastCell($tk, $_g3->head);
			}
			$a->push($this->parseFullExpr($s));
			unset($tk,$_g3);
		}
		return ($a->length === 1 ? $a[0] : hscript_Expr::EBlock($a));
	}
	public function unexpected($tk) {
		throw new HException(hscript_Error::EUnexpected($this->tokenString($tk)));
		return null;
	}
	public function isBlock($e) {
		return eval("if(isset(\$this)) \$퍁his =& \$this;\$퍁 = (\$e);
			switch(\$퍁->index) {
			case 4:
			{
				\$팿 = true;
			}break;
			case 14:
			\$e1 = \$퍁->params[1];
			{
				\$팿 = \$퍁his->isBlock(\$e1);
			}break;
			case 2:
			\$e12 = \$퍁->params[1];
			{
				\$팿 = \$e12 !== null && \$퍁his->isBlock(\$e12);
			}break;
			case 9:
			\$e2 = \$퍁->params[2]; \$e13 = \$퍁->params[1];
			{
				\$팿 = (\$e2 !== null ? \$퍁his->isBlock(\$e2) : \$퍁his->isBlock(\$e13));
			}break;
			case 6:
			\$e14 = \$퍁->params[2];
			{
				\$팿 = \$퍁his->isBlock(\$e14);
			}break;
			case 7:
			\$e15 = \$퍁->params[2]; \$prefix = \$퍁->params[1];
			{
				\$팿 = !\$prefix && \$퍁his->isBlock(\$e15);
			}break;
			case 10:
			\$e16 = \$퍁->params[1];
			{
				\$팿 = \$퍁his->isBlock(\$e16);
			}break;
			case 11:
			\$e17 = \$퍁->params[2];
			{
				\$팿 = \$퍁his->isBlock(\$e17);
			}break;
			case 15:
			\$e18 = \$퍁->params[0];
			{
				\$팿 = \$e18 !== null && \$퍁his->isBlock(\$e18);
			}break;
			default:{
				\$팿 = false;
			}break;
			}
			return \$팿;
		");
	}
	public function parseFullExpr($s) {
		$e = $this->parseExpr($s);
		$tk = $this->token($s);
		if($tk !== hscript_Token::$TSemicolon && $tk !== hscript_Token::$TEof) {
			if($this->isBlock($e)) {
				$_g = $this->tokens;
				$_g->head = new haxe_FastCell($tk, $_g->head);
			}
			else {
				$this->unexpected($tk);
			}
		}
		return $e;
	}
	public function parseExpr($s) {
		$tk = $this->token($s);
		$퍁 = ($tk);
		switch($퍁->index) {
		case 2:
		$id = $퍁->params[0];
		{
			$e = $this->parseStructure($s, $id);
			if($e === null) {
				$e = hscript_Expr::EIdent($id);
			}
			return $this->parseExprNext($s, $e);
		}break;
		case 1:
		$c = $퍁->params[0];
		{
			return $this->parseExprNext($s, hscript_Expr::EConst($c));
		}break;
		case 4:
		{
			$e2 = $this->parseExpr($s);
			$tk = $this->token($s);
			if($tk !== hscript_Token::$TPClose) {
				$this->unexpected($tk);
			}
			return $this->parseExprNext($s, hscript_Expr::EParent($e2));
		}break;
		case 6:
		{
			$a = new _hx_array(array());
			while(true) {
				$tk = $this->token($s);
				if($tk === hscript_Token::$TBrClose) {
					break;
				}
				{
					$_g = $this->tokens;
					$_g->head = new haxe_FastCell($tk, $_g->head);
				}
				$a->push($this->parseFullExpr($s));
				unset($_g);
			}
			return hscript_Expr::EBlock($a);
		}break;
		case 3:
		$op = $퍁->params[0];
		{
			$found = null;
			{
				$_g2 = 0; $_g1 = $this->unopsPrefix;
				while($_g2 < $_g1->length) {
					$x = $_g1[$_g2];
					++$_g2;
					if($x == $op) {
						return $this->makeUnop($op, $this->parseExpr($s));
					}
					unset($x);
				}
			}
			return $this->unexpected($tk);
		}break;
		case 11:
		{
			return $this->parseExprNext($s, hscript_Expr::EArrayDecl($this->parseExprList($s, hscript_Token::$TBkClose)));
		}break;
		default:{
			return $this->unexpected($tk);
		}break;
		}
	}
	public function priority($op) {
		{
			$_g1 = 0; $_g = $this->opPriority->length;
			while($_g1 < $_g) {
				$i = $_g1++;
				if($this->opPriority[$i] == $op) {
					return $i;
				}
				unset($i);
			}
		}
		return -1;
	}
	public function makeUnop($op, $e) {
		return eval("if(isset(\$this)) \$퍁his =& \$this;\$퍁 = (\$e);
			switch(\$퍁->index) {
			case 6:
			\$e2 = \$퍁->params[2]; \$e1 = \$퍁->params[1]; \$bop = \$퍁->params[0];
			{
				\$팿 = hscript_Expr::EBinop(\$bop, \$퍁his->makeUnop(\$op, \$e1), \$e2);
			}break;
			default:{
				\$팿 = hscript_Expr::EUnop(\$op, true, \$e);
			}break;
			}
			return \$팿;
		");
	}
	public function makeBinop($op, $e1, $e) {
		return eval("if(isset(\$this)) \$퍁his =& \$this;\$퍁 = (\$e);
			switch(\$퍁->index) {
			case 6:
			\$e3 = \$퍁->params[2]; \$e2 = \$퍁->params[1]; \$op2 = \$퍁->params[0];
			{
				\$팿 = (\$퍁his->priority(\$op) > \$퍁his->priority(\$op2) ? hscript_Expr::EBinop(\$op2, \$퍁his->makeBinop(\$op, \$e1, \$e2), \$e3) : hscript_Expr::EBinop(\$op, \$e1, \$e));
			}break;
			default:{
				\$팿 = hscript_Expr::EBinop(\$op, \$e1, \$e);
			}break;
			}
			return \$팿;
		");
	}
	public function parseStructure($s, $id) {
		return eval("if(isset(\$this)) \$퍁his =& \$this;switch(\$id) {
			case \"if\":{
				\$팿 = eval(\"if(isset(\\\$this)) \\\$퍁his =& \\\$this;\\\$cond = \\\$퍁his->parseExpr(\\\$s);
					\\\$e1 = \\\$퍁his->parseExpr(\\\$s);
					\\\$e2 = null;
					\\\$semic = false;
					\\\$tk = \\\$퍁his->token(\\\$s);
					if(\\\$tk === hscript_Token::\\\$TSemicolon) {
						\\\$semic = true;
						\\\$tk = \\\$퍁his->token(\\\$s);
					}
					if(Type::enumEq(\\\$tk, hscript_Token::TId(\\\"else\\\"))) {
						\\\$e2 = \\\$퍁his->parseExpr(\\\$s);
					}
					else {
						{
							\\\$_g = \\\$퍁his->tokens;
							\\\$_g->head = new haxe_FastCell(\\\$tk, \\\$_g->head);
						}
						if(\\\$semic) {
							\\\$_g2 = \\\$퍁his->tokens;
							\\\$_g2->head = new haxe_FastCell(hscript_Token::\\\$TSemicolon, \\\$_g2->head);
						}
					}
					\\\$팿2 = hscript_Expr::EIf(\\\$cond, \\\$e1, \\\$e2);
					return \\\$팿2;
				\");
			}break;
			case \"var\":{
				\$팿 = eval(\"if(isset(\\\$this)) \\\$퍁his =& \\\$this;\\\$tk2 = \\\$퍁his->token(\\\$s);
					\\\$ident = null;
					\\\$퍁 = (\\\$tk2);
					switch(\\\$퍁->index) {
					case 2:
					\\\$id1 = \\\$퍁->params[0];
					{
						\\\$ident = \\\$id1;
					}break;
					default:{
						\\\$퍁his->unexpected(\\\$tk2);
					}break;
					}
					\\\$tk2 = \\\$퍁his->token(\\\$s);
					\\\$e = null;
					if(Type::enumEq(\\\$tk2, hscript_Token::TOp(\\\"=\\\"))) {
						\\\$e = \\\$퍁his->parseExpr(\\\$s);
					}
					else {
						\\\$_g3 = \\\$퍁his->tokens;
						\\\$_g3->head = new haxe_FastCell(\\\$tk2, \\\$_g3->head);
					}
					\\\$팿3 = hscript_Expr::EVar(\\\$ident, \\\$e);
					return \\\$팿3;
				\");
			}break;
			case \"while\":{
				\$팿 = eval(\"if(isset(\\\$this)) \\\$퍁his =& \\\$this;\\\$econd = \\\$퍁his->parseExpr(\\\$s);
					\\\$e3 = \\\$퍁his->parseExpr(\\\$s);
					\\\$팿4 = hscript_Expr::EWhile(\\\$econd, \\\$e3);
					return \\\$팿4;
				\");
			}break;
			case \"for\":{
				\$팿 = eval(\"if(isset(\\\$this)) \\\$퍁his =& \\\$this;\\\$tk3 = \\\$퍁his->token(\\\$s);
					if(\\\$tk3 !== hscript_Token::\\\$TPOpen) {
						\\\$퍁his->unexpected(\\\$tk3);
					}
					\\\$tk3 = \\\$퍁his->token(\\\$s);
					\\\$vname = null;
					\\\$퍁2 = (\\\$tk3);
					switch(\\\$퍁2->index) {
					case 2:
					\\\$id12 = \\\$퍁2->params[0];
					{
						\\\$vname = \\\$id12;
					}break;
					default:{
						\\\$퍁his->unexpected(\\\$tk3);
					}break;
					}
					\\\$tk3 = \\\$퍁his->token(\\\$s);
					if(!Type::enumEq(\\\$tk3, hscript_Token::TId(\\\"in\\\"))) {
						\\\$퍁his->unexpected(\\\$tk3);
					}
					\\\$eiter = \\\$퍁his->parseExpr(\\\$s);
					\\\$tk3 = \\\$퍁his->token(\\\$s);
					if(\\\$tk3 !== hscript_Token::\\\$TPClose) {
						\\\$퍁his->unexpected(\\\$tk3);
					}
					\\\$팿5 = hscript_Expr::EFor(\\\$vname, \\\$eiter, \\\$퍁his->parseExpr(\\\$s));
					return \\\$팿5;
				\");
			}break;
			case \"break\":{
				\$팿 = hscript_Expr::\$EBreak;
			}break;
			case \"continue\":{
				\$팿 = hscript_Expr::\$EContinue;
			}break;
			case \"else\":{
				\$팿 = \$퍁his->unexpected(hscript_Token::TId(\$id));
			}break;
			case \"function\":{
				\$팿 = eval(\"if(isset(\\\$this)) \\\$퍁his =& \\\$this;\\\$tk4 = \\\$퍁his->token(\\\$s);
					\\\$name = null;
					\\\$퍁3 = (\\\$tk4);
					switch(\\\$퍁3->index) {
					case 2:
					\\\$id13 = \\\$퍁3->params[0];
					{
						\\\$name = \\\$id13;
						\\\$tk4 = \\\$퍁his->token(\\\$s);
					}break;
					default:{
						;
					}break;
					}
					if(\\\$tk4 !== hscript_Token::\\\$TPOpen) {
						\\\$퍁his->unexpected(\\\$tk4);
					}
					\\\$args = new _hx_array(array());
					\\\$tk4 = \\\$퍁his->token(\\\$s);
					if(\\\$tk4 !== hscript_Token::\\\$TPClose) {
						try {
							while(true) {
								\\\$퍁4 = (\\\$tk4);
								switch(\\\$퍁4->index) {
								case 2:
								\\\$id14 = \\\$퍁4->params[0];
								{
									\\\$args->push(\\\$id14);
								}break;
								default:{
									\\\$퍁his->unexpected(\\\$tk4);
								}break;
								}
								\\\$tk4 = \\\$퍁his->token(\\\$s);
								\\\$퍁5 = (\\\$tk4);
								switch(\\\$퍁5->index) {
								case 9:
								{
									;
								}break;
								case 5:
								{
									throw new _hx_break_exception();
								}break;
								default:{
									\\\$퍁his->unexpected(\\\$tk4);
								}break;
								}
								\\\$tk4 = \\\$퍁his->token(\\\$s);
								unset(\\\$퍁5,\\\$퍁4,\\\$id14);
							}
						} catch(_hx_break_exception \\\$팫){}
					}
					\\\$팿6 = hscript_Expr::EFunction(\\\$args, \\\$퍁his->parseExpr(\\\$s), \\\$name);
					return \\\$팿6;
				\");
			}break;
			case \"return\":{
				\$팿 = eval(\"if(isset(\\\$this)) \\\$퍁his =& \\\$this;\\\$tk5 = \\\$퍁his->token(\\\$s);
					{
						\\\$_g4 = \\\$퍁his->tokens;
						\\\$_g4->head = new haxe_FastCell(\\\$tk5, \\\$_g4->head);
					}
					\\\$팿7 = hscript_Expr::EReturn((\\\$tk5 === hscript_Token::\\\$TSemicolon ? null : \\\$퍁his->parseExpr(\\\$s)));
					return \\\$팿7;
				\");
			}break;
			case \"new\":{
				\$팿 = eval(\"if(isset(\\\$this)) \\\$퍁his =& \\\$this;\\\$a = new _hx_array(array());
					\\\$tk6 = \\\$퍁his->token(\\\$s);
					\\\$퍁6 = (\\\$tk6);
					switch(\\\$퍁6->index) {
					case 2:
					\\\$id15 = \\\$퍁6->params[0];
					{
						\\\$a->push(\\\$id15);
					}break;
					default:{
						\\\$퍁his->unexpected(\\\$tk6);
					}break;
					}
					try {
						while(true) {
							\\\$tk6 = \\\$퍁his->token(\\\$s);
							\\\$퍁7 = (\\\$tk6);
							switch(\\\$퍁7->index) {
							case 8:
							{
								\\\$tk6 = \\\$퍁his->token(\\\$s);
								\\\$퍁8 = (\\\$tk6);
								switch(\\\$퍁8->index) {
								case 2:
								\\\$id16 = \\\$퍁8->params[0];
								{
									\\\$a->push(\\\$id16);
								}break;
								default:{
									\\\$퍁his->unexpected(\\\$tk6);
								}break;
								}
							}break;
							case 4:
							{
								throw new _hx_break_exception();
							}break;
							default:{
								\\\$퍁his->unexpected(\\\$tk6);
							}break;
							}
							unset(\\\$퍁8,\\\$퍁7,\\\$id16);
						}
					} catch(_hx_break_exception \\\$팫){}
					\\\$팿8 = hscript_Expr::ENew(\\\$a->join(\\\".\\\"), \\\$퍁his->parseExprList(\\\$s, hscript_Token::\\\$TPClose));
					return \\\$팿8;
				\");
			}break;
			case \"throw\":{
				\$팿 = hscript_Expr::EThrow(\$퍁his->parseExpr(\$s));
			}break;
			case \"try\":{
				\$팿 = eval(\"if(isset(\\\$this)) \\\$퍁his =& \\\$this;\\\$e4 = \\\$퍁his->parseExpr(\\\$s);
					\\\$tk7 = \\\$퍁his->token(\\\$s);
					if(!Type::enumEq(\\\$tk7, hscript_Token::TId(\\\"catch\\\"))) {
						\\\$퍁his->unexpected(\\\$tk7);
					}
					\\\$tk7 = \\\$퍁his->token(\\\$s);
					if(\\\$tk7 !== hscript_Token::\\\$TPOpen) {
						\\\$퍁his->unexpected(\\\$tk7);
					}
					\\\$tk7 = \\\$퍁his->token(\\\$s);
					\\\$vname2 = eval(\\\"if(isset(\\\\\$this)) \\\\\$퍁his =& \\\\\$this;\\\\\$퍁9 = (\\\\\$tk7);
						switch(\\\\\$퍁9->index) {
						case 2:
						\\\\\$id17 = \\\\\$퍁9->params[0];
						{
							\\\\\$팿10 = \\\\\$id17;
						}break;
						default:{
							\\\\\$팿10 = \\\\\$퍁his->unexpected(\\\\\$tk7);
						}break;
						}
						return \\\\\$팿10;
					\\\");
					\\\$tk7 = \\\$퍁his->token(\\\$s);
					if(\\\$tk7 !== hscript_Token::\\\$TDoubleDot) {
						\\\$퍁his->unexpected(\\\$tk7);
					}
					\\\$tk7 = \\\$퍁his->token(\\\$s);
					if(!Type::enumEq(\\\$tk7, hscript_Token::TId(\\\"Dynamic\\\"))) {
						\\\$퍁his->unexpected(\\\$tk7);
					}
					\\\$tk7 = \\\$퍁his->token(\\\$s);
					if(\\\$tk7 !== hscript_Token::\\\$TPClose) {
						\\\$퍁his->unexpected(\\\$tk7);
					}
					\\\$팿9 = hscript_Expr::ETry(\\\$e4, \\\$vname2, \\\$퍁his->parseExpr(\\\$s));
					return \\\$팿9;
				\");
			}break;
			default:{
				\$팿 = null;
			}break;
			}
			return \$팿;
		");
	}
	public function parseExprNext($s, $e1) {
		$tk = $this->token($s);
		$퍁 = ($tk);
		switch($퍁->index) {
		case 3:
		$op = $퍁->params[0];
		{
			{
				$_g = 0; $_g1 = $this->unopsSuffix;
				while($_g < $_g1->length) {
					$x = $_g1[$_g];
					++$_g;
					if($x == $op) {
						if($this->isBlock($e1) || eval("if(isset(\$this)) \$퍁his =& \$this;\$퍁2 = (\$e1);
							switch(\$퍁2->index) {
							case 3:
							{
								\$팿 = true;
							}break;
							default:{
								\$팿 = false;
							}break;
							}
							return \$팿;
						")) {
							{
								$_g2 = $this->tokens;
								$_g2->head = new haxe_FastCell($tk, $_g2->head);
							}
							return $e1;
						}
						return $this->parseExprNext($s, hscript_Expr::EUnop($op, false, $e1));
					}
					unset($퍁2,$팿,$x,$_g2);
				}
			}
			return $this->makeBinop($op, $e1, $this->parseExpr($s));
		}break;
		case 8:
		{
			$tk = $this->token($s);
			$field = null;
			$퍁3 = ($tk);
			switch($퍁3->index) {
			case 2:
			$id = $퍁3->params[0];
			{
				$field = $id;
			}break;
			default:{
				$this->unexpected($tk);
			}break;
			}
			return $this->parseExprNext($s, hscript_Expr::EField($e1, $field));
		}break;
		case 4:
		{
			return $this->parseExprNext($s, hscript_Expr::ECall($e1, $this->parseExprList($s, hscript_Token::$TPClose)));
		}break;
		case 11:
		{
			$e2 = $this->parseExpr($s);
			$tk = $this->token($s);
			if($tk !== hscript_Token::$TBkClose) {
				$this->unexpected($tk);
			}
			return $this->parseExprNext($s, hscript_Expr::EArray($e1, $e2));
		}break;
		case 13:
		{
			$e22 = $this->parseExpr($s);
			$tk = $this->token($s);
			if($tk !== hscript_Token::$TDoubleDot) {
				$this->unexpected($tk);
			}
			$e3 = $this->parseExpr($s);
			return hscript_Expr::EIf($e1, $e22, $e3);
		}break;
		default:{
			{
				$_g3 = $this->tokens;
				$_g3->head = new haxe_FastCell($tk, $_g3->head);
			}
			return $e1;
		}break;
		}
	}
	public function parseExprList($s, $etk) {
		$args = new _hx_array(array());
		$tk = $this->token($s);
		if($tk === $etk) {
			return $args;
		}
		{
			$_g = $this->tokens;
			$_g->head = new haxe_FastCell($tk, $_g->head);
		}
		try {
			while(true) {
				$args->push($this->parseExpr($s));
				$tk = $this->token($s);
				$퍁 = ($tk);
				switch($퍁->index) {
				case 9:
				{
					;
				}break;
				default:{
					if($tk === $etk) {
						throw new _hx_break_exception();
					}
					$this->unexpected($tk);
				}break;
				}
				unset($퍁);
			}
		} catch(_hx_break_exception $팫){}
		return $args;
	}
	public function readChar($s) {
		return eval("if(isset(\$this)) \$퍁his =& \$this;try {
				\$팿 = \$s->readByte();
			}catch(Exception \$팫) {
			\$_ex_ = (\$팫 instanceof HException) ? \$팫->e : \$팫;
			;
			{ \$e = \$_ex_;
			{
				\$팿 = 0;
			}}}
			return \$팿;
		");
	}
	public function readString($s, $until) {
		$c = null;
		$b = new StringBuf();
		$esc = false;
		$old = $this->line;
		while(true) {
			try {
				$c = $s->readByte();
			}catch(Exception $팫) {
			$_ex_ = ($팫 instanceof HException) ? $팫->e : $팫;
			;
			{ $e = $_ex_;
			{
				$this->line = $old;
				throw new HException(hscript_Error::$EUnterminatedString);
			}}}
			if($esc) {
				$esc = false;
				switch($c) {
				case 110:{
					$b->b .= chr(10);
				}break;
				case 114:{
					$b->b .= chr(13);
				}break;
				case 116:{
					$b->b .= chr(9);
				}break;
				case 39:{
					$b->b .= chr(39);
				}break;
				case 34:{
					$b->b .= chr(34);
				}break;
				case 92:{
					$b->b .= chr(92);
				}break;
				default:{
					throw new HException(hscript_Error::EInvalidChar($c));
				}break;
				}
			}
			else {
				if($c === 92) {
					$esc = true;
				}
				else {
					if($c === $until) {
						break;
					}
					else {
						if($c === 10) {
							$this->line++;
						}
						$b->b .= chr($c);
					}
				}
			}
			unset($팫,$e,$_ex_);
		}
		return $b->b;
	}
	public function token($s) {
		if(!($this->tokens->head === null)) {
			return eval("if(isset(\$this)) \$퍁his =& \$this;\$_g = \$퍁his->tokens;
				\$k = \$_g->head;
				\$팿 = (\$k === null ? null : eval(\"if(isset(\\\$this)) \\\$퍁his =& \\\$this;\\\$_g->head = \\\$k->next;
					\\\$팿2 = \\\$k->elt;
					return \\\$팿2;
				\"));
				return \$팿;
			");
		}
		$char = null;
		if($this->char === null) {
			$char = $this->readChar($s);
		}
		else {
			$char = $this->char;
			$this->char = null;
		}
		while(true) {
			switch($char) {
			case 0:{
				return hscript_Token::$TEof;
			}break;
			case 32:case 9:case 13:{
				;
			}break;
			case 10:{
				$this->line++;
			}break;
			case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:{
				$n = $char - 48;
				$exp = 0;
				while(true) {
					$char = $this->readChar($s);
					$exp *= 10;
					switch($char) {
					case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:{
						$n = $n * 10 + ($char - 48);
					}break;
					case 46:{
						if($exp > 0) {
							if($exp === 10 && $this->readChar($s) === 46) {
								{
									$_g2 = $this->tokens;
									$_g2->head = new haxe_FastCell(hscript_Token::TOp("..."), $_g2->head);
								}
								return hscript_Token::TConst(hscript_Const::CInt($n));
							}
							throw new HException(hscript_Error::EInvalidChar($char));
						}
						$exp = 1;
					}break;
					case 120:{
						if($n > 0 || $exp > 0) {
							throw new HException(hscript_Error::EInvalidChar($char));
						}
						$n1 = 0;
						while(true) {
							$char = $this->readChar($s);
							switch($char) {
							case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:{
								$n1 = (($n1) << 4) + ($char - 48);
							}break;
							case 65:case 66:case 67:case 68:case 69:case 70:{
								$n1 = (($n1) << 4) + ($char - 55);
							}break;
							case 97:case 98:case 99:case 100:case 101:case 102:{
								$n1 = (($n1) << 4) + ($char - 87);
							}break;
							default:{
								$this->char = $char;
								$v = eval("if(isset(\$this)) \$퍁his =& \$this;try {
										\$팿3 = hscript_Const::CInt(eval(\"if(isset(\\\$this)) \\\$퍁his =& \\\$this;if((((\\\$n1) >> 30) & 1) !== (_hx_shift_right((\\\$n1), 31))) {
												throw new HException(\\\"Overflow \\\" . \\\$n1);
											}
											\\\$팿4 = ((\\\$n1) & -1);
											return \\\$팿4;
										\"));
									}catch(Exception \$팫) {
									\$_ex_ = (\$팫 instanceof HException) ? \$팫->e : \$팫;
									;
									{ \$e = \$_ex_;
									{
										\$팿3 = hscript_Const::CInt32(\$n1);
									}}}
									return \$팿3;
								");
								return hscript_Token::TConst($v);
							}break;
							}
							unset($팿4,$팿3,$팫,$v,$e,$_ex_);
						}
					}break;
					default:{
						$this->char = $char;
						return hscript_Token::TConst((($exp > 0) ? hscript_Const::CFloat($n * 10 / $exp) : hscript_Const::CInt($n)));
					}break;
					}
					unset($팿4,$팿3,$팫,$v,$n1,$e,$_g2,$_ex_);
				}
			}break;
			case 59:{
				return hscript_Token::$TSemicolon;
			}break;
			case 40:{
				return hscript_Token::$TPOpen;
			}break;
			case 41:{
				return hscript_Token::$TPClose;
			}break;
			case 44:{
				return hscript_Token::$TComma;
			}break;
			case 46:{
				$char = $this->readChar($s);
				switch($char) {
				case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:{
					$n2 = $char - 48;
					$exp2 = 1;
					while(true) {
						$char = $this->readChar($s);
						$exp2 *= 10;
						switch($char) {
						case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:{
							$n2 = $n2 * 10 + ($char - 48);
						}break;
						default:{
							$this->char = $char;
							return hscript_Token::TConst(hscript_Const::CFloat($n2 / $exp2));
						}break;
						}
						;
					}
				}break;
				case 46:{
					$char = $this->readChar($s);
					if($char !== 46) {
						throw new HException(hscript_Error::EInvalidChar($char));
					}
					return hscript_Token::TOp("...");
				}break;
				default:{
					$this->char = $char;
					return hscript_Token::$TDot;
				}break;
				}
			}break;
			case 123:{
				return hscript_Token::$TBrOpen;
			}break;
			case 125:{
				return hscript_Token::$TBrClose;
			}break;
			case 91:{
				return hscript_Token::$TBkOpen;
			}break;
			case 93:{
				return hscript_Token::$TBkClose;
			}break;
			case 39:{
				return hscript_Token::TConst(hscript_Const::CString($this->readString($s, 39)));
			}break;
			case 34:{
				return hscript_Token::TConst(hscript_Const::CString($this->readString($s, 34)));
			}break;
			case 63:{
				return hscript_Token::$TQuestion;
			}break;
			case 58:{
				return hscript_Token::$TDoubleDot;
			}break;
			default:{
				if($this->ops[$char]) {
					$op = chr($char);
					while(true) {
						$char = $this->readChar($s);
						if(!$this->ops->팤[$char]) {
							if(_hx_char_code_at($op, 0) === 47) {
								return $this->tokenComment($s, $op, $char);
							}
							$this->char = $char;
							return hscript_Token::TOp($op);
						}
						$op .= chr($char);
						;
					}
				}
				if($this->idents[$char]) {
					$id = chr($char);
					while(true) {
						$char = $this->readChar($s);
						if(!$this->idents->팤[$char]) {
							$this->char = $char;
							return hscript_Token::TId($id);
						}
						$id .= chr($char);
						;
					}
				}
				throw new HException(hscript_Error::EInvalidChar($char));
			}break;
			}
			$char = $this->readChar($s);
			unset($팿4,$팿3,$팫,$v,$op,$n2,$n1,$n,$id,$exp2,$exp,$e,$_g2,$_ex_);
		}
		return null;
	}
	public function tokenComment($s, $op, $char) {
		$c = _hx_char_code_at($op, 1);
		if($c === 47) {
			try {
				while($char !== 10 && $char !== 13) $char = $s->readByte();
				$this->char = $char;
			}catch(Exception $팫) {
			$_ex_ = ($팫 instanceof HException) ? $팫->e : $팫;
			;
			{ $e = $_ex_;
			{
				;
			}}}
			return $this->token($s);
		}
		if($c === 42) {
			$old = $this->line;
			try {
				while(true) {
					while($char !== 42) {
						if($char === 10) {
							$this->line++;
						}
						$char = $s->readByte();
						;
					}
					$char = $s->readByte();
					if($char === 47) {
						break;
					}
					;
				}
			}catch(Exception $팫2) {
			$_ex_2 = ($팫2 instanceof HException) ? $팫2->e : $팫2;
			;
			{ $e2 = $_ex_2;
			{
				$this->line = $old;
				throw new HException(hscript_Error::$EUnterminatedComment);
			}}}
			return $this->token($s);
		}
		$this->char = $char;
		return hscript_Token::TOp($op);
	}
	public function constString($c) {
		return eval("if(isset(\$this)) \$퍁his =& \$this;\$퍁 = (\$c);
			switch(\$퍁->index) {
			case 0:
			\$v = \$퍁->params[0];
			{
				\$팿 = Std::string(\$v);
			}break;
			case 3:
			\$v2 = \$퍁->params[0];
			{
				\$팿 = Std::string(\$v2);
			}break;
			case 1:
			\$f = \$퍁->params[0];
			{
				\$팿 = Std::string(\$f);
			}break;
			case 2:
			\$s = \$퍁->params[0];
			{
				\$팿 = \$s;
			}break;
			default:{
				\$팿 = null;
			}break;
			}
			return \$팿;
		");
	}
	public function tokenString($t) {
		return eval("if(isset(\$this)) \$퍁his =& \$this;\$퍁 = (\$t);
			switch(\$퍁->index) {
			case 0:
			{
				\$팿 = \"<eof>\";
			}break;
			case 1:
			\$c = \$퍁->params[0];
			{
				\$팿 = \$퍁his->constString(\$c);
			}break;
			case 2:
			\$s = \$퍁->params[0];
			{
				\$팿 = \$s;
			}break;
			case 3:
			\$s2 = \$퍁->params[0];
			{
				\$팿 = \$s2;
			}break;
			case 4:
			{
				\$팿 = \"(\";
			}break;
			case 5:
			{
				\$팿 = \")\";
			}break;
			case 6:
			{
				\$팿 = \"{\";
			}break;
			case 7:
			{
				\$팿 = \"}\";
			}break;
			case 8:
			{
				\$팿 = \".\";
			}break;
			case 9:
			{
				\$팿 = \",\";
			}break;
			case 10:
			{
				\$팿 = \";\";
			}break;
			case 11:
			{
				\$팿 = \"[\";
			}break;
			case 12:
			{
				\$팿 = \"]\";
			}break;
			case 13:
			{
				\$팿 = \"?\";
			}break;
			case 14:
			{
				\$팿 = \":\";
			}break;
			default:{
				\$팿 = null;
			}break;
			}
			return \$팿;
		");
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->팪ynamics[$m]) && is_callable($this->팪ynamics[$m]))
			return call_user_func_array($this->팪ynamics[$m], $a);
		else
			throw new HException('Unable to call �'.$m.'�');
	}
	function __toString() { return 'hscript.Parser'; }
}
