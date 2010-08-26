/**
 * ...
 * @author Matt Benton
 */

package site.cms.modules.email;
import haxe.Serializer;
import haxe.Unserializer;
import poko.form.elements.Button;
import poko.form.elements.Selectbox;
import poko.form.Form;

private typedef EmailSettings =
{
	var userTable:String;
	var nameField:String;
}

class Settings extends EmailBase
{	
	public var tables : Array<String>;
	public var form : Form;
	
	override public function main()
	{
		super.main();
		
		setupLeftNav();

		form = new Form("form");
		
		var emailSettings = loadSettings();
		
		var userTable = new Selectbox("userTable", "User Table", emailSettings.userTable, null);
		tables = app.db.getTables();
		for ( table in tables )
			userTable.add(table, table);
		form.addElement(userTable);
		
		var nameField = new Selectbox("nameField", "Name Field", emailSettings.nameField, null);
		form.addElement(nameField);
		
		form.setSubmitButton(form.addElement(new Button( "submit", "Submit"), "submit"));
		
		form.populateElements();
			
		if ( userTable.value != null && userTable.value != "" )
		{
			var columns = app.db.request("SHOW COLUMNS FROM `" + userTable.value + "`");
			for ( col in columns )
			{
				var field = Reflect.field(col, "Field");
				nameField.add(field, field);
			}
		}
		
		if ( form.isSubmitted() && form.isValid() )
		{
			var data:EmailSettings = form.getData();
			saveSettings(data);
		}
	}
	
	
	function loadSettings() : EmailSettings
	{
		var data = settings.get("emailSettings");
		if ( data != null && data != "" )
			return Unserializer.run(data);
		return {userTable:"", nameField:""};
	}
	
	function saveSettings(s:EmailSettings) : Void
	{
		var data = Serializer.run(s);
		app.db.update("_settings", {value:data}, "`key` = 'emailSettings'");
	}
}