<?php

class site_cms_modules_base_SettingsTheme extends site_cms_modules_base_SettingsBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->themeDirectory = realpath("") . "/" . "res/cms/theme";
	}}
	public $themes;
	public $currentTheme;
	public $themeDirectory;
	public function main() {
		$this->currentTheme = $this->settings->get("themeCurrent");
		$this->themes = new HList();
		if(is_dir($this->themeDirectory)) {
			{
				$_g = 0; $_g1 = php_FileSystem::readDirectory($this->themeDirectory);
				while($_g < $_g1->length) {
					$d = $_g1[$_g];
					++$_g;
					if($d != "." && $d != ".." && $d != ".svn") {
						$this->themes->add($d);
					}
					unset($d);
				}
			}
		}
		$setTheme = $this->app->params->get("set");
		if($setTheme !== null) {
			if(Lambda::has($this->themes, $setTheme, null)) {
				$this->currentTheme = $setTheme;
				if(file_exists($this->themeDirectory . "/" . $this->currentTheme . "/style.xml")) {
					$xml = Xml::parse(php_io_File::getContent($this->themeDirectory . "/" . $this->currentTheme . "/style.xml"));
					$fast = new haxe_xml_Fast($xml->firstElement());
					$style = _hx_anonymous(array());
					$»it = $fast->getElements();
					while($»it->hasNext()) {
					$e = $»it->next();
					{
						$style->{$e->getName()} = $e->getInnerData();
						;
					}
					}
					$this->app->getDb()->update("_settings", _hx_anonymous(array("value" => haxe_Serializer::run($style))), "`key`='themeStyle'");
				}
				$this->app->getDb()->update("_settings", _hx_anonymous(array("value" => $setTheme)), "`key`='themeCurrent'");
				$this->messages->addMessage("Theme updated to '" . $setTheme . "'");
			}
		}
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.base.SettingsTheme'; }
}
