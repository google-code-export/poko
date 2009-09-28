/*
 * Copyright (c) 2008, TouchMyPixel & contributors
 * Original author : Tony Polinelli <tonyp@touchmypixel.com> 
 * Contributers: Tarwin Stroh-Spijer 
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *   - Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *   - Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in the
 *     documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE TOUCH MY PIXEL & CONTRIBUTERS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE TOUCH MY PIXEL & CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 */

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
			if(data != null){
				groups = data.groups != null ? data.groups.split(",") : new Array();
				id = data.id;
			}else {
				groups = [];
				id = -1;
			}
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
		if (authenticated)
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
	
	public function toString()
	{
		return "User";
	}
}