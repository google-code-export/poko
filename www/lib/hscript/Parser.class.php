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
		return eval("if(isset(\$this)) \$�this =& \$this;\$�t = (\$e);
			switch(\$�t->index) {
			case 4:
			{
				\$�r = true;
			}break;
			case 14:
			\$e1 = \$�t->params[1];
			{
				\$�r = \$�this->isBlock(\$e1);
			}break;
			case 2:
			\$e12 = \$�t->params[1];
			{
				\$�r = \$e12 !== null && \$�this->isBlock(\$e12);
			}break;
			case 9:
			\$e2 = \$�t->params[2]; \$e13 = \$�t->params[1];
			{
				\$�r = (\$e2 !== null ? \$�this->isBlock(\$e2) : \$�this->isBlock(\$e13));
			}break;
			case 6:
			\$e14 = \$�t->params[2];
			{
				\$�r = \$�this->isBlock(\$e14);
			}break;
			case 7:
			\$e15 = \$�t->params[2]; \$prefix = \$�t->params[1];
			{
				\$�r = !\$prefix && \$�this->isBlock(\$e15);
			}break;
			case 10:
			\$e16 = \$�t->params[1];
			{
				\$�r = \$�this->isBlock(\$e16);
			}break;
			case 11:
			\$e17 = \$�t->params[2];
			{
				\$�r = \$�this->isBlock(\$e17);
			}break;
			case 15:
			\$e18 = \$�t->params[0];
			{
				\$�r = \$e18 !== null && \$�this->isBlock(\$e18);
			}break;
			default:{
				\$�r = false;
			}break;
			}
			return \$�r;
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
		$�t = ($tk);
		switch($�t->index) {
		case 2:
		$id = $�t->params[0];
		{
			$e = $this->parseStructure($s, $id);
			if($e === null) {
				$e = hscript_Expr::EIdent($id);
			}
			return $this->parseExprNext($s, $e);
		}break;
		case 1:
		$c = $�t->params[0];
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
		$op = $�t->params[0];
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
		return eval("if(isset(\$this)) \$�this =& \$this;\$�t = (\$e);
			switch(\$�t->index) {
			case 6:
			\$e2 = \$�t->params[2]; \$e1 = \$�t->params[1]; \$bop = \$�t->params[0];
			{
				\$�r = hscript_Expr::EBinop(\$bop, \$�this->makeUnop(\$op, \$e1), \$e2);
			}break;
			default:{
				\$�r = hscript_Expr::EUnop(\$op, true, \$e);
			}break;
			}
			return \$�r;
		");
	}
	public function makeBinop($op, $e1, $e) {
		return eval("if(isset(\$this)) \$�this =& \$this;\$�t = (\$e);
			switch(\$�t->index) {
			case 6:
			\$e3 = \$�t->params[2]; \$e2 = \$�t->params[1]; \$op2 = \$�t->params[0];
			{
				\$�r = (\$�this->priority(\$op) > \$�this->priority(\$op2) ? hscript_Expr::EBinop(\$op2, \$�this->makeBinop(\$op, \$e1, \$e2), \$e3) : hscript_Expr::EBinop(\$op, \$e1, \$e));
			}break;
			default:{
				\$�r = hscript_Expr::EBinop(\$op, \$e1, \$e);
			}break;
			}
			return \$�r;
		");
	}
	public function parseStructure($s, $id) {
		return eval("if(isset(\$this)) \$�this =& \$this;switch(\$id) {
			case \"if\":{
				\$�r = eval(\"if(isset(\\\$this)) \\\$�this =& \\\$this;\\\$cond = \\\$�this->parseExpr(\\\$s);
					\\\$e1 = \\\$�this->parseExpr(\\\$s);
					\\\$e2 = null;
					\\\$semic = false;
					\\\$tk = \\\$�this->token(\\\$s);
					if(\\\$tk === hscript_Token::\\\$TSemicolon) {
						\\\$semic = true;
						\\\$tk = \\\$�this->token(\\\$s);
					}
					if(Type::enumEq(\\\$tk, hscript_Token::TId(\\\"else\\\"))) {
						\\\$e2 = \\\$�this->parseExpr(\\\$s);
					}
					else {
						{
							\\\$_g = \\\$�this->tokens;
							\\\$_g->head = new haxe_FastCell(\\\$tk, \\\$_g->head);
						}
						if(\\\$semic) {
							\\\$_g2 = \\\$�this->tokens;
							\\\$_g2->head = new haxe_FastCell(hscript_Token::\\\$TSemicolon, \\\$_g2->head);
						}
					}
					\\\$�r2 = hscript_Expr::EIf(\\\$cond, \\\$e1, \\\$e2);
					return \\\$�r2;
				\");
			}break;
			case \"var\":{
				\$�r = eval(\"if(isset(\\\$this)) \\\$�this =& \\\$this;\\\$tk2 = \\\$�this->token(\\\$s);
					\\\$ident = null;
					\\\$�t = (\\\$tk2);
					switch(\\\$�t->index) {
					case 2:
					\\\$id1 = \\\$�t->params[0];
					{
						\\\$ident = \\\$id1;
					}break;
					default:{
						\\\$�this->unexpected(\\\$tk2);
					}break;
					}
					\\\$tk2 = \\\$�this->token(\\\$s);
					\\\$e = null;
					if(Type::enumEq(\\\$tk2, hscript_Token::TOp(\\\"=\\\"))) {
						\\\$e = \\\$�this->parseExpr(\\\$s);
					}
					else {
						\\\$_g3 = \\\$�this->tokens;
						\\\$_g3->head = new haxe_FastCell(\\\$tk2, \\\$_g3->head);
					}
					\\\$�r3 = hscript_Expr::EVar(\\\$ident, \\\$e);
					return \\\$�r3;
				\");
			}break;
			case \"while\":{
				\$�r = eval(\"if(isset(\\\$this)) \\\$�this =& \\\$this;\\\$econd = \\\$�this->parseExpr(\\\$s);
					\\\$e3 = \\\$�this->parseExpr(\\\$s);
					\\\$�r4 = hscript_Expr::EWhile(\\\$econd, \\\$e3);
					return \\\$�r4;
				\");
			}break;
			case \"for\":{
				\$�r = eval(\"if(isset(\\\$this)) \\\$�this =& \\\$this;\\\$tk3 = \\\$�this->token(\\\$s);
					if(\\\$tk3 !== hscript_Token::\\\$TPOpen) {
						\\\$�this->unexpected(\\\$tk3);
					}
					\\\$tk3 = \\\$�this->token(\\\$s);
					\\\$vname = null;
					\\\$�t2 = (\\\$tk3);
					switch(\\\$�t2->index) {
					case 2:
					\\\$id12 = \\\$�t2->params[0];
					{
						\\\$vname = \\\$id12;
					}break;
					default:{
						\\\$�this->unexpected(\\\$tk3);
					}break;
					}
					\\\$tk3 = \\\$�this->token(\\\$s);
					if(!Type::enumEq(\\\$tk3, hscript_Token::TId(\\\"in\\\"))) {
						\\\$�this->unexpected(\\\$tk3);
					}
					\\\$eiter = \\\$�this->parseExpr(\\\$s);
					\\\$tk3 = \\\$�this->token(\\\$s);
					if(\\\$tk3 !== hscript_Token::\\\$TPClose) {
						\\\$�this->unexpected(\\\$tk3);
					}
					\\\$�r5 = hscript_Expr::EFor(\\\$vname, \\\$eiter, \\\$�this->parseExpr(\\\$s));
					return \\\$�r5;
				\");
			}break;
			case \"break\":{
				\$�r = hscript_Expr::\$EBreak;
			}break;
			case \"continue\":{
				\$�r = hscript_Expr::\$EContinue;
			}break;
			case \"else\":{
				\$�r = \$�this->unexpected(hscript_Token::TId(\$id));
			}break;
			case \"function\":{
				\$�r = eval(\"if(isset(\\\$this)) \\\$�this =& \\\$this;\\\$tk4 = \\\$�this->token(\\\$s);
					\\\$name = null;
					\\\$�t3 = (\\\$tk4);
					switch(\\\$�t3->index) {
					case 2:
					\\\$id13 = \\\$�t3->params[0];
					{
						\\\$name = \\\$id13;
						\\\$tk4 = \\\$�this->token(\\\$s);
					}break;
					default:{
						;
					}break;
					}
					if(\\\$tk4 !== hscript_Token::\\\$TPOpen) {
						\\\$�this->unexpected(\\\$tk4);
					}
					\\\$args = new _hx_array(array());
					\\\$tk4 = \\\$�this->token(\\\$s);
					if(\\\$tk4 !== hscript_Token::\\\$TPClose) {
						try {
							while(true) {
								\\\$�t4 = (\\\$tk4);
								switch(\\\$�t4->index) {
								case 2:
								\\\$id14 = \\\$�t4->params[0];
								{
									\\\$args->push(\\\$id14);
								}break;
								default:{
									\\\$�this->unexpected(\\\$tk4);
								}break;
								}
								\\\$tk4 = \\\$�this->token(\\\$s);
								\\\$�t5 = (\\\$tk4);
								switch(\\\$�t5->index) {
								case 9:
								{
									;
								}break;
								case 5:
								{
									throw new _hx_break_exception();
								}break;
								default:{
									\\\$�this->unexpected(\\\$tk4);
								}break;
								}
								\\\$tk4 = \\\$�this->token(\\\$s);
								unset(\\\$�t5,\\\$�t4,\\\$id14);
							}
						} catch(_hx_break_exception \\\$�e){}
					}
					\\\$�r6 = hscript_Expr::EFunction(\\\$args, \\\$�this->parseExpr(\\\$s), \\\$name);
					return \\\$�r6;
				\");
			}break;
			case \"return\":{
				\$�r = eval(\"if(isset(\\\$this)) \\\$�this =& \\\$this;\\\$tk5 = \\\$�this->token(\\\$s);
					{
						\\\$_g4 = \\\$�this->tokens;
						\\\$_g4->head = new haxe_FastCell(\\\$tk5, \\\$_g4->head);
					}
					\\\$�r7 = hscript_Expr::EReturn((\\\$tk5 === hscript_Token::\\\$TSemicolon ? null : \\\$�this->parseExpr(\\\$s)));
					return \\\$�r7;
				\");
			}break;
			case \"new\":{
				\$�r = eval(\"if(isset(\\\$this)) \\\$�this =& \\\$this;\\\$a = new _hx_array(array());
					\\\$tk6 = \\\$�this->token(\\\$s);
					\\\$�t6 = (\\\$tk6);
					switch(\\\$�t6->index) {
					case 2:
					\\\$id15 = \\\$�t6->params[0];
					{
						\\\$a->push(\\\$id15);
					}break;
					default:{
						\\\$�this->unexpected(\\\$tk6);
					}break;
					}
					try {
						while(true) {
							\\\$tk6 = \\\$�this->token(\\\$s);
							\\\$�t7 = (\\\$tk6);
							switch(\\\$�t7->index) {
							case 8:
							{
								\\\$tk6 = \\\$�this->token(\\\$s);
								\\\$�t8 = (\\\$tk6);
								switch(\\\$�t8->index) {
								case 2:
								\\\$id16 = \\\$�t8->params[0];
								{
									\\\$a->push(\\\$id16);
								}break;
								default:{
									\\\$�this->unexpected(\\\$tk6);
								}break;
								}
							}break;
							case 4:
							{
								throw new _hx_break_exception();
							}break;
							default:{
								\\\$�this->unexpected(\\\$tk6);
							}break;
							}
							unset(\\\$�t8,\\\$�t7,\\\$id16);
						}
					} catch(_hx_break_exception \\\$�e){}
					\\\$�r8 = hscript_Expr::ENew(\\\$a->join(\\\".\\\"), \\\$�this->parseExprList(\\\$s, hscript_Token::\\\$TPClose));
					return \\\$�r8;
				\");
			}break;
			case \"throw\":{
				\$�r = hscript_Expr::EThrow(\$�this->parseExpr(\$s));
			}break;
			case \"try\":{
				\$�r = eval(\"if(isset(\\\$this)) \\\$�this =& \\\$this;\\\$e4 = \\\$�this->parseExpr(\\\$s);
					\\\$tk7 = \\\$�this->token(\\\$s);
					if(!Type::enumEq(\\\$tk7, hscript_Token::TId(\\\"catch\\\"))) {
						\\\$�this->unexpected(\\\$tk7);
					}
					\\\$tk7 = \\\$�this->token(\\\$s);
					if(\\\$tk7 !== hscript_Token::\\\$TPOpen) {
						\\\$�this->unexpected(\\\$tk7);
					}
					\\\$tk7 = \\\$�this->token(\\\$s);
					\\\$vname2 = eval(\\\"if(isset(\\\\\$this)) \\\\\$�this =& \\\\\$this;\\\\\$�t9 = (\\\\\$tk7);
						switch(\\\\\$�t9->index) {
						case 2:
						\\\\\$id17 = \\\\\$�t9->params[0];
						{
							\\\\\$�r10 = \\\\\$id17;
						}break;
						default:{
							\\\\\$�r10 = \\\\\$�this->unexpected(\\\\\$tk7);
						}break;
						}
						return \\\\\$�r10;
					\\\");
					\\\$tk7 = \\\$�this->token(\\\$s);
					if(\\\$tk7 !== hscript_Token::\\\$TDoubleDot) {
						\\\$�this->unexpected(\\\$tk7);
					}
					\\\$tk7 = \\\$�this->token(\\\$s);
					if(!Type::enumEq(\\\$tk7, hscript_Token::TId(\\\"Dynamic\\\"))) {
						\\\$�this->unexpected(\\\$tk7);
					}
					\\\$tk7 = \\\$�this->token(\\\$s);
					if(\\\$tk7 !== hscript_Token::\\\$TPClose) {
						\\\$�this->unexpected(\\\$tk7);
					}
					\\\$�r9 = hscript_Expr::ETry(\\\$e4, \\\$vname2, \\\$�this->parseExpr(\\\$s));
					return \\\$�r9;
				\");
			}break;
			default:{
				\$�r = null;
			}break;
			}
			return \$�r;
		");
	}
	public function parseExprNext($s, $e1) {
		$tk = $this->token($s);
		$�t = ($tk);
		switch($�t->index) {
		case 3:
		$op = $�t->params[0];
		{
			{
				$_g = 0; $_g1 = $this->unopsSuffix;
				while($_g < $_g1->length) {
					$x = $_g1[$_g];
					++$_g;
					if($x == $op) {
						if($this->isBlock($e1) || eval("if(isset(\$this)) \$�this =& \$this;\$�t2 = (\$e1);
							switch(\$�t2->index) {
							case 3:
							{
								\$�r = true;
							}break;
							default:{
								\$�r = false;
							}break;
							}
							return \$�r;
						")) {
							{
								$_g2 = $this->tokens;
								$_g2->head = new haxe_FastCell($tk, $_g2->head);
							}
							return $e1;
						}
						return $this->parseExprNext($s, hscript_Expr::EUnop($op, false, $e1));
					}
					unset($�t2,$�r,$x,$_g2);
				}
			}
			return $this->makeBinop($op, $e1, $this->parseExpr($s));
		}break;
		case 8:
		{
			$tk = $this->token($s);
			$field = null;
			$�t3 = ($tk);
			switch($�t3->index) {
			case 2:
			$id = $�t3->params[0];
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
				$�t = ($tk);
				switch($�t->index) {
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
				unset($�t);
			}
		} catch(_hx_break_exception $�e){}
		return $args;
	}
	public function readChar($s) {
		return eval("if(isset(\$this)) \$�this =& \$this;try {
				\$�r = \$s->readByte();
			}catch(Exception \$�e) {
			\$_ex_ = (\$�e instanceof HException) ? \$�e->e : \$�e;
			;
			{ \$e = \$_ex_;
			{
				\$�r = 0;
			}}}
			return \$�r;
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
			}catch(Exception $�e) {
			$_ex_ = ($�e instanceof HException) ? $�e->e : $�e;
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
			unset($�e,$e,$_ex_);
		}
		return $b->b;
	}
	public function token($s) {
		if(!($this->tokens->head === null)) {
			return eval("if(isset(\$this)) \$�this =& \$this;\$_g = \$�this->tokens;
				\$k = \$_g->head;
				\$�r = (\$k === null ? null : eval(\"if(isset(\\\$this)) \\\$�this =& \\\$this;\\\$_g->head = \\\$k->next;
					\\\$�r2 = \\\$k->elt;
					return \\\$�r2;
				\"));
				return \$�r;
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
								$v = eval("if(isset(\$this)) \$�this =& \$this;try {
										\$�r3 = hscript_Const::CInt(eval(\"if(isset(\\\$this)) \\\$�this =& \\\$this;if((((\\\$n1) >> 30) & 1) !== (_hx_shift_right((\\\$n1), 31))) {
												throw new HException(\\\"Overflow \\\" . \\\$n1);
											}
											\\\$�r4 = ((\\\$n1) & -1);
											return \\\$�r4;
										\"));
									}catch(Exception \$�e) {
									\$_ex_ = (\$�e instanceof HException) ? \$�e->e : \$�e;
									;
									{ \$e = \$_ex_;
									{
										\$�r3 = hscript_Const::CInt32(\$n1);
									}}}
									return \$�r3;
								");
								return hscript_Token::TConst($v);
							}break;
							}
							unset($�r4,$�r3,$�e,$v,$e,$_ex_);
						}
					}break;
					default:{
						$this->char = $char;
						return hscript_Token::TConst((($exp > 0) ? hscript_Const::CFloat($n * 10 / $exp) : hscript_Const::CInt($n)));
					}break;
					}
					unset($�r4,$�r3,$�e,$v,$n1,$e,$_g2,$_ex_);
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
						if(!$this->ops->�a[$char]) {
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
						if(!$this->idents->�a[$char]) {
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
			unset($�r4,$�r3,$�e,$v,$op,$n2,$n1,$n,$id,$exp2,$exp,$e,$_g2,$_ex_);
		}
		return null;
	}
	public function tokenComment($s, $op, $char) {
		$c = _hx_char_code_at($op, 1);
		if($c === 47) {
			try {
				while($char !== 10 && $char !== 13) $char = $s->readByte();
				$this->char = $char;
			}catch(Exception $�e) {
			$_ex_ = ($�e instanceof HException) ? $�e->e : $�e;
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
			}catch(Exception $�e2) {
			$_ex_2 = ($�e2 instanceof HException) ? $�e2->e : $�e2;
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
		return eval("if(isset(\$this)) \$�this =& \$this;\$�t = (\$c);
			switch(\$�t->index) {
			case 0:
			\$v = \$�t->params[0];
			{
				\$�r = Std::string(\$v);
			}break;
			case 3:
			\$v2 = \$�t->params[0];
			{
				\$�r = Std::string(\$v2);
			}break;
			case 1:
			\$f = \$�t->params[0];
			{
				\$�r = Std::string(\$f);
			}break;
			case 2:
			\$s = \$�t->params[0];
			{
				\$�r = \$s;
			}break;
			default:{
				\$�r = null;
			}break;
			}
			return \$�r;
		");
	}
	public function tokenString($t) {
		return eval("if(isset(\$this)) \$�this =& \$this;\$�t = (\$t);
			switch(\$�t->index) {
			case 0:
			{
				\$�r = \"<eof>\";
			}break;
			case 1:
			\$c = \$�t->params[0];
			{
				\$�r = \$�this->constString(\$c);
			}break;
			case 2:
			\$s = \$�t->params[0];
			{
				\$�r = \$s;
			}break;
			case 3:
			\$s2 = \$�t->params[0];
			{
				\$�r = \$s2;
			}break;
			case 4:
			{
				\$�r = \"(\";
			}break;
			case 5:
			{
				\$�r = \")\";
			}break;
			case 6:
			{
				\$�r = \"{\";
			}break;
			case 7:
			{
				\$�r = \"}\";
			}break;
			case 8:
			{
				\$�r = \".\";
			}break;
			case 9:
			{
				\$�r = \",\";
			}break;
			case 10:
			{
				\$�r = \";\";
			}break;
			case 11:
			{
				\$�r = \"[\";
			}break;
			case 12:
			{
				\$�r = \"]\";
			}break;
			case 13:
			{
				\$�r = \"?\";
			}break;
			case 14:
			{
				\$�r = \":\";
			}break;
			default:{
				\$�r = null;
			}break;
			}
			return \$�r;
		");
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->�dynamics[$m]) && is_callable($this->�dynamics[$m]))
			return call_user_func_array($this->�dynamics[$m], $a);
		else
			throw new HException('Unable to call �'.$m.'�');
	}
	function __toString() { return 'hscript.Parser'; }
}
