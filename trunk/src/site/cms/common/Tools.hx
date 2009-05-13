/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.common;

class Tools 
{

	public function new() 
	{
		
	}
	
	public static function getDBTables():List<Dynamic>
	{
		var application = poko.Application.instance;
		
		var tables:List < Dynamic > = application.db.request("SHOW TABLES");
		var app = application;
		
		tables = Lambda.map(tables, function(table) {
			return Reflect.field(table, "Tables_in_" + app.db.database);
		});
		
		tables = Lambda.filter(tables, function(table) {
			return table.substr(0, 1) != "_";
		});
		
		return tables;
	}
}