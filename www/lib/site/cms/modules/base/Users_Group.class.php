<?php

class site_cms_modules_base_Users_Group extends site_cms_modules_base_UsersBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->authenticationRequired = new _hx_array(array("cms_admin"));
		parent::__construct();
	}}
	public $userData;
	public $action;
	public $actionId;
	public $form1;
	public $heading;
	public function main() {
		$this->action = $this->application->params->get("action");
		$this->actionId = Std::parseInt($this->application->params->get("id"));
		if($this->action != "edit" && $this->action != "add") {
			$this->action = "add";
		}
		$this->heading = (($this->action == "edit") ? "Edit Group" : "Add Group");
		$this->setupForm1();
		if($this->form1->isSubmitted() && $this->form1->isValid()) {
			$this->process();
		}
		parent::main();
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
					}
				}
				$this->application->db->insert("_users_groups", $d);
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e = $_ex_;
			{
				$this->application->messages->addError("Database error.");
			}}}
			if($this->application->db->lastAffectedRows < 1) {
				$this->application->messages->addError("Problem adding group.");
			}
			else {
				$this->application->messages->addMessage("Group added. <a href=\"?request=cms.modules.base.Users_Group&action=edit&id=" . $this->application->db->cnx->lastInsertId() . "\">edit</a>");
				$this->form1->clearData();
			}
		}break;
		case "edit":{
			try {
				$d2 = $this->form1->getData();
				$a2 = php_Web::getParamValues($this->form1->name . "_permissions");
				$d2->isAdmin = "0";
				$d2->isSuper = "0";
				if($a2 !== null) {
					{
						$_g2 = 0;
						while($_g2 < $a2->length) {
							$s2 = $a2[$_g2];
							++$_g2;
							$d2->{$s2} = "1";
							unset($s2);
						}
					}
				}
				$this->application->db->update("_users_groups", $d2, "id=" . $this->form1->getElement("actionId")->value);
			}catch(Exception $»e2) {
			$_ex_2 = ($»e2 instanceof HException) ? $»e2->e : $»e2;
			;
			{ $e2 = $_ex_2;
			{
				$this->application->messages->addError("Database error.");
			}}}
			if($this->application->db->lastAffectedRows < 1) {
				$this->application->messages->addWarning("Nothing changed.");
			}
			else {
				$this->application->messages->addMessage("Group updated.");
			}
		}break;
		}
	}
	public function setupForm1() {
		$groupInfo = _hx_anonymous(array());
		$permissionsListSelected = new _hx_array(array());
		if($this->action == "edit") {
			$groupInfo = $this->application->db->requestSingle("SELECT * FROM `_users_groups` WHERE `id`=" . $this->actionId);
			if($groupInfo->isAdmin) {
				$permissionsListSelected->push("isAdmin");
			}
			if($groupInfo->isSuper) {
				$permissionsListSelected->push("isSuper");
			}
		}
		$permissionsList = new HList();
		$permissionsList->add(_hx_anonymous(array("key" => "isAdmin", "value" => "Admin")));
		$permissionsList->add(_hx_anonymous(array("key" => "isSuper", "value" => "Super")));
		$this->form1 = new poko_form_Form("form1", null, null);
		$this->form1->addElement(new poko_form_elements_Hidden("actionId", $this->actionId, null, null, null), null);
		$this->form1->addElement(new poko_form_elements_Input("stub", "Stub", $groupInfo->stub, true, null, null), null);
		$this->form1->addElement(new poko_form_elements_Input("name", "Name", $groupInfo->name, true, null, null), null);
		$this->form1->addElement(new poko_form_elements_Input("description", "Description", $groupInfo->description, true, null, null), null);
		$this->form1->addElement(new poko_form_elements_CheckboxGroup("permissions", "Permissions", $permissionsList, $permissionsListSelected, null, null), null);
		$this->form1->addElement(new poko_form_elements_Button("submit", "Update", (($this->action == "edit") ? "Update" : "Add"), null), null);
		$this->form1->populateElements();
		$this->form1->setSubmitButton($this->form1->getElement("submit"));
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.base.Users_Group'; }
}
