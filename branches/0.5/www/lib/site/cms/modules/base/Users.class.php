<?php

class site_cms_modules_base_Users extends site_cms_modules_base_UsersBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->authenticationRequired = new _hx_array(array("cms_admin", "cms_manager"));
	}}
	public $users;
	public $action;
	public $actionId;
	public function main() {
		parent::main();
		$this->action = $this->application->params->get("action");
		$this->actionId = Std::parseInt($this->application->params->get("id"));
		if($this->action == "delete") {
			if($this->actionId === null) {
				$this->application->messages->addError("Trying to delete, no ID given.");
			}
			else {
				try {
					$this->application->db->delete("_users", "id=" . $this->actionId);
				}catch(Exception $�e) {
				$_ex_ = ($�e instanceof HException) ? $�e->e : $�e;
				;
				{ $e = $_ex_;
				{
					$this->application->messages->addError("Can't delete user.");
				}}}
				if($this->application->db->lastAffectedRows > 0) {
					$this->application->messages->addMessage("User deleted.");
				}
				else {
					$this->application->messages->addWarning("No user to delete.");
				}
			}
		}
		$this->setContentTemplate("cms/modules/base/Users.mtt");
		$this->users = $this->application->db->request("SELECT * FROM _users");
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->�dynamics[$m]) && is_callable($this->�dynamics[$m]))
			return call_user_func_array($this->�dynamics[$m], $a);
		else
			throw new HException('Unable to call �'.$m.'�');
	}
	function __toString() { return 'site.cms.modules.base.Users'; }
}