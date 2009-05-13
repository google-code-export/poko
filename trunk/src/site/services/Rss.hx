
package site.services;

import poko.Service;
import php.Lib;
import php.Sys;
import php.Web;
import poko.Request;

class Rss extends Service
{
	public var title:String;
	public var description:String;
	public var generator:String;
	public var link:String;
	public var items:Dynamic;
	
	public function new() 
	{
		super();
	}
	
	override public function pre()
	{
		super.pre();
		
		allowedMethods.add("news");
	}
	
	public function news()
	{
		data.title = "Scarygirl";
		data.description = "Scarygirl " + method;
		data.generator = "haXe php (haXe.org)";
		data.link = "http://www.scarygirl.com/";
		
		data.items = application.db.request("SELECT * FROM `news` ORDER BY `order`");
		data.items = Lambda.map(data.items, formatNews);
	}
	
	private function formatNews(v:Dynamic):Dynamic
	{
		var item:Dynamic = { };
		item.title = v.title + " - " + v.date;
		item.description = v.content;
		item.link = "http://scarygirl.com";
		item.guid = v.id;
		item.pubDate = v.updated;
		
		return item;
	}
}