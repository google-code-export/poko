<?php

class site_cms_CmsController extends poko_controllers_HtmlController {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->authenticate = true;
		$this->authenticationRequired = new _hx_array(array());
	}}
	public $messages;
	public $settings;
	public $userName;
	public $authenticate;
	public $authenticationRequired;
	public $user;
	public function init() {
		parent::init();
		$this->messages = site_cms_common_Messages::load("cmsMessages");
		$this->user = (php_Session::get("user") ? php_Session::get("user") : new site_cms_common_User());
		$this->user->update();
		if($this->authenticate && !$this->user->authenticated) {
			$this->app->redirect("?request=cms.Index");
		}
	}
	public function post() {
		$this->messages->save();
		php_Session::set("user", $this->user);
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.CmsController'; }
}
