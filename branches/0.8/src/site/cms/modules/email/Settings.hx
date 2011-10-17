/**
 * ...
 * @author Matt Benton
 */

package site.cms.modules.email;

import poko.form.elements.Button;
import poko.form.elements.Selectbox;
import poko.form.Form;
import poko.js.JsBinding;

class Settings extends EmailBase
{	
	public var tables : Array<String>;
	public var form : Form;
	
	public var jsBind : JsBinding;
	
	public function new()
	{
		authenticationRequired = ["cms_admin"];
		super();
	}
	
	override public function init() : Void
	{
		super.init();
		
		jsBind = new JsBinding("site.cms.modules.email.js.JsSettings");
		remoting.addObject( "api", { getTableFields:getTableFields } );
	}
	
	override public function main()
	{
		super.main();
		
		setupLeftNav();

		form = new Form("form");
		
		var emailSettings = loadSettings();
		
		var userTable = new Selectbox("userTable", "User Table", emailSettings.userTable, null);
		userTable.onChange = jsBind.getRawCall("onChangeSelectbox(this)");
		tables = app.db.getTables();
		for ( table in tables )
			userTable.add(table, table);
		form.addElement(userTable);
		
		var idField = new Selectbox("idField", "ID Field", emailSettings.idField, null);
		form.addElement(idField);
		
		var emailField = new Selectbox("emailField", "Email Field", emailSettings.emailField, null);
		form.addElement(emailField);
		
		var nameField = new Selectbox("nameField", "Name Field", emailSettings.nameField, null);
		form.addElement(nameField);
		
		form.setSubmitButton(form.addElement(new Button( "submit", "Submit")));
		
		form.populateElements();
		
		
		//jsBind.queueRawCall("onChangeSelectbox(document.getElementById('form_userTable'));");
			
		if ( userTable.value != null && userTable.value != "" )
		{
			try {
				var columns = app.db.request("SHOW COLUMNS FROM `" + userTable.value + "`");
				var fields = new List();
				for ( col in columns )
				{
					var field = Reflect.field(col, "Field");
					fields.add( {key:field, value:field} );
				}
				idField.data = emailField.data = nameField.data = fields;
			}catch (e:Dynamic) {
				messages.addWarning("Table does not exist anymore.");
			}
		}
		
		if ( form.isSubmitted() && form.isValid() )
		{
			var data:EmailSettings = form.getData();
			saveSettings(data);
		}
	}
	
	public function getTableFields( table : String ) : List<String>
	{
		if ( table != null && table != "" )
		{
			var columns = app.db.request("SHOW COLUMNS FROM `" + table + "`");
			var fields = new List<String>();
			for ( col in columns )
				fields.add( Reflect.field(col, "Field") );
			return fields;
		}		
		return null;
	}
}