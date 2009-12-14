<?php

class php_io_File {
	public function __construct(){}
	static function getContent($path) {
		return file_get_contents($path);
	}
	static function getBytes($path) {
		return haxe_io_Bytes::ofString(php_io_File::getContent($path));
	}
	static function putContent($path, $content) {
		return file_put_contents($path, $content);
	}
	static function read($path, $binary) {
		return new php_io_FileInput(fopen($path, ($binary ? "rb" : "r")));
	}
	static function write($path, $binary) {
		return new php_io_FileOutput(fopen($path, ($binary ? "wb" : "w")));
	}
	static function append($path, $binary) {
		return new php_io_FileOutput(fopen($path, ($binary ? "ab" : "a")));
	}
	static function copy($src, $dst) {
		return copy($src, $dst);
	}
	static function stdin() {
		return new php_io_FileInput(STDIN);
	}
	static function stdout() {
		return new php_io_FileOutput(STDOUT());
	}
	static function stderr() {
		return new php_io_FileOutput(STDERR());
	}
	function __toString() { return 'php.io.File'; }
}
