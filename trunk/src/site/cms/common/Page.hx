/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.common;
import poko.Application;
import haxe.Unserializer;
import php.Lib;

class Page 
{
	public var id:Int;
	public var name:String;
	public var data:Dynamic;
	public var definition:Definition;
	
	public function new() {}
	
	public function loadById(id:Int)
	{
		var application = Application.instance;
		var result = application.db.requestSingle("SELECT p.id as 'id', d.name as 'name', d.id as 'definitionId', p.data as 'data' FROM `_pages` p, `_definitions` d WHERE p.`id`=\"" + application.db.cnx.escape(Std.string(id)) + "\" AND p.`definitionid`=d.`id`");
		init(result);
	}
	
	public function loadByName(name:String)
	{
		var application = Application.instance;
		var result = application.db.requestSingle("SELECT p.id as 'id', d.name as 'name', d.id as 'definitionId', p.data as 'data' FROM `_pages` p, `_definitions` d WHERE d.`name`=\"" + application.db.cnx.escape(name) + "\" AND p.`definitionid`=d.`id`");
		init(result);
	}
	
	private function init(result:Dynamic)
	{
		id = result.id;
		name = result.name;
		
		//try {
			data = Unserializer.run(result.data);
		/*}catch (e:Dynamic) { 
			Lib.print("There has been an error Unserializing data for this page.");
			result.data = {}
		}*/
		
		definition = new Definition(result.definitionId);
	}
	
	public static function getPageById(id):Page
	{
		var p = new Page();
		p.loadById(id);
		return p;
	}
	
	public static function getPageByName(name:String):Page
	{
		var p = new Page();
		p.loadByName(name);
		return p;
	}
}