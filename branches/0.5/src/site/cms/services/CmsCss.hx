/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.services;
import haxe.Md5;
import haxe.Unserializer;
import php.Web;
import poko.Request;
import poko.utils.GD;

class CmsCss extends Request
{
	private var resPath:String;
	
	// images
	public var imageHeaderBg:String;
	public var imageHeaderButtonBgUp:String;
	public var imageHeaderButtonBgOver:String;
	public var imageHeadingShort:String;
	public var imageHeadingMedium:String;
	public var imageHeadingLong:String;
	public var imageHeadingLoginBg:String;
	public var imageHeadingLogo:String;
	
	// colours
	public var colorLinkOnDark:String;
	public var colorLinkOnLight:String;
	
	// size
	public var sizeLogoWidth:Int;
	public var sizeLogoHeight:Int;
	public var sizeButtonWidth:Int;
	public var sizeButtonHeight:Int;
	
	// left nav footer link
	public var colorNavigationLinkBgUp:String;
	public var colorNavigationLinkBgOver:String;
	public var colorNavigationLinkColor:String;
		
	override public function main():Void
	{	
		var settingResPath = application.db.requestSingle("SELECT value FROM _settings WHERE `key`='themeCurrent'");
		var settingLogo = application.db.requestSingle("SELECT value FROM _settings WHERE `key`='cmsLogo'");
		var settingStyleData = application.db.requestSingle("SELECT value FROM _settings WHERE `key`='themeStyle'");
		var settingStyle:Dynamic = { error: true };
		try{
			settingStyle = Unserializer.run(settingStyleData.value);
		}catch (e:Dynamic) {
			application.messages.addWarning("Problem getting style information from the database.");
		}
		
		// path to assets
		resPath = "./res/cms/theme/"+settingResPath.value+"/";
		
		// site logo
		imageHeadingLogo = "./res/cms/" + settingLogo.value;
		
		// other stuff!
		imageHeaderBg = resPath + "bg.png";
		imageHeaderButtonBgUp = resPath + "button0.png";
		imageHeaderButtonBgOver = resPath + "button1.png";
		imageHeadingShort = resPath + "heading-short-bg.png";
		imageHeadingMedium = resPath + "heading-medium-bg.png";
		imageHeadingLong = resPath + "heading-long-bg.png";
		imageHeadingLoginBg = resPath + "login-bg.png";
		
		// colours (can default if we don't have style!)
		if(settingStyle.error != null){
			colorLinkOnDark = "#93DD22";
			colorLinkOnLight = "#FF6600";
			colorNavigationLinkBgUp = "#244D49";
			colorNavigationLinkBgOver = "#486460";
			colorNavigationLinkColor = "#fff";
		}else {
			colorLinkOnDark = settingStyle.colorLinkOnDark;
			colorLinkOnLight = settingStyle.colorLinkOnLight;
			colorNavigationLinkBgUp = settingStyle.colorNavigationLinkBgUp;
			colorNavigationLinkBgOver = settingStyle.colorNavigationLinkBgOver;
			colorNavigationLinkColor = settingStyle.colorNavigationLinkColor;
		}
		
		// sizes of images
		#if php
			var imgSize = GD.getImageSize(imageHeadingLogo);
			sizeLogoWidth = imgSize.width;
			sizeLogoHeight = imgSize.height;
			var imgSize = GD.getImageSize(imageHeaderButtonBgUp);
			sizeButtonWidth = imgSize.width;
			sizeButtonHeight = imgSize.height;
		#else
			sizeLogoWidth = 174;
			sizeLogoHeight = 60;
			sizeButtonWidth = 80;
			sizeButtonHeight = 25;		
		#end
		
		var hash = Md5.encode(Std.string(sizeButtonHeight) + Std.string(sizeButtonWidth) + Std.string(sizeLogoHeight) + Std.string(sizeLogoWidth) + imageHeaderBg + imageHeaderButtonBgUp + imageHeaderButtonBgOver + imageHeadingShort + imageHeadingMedium + imageHeadingLong + imageHeadingLoginBg + imageHeadingLogo + colorLinkOnDark + colorLinkOnLight + colorNavigationLinkBgUp + colorNavigationLinkBgOver + colorNavigationLinkColor);
		var dateModified = Date.now();
		var dateModifiedString = DateTools.format(dateModified, "%a, %d %b %Y %H:%M:%S") + ' GMT';
		Web.setHeader("Last-Modified", dateModifiedString);
		Web.setHeader("Expires", DateTools.format(new Date(dateModified.getFullYear() + 1, dateModified.getMonth(), dateModified.getDay(), 0, 0, 0), "%a, %d %b %Y %H:%M:%S") + ' GMT');
		Web.setHeader("Cache-Control" ,"public, max-age=31536000");
		Web.setHeader("ETag", "\"" + hash + "\"");
		Web.setHeader("Pragma", "");
		Web.setHeader("content-type", "text/css");
	}
	
}