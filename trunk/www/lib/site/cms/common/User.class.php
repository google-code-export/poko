<?php

class site_cms_common_User {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->authenticated = false;
		$this->groups = new _hx_array(array());
		$this->username = "";
		$this->hasFullGroupDetails = false;
		$this->_isAdmin = false;
		$this->_isSuper = false;
	}}
	public $authenticated;
	public $groups;
	public $id;
	public $username;
	public $hasFullGroupDetails;
	public $_isAdmin;
	public $_isSuper;
	public $name;
	public function authenticate($username) {
		$this->authenticated = true;
		$this->username = $username;
		$this->update();
	}
	public function unauthenticate() {
		$this->authenticated = false;
		$this->username = "";
	}
	public function update() {
		if($this->authenticated) {
			$data = poko_Poko::$instance->getDb()->requestSingle("SELECT * FROM _users WHERE username=" . $this->username);
			if($data !== null) {
				$this->groups = (_hx_field($data, "groups") !== null ? _hx_string_call($data->groups, "split", array(",")) : new _hx_array(array()));
				$this->id = $data->id;
				$this->name = $data->name;
			}
			else {
				$this->groups = new _hx_array(array());
				$this->id = -1;
			}
		}
	}
	public function isAdmin() {
		if(!$this->hasFullGroupDetails) {
			$this->getFullGroupDetails();
		}
		return $this->_isAdmin;
	}
	public function isSuper() {
		if(!$this->hasFullGroupDetails) {
			$this->getFullGroupDetails();
		}
		return $this->_isSuper;
	}
	public function getFullGroupDetails() {
		if($this->authenticated) {
			$sql = "SELECT isAdmin, isSuper FROM _users_groups WHERE";
			$pre = "";
			{
				$_g = 0; $_g1 = $this->groups;
				while($_g < $_g1->length) {
					$s = $_g1[$_g];
					++$_g;
					$sql .= $pre . " stub='" . $s . "'";
					$pre = " OR";
					unset($s);
				}
			}
			$res = poko_Poko::$instance->getDb()->request($sql);
			$this->_isAdmin = false;
			$this->_isSuper = false;
			$»it = $res->iterator();
			while($»it->hasNext()) {
			$a = $»it->next();
			{
				if($a->isAdmin) {
					$this->_isAdmin = true;
				}
				if($a->isSuper) {
					$this->_isSuper = true;
				}
				;
			}
			}
		}
	}
	public function toString() {
		return "User";
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return $this->toString(); }
}
