/**
 * ...
 * @author Matt Benton
 */

package site.cms.modules.base;

import haxe.Md5;
import php.FileSystem;
import php.io.File;
import php.Lib;
import poko.system.Config;
import site.cms.templates.CmsTemplate;

using DateTools;

typedef DbBackupData =
{
	var date : String;
	var size : String;
	var stat : FileStat;
	var filename : String;
	var comment : String;
	var url : String;
}

class DbBackup extends CmsTemplate
{
	public var backups : Array<DbBackupData>;
	public var restoreFile : String;
	public var restoreDate : String;
	public var confirmRestore : Bool;
	public var tables : Array<String>;
	public var executionTime : String;
	
	static inline var RESTORE_COMMENT : String = "# RESTORE_COMMENT ";
	
	override public function init()
	{
		super.init();
		
		untyped __call__( "date_default_timezone_set", "Australia/Melbourne" );
		
		var backupPath = "./backup/";
		var dateFormat = "%Y-%b-%d %I:%M:%S %p";
		var backupDateFormat = "%Y-%m-%d";
		
		if ( app.params.exists("restore") )
		{
			restoreFile = app.params.get("restore");
			restoreDate = FileSystem.stat( backupPath + restoreFile ).mtime.format(dateFormat);
			//trace( "restore: " + restoreFile );
			
			confirmRestore = app.params.exists("confirmRestore") ? app.params.get("confirmRestore") : null;
			
			if ( confirmRestore != null && FileSystem.exists(backupPath + restoreFile) )
			{
				var startTime = Date.now().getTime();
				//var sql = File.getContent(backupPath + restoreFile);
				
				app.db.importFromFile( backupPath + restoreFile );
				
				//app.db.request( sql );
				executionTime = (Date.now().getTime() - startTime) + "ms";
			}
			
			return;
		}		
		else if ( app.params.exists("createBackup") )
		{
			//trace("createBackup");
			var tables = Lib.toHaxeArray( untyped __var__( "_POST", "tables" ) );
			var tableStr = "";
			var i = 0;
			for ( table in tables )
			{
				tableStr += table;
				if ( i < tables.length - 1 )
					tableStr += ",";
				i++;
			}
			
			var exported = app.db.export( tableStr );
			
			var comment = app.params.exists("comment") ? app.params.get("comment") : null;
			if ( comment != null && comment != "" )
				exported = RESTORE_COMMENT + comment + "\n\n" + exported ;
				
			var now = Date.now();
			//var backupName = app.db.database + "-" + now.format(backupDateFormat) + "-" + Md5.encode(tableStr) + ".sql";
			var backupName = app.db.database + "-" + now.format(backupDateFormat) + "-" + now.getTime() + ".sql";
				
			File.putContent( backupPath + backupName, exported );
		}
		
		tables = app.db.getTables();
		
		backups = new Array<DbBackupData>();
		
		var files = FileSystem.readDirectory( backupPath );
		
		for ( filename in files )
		{
			var f : String = backupPath + filename;
			
			var ext = filename.substr( filename.lastIndexOf( "." ) + 1 );

			if ( FileSystem.isDirectory( f ) || ext != "sql" ) 
				continue;
				
			var stat = FileSystem.stat( f );
			var size = "";
			if ( stat.size < 1024 )
				size = stat.size + " bytes";
			else if ( stat.size < 1024 * 1024 )
				size = round2( stat.size / 1024, 2 ) + " KB";
			else
				size = round2( stat.size / 1024 / 1024, 2 ) + " MB";
				
			backups.push( { 
				stat : stat, 
				date : stat.mtime.format(dateFormat), 
				size : size,
				filename : filename,
				comment : getFileComment( f ),
				url : f
			} );
		}
		
		backups.sort( compareBackup );
	}
	
	function compareBackup( a : DbBackupData, b : DbBackupData ) : Int
	{
		var aa = a.stat.mtime.getTime();
		var bb = b.stat.mtime.getTime();
		if ( aa == bb )
			return 0;
		if ( aa > bb )
			return -1;
		return 1;
	}
	
	function getFileComment( filepath : String ) : String
	{
		var f = File.read( filepath, false );
		var line = f.readLine();
		
		if ( line.indexOf( RESTORE_COMMENT ) == 0 )
			line = line.substr( RESTORE_COMMENT.length );
		else
			line = "";
		
		return line;
	}
	
	function round2( number : Float, precision : Int) : Float 
	{
		var num = number;
		num = num * Math.pow(10, precision);
		num = Math.round( num ) / Math.pow(10, precision);
		return num;
	}
	
	/*public static function backup( config : Config, ?tables : String = "*" ) : String
	{
		
	}*/
}