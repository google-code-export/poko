<?php

class poko_utils_StringTools2 {
	public function __construct() { ;
		;
		;
	}
	static function ucFirst($s) {
		$p1 = _hx_substr($s, 0, 1);
		$p2 = _hx_substr($s, 1, null);
		return strtoupper($p1) . strtolower($p2);
		unset($p2,$p1);
	}
	static function printf($string, $vars) {
		$parts = _hx_explode("%s", $string);
		$out = "";
		$c = 0;
		$lastpart = $parts->pop();
		{
			$_g = 0;
			while($_g < $parts->length) {
				$part = $parts[$_g];
				++$_g;
				if($c < $vars->length) {
					$out .= $part . $vars[$c];
					;
				}
				else {
					$out .= $part;
					;
				}
				$c++;
				unset($part);
			}
			unset($_g);
		}
		$out .= $lastpart;
		return $out;
		unset($parts,$out,$lastpart,$c);
	}
	static function toSentenceList($input, $splitBy, $surroundBy) {
		if($surroundBy === null) {
			$surroundBy = "'";
			;
		}
		if($splitBy === null) {
			$splitBy = "";
			;
		}
		$a = _hx_explode($splitBy, $input);
		$c = 0;
		$out = "";
		{
			$_g = 0;
			while($_g < $a->length) {
				$s = $a[$_g];
				++$_g;
				$out .= ($surroundBy . $s) . $surroundBy;
				if($c < $a->length - 2) {
					$out .= ", ";
					;
				}
				else {
					if($c === $a->length - 2) {
						$out .= " and ";
						;
					}
					;
				}
				$c++;
				unset($s);
			}
			unset($_g);
		}
		return $out;
		unset($out,$c,$a);
	}
	static function html2Txt($t) {
		$t = str_replace("<h1>", "=== ", $t);
		$t = str_replace("</h1>", " ===\x0A\x0A", $t);
		$t = str_replace("<h2>", "=== ", $t);
		$t = str_replace("</h2>", " ===\x0A\x0A", $t);
		$t = str_replace("<h3>", "=== ", $t);
		$t = str_replace("</h3>", " ===\x0A\x0A", $t);
		$t = str_replace("<p>", "", $t);
		$t = str_replace("</p>", "\x0A\x0A", $t);
		$t = str_replace("<div>", "", $t);
		$t = str_replace("</div>", "\x0A\x0A", $t);
		$t = str_replace("<br>", "\x0A", $t);
		$t = str_replace("<br />", "\x0A", $t);
		$t = str_replace("<b>", "\"", $t);
		$t = str_replace("</b>", "\"", $t);
		$t = str_replace("<strong>", "\"", $t);
		$t = str_replace("</strong>", "\"", $t);
		$t = str_replace("<u>", "\"", $t);
		$t = str_replace("</u>", "\"", $t);
		$t = str_replace("<i>", "'", $t);
		$t = str_replace("</i>", "'", $t);
		$t = str_replace("<em>", "'", $t);
		$t = str_replace("</em>", "'", $t);
		$t = str_replace("<ul>", "", $t);
		$t = str_replace("</ul>", "\x0A", $t);
		$t = str_replace("<ol>", "", $t);
		$t = str_replace("</ol>", "\x0A", $t);
		$t = str_replace("<li>", "    * ", $t);
		$t = str_replace("</li>", "\x0A", $t);
		return $t;
		;
	}
	function __toString() { return 'poko.utils.StringTools2'; }
}
