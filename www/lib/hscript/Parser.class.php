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
		;
	}}
	public $line;
	public $opChars;
	public $identChars;
	public $opPriority;
	public $unopsPrefix;
	public $unopsSuffix;
	public $allowJSON;
	public $input;
	public $char;
	public $ops;
	public $idents;
	public $tokens;
	public function parseString($s) {
		$this->line = 1;
		return $this->parse(new haxe_io_StringInput($s));
		;
	}
	public function parse($s) {
		$this->char = null;
		$this->input = $s;
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
			unset($_g1,$_g);
		}
		{
			$_g1 = 0; $_g = strlen($this->identChars);
			while($_g1 < $_g) {
				$i = $_g1++;
				$this->idents[_hx_char_code_at($this->identChars, $i)] = true;
				unset($i);
			}
			unset($_g1,$_g);
		}
		$a = new _hx_array(array());
		while(true) {
			$tk = $this->token();
			if($tk === hscript_Token::$TEof) {
				break;
				;
			}
			{
				$_g = $this->tokens;
				$_g->head = new haxe_FastCell($tk, $_g->head);
				unset($_g);
			}
			$a->push($this->parseFullExpr());
			unset($tk);
		}
		return hscript_Parser_0($this, $a, $s);
		unset($a);
	}
	public function unexpected($tk) {
		throw new HException(hscript_Error::EUnexpected($this->tokenString($tk)));
		return null;
		;
	}
	public function isBlock($e) {
		return hscript_Parser_1($this, $e);
		;
	}
	public function parseFullExpr() {
		$e = $this->parseExpr();
		$tk = $this->token();
		if($tk !== hscript_Token::$TSemicolon && $tk !== hscript_Token::$TEof) {
			if($this->isBlock($e)) {
				$_g = $this->tokens;
				$_g->head = new haxe_FastCell($tk, $_g->head);
				unset($_g);
			}
			else {
				$this->unexpected($tk);
				;
			}
			;
		}
		return $e;
		unset($tk,$e);
	}
	public function parseObject() {
		$fl = new _hx_array(array());
		try {
			while(true) {
				$tk = $this->token();
				$id = null;
				$퍁 = ($tk);
				switch($퍁->index) {
				case 2:
				$i = $퍁->params[0];
				{
					$id = $i;
					;
				}break;
				case 1:
				$c = $퍁->params[0];
				{
					if(!$this->allowJSON) {
						$this->unexpected($tk);
						;
					}
					$퍁2 = ($c);
					switch($퍁2->index) {
					case 2:
					$s = $퍁2->params[0];
					{
						$id = $s;
						;
					}break;
					default:{
						$this->unexpected($tk);
						;
					}break;
					}
					;
				}break;
				case 7:
				{
					throw new _hx_break_exception();
					;
				}break;
				default:{
					$this->unexpected($tk);
					;
				}break;
				}
				$tk = $this->token();
				if($tk !== hscript_Token::$TDoubleDot) {
					$this->unexpected($tk);
					;
				}
				$fl->push(_hx_anonymous(array("name" => $id, "e" => $this->parseExpr())));
				$tk = $this->token();
				$퍁 = ($tk);
				switch($퍁->index) {
				case 7:
				{
					throw new _hx_break_exception();
					;
				}break;
				case 9:
				{
					null;
					;
				}break;
				default:{
					$this->unexpected($tk);
					;
				}break;
				}
				unset($tk,$id);
			}
		} catch(_hx_break_exception $팫){}
		return $this->parseExprNext(hscript_Expr::EObject($fl));
		unset($fl);
	}
	public function parseExpr() {
		$tk = $this->token();
		$퍁 = ($tk);
		switch($퍁->index) {
		case 2:
		$id = $퍁->params[0];
		{
			$e = $this->parseStructure($id);
			if($e === null) {
				$e = hscript_Expr::EIdent($id);
				;
			}
			return $this->parseExprNext($e);
			unset($e);
		}break;
		case 1:
		$c = $퍁->params[0];
		{
			return $this->parseExprNext(hscript_Expr::EConst($c));
			;
		}break;
		case 4:
		{
			$e = $this->parseExpr();
			$tk = $this->token();
			if($tk !== hscript_Token::$TPClose) {
				$this->unexpected($tk);
				;
			}
			return $this->parseExprNext(hscript_Expr::EParent($e));
			unset($e);
		}break;
		case 6:
		{
			$tk = $this->token();
			$퍁2 = ($tk);
			switch($퍁2->index) {
			case 7:
			{
				return $this->parseExprNext(hscript_Expr::EObject(new _hx_array(array())));
				;
			}break;
			case 2:
			$id = $퍁2->params[0];
			{
				$tk2 = $this->token();
				{
					$_g = $this->tokens;
					$_g->head = new haxe_FastCell($tk2, $_g->head);
					unset($_g);
				}
				{
					$_g = $this->tokens;
					$_g->head = new haxe_FastCell($tk, $_g->head);
					unset($_g);
				}
				$퍁3 = ($tk2);
				switch($퍁3->index) {
				case 14:
				{
					return $this->parseExprNext($this->parseObject());
					;
				}break;
				default:{
					;
					;
				}break;
				}
				unset($tk2);
			}break;
			case 1:
			$c = $퍁2->params[0];
			{
				if($this->allowJSON) {
					$퍁3 = ($c);
					switch($퍁3->index) {
					case 2:
					{
						$tk2 = $this->token();
						{
							$_g = $this->tokens;
							$_g->head = new haxe_FastCell($tk2, $_g->head);
							unset($_g);
						}
						{
							$_g = $this->tokens;
							$_g->head = new haxe_FastCell($tk, $_g->head);
							unset($_g);
						}
						$퍁4 = ($tk2);
						switch($퍁4->index) {
						case 14:
						{
							return $this->parseExprNext($this->parseObject());
							;
						}break;
						default:{
							;
							;
						}break;
						}
						unset($tk2);
					}break;
					default:{
						{
							$_g = $this->tokens;
							$_g->head = new haxe_FastCell($tk, $_g->head);
							unset($_g);
						}
						;
					}break;
					}
					;
				}
				else {
					$_g = $this->tokens;
					$_g->head = new haxe_FastCell($tk, $_g->head);
					unset($_g);
				}
				;
			}break;
			default:{
				{
					$_g = $this->tokens;
					$_g->head = new haxe_FastCell($tk, $_g->head);
					unset($_g);
				}
				;
			}break;
			}
			$a = new _hx_array(array());
			while(true) {
				$a->push($this->parseFullExpr());
				$tk = $this->token();
				if($tk === hscript_Token::$TBrClose) {
					break;
					;
				}
				{
					$_g = $this->tokens;
					$_g->head = new haxe_FastCell($tk, $_g->head);
					unset($_g);
				}
				;
			}
			return hscript_Expr::EBlock($a);
			unset($a);
		}break;
		case 3:
		$op = $퍁->params[0];
		{
			$found = null;
			{
				$_g = 0; $_g1 = $this->unopsPrefix;
				while($_g < $_g1->length) {
					$x = $_g1[$_g];
					++$_g;
					if($x === $op) {
						return $this->makeUnop($op, $this->parseExpr());
						;
					}
					unset($x);
				}
				unset($_g1,$_g);
			}
			return $this->unexpected($tk);
			unset($found);
		}break;
		case 11:
		{
			$a = new _hx_array(array());
			$tk = $this->token();
			while($tk !== hscript_Token::$TBkClose) {
				{
					$_g = $this->tokens;
					$_g->head = new haxe_FastCell($tk, $_g->head);
					unset($_g);
				}
				$a->push($this->parseExpr());
				$tk = $this->token();
				if($tk === hscript_Token::$TComma) {
					$tk = $this->token();
					;
				}
				;
			}
			return $this->parseExprNext(hscript_Expr::EArrayDecl($a));
			unset($a);
		}break;
		default:{
			return $this->unexpected($tk);
			;
		}break;
		}
		unset($tk);
	}
	public function priority($op) {
		{
			$_g1 = 0; $_g = $this->opPriority->length;
			while($_g1 < $_g) {
				$i = $_g1++;
				if($this->opPriority[$i] === $op) {
					return $i;
					;
				}
				unset($i);
			}
			unset($_g1,$_g);
		}
		return -1;
		;
	}
	public function makeUnop($op, $e) {
		return hscript_Parser_2($this, $e, $op);
		;
	}
	public function makeBinop($op, $e1, $e) {
		return hscript_Parser_3($this, $e, $e1, $op);
		;
	}
	public function parseStructure($id) {
		return hscript_Parser_4($this, $id);
		;
	}
	public function parseExprNext($e1) {
		$tk = $this->token();
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
					if($x === $op) {
						if($this->isBlock($e1) || hscript_Parser_5($this, $_g, $_g1, $e1, $op, $tk, $x)) {
							{
								$_g2 = $this->tokens;
								$_g2->head = new haxe_FastCell($tk, $_g2->head);
								unset($_g2);
							}
							return $e1;
							;
						}
						return $this->parseExprNext(hscript_Expr::EUnop($op, false, $e1));
						;
					}
					unset($x);
				}
				unset($_g1,$_g);
			}
			return $this->makeBinop($op, $e1, $this->parseExpr());
			;
		}break;
		case 8:
		{
			$tk = $this->token();
			$field = null;
			$퍁2 = ($tk);
			switch($퍁2->index) {
			case 2:
			$id = $퍁2->params[0];
			{
				$field = $id;
				;
			}break;
			default:{
				$this->unexpected($tk);
				;
			}break;
			}
			return $this->parseExprNext(hscript_Expr::EField($e1, $field));
			unset($field);
		}break;
		case 4:
		{
			return $this->parseExprNext(hscript_Expr::ECall($e1, $this->parseExprList(hscript_Token::$TPClose)));
			;
		}break;
		case 11:
		{
			$e2 = $this->parseExpr();
			$tk = $this->token();
			if($tk !== hscript_Token::$TBkClose) {
				$this->unexpected($tk);
				;
			}
			return $this->parseExprNext(hscript_Expr::EArray($e1, $e2));
			unset($e2);
		}break;
		case 13:
		{
			$e2 = $this->parseExpr();
			$tk = $this->token();
			if($tk !== hscript_Token::$TDoubleDot) {
				$this->unexpected($tk);
				;
			}
			$e3 = $this->parseExpr();
			return hscript_Expr::EIf($e1, $e2, $e3);
			unset($e3,$e2);
		}break;
		default:{
			{
				$_g = $this->tokens;
				$_g->head = new haxe_FastCell($tk, $_g->head);
				unset($_g);
			}
			return $e1;
			;
		}break;
		}
		unset($tk);
	}
	public function parseExprList($etk) {
		$args = new _hx_array(array());
		$tk = $this->token();
		if($tk === $etk) {
			return $args;
			;
		}
		{
			$_g = $this->tokens;
			$_g->head = new haxe_FastCell($tk, $_g->head);
			unset($_g);
		}
		try {
			while(true) {
				$args->push($this->parseExpr());
				$tk = $this->token();
				$퍁 = ($tk);
				switch($퍁->index) {
				case 9:
				{
					null;
					;
				}break;
				default:{
					if($tk === $etk) {
						throw new _hx_break_exception();
						;
					}
					$this->unexpected($tk);
					;
				}break;
				}
				;
			}
		} catch(_hx_break_exception $팫){}
		return $args;
		unset($tk,$args);
	}
	public function readChar() {
		return hscript_Parser_6($this);
		;
	}
	public function readString($until) {
		$c = null;
		$b = new haxe_io_BytesOutput();
		$esc = false;
		$old = $this->line;
		$s = $this->input;
		while(true) {
			try {
				$c = $s->readByte();
				;
			}catch(Exception $팫) {
			$_ex_ = ($팫 instanceof HException) ? $팫->e : $팫;
			;
			{ $e = $_ex_;
			{
				$this->line = $old;
				throw new HException(hscript_Error::$EUnterminatedString);
				;
			}}}
			if($esc) {
				$esc = false;
				switch($c) {
				case 110:{
					$b->writeByte(10);
					;
				}break;
				case 114:{
					$b->writeByte(13);
					;
				}break;
				case 116:{
					$b->writeByte(9);
					;
				}break;
				case 39:case 34:case 92:{
					$b->writeByte($c);
					;
				}break;
				case 47:{
					if($this->allowJSON) {
						$b->writeByte($c);
						;
					}
					else {
						throw new HException(hscript_Error::EInvalidChar($c));
						;
					}
					;
				}break;
				case 117:{
					if(!$this->allowJSON) {
						throw new HException(hscript_Error::EInvalidChar($c));
						;
					}
					$code = null;
					try {
						$code = $s->readString(4);
						;
					}catch(Exception $팫) {
					$_ex_ = ($팫 instanceof HException) ? $팫->e : $팫;
					;
					{ $e2 = $_ex_;
					{
						$this->line = $old;
						throw new HException(hscript_Error::$EUnterminatedString);
						;
					}}}
					$k = 0;
					{
						$_g = 0;
						while($_g < 4) {
							$i = $_g++;
							$k <<= 4;
							$char = _hx_char_code_at($code, $i);
							switch($char) {
							case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:{
								$k += $char - 48;
								;
							}break;
							case 65:case 66:case 67:case 68:case 69:case 70:{
								$k += $char - 55;
								;
							}break;
							case 97:case 98:case 99:case 100:case 101:case 102:{
								$k += $char - 87;
								;
							}break;
							default:{
								throw new HException(hscript_Error::EInvalidChar($char));
								;
							}break;
							}
							unset($i,$char);
						}
						unset($_g);
					}
					if($k <= 127) {
						$b->writeByte($k);
						;
					}
					else {
						if($k <= 2047) {
							$b->writeByte(192 | ($k >> 6));
							$b->writeByte(128 | ($k & 63));
							;
						}
						else {
							$b->writeByte(224 | ($k >> 12));
							$b->writeByte(128 | (($k >> 6) & 63));
							$b->writeByte(128 | ($k & 63));
							;
						}
						;
					}
					unset($k,$e2,$code);
				}break;
				default:{
					throw new HException(hscript_Error::EInvalidChar($c));
					;
				}break;
				}
				;
			}
			else {
				if($c === 92) {
					$esc = true;
					;
				}
				else {
					if($c === $until) {
						break;
						;
					}
					else {
						if($c === 10) {
							$this->line++;
							;
						}
						$b->writeByte($c);
						;
					}
					;
				}
				;
			}
			unset($e);
		}
		return $b->getBytes()->toString();
		unset($s,$old,$esc,$c,$b);
	}
	public function token() {
		if(!($this->tokens->head === null)) {
			return hscript_Parser_7($this);
			;
		}
		$char = null;
		if($this->char === null) {
			$char = $this->readChar();
			;
		}
		else {
			$char = $this->char;
			$this->char = null;
			;
		}
		while(true) {
			switch($char) {
			case 0:{
				return hscript_Token::$TEof;
				;
			}break;
			case 32:case 9:case 13:{
				null;
				;
			}break;
			case 10:{
				$this->line++;
				;
			}break;
			case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:{
				$n = ($char - 48) * 1.0;
				$exp = 0;
				while(true) {
					$char = $this->readChar();
					$exp *= 10;
					switch($char) {
					case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:{
						$n = $n * 10 + ($char - 48);
						;
					}break;
					case 46:{
						if($exp > 0) {
							if($exp === 10 && $this->readChar() === 46) {
								{
									$_g = $this->tokens;
									$_g->head = new haxe_FastCell(hscript_Token::TOp("..."), $_g->head);
									unset($_g);
								}
								$i = intval($n);
								return hscript_Token::TConst(hscript_Parser_8($this, $char, $exp, $i, $n));
								unset($i);
							}
							throw new HException(hscript_Error::EInvalidChar($char));
							;
						}
						$exp = 1;
						;
					}break;
					case 120:{
						if($n > 0 || $exp > 0) {
							throw new HException(hscript_Error::EInvalidChar($char));
							;
						}
						$n1 = 0;
						while(true) {
							$char = $this->readChar();
							switch($char) {
							case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:{
								$n1 = (($n1) << 4) + ($char - 48);
								;
							}break;
							case 65:case 66:case 67:case 68:case 69:case 70:{
								$n1 = (($n1) << 4) + ($char - 55);
								;
							}break;
							case 97:case 98:case 99:case 100:case 101:case 102:{
								$n1 = (($n1) << 4) + ($char - 87);
								;
							}break;
							default:{
								$this->char = $char;
								$v = hscript_Parser_9($this, $char, $exp, $n, $n1);
								return hscript_Token::TConst($v);
								unset($v);
							}break;
							}
							;
						}
						unset($n1);
					}break;
					default:{
						$this->char = $char;
						$i = intval($n);
						return hscript_Token::TConst(hscript_Parser_10($this, $char, $exp, $i, $n));
						unset($i);
					}break;
					}
					;
				}
				unset($n,$exp);
			}break;
			case 59:{
				return hscript_Token::$TSemicolon;
				;
			}break;
			case 40:{
				return hscript_Token::$TPOpen;
				;
			}break;
			case 41:{
				return hscript_Token::$TPClose;
				;
			}break;
			case 44:{
				return hscript_Token::$TComma;
				;
			}break;
			case 46:{
				$char = $this->readChar();
				switch($char) {
				case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:{
					$n = $char - 48;
					$exp = 1;
					while(true) {
						$char = $this->readChar();
						$exp *= 10;
						switch($char) {
						case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:{
							$n = $n * 10 + ($char - 48);
							;
						}break;
						default:{
							$this->char = $char;
							return hscript_Token::TConst(hscript_Const::CFloat($n / $exp));
							;
						}break;
						}
						;
					}
					unset($n,$exp);
				}break;
				case 46:{
					$char = $this->readChar();
					if($char !== 46) {
						throw new HException(hscript_Error::EInvalidChar($char));
						;
					}
					return hscript_Token::TOp("...");
					;
				}break;
				default:{
					$this->char = $char;
					return hscript_Token::$TDot;
					;
				}break;
				}
				;
			}break;
			case 123:{
				return hscript_Token::$TBrOpen;
				;
			}break;
			case 125:{
				return hscript_Token::$TBrClose;
				;
			}break;
			case 91:{
				return hscript_Token::$TBkOpen;
				;
			}break;
			case 93:{
				return hscript_Token::$TBkClose;
				;
			}break;
			case 39:{
				return hscript_Token::TConst(hscript_Const::CString($this->readString(39)));
				;
			}break;
			case 34:{
				return hscript_Token::TConst(hscript_Const::CString($this->readString(34)));
				;
			}break;
			case 63:{
				return hscript_Token::$TQuestion;
				;
			}break;
			case 58:{
				return hscript_Token::$TDoubleDot;
				;
			}break;
			default:{
				if($this->ops[$char]) {
					$op = chr($char);
					while(true) {
						$char = $this->readChar();
						if(!$this->ops->팤[$char]) {
							if(_hx_char_code_at($op, 0) === 47) {
								return $this->tokenComment($op, $char);
								;
							}
							$this->char = $char;
							return hscript_Token::TOp($op);
							;
						}
						$op .= chr($char);
						;
					}
					unset($op);
				}
				if($this->idents[$char]) {
					$id = chr($char);
					while(true) {
						$char = $this->readChar();
						if(!$this->idents->팤[$char]) {
							$this->char = $char;
							return hscript_Token::TId($id);
							;
						}
						$id .= chr($char);
						;
					}
					unset($id);
				}
				throw new HException(hscript_Error::EInvalidChar($char));
				;
			}break;
			}
			$char = $this->readChar();
			;
		}
		return null;
		unset($char);
	}
	public function tokenComment($op, $char) {
		$c = _hx_char_code_at($op, 1);
		$s = $this->input;
		if($c === 47) {
			try {
				while($char !== 10 && $char !== 13) $char = $s->readByte();
				$this->char = $char;
				;
			}catch(Exception $팫) {
			$_ex_ = ($팫 instanceof HException) ? $팫->e : $팫;
			;
			{ $e = $_ex_;
			{
				;
				;
			}}}
			return $this->token();
			unset($e);
		}
		if($c === 42) {
			$old = $this->line;
			try {
				while(true) {
					while($char !== 42) {
						if($char === 10) {
							$this->line++;
							;
						}
						$char = $s->readByte();
						;
					}
					$char = $s->readByte();
					if($char === 47) {
						break;
						;
					}
					;
				}
				;
			}catch(Exception $팫) {
			$_ex_ = ($팫 instanceof HException) ? $팫->e : $팫;
			;
			{ $e = $_ex_;
			{
				$this->line = $old;
				throw new HException(hscript_Error::$EUnterminatedComment);
				;
			}}}
			return $this->token();
			unset($old,$e);
		}
		$this->char = $char;
		return hscript_Token::TOp($op);
		unset($s,$c);
	}
	public function constString($c) {
		return hscript_Parser_11($this, $c);
		;
	}
	public function tokenString($t) {
		return hscript_Parser_12($this, $t);
		;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->팪ynamics[$m]) && is_callable($this->팪ynamics[$m]))
			return call_user_func_array($this->팪ynamics[$m], $a);
		else if('toString' == $m)
			return $this->__toString();
		else
			throw new HException('Unable to call �'.$m.'�');
	}
	function __toString() { return 'hscript.Parser'; }
}
;
function hscript_Parser_0(&$퍁his, &$a, &$s) {
if($a->length === 1) {
	return $a[0];
	;
}
else {
	return hscript_Expr::EBlock($a);
	;
}
}
function hscript_Parser_1(&$퍁his, &$e) {
$퍁 = ($e);
switch($퍁->index) {
case 4:
{
	return true;
	;
}break;
case 14:
$e1 = $퍁->params[1];
{
	return $퍁his->isBlock($e1);
	;
}break;
case 2:
$e1 = $퍁->params[1];
{
	return $e1 !== null && $퍁his->isBlock($e1);
	;
}break;
case 9:
$e2 = $퍁->params[2]; $e1 = $퍁->params[1];
{
	if($e2 !== null) {
		return $퍁his->isBlock($e2);
		;
	}
	else {
		return $퍁his->isBlock($e1);
		;
	}
	;
}break;
case 6:
$e1 = $퍁->params[2];
{
	return $퍁his->isBlock($e1);
	;
}break;
case 7:
$e1 = $퍁->params[2]; $prefix = $퍁->params[1];
{
	return !$prefix && $퍁his->isBlock($e1);
	;
}break;
case 10:
$e1 = $퍁->params[1];
{
	return $퍁his->isBlock($e1);
	;
}break;
case 11:
$e1 = $퍁->params[2];
{
	return $퍁his->isBlock($e1);
	;
}break;
case 15:
$e1 = $퍁->params[0];
{
	return $e1 !== null && $퍁his->isBlock($e1);
	;
}break;
default:{
	return false;
	;
}break;
}
}
function hscript_Parser_2(&$퍁his, &$e, &$op) {
$퍁 = ($e);
switch($퍁->index) {
case 6:
$e2 = $퍁->params[2]; $e1 = $퍁->params[1]; $bop = $퍁->params[0];
{
	return hscript_Expr::EBinop($bop, $퍁his->makeUnop($op, $e1), $e2);
	;
}break;
default:{
	return hscript_Expr::EUnop($op, true, $e);
	;
}break;
}
}
function hscript_Parser_3(&$퍁his, &$e, &$e1, &$op) {
$퍁 = ($e);
switch($퍁->index) {
case 6:
$e3 = $퍁->params[2]; $e2 = $퍁->params[1]; $op2 = $퍁->params[0];
{
	if($퍁his->priority($op) > $퍁his->priority($op2)) {
		return hscript_Expr::EBinop($op2, $퍁his->makeBinop($op, $e1, $e2), $e3);
		;
	}
	else {
		return hscript_Expr::EBinop($op, $e1, $e);
		;
	}
	;
}break;
default:{
	return hscript_Expr::EBinop($op, $e1, $e);
	;
}break;
}
}
function hscript_Parser_4(&$퍁his, &$id) {
switch($id) {
case "if":{
	$cond = $퍁his->parseExpr();
	$e1 = $퍁his->parseExpr();
	$e2 = null;
	$semic = false;
	$tk = $퍁his->token();
	if($tk === hscript_Token::$TSemicolon) {
		$semic = true;
		$tk = $퍁his->token();
		;
	}
	if(Type::enumEq($tk, hscript_Token::TId("else"))) {
		$e2 = $퍁his->parseExpr();
		;
	}
	else {
		{
			$_g = $퍁his->tokens;
			$_g->head = new haxe_FastCell($tk, $_g->head);
			unset($_g);
		}
		if($semic) {
			$_g = $퍁his->tokens;
			$_g->head = new haxe_FastCell(hscript_Token::$TSemicolon, $_g->head);
			unset($_g);
		}
		;
	}
	return hscript_Expr::EIf($cond, $e1, $e2);
	unset($tk,$semic,$e2,$e1,$cond);
}break;
case "var":{
	$tk = $퍁his->token();
	$ident = null;
	$퍁 = ($tk);
	switch($퍁->index) {
	case 2:
	$id1 = $퍁->params[0];
	{
		$ident = $id1;
		;
	}break;
	default:{
		$퍁his->unexpected($tk);
		;
	}break;
	}
	$tk = $퍁his->token();
	$e = null;
	if(Type::enumEq($tk, hscript_Token::TOp("="))) {
		$e = $퍁his->parseExpr();
		;
	}
	else {
		$_g = $퍁his->tokens;
		$_g->head = new haxe_FastCell($tk, $_g->head);
		unset($_g);
	}
	return hscript_Expr::EVar($ident, $e);
	unset($tk,$ident,$e);
}break;
case "while":{
	$econd = $퍁his->parseExpr();
	$e = $퍁his->parseExpr();
	return hscript_Expr::EWhile($econd, $e);
	unset($econd,$e);
}break;
case "for":{
	$tk = $퍁his->token();
	if($tk !== hscript_Token::$TPOpen) {
		$퍁his->unexpected($tk);
		;
	}
	$tk = $퍁his->token();
	$vname = null;
	$퍁 = ($tk);
	switch($퍁->index) {
	case 2:
	$id1 = $퍁->params[0];
	{
		$vname = $id1;
		;
	}break;
	default:{
		$퍁his->unexpected($tk);
		;
	}break;
	}
	$tk = $퍁his->token();
	if(!Type::enumEq($tk, hscript_Token::TId("in"))) {
		$퍁his->unexpected($tk);
		;
	}
	$eiter = $퍁his->parseExpr();
	$tk = $퍁his->token();
	if($tk !== hscript_Token::$TPClose) {
		$퍁his->unexpected($tk);
		;
	}
	return hscript_Expr::EFor($vname, $eiter, $퍁his->parseExpr());
	unset($vname,$tk,$eiter);
}break;
case "break":{
	return hscript_Expr::$EBreak;
	;
}break;
case "continue":{
	return hscript_Expr::$EContinue;
	;
}break;
case "else":{
	return $퍁his->unexpected(hscript_Token::TId($id));
	;
}break;
case "function":{
	$tk = $퍁his->token();
	$name = null;
	$퍁 = ($tk);
	switch($퍁->index) {
	case 2:
	$id1 = $퍁->params[0];
	{
		$name = $id1;
		$tk = $퍁his->token();
		;
	}break;
	default:{
		;
		;
	}break;
	}
	if($tk !== hscript_Token::$TPOpen) {
		$퍁his->unexpected($tk);
		;
	}
	$args = new _hx_array(array());
	$tk = $퍁his->token();
	if($tk !== hscript_Token::$TPClose) {
		try {
			while(true) {
				$퍁 = ($tk);
				switch($퍁->index) {
				case 2:
				$id1 = $퍁->params[0];
				{
					$args->push($id1);
					;
				}break;
				default:{
					$퍁his->unexpected($tk);
					;
				}break;
				}
				$tk = $퍁his->token();
				$퍁 = ($tk);
				switch($퍁->index) {
				case 9:
				{
					null;
					;
				}break;
				case 5:
				{
					throw new _hx_break_exception();
					;
				}break;
				default:{
					$퍁his->unexpected($tk);
					;
				}break;
				}
				$tk = $퍁his->token();
				;
			}
		} catch(_hx_break_exception $팫){}
		;
	}
	return hscript_Expr::EFunction($args, $퍁his->parseExpr(), $name);
	unset($tk,$name,$args);
}break;
case "return":{
	$tk = $퍁his->token();
	{
		$_g = $퍁his->tokens;
		$_g->head = new haxe_FastCell($tk, $_g->head);
		unset($_g);
	}
	return hscript_Expr::EReturn(hscript_Parser_13($퍁his, $id, $tk));
	unset($tk);
}break;
case "new":{
	$a = new _hx_array(array());
	$tk = $퍁his->token();
	$퍁 = ($tk);
	switch($퍁->index) {
	case 2:
	$id1 = $퍁->params[0];
	{
		$a->push($id1);
		;
	}break;
	default:{
		$퍁his->unexpected($tk);
		;
	}break;
	}
	try {
		while(true) {
			$tk = $퍁his->token();
			$퍁 = ($tk);
			switch($퍁->index) {
			case 8:
			{
				$tk = $퍁his->token();
				$퍁2 = ($tk);
				switch($퍁2->index) {
				case 2:
				$id1 = $퍁2->params[0];
				{
					$a->push($id1);
					;
				}break;
				default:{
					$퍁his->unexpected($tk);
					;
				}break;
				}
				;
			}break;
			case 4:
			{
				throw new _hx_break_exception();
				;
			}break;
			default:{
				$퍁his->unexpected($tk);
				;
			}break;
			}
			;
		}
	} catch(_hx_break_exception $팫){}
	return hscript_Expr::ENew($a->join("."), $퍁his->parseExprList(hscript_Token::$TPClose));
	unset($tk,$a);
}break;
case "throw":{
	return hscript_Expr::EThrow($퍁his->parseExpr());
	;
}break;
case "try":{
	$e = $퍁his->parseExpr();
	$tk = $퍁his->token();
	if(!Type::enumEq($tk, hscript_Token::TId("catch"))) {
		$퍁his->unexpected($tk);
		;
	}
	$tk = $퍁his->token();
	if($tk !== hscript_Token::$TPOpen) {
		$퍁his->unexpected($tk);
		;
	}
	$tk = $퍁his->token();
	$vname = hscript_Parser_14($퍁his, $e, $id, $tk);
	$tk = $퍁his->token();
	if($tk !== hscript_Token::$TDoubleDot) {
		$퍁his->unexpected($tk);
		;
	}
	$tk = $퍁his->token();
	if(!Type::enumEq($tk, hscript_Token::TId("Dynamic"))) {
		$퍁his->unexpected($tk);
		;
	}
	$tk = $퍁his->token();
	if($tk !== hscript_Token::$TPClose) {
		$퍁his->unexpected($tk);
		;
	}
	return hscript_Expr::ETry($e, $vname, $퍁his->parseExpr());
	unset($vname,$tk,$e);
}break;
default:{
	return null;
	;
}break;
}
}
function hscript_Parser_5(&$퍁his, &$_g, &$_g1, &$e1, &$op, &$tk, &$x) {
$퍁2 = ($e1);
switch($퍁2->index) {
case 3:
{
	return true;
	;
}break;
default:{
	return false;
	;
}break;
}
}
function hscript_Parser_6(&$퍁his) {
try {
	return $퍁his->input->readByte();
	;
}catch(Exception $팫) {
$_ex_ = ($팫 instanceof HException) ? $팫->e : $팫;
;
{ $e = $_ex_;
{
	return 0;
	;
}}}
}
function hscript_Parser_7(&$퍁his) {
{
	$_g = $퍁his->tokens;
	$k = $_g->head;
	if($k === null) {
		return null;
		;
	}
	else {
		$_g->head = $k->next;
		return $k->elt;
		;
	}
	unset($k,$_g);
}
}
function hscript_Parser_8(&$퍁his, &$char, &$exp, &$i, &$n) {
if(_hx_equal($i, $n)) {
	return hscript_Const::CInt($i);
	;
}
else {
	return hscript_Const::CFloat($n);
	;
}
}
function hscript_Parser_9(&$퍁his, &$char, &$exp, &$n, &$n1) {
try {
	return hscript_Const::CInt(hscript_Parser_15($퍁his, $char, $exp, $n, $n1));
	;
}catch(Exception $팫) {
$_ex_ = ($팫 instanceof HException) ? $팫->e : $팫;
;
{ $e = $_ex_;
{
	return hscript_Const::CInt32($n1);
	;
}}}
}
function hscript_Parser_10(&$퍁his, &$char, &$exp, &$i, &$n) {
if($exp > 0) {
	return hscript_Const::CFloat(($n * 10) / $exp);
	;
}
else {
	return (hscript_Parser_16($퍁his, $char, $exp, $i, $n));
	;
}
}
function hscript_Parser_11(&$퍁his, &$c) {
$퍁 = ($c);
switch($퍁->index) {
case 0:
$v = $퍁->params[0];
{
	return Std::string($v);
	;
}break;
case 3:
$v = $퍁->params[0];
{
	return Std::string($v);
	;
}break;
case 1:
$f = $퍁->params[0];
{
	return Std::string($f);
	;
}break;
case 2:
$s = $퍁->params[0];
{
	return $s;
	;
}break;
default:{
	return null;
	;
}break;
}
}
function hscript_Parser_12(&$퍁his, &$t) {
$퍁 = ($t);
switch($퍁->index) {
case 0:
{
	return "<eof>";
	;
}break;
case 1:
$c = $퍁->params[0];
{
	return $퍁his->constString($c);
	;
}break;
case 2:
$s = $퍁->params[0];
{
	return $s;
	;
}break;
case 3:
$s = $퍁->params[0];
{
	return $s;
	;
}break;
case 4:
{
	return "(";
	;
}break;
case 5:
{
	return ")";
	;
}break;
case 6:
{
	return "{";
	;
}break;
case 7:
{
	return "}";
	;
}break;
case 8:
{
	return ".";
	;
}break;
case 9:
{
	return ",";
	;
}break;
case 10:
{
	return ";";
	;
}break;
case 11:
{
	return "[";
	;
}break;
case 12:
{
	return "]";
	;
}break;
case 13:
{
	return "?";
	;
}break;
case 14:
{
	return ":";
	;
}break;
default:{
	return null;
	;
}break;
}
}
function hscript_Parser_13(&$퍁his, &$id, &$tk) {
if($tk === hscript_Token::$TSemicolon) {
	return null;
	;
}
else {
	return $퍁his->parseExpr();
	;
}
}
function hscript_Parser_14(&$퍁his, &$e, &$id, &$tk) {
$퍁 = ($tk);
switch($퍁->index) {
case 2:
$id1 = $퍁->params[0];
{
	return $id1;
	;
}break;
default:{
	return $퍁his->unexpected($tk);
	;
}break;
}
}
function hscript_Parser_15(&$퍁his, &$char, &$exp, &$n, &$n1) {
{
	if(((($n1) >> 30) & 1) !== (_hx_shift_right(($n1), 31))) {
		throw new HException("Overflow " . $n1);
		;
	}
	return ($n1) & -1;
	;
}
}
function hscript_Parser_16(&$퍁his, &$char, &$exp, &$i, &$n) {
if(_hx_equal($i, $n)) {
	return hscript_Const::CInt($i);
	;
}
else {
	return hscript_Const::CFloat($n);
	;
}
}