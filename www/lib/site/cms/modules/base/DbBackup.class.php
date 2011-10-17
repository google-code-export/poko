<?php

class site_cms_modules_base_DbBackup extends site_cms_templates_CmsTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->authenticationRequired = new _hx_array(array("cms_admin"));
		parent::__construct();
		;
	}}
	public $backups;
	public $restoreFile;
	public $restoreDate;
	public $confirmRestore;
	public $tables;
	public $executionTime;
	public $listSize;
	public function init() {
		parent::init();
		if($this->app->params->exists("restore")) {
			$this->restoreFile = $this->app->params->get("restore");
			$this->restoreDate = DateTools::format(php_FileSystem::stat("./backup/" . $this->restoreFile)->mtime, "%Y-%b-%d %I:%M:%S %p");
			$this->confirmRestore = site_cms_modules_base_DbBackup_0($this);
			if($this->confirmRestore !== null && file_exists("./backup/" . $this->restoreFile)) {
				$startTime = Date::now()->getTime();
				$fPath = "./backup/" . $this->restoreFile;
				$restoreComment = $this->getFileMetaParam("COMMENT", $fPath);
				$restoreTableStr = $this->getFileMetaParam("TABLES", $fPath);
				$this->createBackup($restoreTableStr, "[Pre-Restore] " . $restoreComment, "prebak-");
				$this->executionTime = (Date::now()->getTime() - $startTime) . "ms";
				unset($startTime,$restoreTableStr,$restoreComment,$fPath);
			}
			return;
			;
		}
		else {
			if($this->app->params->exists("createBackup")) {
				if($this->app->params->get("tables") !== null) {
					$tables = new _hx_array($_POST["tables"]);
					$tableStr = "";
					$i = 0;
					{
						$_g = 0;
						while($_g < $tables->length) {
							$table = $tables[$_g];
							++$_g;
							$tableStr .= $table;
							if($i < $tables->length - 1) {
								$tableStr .= ",";
								;
							}
							$i++;
							unset($table);
						}
						unset($_g);
					}
					$comment = site_cms_modules_base_DbBackup_1($this, $i, $tableStr, $tables);
					$this->createBackup($tableStr, $comment, null);
					unset($tables,$tableStr,$i,$comment);
				}
				else {
					haxe_Log::trace("No tables were selected!", _hx_anonymous(array("fileName" => "DbBackup.hx", "lineNumber" => 107, "className" => "site.cms.modules.base.DbBackup", "methodName" => "init")));
					;
				}
				;
			}
			;
		}
		$this->tables = $this->app->getDb()->getTables();
		$this->listSize = intval(Math::min(10, $this->tables->length));
		$this->backups = new _hx_array(array());
		$files = php_FileSystem::readDirectory("./backup/");
		{
			$_g = 0;
			while($_g < $files->length) {
				$filename = $files[$_g];
				++$_g;
				$f = "./backup/" . $filename;
				$ext = _hx_substr($filename, _hx_last_index_of($filename, ".", null) + 1, null);
				if(is_dir($f) || $ext !== "sql") {
					continue;
					;
				}
				$stat = php_FileSystem::stat($f);
				$size = "";
				if($stat->size < 1024) {
					$size = $stat->size . " bytes";
					;
				}
				else {
					if($stat->size < 1048576) {
						$size = $this->round2($stat->size / 1024, 2) . " KB";
						;
					}
					else {
						$size = $this->round2(($stat->size / 1024) / 1024, 2) . " MB";
						;
					}
					;
				}
				$this->backups->push(_hx_anonymous(array("stat" => $stat, "date" => DateTools::format($stat->mtime, "%Y-%b-%d %I:%M:%S %p"), "size" => $size, "filename" => $filename, "dbHost" => $this->getFileMetaParam("DB_HOST", $f), "dbName" => $this->getFileMetaParam("DB_NAME", $f), "comment" => $this->getFileMetaParam("COMMENT", $f), "url" => $f)));
				unset($stat,$size,$filename,$f,$ext);
			}
			unset($_g);
		}
		$this->backups->sort((isset($this->compareBackup) ? $this->compareBackup: array($this, "compareBackup")));
		unset($files);
	}
	public function createBackup($tableStr, $comment, $fileNamePrefix) {
		if($fileNamePrefix === null) {
			$fileNamePrefix = "";
			;
		}
		$exported = "";
		if($comment !== null && $comment !== "") {
			$exported .= $this->buildMetaParam("COMMENT", $comment) . "\x0A";
			;
		}
		$exported .= $this->buildMetaParam("DB_HOST", $this->app->getDb()->host) . "\x0A";
		$exported .= $this->buildMetaParam("DB_NAME", $this->app->getDb()->database) . "\x0A";
		$exported .= $this->buildMetaParam("TABLES", $tableStr) . "\x0A";
		$exported .= "\x0A" . $this->app->getDb()->export($tableStr);
		$now = Date::now();
		$backupName = (((($this->app->getDb()->database . "-") . DateTools::format($now, "%Y-%m-%d")) . "-") . $now->getTime()) . ".sql";
		php_io_File::putContent(("./backup/" . $fileNamePrefix) . $backupName, $exported);
		unset($now,$exported,$backupName);
	}
	public function getFileMetaParam($paramName, $filePath) {
		$f = php_io_File::read($filePath, false);
		$line = $f->readLine();
		$searchStr = $this->buildMetaParam($paramName, "");
		$result = null;
		try {
			while(_hx_char_at($line, 0) === "#") {
				$index = _hx_index_of($line, $searchStr, null);
				if($index !== -1) {
					$result = _hx_substr($line, strlen($searchStr), null);
					break;
					;
				}
				$line = $f->readLine();
				unset($index);
			}
			;
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		if(($e = $_ex_) instanceof haxe_io_Eof){
			$f->close();
			$f = null;
			;
		} else throw $»e; }
		if($f !== null) {
			$f->close();
			;
		}
		return $result;
		unset($searchStr,$result,$line,$f,$e);
	}
	public function buildMetaParam($paramName, $value) {
		return (("#META_" . strtoupper($paramName)) . "=") . $value;
		;
	}
	public function compareBackup($a, $b) {
		$aa = $a->stat->mtime->getTime();
		$bb = $b->stat->mtime->getTime();
		if($aa === $bb) {
			return 0;
			;
		}
		if($aa > $bb) {
			return -1;
			;
		}
		return 1;
		unset($bb,$aa);
	}
	public function round2($number, $precision) {
		$num = $number;
		$num = $num * Math::pow(10, $precision);
		$num = Math::round($num) / Math::pow(10, $precision);
		return $num;
		unset($num);
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else if('toString' == $m)
			return $this->__toString();
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	static $FILE_META_COMMENT_PREFIX = "#META_";
	static $META_COMMENT = "COMMENT";
	static $META_TABLES = "TABLES";
	static $META_DB_HOST = "DB_HOST";
	static $META_DB_NAME = "DB_NAME";
	static $BACKUP_DIR = "./backup/";
	static $PRE_RESTORE_PREFIX = "[Pre-Restore] ";
	static $PRE_RESTORE_FILE_PREFIX = "prebak-";
	static $DATE_FORMAT = "%Y-%b-%d %I:%M:%S %p";
	static $BACKUP_DATE_FORMAT = "%Y-%m-%d";
	function __toString() { return 'site.cms.modules.base.DbBackup'; }
}
;
function site_cms_modules_base_DbBackup_0(&$»this) {
if($»this->app->params->exists("confirmRestore")) {
	return $»this->app->params->get("confirmRestore");
	;
}
}
function site_cms_modules_base_DbBackup_1(&$»this, &$i, &$tableStr, &$tables) {
if($»this->app->params->exists("comment")) {
	return $»this->app->params->get("comment");
	;
}
}