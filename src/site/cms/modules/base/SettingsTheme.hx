/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.modules.base;
import haxe.Serializer;
import haxe.xml.Fast;
import php.FileSystem;
import php.io.File;
import site.cms.common.CmsSettings;
import site.cms.templates.CmsTemplate;
import site.cms.modules.base.Settings;

class SettingsTheme extends SettingsBase
{
	public var themes:List<Dynamic>;
	public var currentTheme:String;
	public var themeDirectory:String;
	
	public function new() 
	{
		super();
		themeDirectory = FileSystem.fullPath('') + "/" + "res/cms/theme";
	}
	
	override public function main()
	{
		currentTheme = CmsSettings.i.themeCurrent;
		
		themes = new List();
		if (FileSystem.isDirectory(themeDirectory))
		{
			// get all the themes
			for (d in FileSystem.readDirectory(themeDirectory)) {
				if (d != "." && d != ".." && d != ".svn") {
					themes.add(d);
				}
			}
		}
		
		// set current if we want ...
		var setTheme = application.params.get("set");
		if (setTheme != null) {
			if (Lambda.has(themes, setTheme)) {
				currentTheme = setTheme;
				
				// get theme details
				if (FileSystem.exists(themeDirectory + "/" + currentTheme + "/style.xml")) {
					var xml = Xml.parse(File.getContent(themeDirectory + "/" + currentTheme + "/style.xml"));
					var fast = new Fast(xml.firstElement());
					var style = { };
					for (e in fast.elements) {
						Reflect.setField(style, e.name, e.innerData);
					}
					application.db.update("_settings", { value:Serializer.run(style) }, "`key`='themeStyle'");
				}
				
				application.db.update("_settings", { value:setTheme }, "`key`='themeCurrent'");
				application.messages.addMessage("Theme updated to '" + setTheme + "'");
								
			}
		}
	}
}