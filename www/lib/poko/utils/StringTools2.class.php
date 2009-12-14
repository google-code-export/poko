<?php

class poko_utils_StringTools2 {
	public function __construct() { ;
		;
	}
	static function ucFirst($s) {
		$p1 = _hx_substr($s, 0, 1);
		$p2 = _hx_substr($s, 1, null);
		return strtoupper($p1) . strtolower($p2);
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
				}
				else {
					$out .= $part;
				}
				$c++;
				unset($part);
			}
		}
		$out .= $lastpart;
		return $out;
	}
	static function toSentenceList($input, $splitBy, $surroundBy) {
		if($surroundBy === null) {
			$surroundBy = "'";
		}
		if($splitBy === null) {
			$splitBy = "";
		}
		$a = _hx_explode($splitBy, $input);
		$c = 0;
		$out = "";
		{
			$_g = 0;
			while($_g < $a->length) {
				$s = $a[$_g];
				++$_g;
				$out .= $surroundBy . $s . $surroundBy;
				if($c < $a->length - 2) {
					$out .= ", ";
				}
				else {
					if($c === $a->length - 2) {
						$out .= " and ";
					}
				}
				$c++;
				unset($s);
			}
		}
		return $out;
	}
	function __toString() { return 'poko.utils.StringTools2'; }
}
