<?php

class site_Config extends poko_system_Config {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct(null);
		switch($_SERVER['SERVER_NAME']) {
		case "touchmypixel.com":case "staging.touchmypixel.com":{
			$this->database_host = "localhost";
			$this->database_database = "touchmyp_poko";
			$this->database_user = "touchmyp_poko";
			$this->database_password = "poko";
			$this->sessionName = "poko_cms";
		}break;
		default:{
			$this->database_host = "192.168.1.80";
			$this->database_database = "poko";
			$this->database_user = "root";
			$this->database_password = "";
			$this->sessionName = "poko_cms";
		}break;
		}
	}}
	function __toString() { return 'site.Config'; }
}
