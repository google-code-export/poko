<?php

class site_cms_modules_base_Users_Groups extends site_cms_modules_base_UsersBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->authenticationRequired = new _hx_array(array("cms_admin"));
		parent::__construct();
		;
	}}
	public $groups;
	public $action;
	public $actionId;
	public function main() {
		parent::main();
		$this->action = $this->app->params->get("action");
		$this->actionId = Std::parseInt($this->app->params->get("id"));
		if($this->action === "delete") {
			if($this->actionId === null) {
				$this->messages->addError("Trying to delete group, no ID given.");
				;
			}
			else {
				try {
					$this->app->getDb()->delete("_users_groups", "id=" . $this->actionId);
					;
				}catch(Exception $�e) {
				$_ex_ = ($�e instanceof HException) ? $�e->e : $�e;
				;
				{ $e = $_ex_;
				{
					$this->messages->addError("Can't delete group.");
					;
				}}}
				if($this->app->getDb()->lastAffectedRows > 0) {
					$this->messages->addMessage("Group deleted.");
					;
				}
				else {
					$this->messages->addWarning("No group to delete.");
					;
				}
				unset($e);
			}
			;
		}
		$this->groups = $this->app->getDb()->request("SELECT * FROM _users_groups");
		;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->�dynamics[$m]) && is_callable($this->�dynamics[$m]))
			return call_user_func_array($this->�dynamics[$m], $a);
		else if('toString' == $m)
			return $this->__toString();
		else
			throw new HException('Unable to call �'.$m.'�');
	}
	function __toString() { return 'site.cms.modules.base.Users_Groups'; }
}
