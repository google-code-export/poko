package site.services;

import php.Exception;
import php.Lib;
import php.Web;
import poko.Request;

class XmlData extends Request
{
	public var data:List<Dynamic>;
	public var response:String;
	
	public function new() 
	{
		super();
	}
	
	override public function main() 
	{	
		var type = Web.getParams().get("type");
	
		var sql = "";
		switch(type) {
			case "news": sql = "SELECT * FROM `news` ORDER BY `id` DESC LIMIT 8 ";
		}
		
		if (sql == "") {
			forbidden();
			return;
		}
			
		data = application.db.request(sql);	
		response = "OK";			
		
		Web.setHeader("content-type", "text/xml");	
	}
	
	private function forbidden():Void
	{
		//template = "codes/403";
		
		Web.setReturnCode(403);
	}
}