/**
 * ...
 * @author Matt Benton
 */

package site.cms.modules.email.js;

import js.Dom;
import js.Lib;
import poko.js.JsRequest;

class JsSettings extends JsRequest
{	
	var emailField : Select;
	var nameField : Select;
	var idField : Select;
	
	private function onGetTableFields( fields:List<String> ) : Void
	{		
		var innerHtml = "";
		
		if ( fields != null )
		{
			for ( f in fields )
				innerHtml += '<option value="'+f+'">' + f + '</option>';
		}
		else
		{
			innerHtml = '<option value="">-- empty --</option>';
		}
		
		emailField.innerHTML = innerHtml;
		nameField.innerHTML = innerHtml;
		idField.innerHTML = innerHtml;
	}
	
	/** remoting */
	
	public function onChangeSelectbox(selectBox:Select)
	{		
		remoting.api.getTableFields.call([selectBox.value], onGetTableFields);
		
		emailField = cast Lib.document.getElementById("form_emailField");
		nameField = cast Lib.document.getElementById("form_nameField");
		idField = cast Lib.document.getElementById("form_idField");
		
		emailField.innerHTML = '<option value="">-- loading --</option>';
		nameField.innerHTML = '<option value="">-- loading --</option>';
		idField.innerHTML = '<option value="">-- loading --</option>';
	}
	
	/** end remoting */
}