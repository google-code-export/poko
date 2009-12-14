<?php

class poko_system_Config {
	public function __construct($debug) {
		if( !php_Boot::$skip_constructor ) {
		$env = php_Sys::environment();
		$this->applicationPath = dirname($_SERVER["SCRIPT_FILENAME"]) . "/";
		if($env->exists("SCRIPT_NAME")) {
			$parts = _hx_explode("/", $env->get("SCRIPT_NAME"));
			$this->indexFile = $parts[$parts->length - 1];
		}
		else {
			throw new HException("indexFile cannot be auto-detected. Please set it in \"site/Config.hx\".");
		}
		if($env->exists("SCRIPT_NAME")) {
			$script = $env->get("SCRIPT_NAME");
			$this->indexPath = _hx_substr($script, 0, _hx_last_index_of($script, "/", null) + 1);
		}
		else {
			throw new HException("indexPath cannot be auto-detected. Please set it in \"site/Config.hx\".");
		}
		$index = $this->indexPath . $this->indexFile;
		if($env->exists("HTTP_HOST")) {
			$this->siteUrl = ($env->exists("HTTPS") && $env->get("HTTPS") == "on" ? "https" : "http");
			$this->siteUrl .= "://" . $env->get("HTTP_HOST") . $index;
		}
		else {
			$this->siteUrl = $index;
		}
		$this->viewPath = $this->applicationPath . "views/";
		$this->defaultController = "Index";
		$this->defaultAction = "main";
		$this->database_host = "localhost";
		$this->database_port = "3306";
		$this->database_user = "root";
		$this->database_password = "";
		$this->database_database = "";
		$this->sessionName = "poko";
		if($debug !== null) {
			$this->dumpEnvironment($debug);
		}
	}}
	public $development;
	public $controllerPackage;
	public $indexFile;
	public $indexPath;
	public $siteUrl;
	public $applicationPath;
	public $viewPath;
	public $permittedUriChars;
	public $logDateFormat;
	public $errorPage;
	public $error404Page;
	public $defaultController;
	public $defaultAction;
	public $encryptionKey;
	public $sessionName;
	public $database_host;
	public $database_port;
	public $database_user;
	public $database_password;
	public $database_database;
	public function dumpEnvironment($logFile) {
		$date = DateTools::format(Date::now(), "%Y-%m-%d %H:%M:%S");
		$output = "";
		$output .= "*** [" . $date . "] Start of dump\x0A";
		$output .= "\x0AhaXigniter configuration:\x0A\x0A";
		{
			$_g = 0; $_g1 = Reflect::fields($this);
			while($_g < $_g1->length) {
				$field = $_g1[$_g];
				++$_g;
				if($field == "encryptionKey") {
					continue;
				}
				$output .= $field . ": '" . Reflect::field($this, $field) . "'\x0A";
				unset($field);
			}
		}
		$output .= "\x0AhaXe web environment ";
		$output .= "(PHP)";
		$output .= ":\x0A\x0A";
		$output .= "getCwd(): '" . dirname($_SERVER["SCRIPT_FILENAME"]) . "/" . "'\x0A";
		$output .= "getHostName(): '" . $_SERVER['SERVER_NAME'] . "'\x0A";
		$output .= "getURI(): '" . php_Web::getURI() . "'\x0A";
		$output .= "getParamsString(): '" . $_SERVER['QUERY_STRING'] . "'\x0A";
		$output .= "\x0AServer environment:\x0A\x0A";
		$�it = php_Sys::environment()->keys();
		while($�it->hasNext()) {
		$field2 = $�it->next();
		{
			$output .= $field2 . ": '" . php_Sys::environment()->get($field2) . "'\x0A";
			;
		}
		}
		$output .= "\x0APHP environment:\x0A\x0A";
		ob_start();
		foreach($_SERVER as $_dk => $_dv) echo "$_dk: '$_dv'
";;
		$output .= ob_get_clean();
		$output .= "\x0A*** End of dump";
		if(!Std::is($logFile, _hx_qtype("String"))) {
			$output = "<hr><pre>" . $output . "</pre><hr>";
			php_Lib::hprint($output);
		}
		else {
			if(!file_exists($logFile)) {
				php_io_File::putContent($logFile, $output);
			}
		}
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->�dynamics[$m]) && is_callable($this->�dynamics[$m]))
			return call_user_func_array($this->�dynamics[$m], $a);
		else
			throw new HException('Unable to call �'.$m.'�');
	}
	function __toString() { return 'poko.system.Config'; }
}
