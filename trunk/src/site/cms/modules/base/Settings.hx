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
import site.cms.templates.CmsTemplate;

class Settings extends SettingsBase
{
	public var section:String;
	public var sectionTitle:String;
	public var form:Form;
	
	override public function init()
	{	
		super.init();
		
		section = app.params.get('section');
		sectionTitle = section.substr(0, 1).toUpperCase() + section.substr(1, section.length - 1);
	}
	
	override public function main()
	{			
		super.main();		
		
		form = new Form("settingsUpdate", "?request=cms.modules.base.Settings&section="+section, FormMethod.POST);
		
		switch(section)
		{
			case "main":
				var data = app.db.requestSingle("SELECT * FROM _settings WHERE `key`='cmsTitle'");
				form.addElement(new Input("cmsTitle", "CMS Title", data.value, true));
				data = app.db.requestSingle("SELECT * FROM _settings WHERE `key`='cmsLogo'");
				form.addElement(new FileUpload("cmsLogo", "CMS Logo", data.value, false));
				form.setSubmitButton(form.addElement(new Button( "submit", "Submit"), "submit"));
				
				if (form.isSubmitted()) {
					if (form.isValid()) {
						saveDataFromForm();
					}
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
				app.db.update("_settings", { value: e.value }, "`key`='" + e.name + "'");
			}
		}
		messages.addMessage("Settings saved.");
	}
}

class SettingsBase extends CmsTemplate
{
	override public function init()
	{
		super.init();
		
		navigation.setSelected("Settings");
	}
	
	override public function main()
	{
		super.main();
		setupLeftNav();
	}
	
	public function setupLeftNav():Void
	{	
		leftNavigation.addSection("Settings");
		leftNavigation.addLink("Settings", "Main", "cms.modules.base.Settings&section=main");
		leftNavigation.addLink("Settings", "Theme", "cms.modules.base.SettingsTheme");
	}
	
}