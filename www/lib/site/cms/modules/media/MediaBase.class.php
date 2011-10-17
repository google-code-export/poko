<?php

class site_cms_modules_media_MediaBase extends site_cms_templates_CmsTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $imageRoot;
	public function init() {
		parent::init();
		$this->imageRoot = "./res/media/galleries";
		;
	}
	public function setupLeftNav() {
		$this->leftNavigation->addSection("Galleries", null);
		$dir = php_FileSystem::readDirectory($this->imageRoot);
		{
			$_g = 0;
			while($_g < $dir->length) {
				$d = $dir[$_g];
				++$_g;
				if(is_dir(($this->imageRoot . "/") . $d) && $d !== "." && $d !== ".." && $d !== ".svn") {
					$this->leftNavigation->addLink("Galleries", $d, "cms.modules.media.Gallery&name=" . $d, null, null, null);
					;
				}
				unset($d);
			}
			unset($_g);
		}
		if($this->user->isAdmin() || $this->user->isSuper()) {
			$this->leftNavigation->footer = "<a href=\"?request=cms.modules.media.Galleries&manage=true\">Manage Galleries</a>";
			;
		}
		unset($dir);
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
	function __toString() { return 'site.cms.modules.media.MediaBase'; }
}
