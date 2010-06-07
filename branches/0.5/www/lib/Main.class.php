<?php

class Main {
	public function __construct(){}
	static $app;
	static function main() {
		Main::init(false);
	}
	static function init($phpMode) {
		if($phpMode === null) {
			$phpMode = true;
		}
		poko_utils_PhpTools::setupTrace();
		Main::$app = new poko_Application();
		Main::$app->sitePath = "";
		Main::$app->siteRoot = ".";
		Main::$app->uploadFolder = Main::$app->siteRoot . "/res/uploads";
		Main::$app->sitePackage = "site";
		Main::$app->packageRoot = Main::$app->siteRoot;
		Main::$app->skipAuthentication = false;
		Main::$app->debug = true;
		Main::$app->sessionName = "alphastation_dobsons";
		if(Main::$app->debug) {
			Main::execute($phpMode);
		}
		else {
			try {
				Main::execute($phpMode);
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e = $_ex_;
			{
				php_Lib::hprint("<span style='color:#ff0000'><b>Sorry the site has died.</b></span> <br/> Please report the error to: <b>" . Main::$app->errorContactEmail . "</b>");
			}}}
		}
	}
	static function execute($phpMode) {
		try {
			switch($_SERVER['SERVER_NAME']) {
			case "staging.touchmypixel.com":{
				;
			}break;
			default:{
				Main::$app->db->connect("192.168.1.80", "poko", "root", "", null, null);
			}break;
			}
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			$s = "<span style='color:#ff0000'><b>Database Connection Error: " . $e . "</b></span><br/>Please edit the database settings in your applications main '.hx' file<br/>";
			if(Main::$app->debug) {
				php_Lib::hprint($s);
				php_Sys::hexit(1);
			}
			else {
				throw new HException(($e));
			}
		}}}
		if(!$phpMode) {
			Main::$app->execute();
		}
	}
	function __toString() { return 'Main'; }
}
