<?php

class site_cms_modules_base_Users_Group extends site_cms_modules_base_UsersBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->authenticationRequired = new _hx_array(array("cms_admin"));
		parent::__construct();
		;
	}}
	public $userData;
	public $action;
	public $actionId;
	public $form1;
	public $heading;
	public function main() {
		$this->action = $this->app->params->get("action");
		$this->actionId = Std::parseInt($this->app->params->get("id"));
		if($this->action !== "edit" && $this->action !== "add") {
			$this->action = "add";
			;
		}
		$this->heading = site_cms_modules_base_Users_Group_0($this);
		$this->setupForm1();
		if($this->form1->isSubmitted() && $this->form1->isValid()) {
			$this->process();
			;
		}
		parent::main();
		;
	}
	public function process() {
		switch($this->action) {
		case "add":{
			try {
				$d = $this->form1->getData();
				$a = php_Web::getParamValues($this->form1->name . "_permissions");
				$d->isAdmin = "0";
				$d->isSuper = "0";
				if($a !== null) {
					{
						$_g = 0;
						while($_g < $a->length) {
							$s = $a[$_g];
							++$_g;
							$d->{$s} = "1";
							unset($s);
						}
						unset($_g);
					}
					;
				}
				$this->app->getDb()->insert("_users_groups", $d);
				unset($d,$a);
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e = $_ex_;
			{
				$this->messages->addError("Database error.");
				;
			}}}
			if($this->app->getDb()->lastAffectedRows < 1) {
				$this->messages->addError("Problem adding group.");
				;
			}
			else {
				$this->messages->addMessage(("Group added. <a href=\"?request=cms.modules.base.Users_Group&action=edit&id=" . $this->app->getDb()->lastInsertId) . "\">edit</a>");
				$this->form1->clearData();
				;
			}
			unset($e);
		}break;
		case "edit":{
			try {
				$d = $this->form1->getData();
				$a = php_Web::getParamValues($this->form1->name . "_permissions");
				$d->isAdmin = "0";
				$d->isSuper = "0";
				if($a !== null) {
					{
						$_g = 0;
						while($_g < $a->length) {
							$s = $a[$_g];
							++$_g;
							$d->{$s} = "1";
							unset($s);
						}
						unset($_g);
					}
					;
				}
				$this->app->getDb()->update("_users_groups", $d, "id=" . $this->form1->getElement("actionId")->value);
				unset($d,$a);
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e = $_ex_;
			{
				$this->messages->addError("Database error.");
				;
			}}}
			if($this->app->getDb()->lastAffectedRows < 1) {
				$this->messages->addWarning("Nothing changed.");
				;
			}
			else {
				$this->messages->addMessage("Group updated.");
				;
			}
			unset($e);
		}break;
		}
		;
	}
	public function setupForm1() {
		$groupInfo = _hx_anonymous(array());
		$permissionsListSelected = new _hx_array(array());
		if($this->action === "edit") {
			$groupInfo = $this->app->getDb()->requestSingle("SELECT * FROM `_users_groups` WHERE `id`=" . $this->actionId);
			if($groupInfo->isAdmin) {
				$permissionsListSelected->push("isAdmin");
				;
			}
			if($groupInfo->isSuper) {
				$permissionsListSelected->push("isSuper");
				;
			}
			;
		}
		$permissionsList = new HList();
		$permissionsList->add(_hx_anonymous(array("key" => "Admin", "value" => "isAdmin")));
		$permissionsList->add(_hx_anonymous(array("key" => "Super", "value" => "isSuper")));
		$this->form1 = new poko_form_Form("form1", null, null);
		$this->form1->addElement(new poko_form_elements_Hidden("actionId", $this->actionId, null, null, null), null);
		$this->form1->addElement(new poko_form_elements_Input("stub", "Stub", $groupInfo->stub, true, null, null), null);
		$this->form1->addElement(new poko_form_elements_Input("name", "Name", $groupInfo->name, true, null, null), null);
		$this->form1->addElement(new poko_form_elements_Input("description", "Description", $groupInfo->description, true, null, null), null);
		$this->form1->addElement(new poko_form_elements_CheckboxGroup("permissions", "Permissions", $permissionsList, $permissionsListSelected, null, null), null);
		$this->form1->addElement(new poko_form_elements_Button("submit", "Update", site_cms_modules_base_Users_Group_1($this, $groupInfo, $permissionsList, $permissionsListSelected), null), null);
		$this->form1->populateElements(null);
		$this->form1->setSubmitButton($this->form1->getElement("submit"));
		unset($permissionsListSelected,$permissionsList,$groupInfo);
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
	function __toString() { return 'site.cms.modules.base.Users_Group'; }
}
;
function site_cms_modules_base_Users_Group_0(&$»this) {
if($»this->action === "edit") {
	return "Edit Group";
	;
}
else {
	return "Add Group";
	;
}
}
function site_cms_modules_base_Users_Group_1(&$»this, &$groupInfo, &$permissionsList, &$permissionsListSelected) {
if($»this->action === "edit") {
	return "Update";
	;
}
else {
	return "Add";
	;
}
}