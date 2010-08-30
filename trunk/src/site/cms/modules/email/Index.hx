/**
 * ...
 * @author Matt Benton
 */

package site.cms.modules.email;

import poko.form.elements.Button;
import poko.form.elements.Hidden;
import poko.form.elements.Input;
import poko.form.elements.RichtextWym;
import poko.form.Form;
import poko.form.validators.EmailValidator;
import poko.js.JsBinding;
import poko.utils.EmailForwarder;
import poko.utils.html.ScriptType;

using StringTools;

typedef EmailVar =
{
	var name:String;
	var desc:String;
	var link:String;
	var field:String;
}

class Index extends EmailBase
{	
	public var form : Form;
	
	public var emailVars : List<EmailVar>;
	
	public var previewStr : String;
	public var editStr : String;
	
	public var action : String;
	public var userCount : Int;
	
	override public function main()
	{
		super.main();
		
		head.addExternal(ScriptType.js, "js/cms/wymeditor/jquery.wymeditor.pack.js");
		
		setupLeftNav();
		
		action = app.params.exists("action") ? app.params.get("action") : "edit";

		form = new Form("emailForm");
		
		emailVars = new List<EmailVar>();
		emailVars.add( { name:"id", desc:"The ID of the user", link:"idField", field:null } );
		emailVars.add( { name:"email", desc:"The email address of the user", link:"emailField", field:null } );
		emailVars.add( { name:"name", desc:"The name of the user", link:"nameField", field:null } );
		
		var fromName = new Input("fromName", "From Name", null, true);
		form.addElement(fromName);
		var fromEmail = new Input("fromEmail", "From Email", null, true);
		fromEmail.addValidator(new EmailValidator());
		form.addElement(fromEmail);
		var emailSubject = new Input("emailSubject", "Subject", null, true);
		form.addElement(emailSubject);
	
		fromName.width = fromEmail.width = emailSubject.width = 350;
		fromName.useSizeValues = fromEmail.useSizeValues = emailSubject.useSizeValues = true;
		
		var body = new RichtextWym("body", "Email Body");
		body.width = 400;
		body.height = 200;
		form.addElement(body);
		
		var editBtn = new Button("editBtn", "Edit");
		form.addElement(editBtn);
		
		var sendBtn = new Button("sendBtn", "Send");
		form.addElement(sendBtn);
		
		var previewBtn = new Button("previewBtn", "Preview");
		form.addElement(previewBtn);
		//form.setSubmitButton(previewBtn);
		
		form.populateElements();
		
		var emailSettings = loadSettings();
		
		if ( emailSettings.userTable == null || emailSettings.userTable == "" )
		{
			messages.addError("Warning: No user table was defined in email settings.");
			return;
		}
		
		for ( item in emailVars )
			item.field = Reflect.field(emailSettings, item.link);
		
		userCount = app.db.count(emailSettings.userTable, "1");
		
		previewStr = body.value;
		editStr = body.value;
		for ( item in emailVars )
		{
			//var tableField = Reflect.field(emailSettings, item.field);
			//previewStr = previewStr.replace(item.name, "<strong>" + tableField.toUpperCase() + "</strong>");
		}
		
		//previewStr = generateHtml(previewStr, emailVars, emailSettings.userTable, "id = 36", true);
		var userData = getUsers(emailSettings, "1 LIMIT 1").first();
		if (userData == null)
			userData = { id:"#id#", name:"#name#", email:"#email#" };
		previewStr = generateHtml(previewStr, emailVars, userData, true);
		
		if ( form.isSubmitted() && form.isValid() )
		{
			var formData = form.getData();
			
			if ( form.submittedButtonName == "sendBtn" )
			{
				if ( emailSettings.userTable != null && emailSettings.idField != null )
				{
					//var users = app.db.request("SELECT `" + emailSettings.idField + "` FROM `" + emailSettings.userTable + "`");
					var users = getUsers(emailSettings);
					for ( user in users )
					{
						//var id = Reflect.field(user, emailSettings.idField);
						//var html = generateHtml(formData.body, emailVars, emailSettings.userTable, "`" + emailSettings.idField + "` = " + id);
						var html = generateHtml(formData.body, emailVars, user);
						var from = fromName.value + "<" + fromEmail.value + ">";
						EmailForwarder.forwardMultipart(user.email, emailSubject.value, from, "plain@##@$@#$#@", html);
					}
				}
			}
		}
	}
	
	inline function encodeVar(name:String):String
	{
		return "{" + name + "}";
	}
	
	function getUsers(s:EmailSettings, ?where:String="1") : List<Dynamic>
	{
		//return app.db.request("SELECT * FROM `" + s.userTable + "` WHERE " + where);
		return app.db.request("SELECT `" + s.idField + "` as 'id', `" + s.emailField + "` as 'email', `" + s.nameField + "` as 'name' FROM `" + s.userTable + "` WHERE " + where);
	}
	
	function generateHtml(template:String, vars:List<EmailVar>, data:Dynamic, ?preview:Bool=false) : String
	{		
		if ( data != null )
		{
			for ( v in vars )
			{
				var value = preview ? "<em><strong>" + Reflect.field(data, v.field) + "</strong></em>" : Reflect.field(data, v.field);
				//template = template.replace(v.name, value);
				template = template.replace(encodeVar(v.name), value);
			}
			return template;
		}
		return null;
	}
	
	/*function generateHtml(template:String, vars:List<EmailVar>, table:String, ?where:String=null, ?preview:Bool=false) : String
	{
		var data : Dynamic = { };
		if ( where != null )
		{
			data = app.db.requestSingle('SELECT * FROM `' + table + '` WHERE ' + where);
		}
		else
		{
			for ( v in vars )
				Reflect.setField(data, v.field, "{" + table + "." + v.field + "}");
		}
		
		if ( data != null )
		{
			for ( v in vars )
			{
				var value = preview ? "<em><strong>" + Reflect.field(data, v.field) + "</strong></em>" : Reflect.field(data, v.field);
				template = template.replace(v.name, value);
			}
			return template;
		}
		return null;
	}*/
}