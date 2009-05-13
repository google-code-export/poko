/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko;

import php.db.Connection;
import php.db.Mysql;
import php.db.Object;
import php.db.ResultSet;
import php.Exception;
import php.HException;
import php.Lib;
import php.Web;

class Db 
{	
	public var cnx:Connection;
	public var lastError:String;
	public var lastQuery:String;
	public var lastAffectedRows:Int;
	
	public var host:String;
	public var database:String;
	public var user:String;
	public var password:String;
	public var port:Int;
	public var socket:String;
	
	public function new() 
	{
	}
	
	public function connect(host:String, database:String, user:String, password:String, ?port:Int=null, ?socket:String=null)
	{
		cnx = Mysql.connect( {
			
			host : host,
			port : port,
			user : user,
			pass : password,
			socket : socket,
			database : database
		});
		this.host = host;
		this.database = database;
		this.user = user;
		this.password = password;
		this.port = port;
		this.socket = socket;
	}
	
	public function request(sql:String):List<Dynamic>
	{
		lastQuery = sql;
		
		var result:ResultSet  = cnx.request(sql);
		return result.results();
	}
	
	public function requestSingle(sql:String):Dynamic
	{
		lastQuery = sql;
		var result:ResultSet = cnx.request(sql);
		
		return result.next();
	}
	
	public function insert(table:String, data:Dynamic):Bool
	{
		var fields:ResultSet = cnx.request("SHOW FIELDS from `" + table + "`");
		if (fields.length == 0) {
			lastError = "DB UPDATE ERROR";
			return false;
		}
		
		var sql = "INSERT INTO `" + table + "` "; 
		var fieldNames:Array<String> = new Array();
		var fieldData:Array<String> = new Array();
		
		for (field in fields) 
		{
			var fieldName:String = field.Field;
			var variable:Dynamic = (Std.is(data, Hash))  ? data.get(fieldName) : Reflect.field(data, fieldName);
			
			if (variable != null) 
			{
				fieldNames.push(fieldName);
				fieldData.push(variable);
			}
		}
		
		sql += "(";
		
		var c:Int = 0;
		for (f in fieldNames) {
			if (c > 0) sql += " , ";
			sql += "`" + f + "`";
			c++;
		}
		
		sql += ") VALUES (";
		
		var c = 0;
		for (d in fieldData) {
			if (c > 0) sql += " , ";
			sql += "\"" + cnx.escape(d) + "\"";
			c++;
		}
		
		sql += ")";
		
		lastQuery = sql;
		var request = cnx.request(sql);
		lastAffectedRows = request.length;
		
		return true;
	}
	
	public function update(table:String, data:Dynamic, where:String):Bool
	{
		var sql = "SHOW FIELDS from `" + table + "`";
		var fields:ResultSet = null;
		try {
			
			fields = cnx.request(sql);
		} catch (e:Dynamic)
		{
			trace(sql);
			throw(e);
		}
		
		
		if (fields.length == 0) {
			lastError = "DB UPDATE ERROR";
			return false;
		}
		
		var sql = "UPDATE `" + table + "` SET "; 
		var c:Int = 0;
		for (field in fields) 
		{
			var fieldName:String = field.Field;
			var variable:Dynamic = (Std.is(data, Hash))  ? data.get(fieldName) : Reflect.field(data, fieldName);
			
			if (variable != null) 
			{
				if (c > 0) sql += " , ";
				sql += " `"+fieldName+"`=\"" + cnx.escape(variable) + "\"";
				c++;
			}
		}
		
		sql += " WHERE " + where;
		
		lastQuery = sql;
		
		lastAffectedRows = cnx.request(sql).length; 		
		
		return true;
	}
	
	
	
	public function delete(table:String, where:String):Bool
	{
		var sql = "DELETE FROM `"+table+"` WHERE " + where;
		
		lastQuery = sql;
		
		lastAffectedRows = cnx.request(sql).length; 
		
		return true;
	}
	
	
	public function count(table:String, ?where:String = ""):Int
	{
		var sql = "SELECT COUNT(*) as count FROM `" + table + "` WHERE " + where;
		
		lastQuery = sql;
		
		var result:ResultSet = cnx.request(sql); 
		
		return result.next().count;
	}
	
	public function exists(table:String, ?where:String = ""):Bool
	{
		return count(table, where) > 0;
	}
}