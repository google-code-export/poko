<?php

class site_cms_Index extends site_cms_templates_CmsTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->authenticate = false;
		$this->message = $this->inputUsername = "";
	}}
	public $message;
	public $inputUsername;
	public function main() {
		if(_hx_equal($this->app->params->get("logout"), "true")) {
			$this->user->unauthenticate();
		}
		if($this->user->authenticated) {
			$this->app->redirect("?request=cms.modules.base.SiteView");
		}
		if($this->app->params->get("submitted") !== null) {
			$username = $this->app->getDb()->cnx->quote($this->app->params->get("username"));
			$password = $this->app->getDb()->cnx->quote(haxe_Md5::encode($this->app->params->get("password")));
			if($this->app->getDb()->count("_users", "`username`=" . $username . " AND `password`=" . $password) > 0) {
				$this->user->authenticate($username);
				php_Web::redirect(php_Web::getURI() . "?request=cms.modules.base.SiteView");
			}
			else {
				$this->message = "Incorrect user or password";
			}
		}
		if($this->app->params->get("username") !== null) {
			$this->inputUsername = $this->app->params->get("username");
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
	function __toString() { return 'site.cms.Index'; }
}
