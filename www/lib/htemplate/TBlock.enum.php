<?php

class htemplate_TBlock extends Enum {
		public static function captureBlock($v) { return new htemplate_TBlock("captureBlock", 6, array($v)); }
		public static function captureCloseBlock($v) { return new htemplate_TBlock("captureCloseBlock", 7, array($v)); }
		public static $closeBlock;
		public static function codeBlock($s) { return new htemplate_TBlock("codeBlock", 9, array($s)); }
		public static $elseBlock;
		public static function elseifBlock($s) { return new htemplate_TBlock("elseifBlock", 2, array($s)); }
		public static function forBlock($s) { return new htemplate_TBlock("forBlock", 4, array($s)); }
		public static function ifBlock($s) { return new htemplate_TBlock("ifBlock", 1, array($s)); }
		public static function literal($s) { return new htemplate_TBlock("literal", 0, array($s)); }
		public static function printBlock($s) { return new htemplate_TBlock("printBlock", 10, array($s)); }
		public static function whileBlock($s) { return new htemplate_TBlock("whileBlock", 5, array($s)); }
	}
	htemplate_TBlock::$closeBlock = new htemplate_TBlock("closeBlock", 8);
	htemplate_TBlock::$elseBlock = new htemplate_TBlock("elseBlock", 3);
