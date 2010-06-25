/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.common;
import poko.Db;

class CmsSettings 
{
	public var siteView:String;
	public var cms:String;
	public var themeCurrent:String;
	public var themeStyle:String;
	public var live:String;
	public var nonLiveAddress:String;
	
	public var cmsTitle:String;
	public var cmsLogo:String;
	
	public var ftpUrl:String;
	public var ftpUsername:String;
	public var ftpPassword:String;
	public var ftpDirectory:String;
	
	public static var i:CmsSettings;
	
	public function new(){}
	
	public static function load(db:Db)
	{
		if (CmsSettings.i == null) CmsSettings.i = new CmsSettings();
		var i = CmsSettings.i;
		
		var result = db.request("SELECT * FROM `_settings`");
		
		for (r in result)
		{
			if (Reflect.hasField(i, r.key)) {
				Reflect.setField(i, r.key, r.value);
			}
		}
	}
	
	public static function save(db:Db)
	{
		if (CmsSettings.i == null) throw "Please run CmsSettings.load() before trying to run CmsSettings.save()";
		var i = CmsSettings.i;
				
		for (f in Reflect.fields(i))
		{
			var sql = "UPDATE `_settings` SET `value`='" + Reflect.field(i, f) + "' WHERE `key`='" + f + "'";
			db.query(sql);
		}
	}
}