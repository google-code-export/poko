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

package poko.system;

import haxe.io.Eof;
import php.db.Connection;
import php.db.Mysql;
import php.db.Object;
import php.db.ResultSet;
import php.Exception;
import php.HException;
import php.io.File;
import php.Lib;
import php.Web;

using StringTools;

class Db 
{	
	private var cnx:Connection;
	public var lastError:String;
	public var lastQuery:String;
	
	public var lastAffectedRows:Int;
	public var lastInsertId:Int;
	public var numRows:Int;
	
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
	
	public function escape(s)
	{
		return cnx.escape(s);
	}
	
	public function quote(s)
	{
		return cnx.quote(s);
	}
	
	public function query(sql:String):ResultSet
	{
		lastQuery = sql;
		
		var result:ResultSet  = cnx.request(sql);
		return result;
	}	
	
	public function request(sql:String):List<Dynamic>
	{
		lastQuery = sql;
		
		var result:ResultSet  = cnx.request(sql);
		numRows = result.length;
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
			sql += cnx.quote(d);
			c++;
		}
		
		sql += ")";
		
		lastQuery = sql;
		var request = cnx.request(sql); 
		
		lastInsertId = cnx.lastInsertId();
		
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
				sql += " `"+fieldName+"`=" + cnx.quote(variable);
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
	
	public function getPrimaryKey(table:String):String
	{
		var primaryData = request("SHOW COLUMNS FROM `"+table+"` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
		if (primaryData.length > 0){
			return(primaryData.pop().Field);
		}else {
			return(null);
		}
	}
	
	public function getTables() : Array<String>
	{
		var tables = new Array<String>();
		var result = cnx.request( "SHOW TABLES" );
		for ( row in result )
			tables.push( Reflect.field( row, "Tables_in_" + database ) );
		return tables;
	}
	
	public function importFromFile( filepath : String ) : Void
	{
		var f = File.read( filepath, false );
		try
		{
			var temp = "";
			
			while ( true )
			{
				var line = f.readLine();
				
				if ( line.substr( 0, 2 ) == "--" || line.charAt(0) == "#" || line == "" )
					continue;
					
				temp += line;
				
				if ( line.trim().substr( -1, 1 ) == ";" )
				{
					cnx.request( temp );
					temp = "";
				}
			}
		}
		catch ( e : Eof )
		{
			f.close();
		}
	}
	
	// http://davidwalsh.name/backup-mysql-database-php
	public function export( ?tableStr : String = "*" ) : String
	{		
		var output = "";
		var tables : Array<String>;
		
		if ( tableStr == "*" )
		{
			tables = new Array<String>();
			var result = cnx.request( "SHOW TABLES" );
			for ( row in result )
				tables.push( Reflect.field( row, "Tables_in_" + database ) );
		}
		else
		{
			tables = tableStr.split( "," );
			for ( i in 0 ... tables.length )
				tables[i] = tables[i].trim();
		}
		
		for ( table in tables )
		{
			var result = cnx.request( "SELECT * FROM " + table );
			var numFields = result.nfields;
			
			output += "DROP TABLE IF EXISTS " + table + ";";
			
			var createSql = cnx.request( "SHOW CREATE TABLE " + table ).next();
			//trace( createSql );
			//break;
			output += "\n\n" + Reflect.field( createSql, "Create Table" ) + ";\n\n";
			
			for ( i in 0 ... numFields )
			{
				for ( row in result )
				{
					output += "INSERT INTO " + table + " VALUES (";
					
					var j = 0;
					for ( f in Reflect.fields( row ) )
					{
						var value = Reflect.field( row, f );
						value = untyped __call__( "addslashes", value );
						value = untyped __call__( "ereg_replace", "\n", "\\n", value );
						if ( value != "" && value != null )
							output += '"' + value + '"';
						else
							output += '""';
						if ( j < numFields - 1 )
							output += ",";
						j++;
					}
					output += ");\n";
				}
			}
			output += "\n\n\n";
		}
		
		return output;
	}
}