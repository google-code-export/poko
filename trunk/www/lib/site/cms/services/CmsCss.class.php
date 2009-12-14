<?php

class site_cms_services_CmsCss extends site_cms_CmsController {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $resPath;
	public $imageHeaderBg;
	public $imageHeaderButtonBgUp;
	public $imageHeaderButtonBgOver;
	public $imageHeadingShort;
	public $imageHeadingMedium;
	public $imageHeadingLong;
	public $imageHeadingLoginBg;
	public $imageHeadingLogo;
	public $colorLinkOnDark;
	public $colorLinkOnLight;
	public $sizeLogoWidth;
	public $sizeLogoHeight;
	public $sizeButtonWidth;
	public $sizeButtonHeight;
	public $colorNavigationLinkBgUp;
	public $colorNavigationLinkBgOver;
	public $colorNavigationLinkColor;
	public function main() {
		$settingResPath = $this->app->getDb()->requestSingle("SELECT value FROM _settings WHERE `key`='themeCurrent'");
		$settingLogo = $this->app->getDb()->requestSingle("SELECT value FROM _settings WHERE `key`='cmsLogo'");
		$settingStyleData = $this->app->getDb()->requestSingle("SELECT value FROM _settings WHERE `key`='themeStyle'");
		$settingStyle = _hx_anonymous(array("error" => true));
		try {
			$settingStyle = haxe_Unserializer::run($settingStyleData->value);
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			$this->messages->addWarning("Problem getting style information from the database.");
		}}}
		$this->resPath = "./res/cms/theme/" . $settingResPath->value . "/";
		$this->imageHeadingLogo = "./res/cms/" . $settingLogo->value;
		$this->imageHeaderBg = $this->resPath . "bg.png";
		$this->imageHeaderButtonBgUp = $this->resPath . "button0.png";
		$this->imageHeaderButtonBgOver = $this->resPath . "button1.png";
		$this->imageHeadingShort = $this->resPath . "heading-short-bg.png";
		$this->imageHeadingMedium = $this->resPath . "heading-medium-bg.png";
		$this->imageHeadingLong = $this->resPath . "heading-long-bg.png";
		$this->imageHeadingLoginBg = $this->resPath . "login-bg.png";
		if(_hx_field($settingStyle, "error") !== null) {
			$this->colorLinkOnDark = "#93DD22";
			$this->colorLinkOnLight = "#FF6600";
			$this->colorNavigationLinkBgUp = "#244D49";
			$this->colorNavigationLinkBgOver = "#486460";
			$this->colorNavigationLinkColor = "#fff";
		}
		else {
			$this->colorLinkOnDark = $settingStyle->colorLinkOnDark;
			$this->colorLinkOnLight = $settingStyle->colorLinkOnLight;
			$this->colorNavigationLinkBgUp = $settingStyle->colorNavigationLinkBgUp;
			$this->colorNavigationLinkBgOver = $settingStyle->colorNavigationLinkBgOver;
			$this->colorNavigationLinkColor = $settingStyle->colorNavigationLinkColor;
		}
		$imgSize = poko_utils_GD::getImageSize($this->imageHeadingLogo, null);
		$this->sizeLogoWidth = $imgSize->width;
		$this->sizeLogoHeight = $imgSize->height;
		$imgSize1 = poko_utils_GD::getImageSize($this->imageHeaderButtonBgUp, null);
		$this->sizeButtonWidth = $imgSize1->width;
		$this->sizeButtonHeight = $imgSize1->height;
		$hash = haxe_Md5::encode(Std::string($this->sizeButtonHeight) . Std::string($this->sizeButtonWidth) . Std::string($this->sizeLogoHeight) . Std::string($this->sizeLogoWidth) . $this->imageHeaderBg . $this->imageHeaderButtonBgUp . $this->imageHeaderButtonBgOver . $this->imageHeadingShort . $this->imageHeadingMedium . $this->imageHeadingLong . $this->imageHeadingLoginBg . $this->imageHeadingLogo . $this->colorLinkOnDark . $this->colorLinkOnLight . $this->colorNavigationLinkBgUp . $this->colorNavigationLinkBgOver . $this->colorNavigationLinkColor);
		$dateModified = Date::now();
		$dateModifiedString = DateTools::format($dateModified, "%a, %d %b %Y %H:%M:%S") . " GMT";
		header("Last-Modified" . ": " . $dateModifiedString);
		header("Expires" . ": " . DateTools::format(new Date($dateModified->getFullYear() + 1, $dateModified->getMonth(), $dateModified->getDay(), 0, 0, 0), "%a, %d %b %Y %H:%M:%S") . " GMT");
		header("Cache-Control" . ": " . "public, max-age=31536000");
		header("ETag" . ": " . "\"" . $hash . "\"");
		header("Pragma" . ": " . "");
		header("content-type" . ": " . "text/css");
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.services.CmsCss'; }
}
