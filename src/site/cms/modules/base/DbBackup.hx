/**
 * ...
 * @author Matt Benton
 */

package site.cms.modules.base;

import haxe.io.Eof;
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
	var dbHost : String;
	var dbName : String;
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
	public var listSize : Int;
	
	static inline var FILE_META_COMMENT_PREFIX : String = "#META_";
	static inline var META_COMMENT : String = "COMMENT";
	static inline var META_TABLES : String = "TABLES";
	static inline var META_DB_HOST : String = "DB_HOST";
	static inline var META_DB_NAME : String = "DB_NAME";
	
	static inline var BACKUP_DIR : String = "./backup/";
	static inline var PRE_RESTORE_PREFIX : String = "[Pre-Restore] ";
	static inline var PRE_RESTORE_FILE_PREFIX : String = "prebak-";
	static inline var DATE_FORMAT : String = "%Y-%b-%d %I:%M:%S %p";
	static inline var BACKUP_DATE_FORMAT : String = "%Y-%m-%d";
	
	public function new()
	{
		authenticationRequired = ["cms_admin"];
		super();
	}
	
	override public function init()
	{
		super.init();
		
		if ( app.params.exists("restore") )
		{
			restoreFile = app.params.get("restore");
			restoreDate = FileSystem.stat(BACKUP_DIR + restoreFile).mtime.format(DATE_FORMAT);
			//trace( "restore: " + restoreFile );
			
			confirmRestore = app.params.exists("confirmRestore") ? app.params.get("confirmRestore") : null;
			
			if ( confirmRestore != null && FileSystem.exists(BACKUP_DIR + restoreFile) )
			{
				var startTime = Date.now().getTime();
				
				var fPath = BACKUP_DIR + restoreFile;
				
				var restoreComment = getFileMetaParam(META_COMMENT, fPath);
				var restoreTableStr = getFileMetaParam(META_TABLES, fPath);
				createBackup(restoreTableStr, PRE_RESTORE_PREFIX + restoreComment, PRE_RESTORE_FILE_PREFIX);
				
				//app.db.importFromFile(fPath);
				
				executionTime = (Date.now().getTime() - startTime) + "ms";
			}
			
			return;
		}		
		else if ( app.params.exists("createBackup") )
		{
			if ( app.params.get("tables") != null )
			{
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
				
				var comment = app.params.exists("comment") ? app.params.get("comment") : null;
				createBackup(tableStr, comment);
			}
			else
			{
				trace( "No tables were selected!" );
			}
		}
		
		tables = app.db.getTables();
		listSize = Std.int(Math.min(10, tables.length));
		
		backups = new Array<DbBackupData>();
		
		var files = FileSystem.readDirectory(BACKUP_DIR);
		
		for ( filename in files )
		{
			var f : String = BACKUP_DIR + filename;
			
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
				date : stat.mtime.format(DATE_FORMAT), 
				size : size,
				filename : filename,
				dbHost : getFileMetaParam(META_DB_HOST, f),
				dbName : getFileMetaParam(META_DB_NAME, f),
				comment : getFileMetaParam(META_COMMENT, f),
				url : f
			} );
		}
		
		backups.sort( compareBackup );
	}
	
	function createBackup( tableStr : String, comment : String, ?fileNamePrefix : String = "" ) : Void
	{
		var exported = "";
				
		if ( comment != null && comment != "" )
			exported += buildMetaParam(META_COMMENT, comment) + "\n";
			
		exported += buildMetaParam(META_DB_HOST, app.db.host) + "\n";
		exported += buildMetaParam(META_DB_NAME, app.db.database) + "\n";
		exported += buildMetaParam(META_TABLES, tableStr) + "\n";
		
		exported += "\n" + app.db.export( tableStr );
			
		var now = Date.now();
		//var backupName = app.db.database + "-" + now.format(backupDateFormat) + "-" + Md5.encode(tableStr) + ".sql";
		var backupName = app.db.database + "-" + now.format(BACKUP_DATE_FORMAT) + "-" + now.getTime() + ".sql";
			
		File.putContent(BACKUP_DIR + fileNamePrefix + backupName, exported);
	}
	
	function getFileMetaParam( paramName : String, filePath : String ) : String
	{
		// Open file in ASCII mode
		var f = File.read(filePath, false);
		var line = f.readLine();
		var searchStr = buildMetaParam(paramName, "");
		var result = null;
		// Keep searching until we find something that isn't a comment line
		try
		{
			while ( line.charAt(0) == "#" )
			{
				var index = line.indexOf(searchStr);
				if ( index != -1 )
				{
					result = line.substr(searchStr.length);
					break;
				}
				line = f.readLine();
			}
		}
		catch ( e : Eof )
		{
			f.close();
			f = null;
		}
		
		if ( f != null )
			f.close();
			
		return result;
	}
	
	function buildMetaParam( paramName : String, value : String ) : String
	{
		return FILE_META_COMMENT_PREFIX + paramName.toUpperCase() + "=" + value;
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
	
	/*function getFileComment( filepath : String ) : String
	{
		var f = File.read( filepath, false );
		var line = f.readLine();
		
		if ( line.indexOf( RESTORE_COMMENT ) == 0 )
			line = line.substr( RESTORE_COMMENT.length );
		else
			line = "";
		
		return line;
	}*/
	
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