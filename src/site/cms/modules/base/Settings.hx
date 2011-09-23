/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.modules.base;
import poko.form.elements.Button;
import poko.form.elements.FileUpload;
import poko.form.elements.Input;
import poko.form.Form;
import poko.utils.PhpTools;
import site.cms.common.CmsSettings;
import site.cms.templates.CmsTemplate;

class Settings extends SettingsBase
{
	public var section:String;
	public var sectionTitle:String;
	public var sectionDescription:String;
	public var form:Form;

	public function new() 
	{
		super();
		sectionDescription = "Change your settings here.";
	}
	
	override public function init()
	{	
		super.init();
		
		section = app.params.get('section');
		sectionTitle = section.substr(0, 1).toUpperCase() + section.substr(1, section.length - 1);
	}
	
	override public function main()
	{			
		form = new Form("settingsUpdate", "?request=cms.modules.base.Settings&section="+section, FormMethod.POST);
		
		switch(section)
		{
			case "main":
				var data = app.db.requestSingle("SELECT `value` FROM _settings WHERE `key`='cmsTitle'");
				form.addElement(new Input("cmsTitle", "CMS Title", data.value, true));
				data = app.db.requestSingle("SELECT `value` FROM _settings WHERE `key`='cmsLogo'");
				form.addElement(new FileUpload("cmsLogo", "CMS Logo", data.value, false));
				var s = new Button("submit", "Submit");
				form.setSubmitButton(s);
			case "ftp":
				sectionDescription = "Change default FTP details here for media uploading via Applet.";
			
				var data = app.db.requestSingle("SELECT * FROM _settings WHERE `key`='ftpUrl'");
				form.addElement(new Input("ftpUrl", "Url", data.value));
				var data = app.db.requestSingle("SELECT * FROM _settings WHERE `key`='ftpUsername'");
				form.addElement(new Input("ftpUsername", "Username", data.value));
				var data = app.db.requestSingle("SELECT * FROM _settings WHERE `key`='ftpPassword'");
				form.addElement(new Input("ftpPassword", "Password", data.value));
				var data = app.db.requestSingle("SELECT * FROM _settings WHERE `key`='ftpDirectory'");
				form.addElement(new Input("ftpDirectory", "Directory", data.value));				
				var s = new Button("submit", "Submit");
				form.setSubmitButton(s);		
		}
		if (form.isSubmitted()) {
			if (form.isValid()) {
				saveDataFromForm();
			}
		}
	}
	
	private function saveDataFromForm():Void
	{
		form.populateElements();
		var data = { };
		for (e in form.elements) {
			if (e.name != "submit") {
				if (e.value != null && Std.is(e, FileUpload)) {
					var info = PhpTools.getFilesInfo().get(form.name + "_" + e.name);
					if(info.get("error") == "0"){
						var n = cast(e.value, String);
						var n2 = "cmsLogo" + n.substr(n.lastIndexOf("."));
						PhpTools.moveFile(info.get("tmp_name"), "./res/cms/"+n2);
						e.value = n2;
					}
				}
				if (Reflect.hasField(CmsSettings.i, e.name)) Reflect.setField(CmsSettings.i, e.name, e.value);
			}
		}
		messages.addMessage("Settings saved");
	}
}

class SettingsBase extends CmsTemplate
{
	override public function init()
	{
		super.init();
		navigation.setSelected("Settings");
		setupLeftNav();
	}
	
	public function setupLeftNav():Void
	{	
		super.main();
		
		leftNavigation.addSection("Settings");
		leftNavigation.addLink("Settings", "Main", "cms.modules.base.Settings&section=main");
		leftNavigation.addLink("Settings", "Theme", "cms.modules.base.SettingsTheme");
		leftNavigation.addLink("Settings", "FTP", "cms.modules.base.Settings&section=ftp");
	}
	
}