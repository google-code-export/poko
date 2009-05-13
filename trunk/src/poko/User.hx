package poko;

import poko.Application;

class User 
{
	public var authenticated:Bool;
	public var groups:Array<String>;
	public var id:Int;
	public var username:String;
	
	private var hasFullGroupDetails:Bool;
	private var _isAdmin:Bool;
	private var _isSuper:Bool;
	
	//public var isAdmin(getIsAdmin, null):Bool;
	//public var isSuper(getIsSuper, null):Bool;
	
	public function new() 		
	{
		authenticated = false;
		groups = new Array();
		username = "";
		
		hasFullGroupDetails = false;
		_isAdmin = false;
		_isSuper = false;
	}
	
	public function authenticate(username:String)
	{
		authenticated = true;
		this.username = username;
		
		update();
	}
	
	public function unauthenticate()
	{
		authenticated = false;
		this.username = "";
	}
	
	public function update()
	{
		if (authenticated) {
			var data = Application.instance.db.requestSingle("SELECT * FROM _users WHERE username=" + username);
			groups = data.groups != null ? data.groups.split(",") : new Array();
			id = data.id;
		}
	}
	
	public function isAdmin():Bool
	{
		if (!hasFullGroupDetails)
			getFullGroupDetails();
		return _isAdmin;
	}
	
	public function isSuper():Bool
	{
		if (!hasFullGroupDetails)
			getFullGroupDetails();
		return _isSuper;
	}	
	
	private function getFullGroupDetails():Void
	{
		var sql:String = "SELECT isAdmin, isSuper FROM _users_groups WHERE";
		var pre:String = "";
		for (s in groups) {
			sql += pre + " stub='" + s + "'";
			pre = " OR";
		}
		var res = Application.instance.db.request(sql);
		
		_isAdmin = false;
		_isSuper = false;
		for (a in res) {
			if (a.isAdmin) _isAdmin = true;
			if (a.isSuper) _isSuper = true;
		}
	}
}