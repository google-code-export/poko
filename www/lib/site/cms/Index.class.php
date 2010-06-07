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
		$this->authenticate = false;
		if(_hx_equal($this->application->params->get("logout"), "true")) {
			$this->application->user->unauthenticate();
		}
		if($this->application->user->authenticated) {
			php_Web::redirect("?request=cms.modules.base.SiteView");
		}
		if($this->application->params->get("submitted") !== null) {
			$username = $this->application->db->cnx->quote($this->application->params->get("username"));
			$password = $this->application->db->cnx->quote(haxe_Md5::encode($this->application->params->get("password")));
			if($this->application->db->count("_users", "`username`=" . $username . " AND `password`=" . $password) > 0) {
				$this->application->user->authenticate($username);
				php_Web::redirect(php_Web::getURI() . "?request=cms.modules.base.SiteView");
			}
			else {
				$this->message = "Incorrect user or password";
			}
		}
		if($this->application->params->get("username") !== null) {
			$this->inputUsername = $this->application->params->get("username");
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
