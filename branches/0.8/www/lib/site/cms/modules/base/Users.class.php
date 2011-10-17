<?php

class site_cms_modules_base_Users extends site_cms_modules_base_UsersBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->authenticationRequired = new _hx_array(array("cms_admin", "cms_manager"));
		;
	}}
	public $users;
	public $action;
	public $actionId;
	public function main() {
		parent::main();
		$this->action = $this->app->params->get("action");
		$this->actionId = Std::parseInt($this->app->params->get("id"));
		if($this->action === "delete") {
			if($this->actionId === null) {
				$this->messages->addError("Trying to delete, no ID given.");
				;
			}
			else {
				try {
					$this->app->getDb()->delete("_users", "id=" . $this->actionId);
					;
				}catch(Exception $»e) {
				$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
				;
				{ $e = $_ex_;
				{
					$this->messages->addError("Can't delete user.");
					;
				}}}
				haxe_Log::trace($this->app->getDb()->lastAffectedRows, _hx_anonymous(array("fileName" => "Users.hx", "lineNumber" => 61, "className" => "site.cms.modules.base.Users", "methodName" => "main")));
				if($this->app->getDb()->lastAffectedRows > 0) {
					$this->messages->addMessage("User deleted.");
					;
				}
				else {
					$this->messages->addWarning("No user to delete.");
					;
				}
				unset($e);
			}
			;
		}
		$this->view->template = "cms/modules/base/Users.mtt";
		if($this->user->isSuper()) {
			$this->users = $this->app->getDb()->request("SELECT * FROM _users");
			;
		}
		else {
			$this->users = $this->app->getDb()->request("SELECT * FROM _users WHERE groups NOT LIKE '%cms_admin%'");
			;
		}
		;
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
	function __toString() { return 'site.cms.modules.base.Users'; }
}
