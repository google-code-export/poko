<?php

class site_cms_modules_email_Settings extends site_cms_modules_email_EmailBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->authenticationRequired = new _hx_array(array("cms_admin"));
		parent::__construct();
		;
	}}
	public $tables;
	public $form;
	public $jsBind;
	public function init() {
		parent::init();
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.email.js.JsSettings");
		$this->remoting->addObject("api", _hx_anonymous(array("getTableFields" => (isset($this->getTableFields) ? $this->getTableFields: array($this, "getTableFields")))), null);
		;
	}
	public function main() {
		parent::main();
		$this->setupLeftNav();
		$this->form = new poko_form_Form("form", null, null);
		$emailSettings = $this->loadSettings();
		$userTable = new poko_form_elements_Selectbox("userTable", "User Table", null, $emailSettings->userTable, null, null, null);
		$userTable->onChange = $this->jsBind->getRawCall("onChangeSelectbox(this)");
		$this->tables = $this->app->getDb()->getTables();
		{
			$_g = 0; $_g1 = $this->tables;
			while($_g < $_g1->length) {
				$table = $_g1[$_g];
				++$_g;
				$userTable->data->add(_hx_anonymous(array("key" => $table, "value" => $table)));
				unset($table);
			}
			unset($_g1,$_g);
		}
		$this->form->addElement($userTable, null);
		$idField = new poko_form_elements_Selectbox("idField", "ID Field", null, $emailSettings->idField, null, null, null);
		$this->form->addElement($idField, null);
		$emailField = new poko_form_elements_Selectbox("emailField", "Email Field", null, $emailSettings->emailField, null, null, null);
		$this->form->addElement($emailField, null);
		$nameField = new poko_form_elements_Selectbox("nameField", "Name Field", null, $emailSettings->nameField, null, null, null);
		$this->form->addElement($nameField, null);
		$this->form->setSubmitButton($this->form->addElement(new poko_form_elements_Button("submit", "Submit", null, null), null));
		$this->form->populateElements(null);
		if(_hx_field($userTable, "value") !== null && !_hx_equal($userTable->value, "")) {
			try {
				$columns = $this->app->getDb()->request(("SHOW COLUMNS FROM `" . $userTable->value) . "`");
				$fields = new HList();
				if(null == $columns) throw new HException('null iterable');
				$»it = $columns->iterator();
				while($»it->hasNext()) {
				$col = $»it->next();
				{
					$field = Reflect::field($col, "Field");
					$fields->add(_hx_anonymous(array("key" => $field, "value" => $field)));
					unset($field);
				}
				}
				$idField->data = $emailField->data = $nameField->data = $fields;
				unset($fields,$columns);
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			{ $e = $_ex_;
			{
				$this->messages->addWarning("Table does not exist anymore.");
				;
			}}}
			unset($e);
		}
		if($this->form->isSubmitted() && $this->form->isValid()) {
			$data = $this->form->getData();
			$this->saveSettings($data);
			unset($data);
		}
		unset($userTable,$nameField,$idField,$emailSettings,$emailField);
	}
	public function getTableFields($table) {
		if($table !== null && $table !== "") {
			$columns = $this->app->getDb()->request(("SHOW COLUMNS FROM `" . $table) . "`");
			$fields = new HList();
			if(null == $columns) throw new HException('null iterable');
			$»it = $columns->iterator();
			while($»it->hasNext()) {
			$col = $»it->next();
			$fields->add(Reflect::field($col, "Field"));
			}
			return $fields;
			unset($fields,$columns);
		}
		return null;
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
	function __toString() { return 'site.cms.modules.email.Settings'; }
}
