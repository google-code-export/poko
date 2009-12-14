<?php

class site_cms_modules_base_User extends site_cms_modules_base_UsersBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->authenticationRequired = new _hx_array(array("cms_admin", "cms_manager"));
	}}
	public $userData;
	public $action;
	public $actionId;
	public $form1;
	public $heading;
	public function main() {
		$this->action = $this->app->params->get("action");
		$this->actionId = Std::parseInt($this->app->params->get("id"));
		if($this->action != "edit" && $this->action != "add") {
			$this->action = "add";
		}
		$this->heading = (($this->action == "edit") ? "Edit User" : "Add User");
		$this->setupForm1();
		if($this->form1->isSubmitted() && $this->form1->isValid()) {
			$this->process();
		}
		parent::main();
	}
	public function process() {
		switch($this->action) {
		case "add":{
			$d = $this->form1->getData();
			$exists = $this->app->getDb()->exists("_users", "`username`=\"" . $d->username . "\"");
			if(!$exists) {
				try {
					$s = php_Web::getParamValues($this->form1->name . "_groups");
					$a = (($s !== null) ? $s->join(",") : "");
					$d->groups = $a;
					$d->password = haxe_Md5::encode($d->password);
					$d->added = Date::now();
					$this->app->getDb()->insert("_users", $d);
				}catch(Exception $»e) {
				$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
				;
				{ $e = $_ex_;
				{
					$this->messages->addError("Database error.");
				}}}
				if($this->app->getDb()->lastAffectedRows < 1) {
					$this->messages->addError("Problem adding user.");
				}
				else {
					$this->messages->addMessage("User added. <a href=\"?request=cms.modules.base.User&action=edit&id=" . $this->app->getDb()->cnx->lastInsertId() . "\">edit</a>");
					$this->form1->clearData();
				}
			}
			else {
				$this->messages->addError("User '" . $d->username . "' aready exists");
			}
		}break;
		case "edit":{
			$d2 = $this->form1->getData();
			try {
				$s2 = php_Web::getParamValues($this->form1->name . "_groups");
				$a2 = (($s2 !== null) ? $s2->join(",") : "");
				$d2->groups = $a2;
				$oldPassword = $this->app->getDb()->requestSingle("SELECT password FROM `_users` WHERE id=" . $this->actionId)->password;
				if(!_hx_equal($d2->password, $oldPassword)) {
					$d2->password = haxe_Md5::encode($d2->password);
				}
				$this->app->getDb()->update("_users", $d2, "id=" . $this->form1->getElement("actionId")->value);
			}catch(Exception $»e2) {
			$_ex_2 = ($»e2 instanceof HException) ? $»e2->e : $»e2;
			;
			{ $e2 = $_ex_2;
			{
				if($this->app->getDb()->exists("_users", "`username`=\"" . $d2->username . "\"")) {
					$this->messages->addError("Another user '" . $d2->username . "' already exists");
				}
				else {
					$this->messages->addError("Database error.");
				}
			}}}
			if($this->app->getDb()->lastAffectedRows < 1) {
				$this->messages->addWarning("Nothing changed.");
			}
			else {
				$this->messages->addMessage("User updated.");
			}
		}break;
		}
	}
	public function setupForm1() {
		$userInfo = _hx_anonymous(array());
		$groupsSelected = new _hx_array(array());
		$groups = null;
		$sql = null;
		if(!$this->user->isSuper()) {
			$sql = "SELECT `stub` AS 'key', `name` AS 'value' FROM _users_groups WHERE isAdmin=0 AND isSuper=0";
			$groups = $this->app->getDb()->request($sql);
		}
		else {
			$sql = "SELECT `stub` AS 'key', `name` AS 'value' FROM _users_groups";
			$groups = $this->app->getDb()->request($sql);
		}
		if($this->action == "edit") {
			$userInfo = $this->app->getDb()->requestSingle("SELECT * FROM `_users` WHERE `id`=" . $this->actionId);
			$groupsSelected = _hx_string_call($userInfo->groups, "split", array(","));
		}
		$this->form1 = new poko_form_Form("form1", null, null);
		$this->form1->addElement(new poko_form_elements_Hidden("actionId", $this->actionId, null, null, null), null);
		$this->form1->addElement(new poko_form_elements_Input("username", "Username", $userInfo->username, true, null, null), null);
		$pass = new poko_form_elements_Input("password", "Password", $userInfo->password, true, null, null);
		$pass->password = true;
		$this->form1->addElement($pass, null);
		$this->form1->addElement(new poko_form_elements_Input("name", "Name", $userInfo->name, true, null, null), null);
		$this->form1->addElement(new poko_form_elements_Input("email", "Email", $userInfo->email, true, null, null), null);
		$this->form1->addElement(new poko_form_elements_CheckboxGroup("groups", "Group", $groups, $groupsSelected, null, null), null);
		$this->form1->addElement(new poko_form_elements_Button("submit", "Update", (($this->action == "edit") ? "Update" : "Add"), null), null);
		$this->form1->populateElements();
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.base.User'; }
}
